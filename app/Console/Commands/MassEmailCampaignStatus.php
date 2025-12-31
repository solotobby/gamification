<?php

namespace App\Console\Commands;

use App\Models\MassEmailCampaign;
use App\Models\MassEmailLog;
use Illuminate\Console\Command;

class MassEmailCampaignStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mass-email:status {campaign_id? : The campaign ID to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of mass email campaigns';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $campaignId = $this->argument('campaign_id');

        if ($campaignId) {
            return $this->showCampaignStatus($campaignId);
        }

        // Show all email campaigns summary
        $this->showAllCampaigns();

        return Command::SUCCESS;
    }

    protected function showCampaignStatus($campaignId)
    {
        $campaign = MassEmailCampaign::where('channel', 'email')->find($campaignId);

        if (!$campaign) {
            $this->error("Email campaign with ID {$campaignId} not found.");
            return Command::FAILURE;
        }

        $stats = MassEmailLog::where('campaign_id', $campaignId)
            ->where('channel', 'email')
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed
            ')
            ->first();

        $this->info("Campaign Details:");
        $this->newLine();

        $this->table(
            ['Property', 'Value'],
            [
                ['ID', $campaign->id],
                ['Subject', $campaign->subject],
                ['Audience Type', $campaign->audience_type],
                ['Country Filter', $campaign->country_filter ?: 'All'],
                ['Date Range', $this->getDateRange($campaign)],
                ['Created', $campaign->created_at->format('Y-m-d H:i:s')],
                ['Sent By', $campaign->sentBy->name ?? 'N/A'],
            ]
        );

        $this->newLine();
        $this->info("Email Status:");
        $this->newLine();

        $total = $stats->total;
        $sent = $stats->sent;
        $pending = $stats->pending;
        $failed = $stats->failed;

        $sentPercent = $total > 0 ? round(($sent / $total) * 100, 2) : 0;
        $pendingPercent = $total > 0 ? round(($pending / $total) * 100, 2) : 0;
        $failedPercent = $total > 0 ? round(($failed / $total) * 100, 2) : 0;

        $this->table(
            ['Status', 'Count', 'Percentage'],
            [
                ['Total', $total, '100%'],
                ['Sent', $sent, $sentPercent . '%'],
                ['Pending', $pending, $pendingPercent . '%'],
                ['Failed', $failed, $failedPercent . '%'],
            ]
        );

        if ($pending > 0) {
            $this->newLine();
            $this->warn("⚠️  {$pending} email(s) are still pending.");
            $this->info("Run: php artisan mass-email:retry-pending {$campaignId}");
        }

        if ($failed > 0) {
            $this->newLine();
            $this->error("❌ {$failed} email(s) failed to send.");
            $this->info("Run: php artisan mass-email:retry-failed {$campaignId}");

            // Show recent errors
            $recentErrors = MassEmailLog::where('campaign_id', $campaignId)
                ->where('channel', 'email')
                ->where('status', 'failed')
                ->whereNotNull('error_message')
                ->select('error_message')
                ->limit(5)
                ->get()
                ->pluck('error_message')
                ->unique();

            if ($recentErrors->isNotEmpty()) {
                $this->newLine();
                $this->info("Recent error messages:");
                foreach ($recentErrors as $error) {
                    $this->line("  • " . \Str::limit($error, 80));
                }
            }
        }

        if ($sent === $total && $total > 0) {
            $this->newLine();
            $this->info("✓ All emails sent successfully!");
        }

        return Command::SUCCESS;
    }

    protected function showAllCampaigns()
    {
        $campaigns = MassEmailCampaign::where('channel', 'email')
            ->with('sentBy')
            ->withCount([
                'logs as total_logs' => fn($q) => $q->where('channel', 'email'),
                'logs as sent_count' => fn($q) => $q->where('channel', 'email')->where('status', 'sent'),
                'logs as pending_count' => fn($q) => $q->where('channel', 'email')->where('status', 'pending'),
                'logs as failed_count' => fn($q) => $q->where('channel', 'email')->where('status', 'failed'),
            ])
            ->latest()
            ->take(20)
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('No email campaigns found.');
            return;
        }

        $this->info('Recent Mass Email Campaigns:');
        $this->newLine();

        $headers = ['ID', 'Subject', 'Total', 'Sent', 'Pending', 'Failed', 'Created'];
        $rows = [];

        foreach ($campaigns as $campaign) {
            $rows[] = [
                $campaign->id,
                \Str::limit($campaign->subject, 35),
                $campaign->total_logs,
                $campaign->sent_count,
                $campaign->pending_count,
                $campaign->failed_count,
                $campaign->created_at->format('M d, H:i'),
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();
        $this->info('Run with campaign ID for details: php artisan mass-email:status {campaign_id}');
    }

    protected function getDateRange($campaign)
    {
        if ($campaign->start_date && $campaign->end_date) {
            return $campaign->start_date->format('M d') . ' - ' . $campaign->end_date->format('M d, Y');
        }
        if ($campaign->days_filter) {
            return "Last {$campaign->days_filter} days";
        }
        return 'All time';
    }
}
