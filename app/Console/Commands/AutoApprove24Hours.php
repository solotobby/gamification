<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoApprove24Hours extends Command
{
    protected $signature = 'campaigns:auto-approve-24hours';
    protected $description = 'Auto-approve pending campaign workers after 24 hours (excluding business accounts)';

    public function handle()
    {
        $startTime = Carbon::now()->subDays(1)->startOfHour();
        $endTime = Carbon::now()->subDays(1)->endOfHour();

        $lists = CampaignWorker::where('status', 'Pending')
            ->whereNull('reason')
            ->where('campaign_id', '!=', 8099)
            ->whereHas('campaign.user', function($query) {
                $query->where('is_business', false);
            })
            ->whereBetween('created_at', [$startTime, $endTime])
            ->get();

        foreach ($lists as $list) {
            $this->approveCampaignWorker($list);
        }

        $this->info('Auto-approved ' . $lists->count() . ' campaign workers (24 hours).');
    }

    private function approveCampaignWorker($ca)
    {
        $ca->status = 'Approved';
        $ca->reason = 'Auto-approval';
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

        $ref = time();

        PaymentTransaction::create([
            'user_id' => $ca->user_id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => $amountCredited,
            'balance' => walletBalance($ca->user_id),
            'status' => 'successful',
            'currency' => $currency,
            'channel' => $channel,
            'type' => 'campaign_payment',
            'description' => 'Campaign Payment for ' . $ca->campaign->post_title,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);
    }

    //    $schedule->call(function () {
    //         // $yesterday = Carbon::yesterday();
    //         // Get the start and end time for 24 hours ago
    //         $startTime = Carbon::now()->subDays(1)->startOfHour();
    //         $endTime = Carbon::now()->subDays(1)->endOfHour();
    //         // $lists =  CampaignWorker::where('status', 'Pending')->where('reason', null)
    //         //     ->whereBetween('created_at', [$startTime, $endTime])
    //         //     //->whereDate('created_at', $yesterday)
    //         //     ->get();

    //         $lists = CampaignWorker::where('status', 'Pending')
    //             ->whereNull('reason')
    //             ->where('campaign_id', '!=', 8099)
    //             ->whereBetween('created_at', [$startTime, $endTime])
    //             ->get();

    //         foreach ($lists as $list) {

    //             $ca = CampaignWorker::where('id', $list->id)->first();
    //             $ca->status = 'Approved';
    //             $ca->reason = 'Auto-approval';
    //             $ca->save();

    //             $camp = Campaign::where('id', $ca->campaign_id)->first();
    //             checkCampaignCompletedStatus($camp->id);

    //             // $camp->pending_count = $campaignStatus['Pending'] ?? 0;
    //             // $camp->completed_count = $campaignStatus['Approved'] ?? 0;
    //             // $camp->save();
    //             // $camp->completed_count += 1;
    //             // $camp->pending_count -= 1;
    //             // $camp->save();

    //             $user = User::where('id', $ca->user_id)->first();
    //             $baseCurrency = baseCurrency($user);
    //             $amountCredited = $ca->amount;
    //             if ($baseCurrency == 'NGN') {
    //                 $currency = 'NGN';
    //                 $channel = 'paystack';
    //                 $wallet = Wallet::where('user_id', $ca->user_id)->first();
    //                 // $wallet->balance += (int)$amountCredited;
    //                 $wallet->balance += $amountCredited;
    //                 $wallet->save();
    //             } elseif ($camp->currency == 'USD') {
    //                 $currency = 'USD';
    //                 $channel = 'paypal';
    //                 $wallet = Wallet::where('user_id', $ca->user_id)->first();
    //                 $wallet->usd_balance += $amountCredited;
    //                 $wallet->save();
    //             } else {
    //                 $currency = baseCurrency($user);
    //                 $channel = 'flutterwave';
    //                 $wallet = Wallet::where('user_id', $ca->user_id)->first();
    //                 $wallet->base_currency_balance += $amountCredited;
    //                 $wallet->save();
    //             }

    //             $ref = time();

    //             // setIsComplete($ca->campaign_id);

    //             PaymentTransaction::create([
    //                 'user_id' => $ca->user_id,
    //                 'campaign_id' => '1',
    //                 'reference' => $ref,
    //                 'amount' => $amountCredited,
    //                 'balance' => walletBalance($ca->user_id),
    //                 'status' => 'successful',
    //                 'currency' => $currency,
    //                 'channel' => $channel,
    //                 'type' => 'campaign_payment',
    //                 'description' => 'Campaign Payment for ' . $ca->campaign->post_title,
    //                 'tx_type' => 'Credit',
    //                 'user_type' => 'regular'
    //             ]);
    //         }

    //         //$user = User::where('id', 4)->first(); //$user['name'] = 'Oluwatobi';
    //         //$subject = 'Batched Job Approval - Notification';
    //         // $content = 'Job Automatic Approval of '.$lists->count();
    //         //Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));

    //     })->hourly();
}
