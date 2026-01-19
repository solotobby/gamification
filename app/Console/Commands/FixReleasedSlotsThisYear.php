<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CampaignWorker;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixReleasedSlotsThisYear extends Command
{
    protected $signature = 'campaign:fix-released-slots-this-year';

    protected $description = 'Restore wrongly reduced campaign slots for released denied jobs (current year only)';

    // public function handle()
    // {
    //     $this->info('Starting slot correction for this year...');

    //     $yearStart = Carbon::now()->startOfYear();

    //     // Get released denied jobs THIS YEAR and group by campaign
    //     $releasedByCampaign = CampaignWorker::where('status', 'Denied')
    //         ->where('slot_released', true)
    //         ->where('updated_at', '>=', $yearStart)
    //         ->get()
    //         ->groupBy('campaign_id');

    //     if ($releasedByCampaign->isEmpty()) {
    //         $this->info('No released slots found for correction.');
    //         return Command::SUCCESS;
    //     }

    //     DB::transaction(function () use ($releasedByCampaign) {
    //         foreach ($releasedByCampaign as $campaignId => $workers) {
    //             $slotCount = $workers->count();

    //             $campaign = Campaign::find($campaignId);

    //             if (! $campaign) {
    //                 continue;
    //             }

    //             // Restore the wrongly reduced slots
    //             $restoredSlots = $slotCount * 2;

    //             $campaign->pending_count += $restoredSlots;
    //             $campaign->save();

    //             $this->info("Campaign ID {$campaignId}: restored {$restoredSlots} slots");

    //             Log::info('Campaign slots restored (manual correction)', [
    //                 'campaign_id' => $campaignId,
    //                 'released_slots' => $slotCount,
    //                 'slots_restored' => $restoredSlots,
    //             ]);
    //         }
    //     });

    //     $this->info('Slot correction completed successfully.');

    //     return Command::SUCCESS;
    // }
}
