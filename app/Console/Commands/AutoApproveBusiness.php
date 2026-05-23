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


 class AutoApproveBusiness extends Command
{
    protected $signature = 'campaigns:auto-approve-business';
    protected $description = 'Auto-approve business account campaigns based on their approval time setting';

    public function handle()
    {
        Log::info('Auto business campaign approval started');

        $query = CampaignWorker::query()
            ->with(['campaign', 'user'])
            ->where('status', 'Pending')
            ->whereNull('reason')
            ->whereHas('campaign.user', function ($q) {
                $q->where('is_business', true);
            })
            ->whereHas('campaign', function ($q) {
                $q->whereRaw('DATE_ADD(campaign_workers.created_at, INTERVAL approval_time HOUR) <= NOW()');
            })
            ->orderBy('id');

        $count = $query->count();

        $this->info("Found {$count} business campaign workers to auto approve");
        Log::info("Found {$count} business campaign workers to auto approve");

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
// class AutoApproveBusiness extends Command
// {
//     protected $signature = 'campaigns:auto-approve-business';
//     protected $description = 'Auto-approve business account campaigns based on their approval time setting';

//     public function handle()
//     {

//         Log::info(message: 'Auto-approve business account campaigns based on their approval time setting started');

//         $campaigns = Campaign::whereHas('user', function($query) {
//             $query->where('is_business', true);
//         })->where('approval_time', '>', 0)->get();

//         $totalApproved = 0;

//         foreach ($campaigns as $campaign) {
//             $approvalTime = $campaign->approval_time;
//             $cutoffTime = Carbon::now()->subHours($approvalTime);

//             $lists = CampaignWorker::where('campaign_id', $campaign->id)
//                 ->where('status', 'Pending')
//                 ->whereNull('reason')
//                 ->where('created_at', '<=', $cutoffTime)
//                 ->get();

//             foreach ($lists as $list) {
//                 $this->approveCampaignWorker($list);
//                 $totalApproved++;
//             }
//         }

//         $this->info('Auto-approved ' . $totalApproved . ' business campaign workers.');
//         Log::info('Auto-approved ' . $totalApproved . ' business campaign workers.');
//     }

//     private function approveCampaignWorker($ca)
//     {
//         $ca->status = 'Approved';
//         $ca->reason = 'Auto-approval';
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

//         $ref = time();

//         PaymentTransaction::create([
//             'user_id' => $ca->user_id,
//             'campaign_id' => '1',
//             'reference' => $ref,
//             'amount' => $amountCredited,
//             'balance' => walletBalance($ca->user_id),
//             'status' => 'successful',
//             'currency' => $currency,
//             'channel' => $channel,
//             'type' => 'campaign_payment',
//             'description' => 'Campaign Payment for ' . $ca->campaign->post_title,
//             'tx_type' => 'Credit',
//             'user_type' => 'regular'
//         ]);
//     }
// }
