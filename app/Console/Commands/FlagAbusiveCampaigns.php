<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\User;
use App\Mail\GeneralMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class FlagAbusiveCampaigns extends Command
{
    protected $signature = 'campaigns:flag-abusive';
    protected $description = 'Flag campaigns where owner denied 70% or more of campaign workers';

    public function handle()
    {
        $liveCampaigns = Campaign::where('status', 'Live')
            ->where('is_completed', false)
            ->get();

        $flaggedCount = 0;

        foreach ($liveCampaigns as $campaign) {
            // Get all campaign workers for this campaign
            $totalWorkers = CampaignWorker::where('campaign_id', $campaign->id)->count();

            // Skip if no workers yet
            if ($totalWorkers < 10) {
                continue;
            }

            // Count denied workers
            $deniedWorkers = CampaignWorker::where('campaign_id', $campaign->id)
                ->where('status', 'Denied')
                ->count();

            // Calculate denial percentage
            $denialPercentage = ($deniedWorkers / $totalWorkers) * 100;

            // Flag if 70% or more denied
            if ($denialPercentage >= 70) {
                $campaign->status = 'Flagged';
                $campaign->flagged_at = now();
                $campaign->flagged_reason = "Campaign flagged: {$denialPercentage}% denial rate ({$deniedWorkers}/{$totalWorkers} workers denied)";
                $campaign->save();

                // Notify campaign owner
                $this->notifyCampaignOwner($campaign, $denialPercentage, $deniedWorkers, $totalWorkers);

                $flaggedCount++;
                
                $this->warn("Flagged Campaign ID {$campaign->id}: {$denialPercentage}% denial rate");
            }
        }

        $this->info("Flagged {$flaggedCount} abusive campaigns.");

        // Notify admin
        if ($flaggedCount > 0) {
            $this->notifyAdmin($flaggedCount);
        }
    }

    private function notifyCampaignOwner($campaign, $denialPercentage, $deniedWorkers, $totalWorkers)
    {
        $user = User::find($campaign->user_id);
        
        if ($user) {
            $subject = 'Campaign Flagged - High Denial Rate';
            $content = "Your campaign '{$campaign->post_title}' has been flagged due to excessive worker denials. 
                        You denied {$deniedWorkers} out of {$totalWorkers} workers (" . round($denialPercentage, 2) . "%). 
                        This campaign cannot be reactivated. Please contact support if you believe this is an error.";
            
            try {
                Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
            } catch (\Exception $e) {
                $this->error("Failed to send email to user {$user->id}: " . $e->getMessage());
            }
        }
    }

    private function notifyAdmin($count)
    {
        $adminUser = User::where('id', 1)->first();
        
        if ($adminUser) {
            $subject = 'Campaigns Flagged - Automated Report';
            $content = "{$count} campaigns were flagged for excessive worker denials (70%+ denial rate).";
            
            try {
                 if (config('app.env') == 'Production') {

                    Mail::to('hello@freebyztechnologies.com')->cc('blessing@freebyztechnologies.com')->send(new GeneralMail($adminUser, $content, $subject, ''));
                 }
            } catch (\Exception $e) {
                $this->error("Failed to send admin notification: " . $e->getMessage());
            }
        }
    }
}