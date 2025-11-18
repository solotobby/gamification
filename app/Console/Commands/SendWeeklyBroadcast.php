<?php

namespace App\Console\Commands;

use App\Mail\JobBroadcast;
use App\Models\Campaign;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyBroadcast extends Command
{
    protected $signature = 'campaigns:send-weekly-broadcast';
    protected $description = 'Send weekly campaign broadcast to users registered last week';

    public function handle()
    {
        $campaigns = Campaign::where('status', 'Live')
            ->where('is_completed', false)
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
            ];
        }

        $filteredArray = array_filter($list, function ($item) {
            return $item['is_completed'] !== true;
        });

        $startOfWeek = Carbon::now()->startOfWeek()->subWeek();
        $endOfWeek = Carbon::now()->endOfWeek()->subWeek();
        $usersLastWeek = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();

        foreach ($usersLastWeek as $user) {
            $subject = 'Fresh Campaign';
            Mail::to($user->email)->send(new JobBroadcast($user, $subject, $filteredArray));
        }

        $this->info('Weekly broadcast sent to ' . $usersLastWeek->count() . ' users.');
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
}
