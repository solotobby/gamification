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

class AutoApprove7Days extends Command
{
    protected $signature = 'campaigns:auto-approve-7days';
    protected $description = 'Auto-approve all pending campaign workers after 7 days (excluding jobs older than 6 months)';

    public function handle()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $lists = CampaignWorker::where('status', 'Pending')
            ->whereNull('reason')
            ->where('created_at', '<=', $sevenDaysAgo)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->get();

        $approvedCount = 0;
        $skippedCount = 0;

        foreach ($lists as $list) {
            // Double check if campaign is not older than 6 months
            if ($list->created_at->greaterThanOrEqualTo($sixMonthsAgo)) {
                $this->approveCampaignWorker($list);
                $approvedCount++;
            } else {
                $skippedCount++;
            }
        }

        $this->info('Auto-approved ' . $approvedCount . ' campaign workers (7 days).');

        if ($skippedCount > 0) {
            $this->warn('Skipped ' . $skippedCount . ' campaign workers older than 6 months.');
        }
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
        $amountCredited = (float) $ca->amount;

        $wallet = Wallet::where('user_id', $ca->user_id)->first();

        // Ensure numeric fields
        $wallet->balance = (float) ($wallet->balance ?? 0);
        $wallet->usd_balance = (float) ($wallet->usd_balance ?? 0);
        $wallet->base_currency_balance = (float) ($wallet->base_currency_balance ?? 0);

        if ($baseCurrency == 'NGN') {
            $currency = 'NGN';
            $channel = 'paystack';
            $wallet->balance += $amountCredited;
        } elseif ($camp->currency == 'USD') {
            $currency = 'USD';
            $channel = 'paypal';
            $wallet->usd_balance += $amountCredited;
        } else {
            $currency = $baseCurrency;
            $channel = 'flutterwave';
            $wallet->base_currency_balance += $amountCredited;
        }

        $wallet->save();

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


    // private function approveCampaignWorker($ca)
    // {
    //     $ca->status = 'Approved';
    //     $ca->reason = 'Auto-approval';
    //     $ca->save();

    //     $camp = Campaign::where('id', $ca->campaign_id)->first();
    //     checkCampaignCompletedStatus($camp->id);

    //     $user = User::where('id', $ca->user_id)->first();
    //     $baseCurrency = baseCurrency($user);
    //     $amountCredited = $ca->amount;

    //     if ($baseCurrency == 'NGN') {
    //         $currency = 'NGN';
    //         $channel = 'paystack';
    //         $wallet = Wallet::where('user_id', $ca->user_id)->first();
    //         $wallet->balance += $amountCredited;
    //         $wallet->save();
    //     } elseif ($camp->currency == 'USD') {
    //         $currency = 'USD';
    //         $channel = 'paypal';
    //         $wallet = Wallet::where('user_id', $ca->user_id)->first();
    //         $wallet->usd_balance += $amountCredited;
    //         $wallet->save();
    //     } else {
    //         $currency = baseCurrency($user);
    //         $channel = 'flutterwave';
    //         $wallet = Wallet::where('user_id', $ca->user_id)->first();
    //         $wallet->base_currency_balance += $amountCredited;
    //         $wallet->save();
    //     }

    //     $ref = time();

    //     PaymentTransaction::create([
    //         'user_id' => $ca->user_id,
    //         'campaign_id' => '1',
    //         'reference' => $ref,
    //         'amount' => $amountCredited,
    //         'balance' => walletBalance($ca->user_id),
    //         'status' => 'successful',
    //         'currency' => $currency,
    //         'channel' => $channel,
    //         'type' => 'campaign_payment',
    //         'description' => 'Campaign Payment for ' . $ca->campaign->post_title,
    //         'tx_type' => 'Credit',
    //         'user_type' => 'regular'
    //     ]);
    // }
}
