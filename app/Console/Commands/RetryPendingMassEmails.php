<?php

namespace App\Console\Commands;

use App\Models\MassEmailCampaign;
use App\Models\MassEmailLog;
use App\Jobs\SendMassEmail;
use Illuminate\Console\Command;

class RetryPendingMassEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mass-email:retry-pending {campaign_id? : The campaign ID to retry}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry pending mass email campaigns';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $campaignId = $this->argument('campaign_id');

        if ($campaignId) {
            return $this->retryCampaign($campaignId);
        }

        // If no campaign_id, show list of campaigns with pending emails
        $this->listPendingCampaigns();

        return Command::SUCCESS;
    }

    protected function retryCampaign($campaignId)
    {
        $campaign = MassEmailCampaign::where('channel', 'email')->find($campaignId);

        if (!$campaign) {
            $this->error("Email campaign with ID {$campaignId} not found.");
            return Command::FAILURE;
        }

        $pendingCount = MassEmailLog::where('campaign_id', $campaignId)
            ->where('channel', 'email')
            ->where('status', 'pending')
            ->count();

        if ($pendingCount === 0) {
            $this->info("No pending emails found for campaign ID {$campaignId}");
            return Command::SUCCESS;
        }

        $this->info("Found {$pendingCount} pending emails for campaign: {$campaign->subject}");

        if (!$this->confirm('Do you want to retry sending these emails?', true)) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        $this->info('Dispatching email jobs...');

        $bar = $this->output->createProgressBar($pendingCount);
        $bar->start();

        $jobsDispatched = 0;

        MassEmailLog::where('campaign_id', $campaign->id)
            ->where('channel', 'email')
            ->where('status', 'pending')
            ->select('user_id')
            ->chunk(50, function ($logs) use ($campaign, $bar, &$jobsDispatched) {
                $userIds = $logs->pluck('user_id')->toArray();

                dispatch(new SendMassEmail(
                    $userIds,
                    $campaign->message,
                    $campaign->subject,
                    $campaign->id,
                    'https://res.cloudinary.com/dwisk11nl/image/upload/v1767172508/uploads/mvh1cacam5uu3kp8doz3.jpg'
                ));

                $jobsDispatched++;
                $bar->advance(count($userIds));
            });

        $bar->finish();
        $this->newLine(2);
        $this->info("Successfully dispatched {$jobsDispatched} job(s) for campaign ID {$campaignId}");
        $this->info("Total pending emails: {$pendingCount}");

        return Command::SUCCESS;
    }

    protected function listPendingCampaigns()
    {
        $campaigns = MassEmailCampaign::where('channel', 'email')
            ->whereHas('logs', function ($query) {
                $query->where('channel', 'email')->where('status', 'pending');
            })
            ->withCount(['logs as pending_count' => function ($query) {
                $query->where('channel', 'email')->where('status', 'pending');
            }])
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('No email campaigns with pending messages found.');
            return;
        }

        $this->info('Email campaigns with pending messages:');
        $this->newLine();

        $headers = ['ID', 'Subject', 'Pending Count', 'Created At'];
        $rows = [];

        foreach ($campaigns as $campaign) {
            $rows[] = [
                $campaign->id,
                \Str::limit($campaign->subject, 50),
                $campaign->pending_count,
                $campaign->created_at->diffForHumans(),
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();
        $this->info('Run with campaign ID to retry: php artisan mass-email:retry-pending {campaign_id}');
    }
}
