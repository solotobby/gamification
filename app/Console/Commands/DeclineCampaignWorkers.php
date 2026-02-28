<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeclineCampaignWorkers extends Command
{
    protected $signature = 'campaigns:denied-workers {campaign_id : The ID of the campaign}';
    protected $description = 'denied all campaign workers for a given campaign ID, debiting users for approved jobs';

    public function handle()
    {
        $campaignId = $this->argument('campaign_id');

        $campaign = Campaign::find($campaignId);

        if (!$campaign) {
            $this->error("Campaign ID {$campaignId} not found.");
            return 1;
        }

        $workers = CampaignWorker::where('campaign_id', $campaignId)
            ->whereIn('status', ['Pending', 'Approved'])
            ->get();

        $this->info("Found {$workers->count()} worker(s) to process for Campaign ID {$campaignId}.");
        Log::info("DeclineCampaignWorkers: Processing {$workers->count()} workers for campaign {$campaignId}");

        foreach ($workers as $worker) {
            try {
                if ($worker->status === 'Approved') {
                    $this->declineAndDebit($worker, $campaign);
                    $this->info("Denied & Debited: CampaignWorker ID {$worker->id}");
                } elseif ($worker->status === 'Pending') {
                    $this->declineOnly($worker);
                    $this->info("Denied (no debit): CampaignWorker ID {$worker->id}");
                }
            } catch (\Exception $e) {
                Log::error("DeclineCampaignWorkers: Failed on CampaignWorker ID {$worker->id}: " . $e->getMessage());
                $this->error("Failed: CampaignWorker ID {$worker->id}");
            }
        }

        $this->info('Done processing campaign workers.');
        Log::info("DeclineCampaignWorkers: Finished for campaign {$campaignId}");

        return 0;
    }

    private function declineOnly(CampaignWorker $worker): void
    {
        $worker->status = 'Denied';
        $worker->reason = 'Campaign forcefully Denied';
        $worker->save();
    }

    private function declineAndDebit(CampaignWorker $worker, Campaign $campaign): void
    {
        // Decline the worker first
        $worker->status = 'Denied';
        $worker->reason = 'Campaign forcefully Denied';
        $worker->save();

        $user = User::find($worker->user_id);
        $amountToDebit = $worker->amount;
        $baseCurrency = baseCurrency($user);

        $wallet = Wallet::where('user_id', $worker->user_id)->firstOrFail();

        // Debit the worker's wallet based on currency
        if ($baseCurrency === 'NGN') {
            $currency = 'NGN';
            $channel = 'paystack';
            $wallet->balance -= $amountToDebit;
        } elseif ($campaign->currency === 'USD') {
            $currency = 'USD';
            $channel = 'paypal';
            $wallet->usd_balance -= $amountToDebit;
        } else {
            $currency = $baseCurrency;
            $channel = 'flutterwave';
            $wallet->base_currency_balance -= $amountToDebit;
        }

        $wallet->save();

        // Log debit transaction for the worker
        $ref = time() . '_debit_' . $worker->id;
        PaymentTransaction::create([
            'user_id'     => $worker->user_id,
            'campaign_id' => $worker->campaign_id,
            'reference'   => $ref,
            'amount'      => $amountToDebit,
            'balance'     => walletBalance($worker->user_id),
            'status'      => 'successful',
            'currency'    => $currency,
            'channel'     => $channel,
            'type'        => 'campaign_payment',
            'description' => 'Debit reversal due to campaign decline: ' . $worker->campaign->post_title,
            'tx_type'     => 'Debit',
            'user_type'   => 'regular',
        ]);

    }


}
