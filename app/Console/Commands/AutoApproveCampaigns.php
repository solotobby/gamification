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
    protected $description = 'Auto approve campaign workers when campaign approval window expires';

    public function handle()
    {
        Log::info('Auto campaign approval started');

        $query = CampaignWorker::query()
            ->with(['campaign', 'user']) // prevent N+1
            ->where('status', 'Pending')
            ->whereNull('reason')
            ->whereHas('campaign.user', function ($q) {
                $q->where('is_business', false);
            })
            ->whereHas('campaign', function ($q) {
                $q->whereRaw('DATE_ADD(campaign_workers.created_at, INTERVAL approval_time HOUR) <= NOW()');
            })
            ->orderBy('id');

        $count = $query->count();

        $this->info("Found {$count} campaign workers to auto approve");
        Log::info("Found {$count} campaign workers to auto approve");

        $query->chunkById(100, function ($workers) {

            foreach ($workers as $worker) {

                try {

                    DB::transaction(function () use ($worker) {

                        // Refresh row for consistency
                        $worker->refresh();

                        if ($worker->status !== 'Pending') {
                            return; // already processed by another process
                        }

                        $worker->update([
                            'status' => 'Approved',
                            'reason' => 'Auto approval after approval window'
                        ]);

                        $campaign = $worker->campaign;
                        $user = $worker->user;

                        checkCampaignCompletedStatus($campaign->id);

                        $wallet = Wallet::where('user_id', $worker->user_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$wallet) {
                            throw new \Exception("Wallet not found for user {$worker->user_id}");
                        }

                        $baseCurrency = baseCurrency($user);
                        $amount = $worker->amount;

                        if ($baseCurrency === 'NGN') {
                            $currency = 'NGN';
                            $channel = 'paystack';
                            $wallet->balance += $amount;
                        } elseif ($campaign->currency === 'USD') {
                            $currency = 'USD';
                            $channel = 'paypal';
                            $wallet->usd_balance += $amount;
                        } else {
                            $currency = $baseCurrency;
                            $channel = 'flutterwave';
                            $wallet->base_currency_balance += $amount;
                        }

                        $wallet->save();

                        $reference = 'AUTO_' . now()->timestamp . '_' . $worker->id;

                        PaymentTransaction::create([
                            'user_id' => $worker->user_id,
                            'campaign_id' => $worker->campaign_id,
                            'reference' => $reference,
                            'amount' => $amount,
                            'balance' => walletBalance($worker->user_id),
                            'status' => 'successful',
                            'currency' => $currency,
                            'channel' => $channel,
                            'type' => 'campaign_payment',
                            'description' => 'Auto-approved payment for ' . $campaign->post_title,
                            'tx_type' => 'Credit',
                            'user_type' => 'regular'
                        ]);
                    });

                    $this->info("Approved Campaign Worker ID {$worker->id}");
                } catch (\Throwable $e) {

                    Log::error("Auto approval failed for worker {$worker->id}: " . $e->getMessage());
                    $this->error("Failed Campaign Worker ID {$worker->id}");
                }
            }
        });

        Log::info('Auto campaign approval completed');
        $this->info('Auto campaign approval completed');
    }
}

// class AutoApproveCampaigns extends Command
// {
//     protected $signature = 'campaigns:auto-approve';
//     protected $description = 'Auto approve campaign workers when campaign approval window expires';

//     public function handle()
//     {
//         Log::info('Auto campaign approval started');

//         // $query = CampaignWorker::query()
//         //     ->join('campaigns', 'campaign_workers.campaign_id', '=', 'campaigns.id')
//         //     ->join('users', 'campaigns.user_id', '=', 'users.id')
//         //     ->where('campaign_workers.status', 'Pending')
//         //     ->whereNull('campaign_workers.reason')
//         //     ->where('users.is_business', false)
//         //     ->whereRaw('DATE_ADD(campaign_workers.created_at, INTERVAL campaigns.approval_time HOUR) <= NOW()')
//         //     ->select('campaign_workers.*', 'campaign_workers.id as chunk_id')
//         //     ->orderBy('campaign_workers.id');

//         $query = CampaignWorker::query()
//     ->join('campaigns', 'campaign_workers.campaign_id', '=', 'campaigns.id')
//     ->join('users', 'campaigns.user_id', '=', 'users.id')
//     ->where('campaign_workers.status', 'Pending')
//     ->whereNull('campaign_workers.reason')
//     ->where('users.is_business', false)
//     ->whereRaw('DATE_ADD(campaign_workers.created_at, INTERVAL campaigns.approval_time HOUR) <= NOW()')
//     ->select('campaign_workers.*')
//     ->orderBy('campaign_workers.id');

//         $count = $query->count();

//         $this->info("Found {$count} campaign workers to auto approve");
//         Log::info("Found {$count} campaign workers to auto approve");

//         $query->chunkById(50, function ($workers) {

//             foreach ($workers as $worker) {

//                 try {

//                     DB::transaction(function () use ($worker) {

//                         $worker->status = 'Approved';
//                         $worker->reason = 'Auto approval after approval window';
//                         $worker->save();

//                         $campaign = $worker->campaign;
//                         $user = $worker->user;

//                         checkCampaignCompletedStatus($campaign->id);

//                         $wallet = Wallet::where('user_id', $worker->user_id)
//                             ->lockForUpdate()
//                             ->first();

//                         $baseCurrency = baseCurrency($user);
//                         $amount = $worker->amount;

//                         if ($baseCurrency == 'NGN') {

//                             $currency = 'NGN';
//                             $channel = 'paystack';
//                             $wallet->balance += $amount;
//                         } elseif ($campaign->currency == 'USD') {

//                             $currency = 'USD';
//                             $channel = 'paypal';
//                             $wallet->usd_balance += $amount;
//                         } else {

//                             $currency = $baseCurrency;
//                             $channel = 'flutterwave';
//                             $wallet->base_currency_balance += $amount;
//                         }

//                         $wallet->save();

//                         $reference = 'AUTO_' . now()->timestamp . '_' . $worker->id;

//                         PaymentTransaction::create([
//                             'user_id' => $worker->user_id,
//                             'campaign_id' => $worker->campaign_id,
//                             'reference' => $reference,
//                             'amount' => $amount,
//                             'balance' => walletBalance($worker->user_id),
//                             'status' => 'successful',
//                             'currency' => $currency,
//                             'channel' => $channel,
//                             'type' => 'campaign_payment',
//                             'description' => 'Auto-approved payment for ' . $campaign->post_title,
//                             'tx_type' => 'Credit',
//                             'user_type' => 'regular'
//                         ]);
//                     });

//                     $this->info("Approved Campaign Worker ID {$worker->id}");
//                 } catch (\Exception $e) {

//                     Log::error("Auto approval failed for worker {$worker->id}: " . $e->getMessage());
//                     $this->error("Failed Campaign Worker ID {$worker->id}");
//                 }
//             }
//         }, 'campaign_workers.id');

//         Log::info('Auto campaign approval completed');
//         $this->info('Auto campaign approval completed');
//     }
// }


// class AutoApprove24Hours extends Command
// {
//     protected $signature = 'campaigns:auto-approve-24hours';
//     protected $description = 'Auto-approve pending campaign workers after 24 hours (excluding business accounts)';

//     public function handle()
//     {
//         Log::info('Auto-approve pending campaign workers after 24 hours (excluding business accounts) started');

//         $cutoffTime = Carbon::now()->subHours(24);

//         $lists = CampaignWorker::where('status', 'Pending')
//             ->whereNull('reason')
//             ->whereHas('campaign.user', function ($query) {
//                 $query->where('is_business', false);
//             })
//             ->where('created_at', '<=', $cutoffTime)
//             ->get();

//         $this->info('Found ' . $lists->count() . ' campaign workers to auto-approve.');
//         Log::info('Found ' . $lists->count() . ' campaign workers to auto-approve.');

//         foreach ($lists as $list) {
//             try {
//                 $this->approveCampaignWorker($list);
//                 $this->info('Approved: Campaign Worker ID ' . $list->id);
//             } catch (\Exception $e) {
//                 Log::error('Failed to approve campaign worker ID ' . $list->id . ': ' . $e->getMessage());
//                 $this->error('Failed: Campaign Worker ID ' . $list->id);
//             }
//         }

//         $this->info('Auto-approved ' . $lists->count() . ' campaign workers (24+ hours old).');
//         Log::info('Auto-approved ' . $lists->count() . ' campaign workers (24+ hours old).');
//     }

//     private function approveCampaignWorker($ca)
//     {
//         $ca->status = 'Approved';
//         $ca->reason = 'Auto-approval after 24 hours';
//         $ca->save();

//         $camp = Campaign::where('id', $ca->campaign_id)->first();
//         checkCampaignCompletedStatus($camp->id);

//         $user = User::where('id', $ca->user_id)->first();
//         $baseCurrency = baseCurrency($user);
//         $amountCredited = $ca->amount;

//         if ($baseCurrency == 'NGN') {
//             $currency = 'NGN';
//             $channel = 'paystack';
//             $wallet = Wallet::where('user_id', $ca->user_id)->first();
//             $wallet->balance += $amountCredited;
//             $wallet->save();
//         } elseif ($camp->currency == 'USD') {
//             $currency = 'USD';
//             $channel = 'paypal';
//             $wallet = Wallet::where('user_id', $ca->user_id)->first();
//             $wallet->usd_balance += $amountCredited;
//             $wallet->save();
//         } else {
//             $currency = baseCurrency($user);
//             $channel = 'flutterwave';
//             $wallet = Wallet::where('user_id', $ca->user_id)->first();
//             $wallet->base_currency_balance += $amountCredited;
//             $wallet->save();
//         }

//         $ref = time() . '_' . $ca->id;

//         PaymentTransaction::create([
//             'user_id' => $ca->user_id,
//             'campaign_id' => $ca->campaign_id,
//             'reference' => $ref,
//             'amount' => $amountCredited,
//             'balance' => walletBalance($ca->user_id),
//             'status' => 'successful',
//             'currency' => $currency,
//             'channel' => $channel,
//             'type' => 'campaign_payment',
//             'description' => 'Auto-approved payment for ' . $ca->campaign->post_title,
//             'tx_type' => 'Credit',
//             'user_type' => 'regular'
//         ]);
//     }
// }
