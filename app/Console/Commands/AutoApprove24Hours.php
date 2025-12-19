<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoApprove24Hours extends Command
{
    protected $signature = 'campaigns:auto-approve-24hours';
    protected $description = 'Auto-approve pending campaign workers after 24 hours (excluding business accounts)';

    public function handle()
    {
        Log::info('Auto-approve pending campaign workers after 24 hours (excluding business accounts) started');

        $cutoffTime = Carbon::now()->subHours(24);

        $lists = CampaignWorker::where('status', 'Pending')
            ->whereNull('reason')
            ->whereHas('campaign.user', function ($query) {
                $query->where('is_business', false);
            })
            ->where('created_at', '<=', $cutoffTime)
            ->get();

        $this->info('Found ' . $lists->count() . ' campaign workers to auto-approve.');
        Log::info('Found ' . $lists->count() . ' campaign workers to auto-approve.');

        foreach ($lists as $list) {
            try {
                $this->approveCampaignWorker($list);
                $this->info('Approved: Campaign Worker ID ' . $list->id);
            } catch (\Exception $e) {
                Log::error('Failed to approve campaign worker ID ' . $list->id . ': ' . $e->getMessage());
                $this->error('Failed: Campaign Worker ID ' . $list->id);
            }
        }

        $this->info('Auto-approved ' . $lists->count() . ' campaign workers (24+ hours old).');
        Log::info('Auto-approved ' . $lists->count() . ' campaign workers (24+ hours old).');
    }

    private function approveCampaignWorker($ca)
    {
        $ca->status = 'Approved';
        $ca->reason = 'Auto-approval after 24 hours';
        $ca->save();

        $camp = Campaign::where('id', $ca->campaign_id)->first();
        checkCampaignCompletedStatus($camp->id);

        $user = User::where('id', $ca->user_id)->first();
        $baseCurrency = baseCurrency($user);
        $amountCredited = $ca->amount;

        if ($baseCurrency == 'NGN') {
            $currency = 'NGN';
            $channel = 'paystack';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->balance += $amountCredited;
            $wallet->save();
        } elseif ($camp->currency == 'USD') {
            $currency = 'USD';
            $channel = 'paypal';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->usd_balance += $amountCredited;
            $wallet->save();
        } else {
            $currency = baseCurrency($user);
            $channel = 'flutterwave';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->base_currency_balance += $amountCredited;
            $wallet->save();
        }

        $ref = time() . '_' . $ca->id; // More unique reference

        PaymentTransaction::create([
            'user_id' => $ca->user_id,
            'campaign_id' => $ca->campaign_id,
            'reference' => $ref,
            'amount' => $amountCredited,
            'balance' => walletBalance($ca->user_id),
            'status' => 'successful',
            'currency' => $currency,
            'channel' => $channel,
            'type' => 'campaign_payment',
            'description' => 'Auto-approved payment for ' . $ca->campaign->post_title,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);
    }
}
