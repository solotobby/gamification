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

class AutoApproveBusiness extends Command
{
    protected $signature = 'campaigns:auto-approve-business';
    protected $description = 'Auto-approve business account campaigns based on their approval time setting';

    public function handle()
    {

        Log::info(message: 'Auto-approve business account campaigns based on their approval time setting started');

        $campaigns = Campaign::whereHas('user', function($query) {
            $query->where('is_business', true);
        })->where('approval_time', '>', 0)->get();

        $totalApproved = 0;

        foreach ($campaigns as $campaign) {
            $approvalTime = $campaign->approval_time;
            $cutoffTime = Carbon::now()->subHours($approvalTime);

            $lists = CampaignWorker::where('campaign_id', $campaign->id)
                ->where('status', 'Pending')
                ->whereNull('reason')
                ->where('created_at', '<=', $cutoffTime)
                ->get();

            foreach ($lists as $list) {
                $this->approveCampaignWorker($list);
                $totalApproved++;
            }
        }

        $this->info('Auto-approved ' . $totalApproved . ' business campaign workers.');
        Log::info('Auto-approved ' . $totalApproved . ' business campaign workers.');
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
}
