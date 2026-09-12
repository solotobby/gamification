<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoApproveCampaigns extends Command
{
    protected $signature = 'campaigns:auto-approve';
    protected $description = 'Auto approve campaign workers when campaign approval window expires across all user currencies';

    public function handle()
    {
        Log::info('Auto campaign approval started');

        // Query all pending workers whose campaign approval window has passed (defaulting null/0 to 24 hours)
        $query = CampaignWorker::query()
            ->with(['campaign', 'user.wallet'])
            ->where('status', 'Pending')
            ->whereNull('reason')
            ->whereHas('campaign', function ($q) {
                $q->whereRaw('DATE_ADD(campaign_workers.created_at, INTERVAL COALESCE(NULLIF(approval_time, 0), 24) HOUR) <= NOW()');
            })
            ->orderBy('id');

        $count = $query->count();

        $this->info("Found {$count} campaign worker(s) to auto approve");
        Log::info("Found {$count} campaign worker(s) to auto approve");

        if ($count === 0) {
            Log::info('Auto campaign approval completed (no pending workers)');
            return 0;
        }

        $query->chunkById(100, function ($workers) {
            foreach ($workers as $worker) {
                try {
                    DB::transaction(function () use ($worker) {
                        // Lock worker row for update to prevent race conditions
                        $lockedWorker = CampaignWorker::where('id', $worker->id)
                            ->lockForUpdate()
                            ->first();

                        if (!$lockedWorker || $lockedWorker->status !== 'Pending') {
                            return; // Already processed by another worker/process
                        }

                        $campaign = $lockedWorker->campaign;
                        $user = $lockedWorker->user;

                        if (!$campaign || !$user) {
                            Log::warning("Auto approval skipped: missing campaign or user for worker #{$lockedWorker->id}");
                            return;
                        }

                        // Verify that a credit transaction hasn't already been created for this worker submission
                        $existingTx = PaymentTransaction::where('user_id', $lockedWorker->user_id)
                            ->where('campaign_id', $lockedWorker->campaign_id)
                            ->where('type', 'campaign_payment')
                            ->where('reference', 'like', "%_{$lockedWorker->id}")
                            ->first();

                        if ($existingTx) {
                            $lockedWorker->update([
                                'status' => 'Approved',
                                'reason' => 'Auto approval (reconciled existing transaction)'
                            ]);
                            return;
                        }

                        // Update worker status
                        $lockedWorker->update([
                            'status' => 'Approved',
                            'reason' => 'Auto approval after approval window'
                        ]);

                        checkCampaignCompletedStatus($campaign->id);

                        // Lock wallet row for update
                        $wallet = Wallet::where('user_id', $lockedWorker->user_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$wallet) {
                            throw new \Exception("Wallet not found for user ID {$lockedWorker->user_id}");
                        }

                        // Determine the user's base currency accurately
                        $baseCurrency = baseCurrency($user);
                        $amount = (float) $lockedWorker->amount;

                        // Ensure numeric fields
                        $wallet->balance = (float) ($wallet->balance ?? 0);
                        $wallet->usd_balance = (float) ($wallet->usd_balance ?? 0);
                        $wallet->base_currency_balance = (float) ($wallet->base_currency_balance ?? 0);

                        // Route credit strictly by the user's base currency
                        if (in_array(strtoupper($baseCurrency), ['NGN', 'NAIRA'])) {
                            $currency = 'NGN';
                            $channel = 'paystack';
                            $wallet->balance += $amount;
                        } elseif (in_array(strtoupper($baseCurrency), ['USD', 'DOLLAR'])) {
                            $currency = 'USD';
                            $channel = 'paypal';
                            $wallet->usd_balance += $amount;
                        } else {
                            $currency = strtoupper($baseCurrency);
                            $channel = 'flutterwave';
                            $wallet->base_currency_balance += $amount;
                        }

                        $wallet->save();

                        $reference = 'AUTO_' . now()->timestamp . '_' . $lockedWorker->id;

                        PaymentTransaction::create([
                            'user_id' => $lockedWorker->user_id,
                            'campaign_id' => $lockedWorker->campaign_id,
                            'reference' => $reference,
                            'amount' => $amount,
                            'balance' => walletBalance($lockedWorker->user_id),
                            'status' => 'successful',
                            'currency' => $currency,
                            'channel' => $channel,
                            'type' => 'campaign_payment',
                            'description' => 'Auto-approved payment for ' . ($campaign->post_title ?? 'Task'),
                            'tx_type' => 'Credit',
                            'user_type' => 'regular'
                        ]);
                    });

                    $this->info("Approved Campaign Worker ID {$worker->id}");
                } catch (\Throwable $e) {
                    Log::error("Auto approval failed for worker {$worker->id}: " . $e->getMessage());
                    $this->error("Failed Campaign Worker ID {$worker->id}: " . $e->getMessage());
                }
            }
        });

        Log::info('Auto campaign approval completed');
        $this->info('Auto campaign approval completed');

        return 0;
    }
}
