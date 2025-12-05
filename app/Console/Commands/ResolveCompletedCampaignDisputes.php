<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CampaignWorker;
use App\Models\DisputedJobs;
use App\Models\Campaign;

class ResolveCompletedCampaignDisputes extends Command
{
    protected $signature = 'disputes:resolve-completed';
    protected $description = 'Auto-resolve disputes for already completed campaigns';

    public function handle()
    {
        // Get all unresolved disputes where campaign is completed
        $disputes = CampaignWorker::where('is_dispute', true)
            ->where('is_dispute_resolved', false)
            ->whereHas('campaign', function($query) {
                $query->where('is_completed', true);
            })
            ->get();

        $count = 0;

        foreach ($disputes as $workDone) {
            // Mark dispute as resolved
            $workDone->is_dispute_resolved = true;
            $workDone->is_dispute = false;
            $workDone->save();

            // Update dispute record if exists
            $disputeJob = DisputedJobs::where('campaign_worker_id', $workDone->id)->first();
            if ($disputeJob) {
                // $disputeJob->is_resolved = true;
                $disputeJob->response = 'Auto-resolved: Campaign already completed';
                $disputeJob->save();
            }

            $count++;
        }

        $this->info("Resolved {$count} disputes for completed campaigns");

        return 0;
    }
}
