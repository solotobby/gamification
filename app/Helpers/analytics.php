<?php

namespace App\Helpers;

use App\Models\Campaign;
use App\Models\PaymentTransaction;
use App\Models\Statistics;
use App\Models\User;
use Carbon\Carbon;

class Analytics{

    public static function index(){
        return 'analytics';
    }

    public static function dailyVisit(){

        $date = \Carbon\Carbon::today()->toDateString();
        
        $check = Statistics::where('date', $date)->first();
        if($check == null)
        {
            Statistics::create(['type' => 'visits', 'date' => $date, 'count' => '1']);
        }else{
            $check->count += 1;
            $check->save();
        }
    }

    public static function dailyActivities(){
        $data = User::select(\DB::raw('DATE(updated_at) as date'), \DB::raw('count(*) as total_reg'), \DB::raw('SUM(is_verified) as verified'))
        ->where('created_at', '>=', Carbon::now()->subMonths(3))->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();
       
        $result[] = ['Year','Registered','Verified'];
        foreach ($data as $key => $value) {
            $result[++$key] = [$value->date, (int)$value->total_reg, (int)$value->verified];
        }

        return $result;
    }

    public static function dailyStats(){
        $data = Statistics::select(\DB::raw('DATE(date) as date'), \DB::raw('sum(count) as visits'))
        ->where('created_at', '>=', Carbon::now()->subMonths(2))->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();
        $dailyVisitresult[] = ['Year','Visits'];
        foreach ($data as $key => $value) {
            $dailyVisitresult[++$key] = [$value->date, (int)$value->visits];
        }
        return $dailyVisitresult;
    }
    
    public static function monthlyVisits($start_date, $end_date){
        $MonthlyVisitresult = User::select(\DB::raw('DATE_FORMAT(created_at, "%b %Y") as month, COUNT(*) as user_per_month, SUM(is_verified) as verified_users'))
        ->whereBetween('created_at',[$start_date, $end_date])->groupBy('month')->get(); 
        // ->where('created_at', '>=', Carbon::now()->subMonths(3))
        $MonthlyVisit[] = ['Month', 'Users','Verified'];
        foreach ($MonthlyVisitresult as $key => $value) {
            $MonthlyVisit[++$key] = [$value->month, (int)$value->user_per_month, (int)$value->verified_users ];
        }
        return $MonthlyVisit;
    }

    public static function registrationChannel(){
        $registrationChannel = User::select('source', \DB::raw('COUNT(*) as total'))->groupBy('source')->get();
        $list[] = ['Channel', 'Total'];
         foreach($registrationChannel as $key => $value){
            $list[++$key] = [$value->source == null ? 'Organic' :$value->source, (int)$value->total ];
         }
         return $list;
    }

    public static function revenueChannel(){
       $revenue = PaymentTransaction::select('type', \DB::raw('SUM(amount) as amount'))->groupBy('type')->where('user_id', '1')->where('tx_type', 'Credit')->get();
       $list[] = ['Revenue Channel', 'Total'];
         foreach($revenue as $key => $value){
            $list[++$key] = [$value->type, (int)$value->amount ];
         }
         return $list;
    }

    public static function countryDistribution(){
        $countryDristibution = User::select('country', \DB::raw('COUNT(*) as total'))->groupBy('country')->get();
        $country[] = ['Country', 'Total'];
         foreach($countryDristibution as $key => $value){
            $country[++$key] = [$value->country == null ? 'Unassigned' :$value->country, (int)$value->total ];
         }
         return $country;
    }

    public static function ageDistribution(){
        $ageDristibution = User::select('age_range', \DB::raw('COUNT(*) as total'))->groupBy('age_range')->get();
        $age[] = ['Age Range', 'Total'];
         foreach($ageDristibution as $key => $value){
            $age[++$key] = [$value->age_range == null ? 'Unassigned' :$value->age_range, (int)$value->total ];
         }
         return $age;
       // return 'age';
    }

    public static function campaignMetrics(){
        // return $campaigns = Campaign::where('status', 'Live')->orderBy('created_at', 'DESC')->withCount(['completed'])->get();
        return $campaigns = Campaign::with(['completedAll'])
        ->where('status', 'Live')
        ->orderBy('created_at', 'DESC')
        ->get();


        // foreach($campaigns as $camp){

        // }

        // $list = [];
        // foreach($campaigns as $key => $value){
        //     $attempts = $value->completed->count();
        //     $completed = $value->completed()->where('status', '!=', 'Denied')->count();
        //     $list[] = [
        //         'total_campaigns' => $campaigns->count(),
        //         'available_campaigns' => '4'
        //     ];

        //     // $div = $completed / $value->number_of_staff;
        //     // $progress = $div * 100;
        //     // $list[] = [ 
        //     //     'job_id' => $value->job_id, 
        //     //     'campaign_amount' => $value->campaign_amount,
        //     //     'post_title' => $value->post_title, 
        //     //     'number_of_staff' => $value->number_of_staff, 
        //     //     'type' => $value->campaignType->name, 
        //     //     'category' => $value->campaignCategory->name,
        //     //     'attempts' => $attempts,
        //     //     'completed' => $completed,
        //     //     'is_completed' => $completed >= $value->number_of_staff ? true : false,
        //     //     'progress' => $progress 
        //     // ];
        // }

        // return $list;

    }


}