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

class AutoApprove7Days extends Command
{
    protected $signature = 'campaigns:auto-approve-7days';
    protected $description = 'Auto-approve all pending campaign workers after 7 days (excluding jobs older than 6 months)';

    public function handle()
    {

        Log::info(message: 'Jobs pending more than 7days auto approval started.');

        $sevenDaysAgo = Carbon::now()->subDays(7);
        $sixMonthsAgo = Carbon::now()->subMonths(9);

        // Fetch pending campaign workers within the allowed range
        $lists = CampaignWorker::where('status', 'Pending')
            ->where('created_at', '<=', $sevenDaysAgo)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->whereNull('reason')
            ->get();

        $count = $lists->count();
        $this->info('Found ' . $count . ' campaign workers to approve.');
        Log::info('Found ' . $count . ' campaign workers to approve.');

        foreach ($lists as $list) {
            $isPaid = PaymentTransaction::where('user_id', $list->user_id)
                ->where('campaign_id', $list->campaign_id)
                ->where('type', 'campaign_payment')
                ->first();

            if (!$isPaid) {
                $this->approveCampaignWorker($list);
            }
        }

        // Count skipped old jobs
        $skippedCount = CampaignWorker::where('status', 'Pending')
            ->where('created_at', '<', $sixMonthsAgo)
            ->count();

        if ($skippedCount > 0) {
            $this->warn('Skipped ' . $skippedCount . ' campaign workers older than 6 months.');
            Log::warn('Skipped ' . $skippedCount . ' campaign workers older than 6 months.');
        }
    }

    private function approveCampaignWorker($ca)
    {
        DB::transaction(function () use ($ca) {
            $ca = CampaignWorker::where('id', $ca->id)->lockForUpdate()->first();
            if (!$ca || $ca->status !== 'Pending') {
                return;
            }

            $ca->status = 'Approved';
            $ca->reason = 'Auto-approval';
            $ca->save();

            $camp = Campaign::where('id', $ca->campaign_id)->first();
            checkCampaignCompletedStatus($camp->id);

            $user = User::where('id', $ca->user_id)->first();
            $baseCurrency = baseCurrency($user);
            $amountCredited = (float) $ca->amount;

            $wallet = Wallet::where('user_id', $ca->user_id)->lockForUpdate()->first();
            if (!$wallet) {
                return;
            }

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

            $ref = 'AUTO7_' . time() . '_' . $ca->id;

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
                'description' => 'Campaign Payment for ' . ($camp->post_title ?? 'Task'),
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]);
        });
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
