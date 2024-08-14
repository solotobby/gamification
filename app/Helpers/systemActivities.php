<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\LoginPoints;
use App\Models\Referral;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        // $date = \Carbon\Carbon::today()->toDateString();
        // $check = LoginPoints::where('user_id', $user->id)->where('date', $date)->first();
        
        // if(!$check)
        // {
        //     $names = explode(' ', $user->name);
        //     $initials = '';
        //     foreach ($names as $name) {
        //         $initials .= isset($name[0]) . '.';
        //     }
        //     $initials = rtrim($initials, '.');
        //     ActivityLog::create(['user_id' => $user->id, 'activity_type' => 'login_points', 'description' =>  $initials .' earned 50 points for log in', 'user_type' => 'regular']);
            // LoginPoints::create(['user_id' => $user->id, 'date' => $date, 'point' => '50']);
        // }

    }

    

    public static function getInitials($name){
        $names = explode(' ', $name);
        $initials = '';
        foreach ($names as $name) {
            $initials .= isset($name[0]) . '.';
        }
        $initials = rtrim($initials, '.');
        return $initials; 
    }
    ///this is very important, cannot be removed
    public static function activityLog($user, $activity_type, $description, $user_type){
        return ActivityLog::create(['user_id' => $user->id, 'activity_type' => $activity_type, 'description' => $description, 'user_type' => $user_type]);
    }

    public static function showActivityLog(){
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
       return ActivityLog::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('user_type', 'regular')->get();  
    }

    public static function filterCampaign($categoryID){
        $user = Auth::user();
        $jobfilter = '';
        $campaigns = '';

        if($user){
            $jobfilter= $user->wallet->base_currency == 'Naira' ? 'NGN' : 'USD';
        }

        if($user->USD_verified){ //if user is usd verified, they see all jobs
            if($categoryID == 0){
                $campaigns = Campaign::where('status', 'Live')->where('is_completed', false)->orderBy('created_at', 'DESC')->get();
            }else{
                $campaigns = Campaign::where('status', 'Live')->where('campaign_type', $categoryID)->where('is_completed', false)->orderBy('created_at', 'DESC')->get();
            }
            
        }else{
            if($categoryID == 0){
                $campaigns = Campaign::where('status', 'Live')->where('currency', $jobfilter)->where('is_completed', false)->orderBy('created_at', 'DESC')->get();
            }else{
                $campaigns = Campaign::where('status', 'Live')->where('currency', $jobfilter)->where('campaign_type', $categoryID)->where('is_completed', false)->orderBy('created_at', 'DESC')->get();
            }
            
        }

      

        $list = [];
        foreach($campaigns as $key => $value){
            $c = $value->pending_count + $value->completed_count;//
            $div = $c / $value->number_of_staff;
            $progress = $div * 100;

            $list[] = [ 
                'id' => $value->id, 
                'job_id' => $value->job_id, 
                'campaign_amount' => $value->campaign_amount,
                'post_title' => $value->post_title, 
                'number_of_staff' => $value->number_of_staff, 
                'type' => $value->campaignType->name, 
                'category' => $value->campaignCategory->name,
                //'attempts' => $attempts,
                'completed' => $c, //$value->completed_count+$value->pending_count,
                'is_completed' => $c >= $value->number_of_staff ? true : false,
                'progress' => $progress,
                'currency' => $value->currency,
                'currency_code' => $value->currency == 'NGN' ? '&#8358;' : '$',
                'priotized' => $value->approved,
                // 'created_at' => $value->created_at
            ];
        }

        //$sortedList = collect($list)->sortBy('is_completed')->values()->all();//collect($list)->sortByDesc('is_completed')->values()->all(); //collect($list)->sortBy('is_completed')->values()->all();

        // Remove objects where 'is_completed' is true
        $filteredArray = array_filter($list, function ($item) {
            return $item['is_completed'] !== true;
        });

        // Sort the array to prioritize 'Priotized'
        usort($filteredArray, function ($a, $b) {
            return strcmp($b['priotized'], $a['priotized']);
        });

         return  $filteredArray;
      
    }

    public static function availableJobs(){ //depreciated
        $user = Auth::user();
        $jobfilter = '';
        $campaigns = '';

        if($user){
            $jobfilter= $user->wallet->base_currency == 'Naira' ? 'NGN' : 'USD';
        }

        if($user->USD_verified){ //if user is usd verified, they see all jobs
            $campaigns = Campaign::where('status', 'Live')->where('is_completed', false)->orderBy('created_at', 'DESC')->get();
        }else{
            $campaigns = Campaign::where('status', 'Live')->where('currency', $jobfilter)->where('is_completed', false)->orderBy('created_at', 'DESC')->get();
        }
        
        $list = [];
        foreach($campaigns as $key => $value){
            $c = $value->pending_count + $value->completed_count;//
            $div = $c / $value->number_of_staff;
            $progress = $div * 100;

            $list[] = [ 
                'id' => $value->id, 
                'job_id' => $value->job_id, 
                'campaign_amount' => $value->campaign_amount,
                'post_title' => $value->post_title, 
                'number_of_staff' => $value->number_of_staff, 
                'type' => $value->campaignType->name, 
                'category' => $value->campaignCategory->name,
                //'attempts' => $attempts,
                'completed' => $c, //$value->completed_count+$value->pending_count,
                'is_completed' => $c >= $value->number_of_staff ? true : false,
                'progress' => $progress,
                'currency' => $value->currency,
                'priotized' => $value->approved,
                // 'created_at' => $value->created_at
            ];
        }

        //$sortedList = collect($list)->sortBy('is_completed')->values()->all();//collect($list)->sortByDesc('is_completed')->values()->all(); //collect($list)->sortBy('is_completed')->values()->all();

        // Remove objects where 'is_completed' is true
        $filteredArray = array_filter($list, function ($item) {
            return $item['is_completed'] !== true;
        });

        // Sort the array to prioritize 'Priotized'
        usort($filteredArray, function ($a, $b) {
            return strcmp($b['priotized'], $a['priotized']);
        });

         return  $filteredArray;
      
    }

    public static function badgeCount(){
        $currentDate = Carbon::now()->subMonth();
        return Referral::where('referee_id', auth()->user()->id)
                ->whereMonth('updated_at', $currentDate->month)
            // ->whereDate('updated_at', today())
                ->count();
    }

    public static function badge(){
        $currentDate = Carbon::now()->subMonth();
        $count = Referral::where('referee_id', auth()->user()->id)->whereMonth('updated_at', $currentDate->month)->count();

        $color = '';
        $membership = '';
        $amount = '';
        
        if($count >= 10 && $count <= 20){
            $color = '#E5E4E2';
            $membership = 'Platinum';
            $amount = 500;
        }elseif($count >= 21 && $count <= 49){
            $color = 'silver';
            $membership = 'Silver';
            $amount = 1500;
        }elseif($count >= 50){
            $color = 'gold';
            $membership = 'Gold';
            $amount = 500;
        }else{
            $color = 'grey';
            $membership = 'Standard';
            $amount = 0;
        }
        $data['count'] = $count;
        $data['color'] = $color;
        $data['badge'] = $membership;
        $data['amount'] = $amount;
        $data['duration'] = Carbon::now()->subMonth()->format('M, Y');

        return $data;

    }

    public static function viewCampaign($campaign_id){
        if($campaign_id == null){
            return false;
        }
       $campaign = Campaign::with(['campaignType', 'campaignCategory'])->where('job_id', $campaign_id)->first();
       if($campaign){
            $campaign->impressions += 1;
            $campaign->save();
    
            $data = $campaign;
            $data['current_user_id'] = auth()->user()->id;
            $data['is_attempted'] = $campaign->completed()->where('user_id', auth()->user()->id)->first() != null ? true : false;
            $data['attempts'] = $campaign->completed()->count();
            return $data;
       }else{
            return false;
       }
      
        //$completed = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
    }

    public static function awardPoints($point_type){
        return $point_type;
    }

    public static function phoneNumber($phone_number, $location){
        $loc = $location->countryName;
        $formatted_number = substr($phone_number, 1);
        if($loc == "United States"){
            if (preg_match('/^[0-9]+$/', $formatted_number)) {
                return '+'.$formatted_number;
            } else {
               return 'error_1'; //return "Invalid phone number. It contains letters or special characters.";
            }
    
            //count the  number of string
            $phoneCount = preg_match_all('/\d/', $formatted_number);
            if($phoneCount == 13){
                return '+'.$formatted_number;
            }else{
                return 'error_2'; //"Phone Number is not valid";
            }
        }else{
            return $phone_number;
        }
        
        
    }

}