<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\LoginPoints;
use Carbon\Carbon;

class SystemActivities{
    public static function numberFormat($number, $plus = true){
        if($number >= 1000000000){
            $number = number_format(($number/1000000000), 1);
            $number = $number > (int)$number && $plus ? (int)$number.'B+':(int)$number.'B';
            return $number;
        }
        if($number >= 1000000){
            $number = number_format(($number/1000000), 1);
            $number = $number > (int)$number && $plus ? (int)$number.'M+':(int)$number.'M';
            return $number;
        }

        if($number >= 1000){
            $number = number_format(($number/1000), 1);
            $number = $number > (int)$number && $plus ? (int)$number.'K+':(int)$number.'K';
            return $number;
        }
        return $number;
    }

    public static function loginPoints($user){
        $date = \Carbon\Carbon::today()->toDateString();
        $check = LoginPoints::where('user_id', $user->id)->where('date', $date)->first();
        
        if(!$check)
        {
            $names = explode(' ', $user->name);
            $initials = '';
            foreach ($names as $name) {
                $initials .= $name[0] . '.';
            }
            $initials = rtrim($initials, '.');
            ActivityLog::create(['user_id' => $user->id, 'activity_type' => 'login_points', 'description' =>  $initials .' earned 50 points for log in', 'user_type' => 'regular']);
            LoginPoints::create(['user_id' => $user->id, 'date' => $date, 'point' => '50']);
        }

    }

    public static function getInitials($name){
        $names = explode(' ', $name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= $name[0] . '.';
        }
        $initials = rtrim($initials, '.');
        return $initials; 
    }

    public static function activityLog($user, $activity_type, $description, $user_type){
        return ActivityLog::create(['user_id' => $user->id, 'activity_type' => $activity_type, 'description' => $description, 'user_type' => $user_type]);
    }

    public static function showActivityLog(){
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
       return ActivityLog::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('user_type', 'regular')->get();  
    }

    public static function availableJobs(){
        $campaigns = Campaign::where('status', 'Live')->orderBy('created_at', 'DESC')->get();
        $list = [];
        foreach($campaigns as $key => $value){
            $attempts = $value->completed->count();
            $completed = $value->completed()->where('status', '!=', 'Denied')->count();

            $div = $completed / $value->number_of_staff;
            $progress = $div * 100;
            $list[] = [ 
                'job_id' => $value->job_id, 
                'campaign_amount' => $value->campaign_amount,
                'post_title' => $value->post_title, 
                'number_of_staff' => $value->number_of_staff, 
                'type' => $value->campaignType->name, 
                'category' => $value->campaignCategory->name,
                'attempts' => $attempts,
                'completed' => $completed,
                'is_completed' => $completed >= $value->number_of_staff ? true : false,
                'progress' => $progress 
            ];
        }

        $sortedList = collect($list)->sortBy('is_completed')->values()->all();//collect($list)->sortByDesc('is_completed')->values()->all(); //collect($list)->sortBy('is_completed')->values()->all();

        return $sortedList;
    }

    public static function viewCampaign($campaign_id){
       $campaign = Campaign::with(['campaignType', 'campaignCategory'])->where('job_id', $campaign_id)->first();

       $data = $campaign;
       $data['current_user_id'] = auth()->user()->id;
       $data['is_attempted'] = $campaign->completed()->where('user_id', auth()->user()->id)->first() != null ? true : false;
       $data['attempts'] = $campaign->completed()->count();
       return $data;
        //$completed = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
    }

    public static function awardPoints($point_type){
        return $point_type;
    }



}