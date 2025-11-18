<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoApprove7Days extends Command
{
    protected $signature = 'campaigns:auto-approve-7days';
    protected $description = 'Auto-approve all pending campaign workers after 7 days';

    public function handle()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $lists = CampaignWorker::where('status', 'Pending')
            ->whereNull('reason')
            ->where('created_at', '<=', $sevenDaysAgo)
            ->get();

        foreach ($lists as $list) {
            $this->approveCampaignWorker($list);
        }

        $this->info('Auto-approved ' . $lists->count() . ' campaign workers (7 days).');
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
