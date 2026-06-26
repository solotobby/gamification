<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use Illuminate\Console\Command;

class UpdateCompletedCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:update-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update campaign completion status and fix invalid pending counts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fixedPending = 0;
        $completedCampaigns = 0;
        $closedCampaigns = 0;

        /**
         * ======================================================
         * Action 1:
         * Reset negative pending_count values to 0
         * ======================================================
         */
        $this->info('Fixing negative pending counts...');

        Campaign::where('pending_count', '<', 0)
            ->chunkById(500, function ($campaigns) use (&$fixedPending) {

                foreach ($campaigns as $campaign) {

                    $campaign->update([
                        'pending_count' => 0,
                    ]);

                    $fixedPending++;

                    $this->line("✓ Campaign #{$campaign->id}: pending_count reset to 0");
                }
            });

        /**
         * ======================================================
         * Action 2:
         * Mark campaign as Completed when
         * completed_count >= number_of_staff
         * ======================================================
         */
        $this->info('Marking completed campaigns...');

        Campaign::where('status', '!=', 'Completed')
            ->whereColumn('completed_count', '>=', 'number_of_staff')
            ->chunkById(500, function ($campaigns) use (&$completedCampaigns) {

                foreach ($campaigns as $campaign) {

                    $campaign->update([
                        'is_completed' => true,
                        'status'       => 'Completed',
                    ]);

                    $completedCampaigns++;

                    $this->line("✓ Campaign #{$campaign->id}: marked Completed");
                }
            });

        /**
         * ======================================================
         * Action 3:
         * Stop accepting new workers when
         * completed_count + pending_count >= number_of_staff
         *
         * Leave status unchanged.
         * ======================================================
         */
        $this->info('Closing filled campaigns...');

        Campaign::where('status', '!=', 'Completed')
            ->where('is_completed', false)
            ->whereRaw('(completed_count + pending_count) >= number_of_staff')
            ->chunkById(500, function ($campaigns) use (&$closedCampaigns) {

                foreach ($campaigns as $campaign) {

                    $campaign->update([
                        'is_completed' => true,
                    ]);

                    $closedCampaigns++;

                    $this->line("✓ Campaign #{$campaign->id}: closed");
                }
            });

        $this->newLine();
        $this->info('=======================================');
        $this->info("Negative pending counts fixed : {$fixedPending}");
        $this->info("Campaigns marked Completed    : {$completedCampaigns}");
        $this->info("Campaigns closed              : {$closedCampaigns}");
        $this->info('=======================================');

        return Command::SUCCESS;
    }
}
