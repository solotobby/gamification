<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CampaignWorker;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReleaseExpiredDeniedSlots extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'campaign:release-expired-slots';

    /**
     * The console command description.
     */
    protected $description = 'Release campaign slots that were denied and not disputed within 12 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to release expired denied slots...');

        // Find all denied jobs where:
        // 1. Status is 'Denied'
        // 2. Slot has not been released yet
        // 3. No dispute has been raised
        // 4. denied_at is more than 12 hours ago
        $expiredDenials = CampaignWorker::where('status', 'Denied')
            ->where('slot_released', false)
            ->where('is_dispute', false)
            ->whereNotNull('denied_at')
            ->where('denied_at', '<=', Carbon::now()->subHours(12))
            ->get();

        $releasedCount = 0;

        foreach ($expiredDenials as $deniedWork) {
            try {
                // Release the slot
                $deniedWork->slot_released = true;
                $deniedWork->save();

                // Increase the pending count for the campaign
                $campaign = Campaign::find($deniedWork->campaign_id);
                if ($campaign && $campaign->pending_count > 0) {
                    $campaign->pending_count += 1;
                    $campaign->save();

                    $this->info("Released slot for Campaign ID: {$campaign->id}, Worker ID: {$deniedWork->id}");
                    $releasedCount++;
                }

                // Log the action
                Log::info("Slot released automatically", [
                    'campaign_worker_id' => $deniedWork->id,
                    'campaign_id' => $deniedWork->campaign_id,
                    'user_id' => $deniedWork->user_id,
                    'denied_at' => $deniedWork->denied_at,
                    'released_at' => now()
                ]);

            } catch (\Exception $e) {
                $this->error("Error releasing slot for Worker ID {$deniedWork->id}: {$e->getMessage()}");
                Log::error("Failed to release slot", [
                    'campaign_worker_id' => $deniedWork->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Successfully released {$releasedCount} expired slots.");

        return 0;
    }
}
