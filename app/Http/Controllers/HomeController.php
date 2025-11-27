<?php

namespace App\Http\Controllers;

use App\Helpers\Admin;
use App\Helpers\Analytics;
use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Models\AccountInformation;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Answer;
use App\Models\BankInformation;
use App\Models\Business;
use App\Models\Games;
use App\Models\Question;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\Category;
use App\Models\LoginPoints;
use App\Models\OTP;
use App\Models\PaymentTransaction;
use App\Models\Profile;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\Statistics;
use App\Models\UserLocation;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use App\Models\Withrawal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nette\Utils\Random;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'email']);
        // $this->middleware('auth');
    }


    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // return 'admin';
            return redirect()->route('admin.home');
        } elseif ($user->hasRole('staff')) {
            // return 'staff';
            return redirect()->route('staff.home');
        } elseif ($user->hasRole('super_admin')) {
            // return 'staff';
            return redirect()->route('admin.home');
        } else {
            // return 'user';
            return redirect()->route('user.home');
        }
    }

    public function userHome()
    {

        dailyVisit('Dashboard');
        if (config('app.env') == 'Production') {
            setProfile(auth()->user());
            // setWalletBaseCurrency();
        }

        // return auth()->user()->skill;

        // if (auth()->user()->phone == '' || auth()->user()->country == '') {
        //     return view('phone');
        // }

        if (auth()->user()->age_range == '' || auth()->user()->gender == '') { //compell people to take survey
            return redirect('survey');
        }





        $balance = '';

        $badgeCount = badgeCount();

        $completed = CampaignWorker::where('user_id', auth()->user()->id)->where('status', 'Approved')->count();

        $announcement = Announcement::where('status', true)->first();

        $ads = adBanner();
        $categories = $this->listCategories();

        $businessPromotion = Business::where('is_live', true)->first();


        return view('user.home', [
            'badgeCount' => $badgeCount,
            // 'available_jobs' => $available_jobs,
            'completed' => $completed,
            'user' => auth()->user(),
            'balance' => $balance,
            'announcement' => $announcement,
            'ads' => $ads,
            'categories' => $categories,
            'promotion' => $businessPromotion,
        ]);
    }

    public function newHome()
    {

        $categoryID = 0;
        return $campaigns = Campaign::where('status', 'Live')
            ->where('is_completed', false)
            ->where('campaign_type', $categoryID)
            ->whereDoesntHave('attempts', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderByRaw("CASE WHEN approved = 'Priotize' THEN 1 ELSE 2 END")
            // ->orderByDesc('created_at') // Then order by newest creation date
            ->paginate(10);



        // return $this->listCategories();
    }

    public function listCategories()
    {
        return Category::query()->orderBy('name', 'ASC')->get();
    }

    public function filterCampaignByCategories($category_id)
    {

        // return $this->newHome();
        return filterCampaign($category_id);
    }

    public function testCampaignList()
    {
        // testCampaign('0');
        //46  43 : 0,0,0
        return checkCampaignCompletedStatus('6059');
        // return filterCampaign('0');
    }

    public function howTo()
    {
        return view('user.documentation.how_to_approve');
    }

    public function adminNewHome()
    {
        return view('admin.new_index');
    }

    // public function adminHome()
    // {
    //     // $retention = retentionRate();
    //     // return getPosts();
    //     // $campaigns = Campaign::where('status', 'Live')->get();
    //     // $campaignWorker = CampaignWorker::where('status', 'Approved')->sum('amount');
    //     // $user = User::where('role', 'regular')->get();
    //     // $loginPoints = LoginPoints::where('is_redeemed', false)->get();

    //     $wallet =
    //     \DB::select('
    //             SELECT
    //             SUM(balance) AS total_balance,
    //             SUM(CASE WHEN balance > 2500 THEN balance ELSE 0 END) AS balance_gt_200,
    //             SUM(usd_balance) AS total_usd_balance
    //             FROM wallets
    //     ');
    //     //Wallet::where('user_id', '!=', '1')->get();

    //     //this wwee
    //     $start_week = Carbon::now()->startOfWeek(); //->format('Y-m-d h:i:s');//next('Friday')->format('Y-m-d h:i:s');
    //     $end_week = Carbon::now()->endOfWeek();
    //     $withdrawal = Withrawal::get(['status', 'amount', 'is_usd', 'created_at']); //Date('')
    //     $thisWeekPayment = $withdrawal->where('status', false)->whereBetween('created_at', [$start_week, $end_week])->sum('amount');
    //     $totalPayout = $withdrawal->where('is_usd', false)->sum('amount');
    //     $transactions = PaymentTransaction::where('status', 'successful')->sum('amount');
    //     $available_jobsCount = 0;  //count(filterCampaign('0'));
    //     $totalPayout_ = $withdrawal->where('status', false)->whereBetween('created_at', ['2024-11-01 11:00:27', '2025-01-14 11:00:27'])->sum('amount');

    //     $transactions = \DB::select('
    //         SELECT SUM(amount) AS total_successful_transactions
    //         FROM payment_transactions
    //         WHERE status = ?
    //     ', ['successful']);

    //     //$ref_rev = Referral::where('is_paid', true)->count();
    //     //$transactions = PaymentTransaction::where('user_type', 'admin')->get();
    //     //$Wal = Wallet::where('user_id', auth()->user()->id)->first();


    //     // return $campaignMetric = campaignMetrics();
    //     //users registered

    //     $dailyActivity = dailyActivities();

    //     //monthly visits
    //     // $start_date = \Carbon\Carbon::today()->subDays(30);
    //     // $end_date = \Carbon\Carbon::now()->format('Y-m-d');

    //     $MonthlyVisit = monthlyVisits();

    //     //daily visits
    //     $dailyVisits = dailyStats();

    //     //registration channel
    //     // $registrationChannel = registrationChannel();

    //     //revenue channel
    //     $revenueChannel = revenueChannel();

    //     //revenue
    //     $revenue = monthlyRevenue();

    //     $weeklyRegistrationChannel = weeklyRegistrationChannel();
    //     $weeklyVerificationChannel = weeklyVerificationChannel();

    //     //country distribution
    //     $countryDistribution = countryDistribution();

    //     //age distribution
    //     $ageDistribution = ageDistribution();

    //     $currencyDistribution = currencyDistribution();

    //     // $christmas = Profile::where('is_xmas', true)->count();

    //     return view('admin.index', [
    //         'wallet' => $wallet,
    //         'weekPayment' => $thisWeekPayment,
    //         'totalPayout' => $totalPayout,
    //         'transactions' => $transactions,
    //         'totalPayout_' => $totalPayout_,
    //         // 'xmas' => $christmas,
    //         'av_count' => $available_jobsCount
    //     ]) // ['users' => $user, 'campaigns' => $campaigns, 'workers' => $campaignWorker, 'loginPoints' => $loginPoints]) // 'wallet' => $wallet, 'ref_rev' => $ref_rev, 'tx' => $transactions, 'wal'=>$Wal])
    //         ->with('visitor', json_encode($dailyActivity))
    //         ->with('daily', json_encode($dailyVisits))
    //         ->with('monthly', json_encode($MonthlyVisit))
    //         // ->with('channel', json_encode($registrationChannel))
    //         ->with('revenue', json_encode($revenueChannel))
    //         ->with('country', json_encode($countryDistribution))
    //         ->with('age', json_encode($ageDistribution))
    //         ->with('currency', json_encode($currencyDistribution))
    //         ->with('monthlyRevenue', json_encode($revenue))
    //         ->with('weeklyRegistrationChannel', json_encode($weeklyRegistrationChannel))
    //         ->with('weeklyVerificationChannel', json_encode($weeklyVerificationChannel));
    //         // ->with('retention', json_encode($retention));
    // }


    // public function adminHome(Request $request)
    // {
    //     // Increase max execution time to 120 seconds for this method
    //     // set_time_limit(120);

    //     // Get period from request, default to 7 days
    //     $period = $request->get('period', 7);

    //     // Calculate date range based on period
    //     $startDate = Carbon::now()->subDays($period)->startOfDay();
    //     $endDate = Carbon::now()->endOfDay();

    //     // $wallet = DB::select('
    //     //     SELECT
    //     //         SUM(balance) AS total_balance,
    //     //         SUM(CASE WHEN balance > 2500 THEN balance ELSE 0 END) AS balance_gt_200,
    //     //         SUM(usd_balance) AS total_usd_balance
    //     //     FROM wallets
    //     // ');

    //     // Single optimized query for all withdrawal metrics using period dates
    //     // $withdrawalMetrics = DB::table('withrawals')
    //     //     ->selectRaw('
    //     //     SUM(CASE WHEN status = 0 AND created_at BETWEEN ? AND ? THEN amount ELSE 0 END) AS period_payment,
    //     //     SUM(CASE WHEN is_usd = 0 THEN amount ELSE 0 END) AS total_payout,
    //     //     SUM(CASE WHEN status = 0 THEN amount ELSE 0 END) AS total_pending_payout
    //     // ', [$startDate, $endDate])
    //     //     ->first();

    //     // $cacheKey = "admin_transactions_total_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}";

    //     // Cache successful transactions sum (updates less frequently)
    //     // $transactions = DB::select('
    //     //         SELECT SUM(amount) AS total_successful_transactions
    //     //         FROM payment_transactions
    //     //         WHERE status = ?
    //     //         AND created_at BETWEEN ? AND ?
    //     //     ', ['successful', $startDate, $endDate]);

    //     return view('admin.index_new', [
    //         // 'wallet' => $wallet,
    //         // 'periodPayment' => $withdrawalMetrics->period_payment ?? 0,
    //         // 'totalPayout' => $withdrawalMetrics->total_payout ?? 0,
    //         // 'transactions' => $transactions ?? 0.00,
    //         // 'totalPendingPayout' => $withdrawalMetrics->total_pending_payout ?? 0,
    //         // 'av_count' => 0,
    //         'period' => $period
    //     ]);
    // }

    public function adminHome(Request $request)
    {
        $period = $request->get('period', 7);
        $startDate = Carbon::now()->subDays($period)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $data = ['period' => $period];

        // Only show financial data to super_admin
        if (auth()->user()->role === 'super_admin') {
            $data['wallet'] = DB::select('
                SELECT
                    SUM(balance) AS total_balance,
                    SUM(CASE WHEN balance > 2500 THEN balance ELSE 0 END) AS balance_gt_200,
                    SUM(usd_balance) AS total_usd_balance
                FROM wallets
            ');

            $withdrawalMetrics = DB::table('withrawals')
                ->selectRaw('
                    SUM(CASE WHEN status = 0 AND created_at BETWEEN ? AND ? THEN amount ELSE 0 END) AS period_payment,
                    SUM(CASE WHEN is_usd = 0 THEN amount ELSE 0 END) AS total_payout,
                    SUM(CASE WHEN status = 0 THEN amount ELSE 0 END) AS total_pending_payout
                ', [$startDate, $endDate])
                ->first();

            $data['withdrawalMetrics'] = $withdrawalMetrics;
            $data['periodPayment'] = $withdrawalMetrics->period_payment ?? 0;
            $data['totalPayout'] = $withdrawalMetrics->total_payout ?? 0;
            $data['totalPendingPayout'] = $withdrawalMetrics->total_pending_payout ?? 0;
            $data['totalPendingPayout'] = $withdrawalMetrics->total_pending_payout ?? 0;

            $data['transactions'] = DB::select('
                SELECT SUM(amount) AS total_successful_transactions
                FROM payment_transactions
                WHERE status = ?
                AND created_at BETWEEN ? AND ?
            ', ['successful', $startDate, $endDate]);
        }

        return view('admin.index_new', $data);
    }

    public function analytics(Request $request)
    {
        $period = $request->get('period', 7);

        // Prepare chart data with caching for expensive operations
        $chartData = [
            'visitor' => Cache::remember('chart_daily_activities_' . $period, 300, fn() => dailyActivities()),
            'daily' => Cache::remember('chart_daily_stats_' . $period, 300, fn() => dailyStats()),
            'monthly' => Cache::remember('chart_monthly_visits_' . $period, 300, fn() => monthlyVisits()),
            'revenue' => Cache::remember('chart_revenue_channel_' . $period, 300, fn() => revenueChannel()),
            'country' => Cache::remember('chart_country_dist_' . $period, 1800, fn() => countryDistribution()),
            'age' => Cache::remember('chart_age_dist_' . $period, 1800, fn() => ageDistribution()),
            'currency' => Cache::remember('chart_currency_dist_' . $period, 1800, fn() => currencyDistribution()),
            'monthlyRevenue' => Cache::remember('chart_monthly_revenue_' . $period, 300, fn() => monthlyRevenue()),
            'weeklyRegistrationChannel' => Cache::remember('chart_weekly_reg_' . $period, 300, fn() => weeklyRegistrationChannel()),
            'weeklyVerificationChannel' => Cache::remember('chart_weekly_verif_' . $period, 300, fn() => weeklyVerificationChannel())
        ];

        return view('admin.analytics', [
            'period' => $period
        ])->with(array_map('json_encode', $chartData));
    }

    public function adminApiDefault(Request $request)
    {
        $period = (int) $request->period ?? 7;
        $startDate = Carbon::today()->subDays($period);
        $endDate = Carbon::today();

        $userRole = auth()->user()->role;
        $cacheKey = "admin_dashboard_stats_{$period}_{$userRole}";

        // Cache for 30 minutes (1800 seconds)
        return Cache::remember($cacheKey, 1800, function () use ($startDate, $endDate, $userRole) {
            // --- User stats ---
            $userStats = User::selectRaw("
            COUNT(*) as registered,
            SUM(CASE WHEN is_verified = 1 THEN 1 ELSE 0 END) as verified
        ")
                ->where('role', 'regular')
                ->where('created_at', '>=', $startDate)
                ->first();

            // --- Campaign stats ---
            $campaignStats = Campaign::where('status', 'Live')
                ->where('created_at', '>=', $startDate)
                ->selectRaw('COUNT(*) as total')
                ->first();

            // --- Active users ---
            $activeUsersCount = DB::table('activity_logs')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct('user_id')
                ->count('user_id');

            $registeredUsersCount = User::where('role', 'regular')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // --- Prepare base response ---
            $data = [
                'campaigns' => (int) $campaignStats->total,
                'registeredUser' => (int) $userStats->registered,
                'verifiedUser' => (int) $userStats->verified,
                'activeUsers' => $activeUsersCount,
                'registeredUsers' => $registeredUsersCount,
            ];

            // --- Add financial data only for super_admin ---
            if ($userRole === 'super_admin') {
                $campaignValue = Campaign::where('status', 'Live')
                    ->where('created_at', '>=', $startDate)
                    ->sum('total_amount');

                $campaignWorkerSum = CampaignWorker::where('status', 'Approved')
                    ->where('created_at', '>=', $startDate)
                    ->sum('amount');

                $data['campaignValue'] = (float) $campaignValue;
                $data['campaignWorker'] = (float) $campaignWorkerSum;
            }

            return $data;
        });
    }

    // public function adminApiDefault(Request $request)
    // {
    //     $period = $request->period;

    //     $data = [];

    //     $date = \Carbon\Carbon::today()->subDays($period);

    //     $camp = Campaign::where('status', 'Live')->where('created_at', '>=', $date)->get();
    //     $data['campaigns'] = $camp->count();
    //     $data['campaignValue'] = $camp->sum('total_amount');
    //     $data['campaignWorker'] = CampaignWorker::where('status', 'Approved')->where('created_at', '>=', $date)->sum('amount');
    //     $data['registeredUser'] = User::where('role', 'regular')->where('created_at', '>=', $date)->count();
    //     $data['verifiedUser'] = User::where('role', 'regular')->where('is_verified', true)->where('created_at', '>=', $date)->count();
    //     $data['activeUsers'] =  $this->getDailyReport($start_date, $end_date);
    //     // $data['activeUsers'] =  [
    //     //                             'registered_users' => 0, //$registeredUsersCount,
    //     //                             'active_users'     => 0
    //     //                         ];
    //     // $data['loginPoints'] = LoginPoints::where('is_redeemed', false)->where('created_at','>=',$date)->sum('point');
    //     // $data['loginPointsValue'] = $data['loginPoints']/5;

    //     // $data['monthlyVisits'] = monthlyVisits();
    //     return $data;
    // }

    // public function getDailyReport($start_date, $end_date)
    // {
    //     // $validated = $request->validate([
    //     //     'start_date' => 'required|date',
    //     //     'end_date' => 'required|date|after_or_equal:start_date'
    //     // ]);



    //     // $startDate = Carbon::parse($start_date)->startOfDay();
    //     // $endDate = Carbon::parse($end_date)->endOfDay();

    //     // $registeredUsersCount = ActivityLog::where('activity_type', 'account_creation')
    //     // ->whereBetween('created_at', [$start_date, $end_date])
    //     // ->distinct('user_id')
    //     // ->count('user_id');

    //     $activeUsersCount = ActivityLog::whereIn('activity_type', ['login'])
    //         ->whereBetween('created_at', [$start_date, $end_date])
    //         ->distinct('user_id')
    //         ->count('user_id');

    //     return response()->json([
    //         'registered_users' => 0, //$registeredUsersCount,
    //         'active_users'     => $activeUsersCount,
    //     ]);






    //     // Get registered users per day
    //     // $registered = ActivityLog::where('activity_type', 'account_creation')
    //     //     ->whereBetween('created_at', [$start_date, $end_date])
    //     //     ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT user_id) as registered')
    //     //     ->groupBy('date')
    //     //     ->get()
    //     //     ->keyBy('date');

    //     // // Get active users per day
    //     // $active = ActivityLog::whereBetween('created_at', [$start_date, $end_date])
    //     //     ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT user_id) as active')
    //     //     ->groupBy('date')
    //     //     ->get()
    //     //     ->keyBy('date');

    //     // // Generate complete date range
    //     // $period = CarbonPeriod::create($start_date, $end_date);

    //     // // Build results with all dates in range
    //     // $results = [];
    //     // foreach ($period as $date) {
    //     //     $dateStr = $date->format('d-m-Y');
    //     //     $results[] = [
    //     //         'date' => $dateStr,
    //     //         'registered' => $registered->get($dateStr)->registered ?? 0,
    //     //         'active' => $active->get($dateStr)->active ?? 0,
    //     //     ];
    //     // }



    //     // return response()->json([
    //     //     // 'data' => $results,
    //     //     'meta' => [
    //     //         'total_registered' => $registered->sum('registered'),
    //     //         'total_active' => $active->sum('active')
    //     //     ]
    //     // ]);
    // }


    public function getDailyReport($startDate, $endDate)
    {
        $cacheKey = "daily_report_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}";

        // Cache for 30 minutes
        return Cache::remember($cacheKey, 1800, function () use ($startDate, $endDate) {
            return DB::table('activity_logs')
                ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT user_id) as active_users')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date', 'asc')
                ->get()
                ->map(function ($row) {
                    return [
                        'date' => Carbon::parse($row->date)->format('Y-m-d'),
                        'active_users' => (int) $row->active_users,
                    ];
                });
        });
    }

    public function staffHome()
    {

        $campaigns = Campaign::where('status', 'Live')->get();
        $campaignWorker = CampaignWorker::all();
        $user = User::where('role', 'regular')->get();
        $wallet = Wallet::all();
        $ref_rev = Referral::where('is_paid', true)->count();
        $transactions = PaymentTransaction::where('user_type', 'admin')->get();
        $Wal = Wallet::where('user_id', auth()->user()->id)->first();

        //users registered
        $dailyActivity = dailyActivities();

        //monthly visis
        $MonthlyVisit = monthlyVisits();

        ///daily visits
        $dailyVisits = dailyStats();

        //registration channel
        $registrationChannel = registrationChannel();

        return view('staff.home', [
            'users' => $user,
            'campaigns' => $campaigns,
            'workers' => $campaignWorker,
            'wallet' => $wallet,
            'ref_rev' => $ref_rev,
            'tx' => $transactions,
            'wal' => $Wal
        ])
            ->with('visitor', json_encode($dailyActivity))
            ->with('daily', json_encode($dailyVisits))
            ->with('monthly', json_encode($MonthlyVisit))
            ->with('channel', json_encode($registrationChannel));
    }

    public function testpayment()
    {

        $amount = amountCalculator('100');

        $payloadNGN = [
            "amount" => $amount,
            "redirect_url" => url('wallet/fund/redirect'),
            "currency" => "NGN",
            "reference" => time(),
            "narration" => "Wallet top up",
            "channels" => [
                "card",
                "bank_transfer"
            ],
            // "default_channel"=> "card",
            "customer" => [
                "name" => auth()->user()->name,
                "email" => auth()->user()->email
            ],
            "notification_url" => "https://webhook.site/8d321d8d-397f-4bab-bf4d-7e9ae3afbd50",
            "metadata" => [
                "key0" => "test0",
                "key1" => "test1",
                "key2" => "test2",
                "key3" => "test3",
                "key4" => "test4"
            ]
        ];



        return $redirectUrl = initializeKorayPay($payloadNGN);


        //    return redirect($redirectUrl);
    }

    public function generateVirtualAccount()
    {

        $res = reGenerateVirtualAccount(auth()->user());
        $responseData = $res->getData(true);

        if ($responseData['status'] == true) {
            return back()->with('success', 'Freebyz Personal Account Created Successfully');
        } else {
            return back()->with('error', 'Sorry, we couldn\'t generate your virtual account for top up at the moment. You can pay manually via
                                6667335193 (Moniepoint BANK- Freebyz Technologies LTD).');
        }
    }

    public function createUpdatedVirtualAccount()
    {

        $user = Auth::user();
        $email = $user->email;
        $divide = explode('@', $email);
        $emailString = Str::random(5);
        $newEmail = $divide[0] . $emailString . '@' . $divide[1];

        $newRandPhone =  $this->replaceMiddleDigits(substr($user->phone, 1));

        $phoneFormated = '234' . $newRandPhone;


        $updateVa = reGenerateUpdatedVirtualAccount($newEmail, $phoneFormated, auth()->user());

        if ($updateVa) {
            return back()->with('success', 'Freebyz Personal Account Created Successfully');
        } else {
            return back()->with('error', 'An error occoured while creating account, please contact admin by clicking Talk to Us on the side menu');
        }
        //  if($updateVa[0]['status'] == true){
        //     return back()->with('success', 'Freebyz Personal Account Created Successfully');
        // }else{
        //     return back()->with('error', 'An error occoured while creating account, please contact admin by clicking Talk to Us on the side menu');
        // }
    }

    public function replaceMiddleDigits($phoneNumber)
    {

        $firstPart = substr($phoneNumber, 0, 3);
        $lastPart = substr($phoneNumber, 7);

        // Generate a random 4-digit number
        $randomDigits = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Combine parts with the new 4-digit number
        return $modifiedNumber = $firstPart . $randomDigits . $lastPart;
    }



    public function savePhoneInformation(Request $request)
    {
        // $this->validate($request, [
        //     'phone' => 'numeric|required|digits:11|unique:users'
        // ]);

        $user = User::where('id', auth()->user()->id)->first();
        $user->phone = $request->phone_number;
        $user->source = $request->source;
        $user->country = $request->country;
        $user->save();
        return redirect('/home');
    }

    public function instruction()
    {
        $games = Games::where('status', '1')->first();
        return view('user.instruction', ['games' => $games]);
    }

    public function takeQuiz()
    {
        $games = Games::where('status', '1')->first();

        $userScore = UserScore::where('user_id', auth()->user()->id)->where('game_id', $games->id)->get();

        if (count($userScore) > 0) {
            return view('user.error');
        }
        $questions = Question::inRandomOrder()->limit(1)->first();
        return view('user.play', ['question' => $questions, 'game' => $games]);
    }

    public function storeAnswer(Request $request)
    {
        $question = Question::where('id', $request->question_id)->first();

        //getcorrect answer
        $correctAnswer = $question->correct_answer;
        $userAnswer = $request->option;

        if ($userAnswer == $correctAnswer) {
            $isCorrect = 1;
        } else {
            $isCorrect = 0;
        }

        Answer::create([
            'game_id' => $request->game_id,
            'question_id' => $request->question_id,
            'user_id' => auth()->user()->id,
            'selected_option' => $request->option,
            'correct_option' => $question->correct_answer,
            'is_correct' => $isCorrect
        ]);

        return redirect('next/question');
    }

    public function nextQuestion()
    {
        $games = Games::where('status', '1')->first();

        $userScore = UserScore::where('user_id', auth()->user()->id)->where('game_id', $games->id)->get();

        if (count($userScore) > 0) {
            return view('error');
        }

        $questions = Question::inRandomOrder()->limit(1)->first();

        $answered = Answer::where('user_id', auth()->user()->id)->where('game_id', $games->id)->count();
        $index = $answered + 1;
        if ($answered == $games->number_of_questions) {
            return redirect('submit/answers');
        }

        return view('user.next', ['question' => $questions, 'game' => $games, 'index' => $index]);
    }

    public function submitAnswers()
    {
        $games = Games::where('status', '1')->first();

        $getCorrectAnswers = Answer::where('game_id', $games->id)->where('user_id', auth()->user()->id)->where('is_correct', '1')->count();
        $percentage = ($getCorrectAnswers / $games->number_of_questions) * 100;

        $userScore = UserScore::where('user_id', auth()->user()->id)->where('game_id', $games->id)->get();

        if (count($userScore) > 0) {
            return view('user.completed', ['score' => $percentage]);
        }
        UserScore::Create(['user_id' => auth()->user()->id, 'game_id' => $games->id, 'score' => $percentage]);
        return view('user.completed', ['score' => $percentage]);
    }

    public function scores()
    {
        $scores = UserScore::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return view('user.scores', ['scores' => $scores]);
    }

    public function redeemReward($id)
    {
        $reward_type = UserScore::where('id', $id)->first();
        if ($reward_type->reward_type == 'CASH' && $reward_type->is_redeem == '0') {
            $bankInformation = BankInformation::where('user_id', auth()->user()->id)->first();
            if ($bankInformation == null) {
                $bankList = bankList();
                return view('bank_information', ['bankList' => $bankList, 'id' => $id]);
            }

            $parameters = Reward::where('name', 'CASH')->first();
            $amount = $parameters->amount * 100;
            //transfer the fund
            $transfer = $this->transferFund($amount, $bankInformation->recipient_code, 'Redeem Reward');


            if ($transfer['status'] == 'false') {
                if ($transfer['data']['status'] == 'success' || $transfer['data']['status'] == 'pending') {
                    $userScore = UserScore::where('id', $id)->first();
                    $userScore->is_redeem = true;
                    $userScore->save();

                    Transaction::create([
                        'user_id' => auth()->user()->id,
                        'game_id' => $userScore->game_id,
                        'amount' => $transfer['data']['amount'],
                        'reward_type' => 'CASH',
                        'reference' => $transfer['data']['reference'],
                        'transfer_code' => $transfer['data']['transfer_code'],
                        'recipient' => $transfer['data']['recipient'],
                        'status' => $transfer['data']['status'],
                        'currency' => $transfer['data']['currency']
                    ]);
                    return redirect('score/list')->with('status', 'Money successfully sent to your account');
                } else {
                    return redirect('score/list')->with('error', 'There was an error while sending cash, please try again later');
                }
            } else {
                return redirect('score/list')->with('error', 'There was an error while sending cash, please try again later!!!');
            }
        } elseif ($reward_type->reward_type == 'AIRTIME' && $reward_type->is_redeem == '0') {

            $parameters = Reward::where('name', 'AIRTIME')->first();
            //$phone = '+234'.substr(auth()->user()->phone, 1);
            $amount = $parameters->amount;
            $phone = auth()->user()->phone;
            return $airtime = $this->sendAirtime($phone, $amount); //['data'];
            // if($airtime->errorMessage == "None")
            // {

            //     $userScore = UserScore::where('id', $id)->first();
            //         $userScore->is_redeem = true;
            //         $userScore->save();

            //         Transaction::create([
            //             'user_id' => auth()->user()->id,
            //             'game_id' => $userScore->game_id,
            //             'amount' =>  $airtime->totalAmount,//$transfer['data']['amount'],
            //             'reward_type' => 'AIRTIME',
            //             'reference' => time(), //$transfer['data']['reference'],
            //             'transfer_code' => time(),//$transfer['data']['transfer_code'],
            //             'recipient' => time(), //$airtime->responses['phoneNumber']
            //             'status' => 'success', //$airtime->responses['status'],
            //             'currency' => "NGN"
            //         ]);
            //         return redirect('score/list')->with('status', 'Airtime Successfully Sent to your Number');
            // }else{
            //    return redirect('score/list')->with('error', 'There was an error while sending airtime, please try again later');
            // }

        } else {
            return 'nothing dey happen';
        }
    }

    public function selectBankInformation()
    {
        $bankList = bankList();
        @$bankInfo = BankInformation::where('user_id', auth()->user()->id)->first();
        $otp = OTP::where('user_id', auth()->user()->id)->where('is_verified', false)->latest()->first();
        return view('user.bank_information', ['bankList' => $bankList, 'bankInfo' => $bankInfo, 'otp' => $otp]);
    }

    public function validateBankAccount(Request $request)
    {
        $request->validate([
            'account_number' => 'required|digits:10|numeric',
            'bank_code'      => 'required|string',
        ]);

        // Log::info("Validating account: " . $request->account_number);

        $accountInfo = resolveBankName($request->account_number, $request->bank_code);

        // Log::info($accountInfo);

        if (!isset($accountInfo['status']) || $accountInfo['status'] != true) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid account details'
            ], 400);
        }

        $validatedName = $accountInfo['data']['account_name'];

        return response()->json([
            'success'      => true,
            'account_name' => $validatedName,
            'bank_name'    => $accountInfo['data']['bank_name'] ?? null,
            'name_match'   => strtolower(trim($validatedName)) === strtolower(trim(auth()->user()->name)),
            'current_name' => auth()->user()->name
        ]);
    }

    public function saveBankInformation(Request $request)
    {
        $request->validate([
            'account_number' => 'required|digits:10|numeric',
            'bank_code'      => 'required|string',
            'validated_name' => 'required|string',
            'bank_name'      => 'required|string',
        ]);

        // Check if same account exists for another user
        // $duplicate = BankInformation::where('account_number', $request->account_number)
        //     ->where('name', $request->validated_name)
        //     ->where('user_id', '!=', auth()->id())
        //     ->exists();

        // if ($duplicate) {
        //     auth()->user()->update(['is_blacklisted' => true]);
        //     // return back()->with(key: 'error', 'Account already exists for another user. You have been flagged for review.');
        // }

        try {
            // Create recipient code
            $recipientCode = recipientCode(
                $request->validated_name,
                $request->account_number,
                $request->bank_code
            );

            // Create or update bank info
            $bankInfo = BankInformation::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'name'           => $request->validated_name,
                    'bank_name'      => $request->bank_name,
                    'account_number' => $request->account_number,
                    'bank_code'      => $request->bank_code,
                    'recipient_code' => $recipientCode['data']['recipient_code'] ?? null,
                    'currency'       => 'NGN',
                    'status'         => true
                ]
            );

            // Update user name if it does not match
            if (strtolower(trim(auth()->user()->name)) !== strtolower(trim($request->validated_name))) {
                auth()->user()->update(['name' => $request->validated_name]);
            }

            // Generate virtual account only if user phone is verified
            if (auth()->user()->profile->phone_verified && $bankInfo) {
                generateVirtualAccount($request->validated_name, auth()->user()->phone);
            }

            return back()->with('success', 'Bank details saved successfully.');
        } catch (\Exception $e) {
            Log::error("Bank info saving failed: " . $e->getMessage());
            return back()->with('error', 'Failed to save bank details. Please try again.');
        }
    }

    // public function saveBankInformation(Request $request)
    // {

    //     $this->validate($request, [
    //         'account_number' => 'numeric|required|digits:10'
    //     ]);

    //     $accountInformation = resolveBankName($request->account_number, $request->bank_code);

    //     if ($accountInformation['status'] == 'true') {
    //         $recipientCode = recipientCode($accountInformation['data']['account_name'], $request->account_number, $request->bank_code);
    //         $bankInfor = BankInformation::updateOrCreate(
    //             [
    //                 'user_id' => auth()->id(),
    //             ],
    //             [
    //                 'name' => $accountInformation['data']['account_name'],
    //                 'bank_name' => $recipientCode['data']['details']['bank_name'],
    //                 'account_number' => $request->account_number,
    //                 'bank_code' => $request->bank_code,
    //                 'recipient_code' => $recipientCode['data']['recipient_code'],
    //                 'currency' => 'NGN',
    //             ]
    //         );


    //         if (auth()->user()->profile->phone_verified == true && $bankInfor) {
    //             generateVirtualAccount($accountInformation['data']['account_name'], auth()->user()->phone);
    //         }

    //         return back()->with('success', 'Account Details Added');
    //         //return redirect('wallet/withdraw')->with('success', 'Withdrawal Successfully queued');
    //     } else {
    //         return back()->with('error', 'Your bank account is not valid');
    //     }
    // }

    public function transferFund($amount, $recipient)
    {
        return transferFund($amount, $recipient, 'Freebyz Withdrawal');
    }

    public function continueCountry(Request $request)
    {

        $currencyRequest = explode(',', $request->currency);

        $currencyCode = $currencyRequest[0];
        $country = $currencyRequest[1];


        $profile = Profile::where('user_id', auth()->user()->id)->first();
        $profile->country = $country;
        $profile->currency_code = $currencyCode;
        $profile->save();

        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        $wallet->base_currency = $currencyCode;
        $wallet->base_currency_set = true;
        $wallet->Save();

        return back();
    }

    public function pathway($pathway)
    {

        $profile = Profile::where('user_id', auth()->user()->id)->first();

        $profile->pathway = $pathway;
        $profile->save();

        if ($pathway == 'hire') {
            return redirect('skills');
        } elseif ($pathway == 'setup') {
            return redirect('create/skill');
        } else {
            return redirect('home');
        }
    }
}
