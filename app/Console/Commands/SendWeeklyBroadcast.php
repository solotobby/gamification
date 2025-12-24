<?php

namespace App\Console\Commands;

use App\Jobs\SendBroadcastEmailJob;
use App\Models\Campaign;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendWeeklyBroadcast extends Command
{
    protected $signature = 'campaigns:send-weekly-broadcast {startDays=0 : Days ago for start date} {endDays=30 : Days ago for end date}';
    protected $description = 'Send weekly campaign broadcast to active users within specified date range';

    public function handle()
    {
        $startDays = (int) $this->argument('startDays');
        $endDays = (int) $this->argument('endDays');

        Log::info("Send campaign broadcast started (startDays: {$startDays}, endDays: {$endDays})");

        // Get campaigns prioritized first, then ordered by created_at
        $campaigns = Campaign::where('status', 'Live')
            ->where('is_completed', false)
            ->orderByRaw("CASE WHEN approved = 'Priotized' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'DESC')
            ->take(20)
            ->get();

        $list = [];
        foreach ($campaigns as $value) {
            $c = $value->pending_count + $value->completed_count;

            $list[] = [
                'id' => $value->id,
                'job_id' => $value->job_id,
                'campaign_amount' => $value->campaign_amount,
                'post_title' => $value->post_title,
                'type' => $value->campaignType->name,
                'category' => $value->campaignCategory->name,
                'is_completed' => $c >= $value->number_of_staff ? true : false,
                'currency' => $value->currency,
                'is_prioritized' => $value->approved === 'Priotized',
            ];
        }

        $filteredArray = array_filter($list, function ($item) {
            return $item['is_completed'] !== true;
        });


        $startDate = $startDays === 0 ? Carbon::now() : Carbon::now()->subDays($startDays);
        $endDate = Carbon::now()->subDays($endDays);

        $this->info("Fetching users active between {$startDate->toDateString()} and {$endDate->toDateString()}");

        $activeUserIds = DB::table('activity_logs')
            ->whereBetween('created_at', [$endDate, $startDate])
            ->distinct()
            ->pluck('user_id');

        if ($activeUserIds->isEmpty()) {
            $this->info('No active users found in the specified date range.');
            Log::info('No active users found in the specified date range.');
            return;
        }

        $subject = 'Fresh Campaign Just For You!';

        // Process users in chunks of 50
        User::whereIn('id', $activeUserIds)
            ->chunk(50, function ($users) use ($subject, $filteredArray) {
                foreach ($users as $user) {
                    SendBroadcastEmailJob::dispatch($user, $subject, $filteredArray);
                }
            });

        $totalUsers = count($activeUserIds);
        $this->info("Campaign broadcast queued for {$totalUsers} active users in chunks of 50.");
        Log::info("Campaign broadcast queued for {$totalUsers} users (startDays: {$startDays}, endDays: {$endDays})");
    }
}

    //    $schedule->call(function () {
    //         $campaigns = Campaign::where('status', 'Live')->where('is_completed', false)->orderBy('created_at', 'DESC')->take(20)->get();

    //         $list = [];
    //         foreach ($campaigns as $key => $value) {

    //             $c = $value->pending_count + $value->completed_count;
    //             //$div = $c / $value->number_of_staff;
    //             // $progress = $div * 100;

    //             $list[] = [
    //                 'id' => $value->id,
    //                 'job_id' => $value->job_id,
    //                 'campaign_amount' => $value->campaign_amount,
    //                 'post_title' => $value->post_title,
    //                 //'number_of_staff' => $value->number_of_staff,
    //                 'type' => $value->campaignType->name,
    //                 'category' => $value->campaignCategory->name,
    //                 //'attempts' => $attempts,
    //                 //'completed' => $c, //$value->completed_count+$value->pending_count,
    //                 'is_completed' => $c >= $value->number_of_staff ? true : false,
    //                 //'progress' => $progress,
    //                 'currency' => $value->currency,
    //                 //'created_at' => $value->created_at
    //             ];
    //         }

    //         //$sortedList = collect($list)->sortBy('is_completed')->values()->all();//collect($list)->sortByDesc('is_completed')->values()->all(); //collect($list)->sortBy('is_completed')->values()->all();

    //         // Remove objects where 'is_completed' is true
    //         $filteredArray = array_filter($list, function ($item) {
    //             return $item['is_completed'] !== true;
    //         });

    //         // return $filteredArray;
    //         $startOfWeek = Carbon::now()->startOfWeek()->subWeek();
    //         $endOfWeek = Carbon::now()->endOfWeek()->subWeek();

    //         // Query users registered within last week
    //         $usersLastWeek = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();

    //         // $user = User::where('id', 1)->first();
    //         foreach ($usersLastWeek as $user) {
    //             $subject = 'Fresh Campaign';
    //             Mail::to($user->email)->send(new JobBroadcast($user, $subject, $filteredArray));
    //         }
    //     })->daily(); //d
