<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use App\Models\Campaign;
use App\Models\PaymentTransaction;
use App\Models\Statistics;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Analytics{

    public static function retentionRate(){

    //     $startOfMonth = Carbon::now()->startOfMonth();
    //     $endOfMonth = Carbon::now()->endOfMonth();

    //     $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
    //     $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

    //    $lastMonth = ActivityLog::select('activity_type', \DB::raw('COUNT(*) as count'))
    //     ->whereIn('activity_type', ['login', 'account_creation'])
    //     ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
    //     ->groupBy('activity_type')
    //     ->get()
    //     ->pluck('count', 'activity_type')
    //     ->toArray();

    //     $lastMonthTotal = $lastMonth['login'] + $lastMonth['account_creation'];
    //     $lastperLogin = ($lastMonth['login'] / $lastMonthTotal) * 100;
       
    //     $lastMonthdata['perLogin'] = $lastperLogin;


    //     $currentMonth = ActivityLog::select('activity_type', \DB::raw('COUNT(*) as count'))
    //     ->whereIn('activity_type', ['login', 'account_creation'])
    //     ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    //     ->groupBy('activity_type')
    //     ->get()
    //     ->pluck('count', 'activity_type')
    //     ->toArray();

    //     $currentMonthTotal = $currentMonth['login'] + $currentMonth['account_creation'];
    //     $currentperLogin = ($currentMonth['login'] / $currentMonthTotal) * 100;
    //     $currentMonthdata['perLogin'] = $currentperLogin;
   

    //     // return [$lastperLogin, $currentperLogin];

    //     return  $currentperLogin - $lastperLogin;
        

    }            

    public static function dailyVisit($type){

        $date = \Carbon\Carbon::today()->toDateString();
        
        $check = Statistics::where('date', $date)->where('type', $type)->first();
        if($check == null)
        {
            Statistics::create(['type' => $type, 'date' => $date, 'count' => '1']);
        }else{
            $check->count += 1;
            $check->save();
        }
    }

    public static function dailyActivities(){
        
        
       $data= ActivityLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(CASE WHEN activity_type = "google_account_creation" THEN 1 ELSE 0 END) as google_reg_count'),
            DB::raw('SUM(CASE WHEN activity_type = "account_creation" THEN 1 ELSE 0 END) as reg_count'),
            DB::raw('SUM(CASE WHEN activity_type = "account_verification" THEN 1 ELSE 0 END) as verified_count')
        )->where('created_at', '>=', Carbon::now()->subMonths(3))->groupBy('date')
        ->get();

        $result[] = ['Year','Registered','Verified'];
        foreach ($data as $key => $value) {
            $result[++$key] = [$value->date, (int)$value->reg_count+(int)$value->google_reg_count, (int)$value->verified_count];
        }

        return $result;
    }

    public static function dailyStats(){
        
         $data = Statistics::select(DB::raw('DATE(date) as date'))
         ->selectRaw('SUM(CASE WHEN type = "visits" THEN count ELSE 0 END) as visits')
        ->selectRaw('SUM(CASE WHEN type = "LandingPage" THEN count ELSE 0 END) as landing_page_count')
        ->selectRaw('SUM(CASE WHEN type = "Dashboard" THEN count ELSE 0 END) as dashboard_count')
        ->where('created_at', '>=', Carbon::now()->subMonths(1))->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();
        $dailyVisitresult[] = ['Year','Visits**', 'LandingPage', 'Dashboard', 'Total Visit'];
        foreach ($data as $key => $value) {
            $dailyVisitresult[++$key] = [$value->date, (int)$value->visits, (int)$value->landing_page_count, (int)$value->dashboard_count, (int)$value->landing_page_count+(int)$value->dashboard_count];
        }
        return $dailyVisitresult;
    }
    
    public static function monthlyVisits(){
         $MonthlyVisitresult = User::select(DB::raw('DATE_FORMAT(created_at, "%b %Y") as month, COUNT(*) as user_per_month, SUM(is_verified) as verified_users'))
        // ->whereBetween('created_at',[$start_date, $end_date])
        ->where('created_at', '>=', Carbon::now()->subMonths(3))
        ->groupBy('month')->get(); 
        // 
        $MonthlyVisit[] = ['Month', 'Users','Verified'];
        foreach ($MonthlyVisitresult as $key => $value) {
            $MonthlyVisit[++$key] = [$value->month, (int)$value->user_per_month, (int)$value->verified_users ];
        }
        return $MonthlyVisit;
    }

    public static function registrationChannel(){
        $registrationChannel = User::select('source', DB::raw('COUNT(*) as total'))->groupBy('source')->get();
        $list[] = ['Channel', 'Total'];
         foreach($registrationChannel as $key => $value){
            $list[++$key] = [$value->source == null ? 'Organic' :$value->source, (int)$value->total ];
         }
         return $list;
    }

    public static function revenueChannel(){
       $revenue = PaymentTransaction::select('type', DB::raw('SUM(amount) as amount'))->groupBy('type')->where('user_id', '1')->where('tx_type', 'Credit')->where('type', '!=', 'direct_referer_bonus_naira_usd')->get();
       $list[] = ['Revenue Channel', 'Total'];
         foreach($revenue as $key => $value){
            $list[++$key] = [$value->type, (int)$value->amount ];
         }
         return $list;
    }

    public static function monthlyRevenue(){

        $monthlyRev =  PaymentTransaction::
        select(
            DB::raw('DATE_FORMAT(created_at, "%b %Y") AS month'),
            DB::raw('SUM(CASE WHEN type = "direct_referer_bonus" THEN amount ELSE 0 END) AS direct_referer_bonus'),
            DB::raw('SUM(CASE WHEN type = "referer_bonus" THEN amount ELSE 0 END) AS referer_bonus'),
            DB::raw('SUM(CASE WHEN type = "campaign_revenue" THEN amount ELSE 0 END) AS campaign_revenue'),
            DB::raw('SUM(CASE WHEN type = "withdrawal_commission" THEN amount ELSE 0 END) AS withdrawal_commission'),
            DB::raw('SUM(CASE WHEN type = "campaign_revenue_add" THEN amount ELSE 0 END) AS campaign_revenue_add'),
        )
        ->where('user_id', '!=', 84)->where('created_at', '>', Carbon::now()->subMonths(3))
        ->groupBy('month')
        
        ->get();


        $rev[] = ['Month', 'Direct Referral','Referral Bonus', 'Campaign Rev', 'Campaign Rev(Added)', 'Withdrawal Commission'];
        foreach ($monthlyRev as $key => $value) {
            $rev[++$key] = [$value->month, (int)$value->direct_referer_bonus, (int)$value->referer_bonus, (int)$value->campaign_revenue,(int)$value->campaign_revenue_add, (int)$value->withdrawal_commission];
        }
        return $rev;
    
    }

    public static function countryDistribution(){
        $countryDristibution = User::select('country', DB::raw('COUNT(*) as total'))->groupBy('country')->get();
        $country[] = ['Country', 'Total'];
         foreach($countryDristibution as $key => $value){
            $country[++$key] = [$value->country == null ? 'Unassigned' :$value->country, (int)$value->total ];
         }
         return $country;
    }

    public static function ageDistribution(){
        $ageDristibution = User::select('age_range', DB::raw('COUNT(*) as total'))->groupBy('age_range')->get();
        $age[] = ['Age Range', 'Total'];
         foreach($ageDristibution as $key => $value){
            $age[++$key] = [$value->age_range == null ? 'Unassigned' :$value->age_range, (int)$value->total ];
         }
         return $age;
       // return 'age';
    }

    

    public static function campaignMetrics(){
        $campaigns = Campaign::with(['completed'])
        ->orderBy('created_at', 'DESC')
        ->get();

        $list = [ ];
        foreach($campaigns as $key => $value){
            $completed =$value->completed()->where('status', '=', 'Approved')->count();
            $list[] = [ 
                'completed' => $completed,
                'incomplete' => $value->number_of_staff - $completed,
                'number_of_staff' =>(int) $value->number_of_staff, 
            ];
        }

        $collection = collect($list);

        $data['completedSum'] = $collection->sum('completed');
        $data['incompleteSum'] = $collection->sum('incomplete');
        $data['staffSum'] = $collection->sum('number_of_staff');
        $data['all_campaigns'] = $campaigns->count();
        $data['live_campaigns'] = $campaigns->where('status', 'Live')->count();
        $data['pending_campaigns'] = $campaigns->where('status', 'Offline')->count();
        $data['denied_campaigns'] = $campaigns->where('status', 'Declined')->count();
    
        return $data;
    }

    public static function dashboardAnalytics(){
        $logs = ActivityLog::get(['activity_type', 'user_type', 'created_at']);
        
        $startoftoday = Carbon::now()->startOfDay();
        $currently = Carbon::now();

//        $today = Carbon::today()->toDateString();

        $startOfWeek = Carbon::now()->startOfWeek();
        $startofMonth = Carbon::now()->startOfMonth();
        
  //      $endOfWeek = Carbon::now()->endOfWeek();

        $data['registered_today'] = $logs->whereIn('activity_type', ['account_creation','google_account_creation'])->whereBetween('created_at', [$startoftoday, $currently])->count();
        $data['registered_this_week'] = $logs->whereIn('activity_type', ['account_creation','google_account_creation'])->whereBetween('created_at', [$startOfWeek, $currently])->count();
        $data['registered_this_month'] = $logs->whereIn('activity_type', ['account_creation','google_account_creation'])->whereBetween('created_at', [$startofMonth, $currently])->count();

        $data['login_today'] = $logs->whereIn('activity_type', ['login'])->whereBetween('created_at', [$startoftoday, $currently])->count();
        $data['login_this_week'] = $logs->whereIn('activity_type', ['login'])->whereBetween('created_at', [$startOfWeek, $currently])->count();
        $data['login_this_month'] = $logs->whereIn('activity_type', ['login'])->whereBetween('created_at', [$startofMonth, $currently])->count();

        return $data;
    }


}