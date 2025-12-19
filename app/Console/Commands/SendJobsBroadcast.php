<?php

namespace App\Console\Commands;

use App\Jobs\SendJobBroadcastEmailJob;
use App\Models\JobListing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendJobsBroadcast extends Command
{
    protected $signature = 'jobs:send-broadcast';
    protected $description = 'Send job listings broadcast to active users in the last month';

    public function handle()
    {
        Log::info('Send job listings broadcast to active users started');

        // Get active job listings
        $jobs = JobListing::active()
            ->with('postedBy:id,name')
            ->where('tier', 'free')
            ->orderBy('created_at', 'DESC')
            ->take(10)
            ->get();

        if ($jobs->isEmpty()) {
            $this->info('No active jobs found.');
            return;
        }

        $jobList = $jobs->map(function ($job) {
            return [
                'id' => $job->id,
                'slug' => $job->slug,
                'title' => $job->title,
                'company_name' => $job->company_name,
                'company_logo' => displayImage($job->company_logo),
                'location' => $job->location,
                'type' => $job->type,
                'tier' => $job->tier,
                'salary_range' => $job->salary_range,
                'remote_allowed' => $job->remote_allowed,
                'posted_at' => $job->created_at->diffForHumans(),
            ];
        })->toArray();

        // Get active users in the last month
        $startDate = Carbon::now()->subMonth();
        $endDate = Carbon::now();

        $activeUserIds = DB::table('activity_logs')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('user_id');

        $activeUsers = User::whereIn('id', $activeUserIds)->get();

        $subject = 'User, We found Jobs that matches your Skills/Interests';

        foreach ($activeUsers as $user) {
            SendJobBroadcastEmailJob::dispatch($user, $subject, $jobList);
        }

        $this->info('Job broadcast queued for ' . $activeUsers->count() . ' active users.');
        Log::info('Job broadcast queued for ' . $activeUsers->count() . ' users');
    }
}
