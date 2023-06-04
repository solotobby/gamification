<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use App\Models\LoginPoints;
use Carbon\Carbon;

class SystemActivities{
    public static function index(){
        return 'ok';
    }

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
        // Output the initials
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



}