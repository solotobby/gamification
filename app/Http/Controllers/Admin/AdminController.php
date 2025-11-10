<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AfricaTalkingHandlers;
use App\Helpers\Analytics;
use App\Helpers\CapitalSage;
use App\Helpers\FacebookHelper;
use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Http\Controllers\Controller;
use App\Jobs\SendMassEmail;
use App\Mail\ApproveCampaign;
use App\Mail\GeneralMail;
use App\Mail\MassMail;
use App\Mail\UpgradeUser;
use App\Models\BankInformation;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\DataBundle;
use App\Models\DisputedJobs;
use App\Models\Games;
use App\Models\ExportJob;
use App\Models\MarketPlaceProduct;
use App\Models\PaymentTransaction;
use App\Models\ProductType;
use App\Models\Profile;
use App\Models\Question;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\UsdReferral;
use App\Models\Usdverified;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\UserScore;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use App\Models\Withrawal;
use App\Models\MassEmailLog;
use App\Models\MassEmailCampaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\ExportVerifiedUsersJob;


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createGame()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            //$game = Games::find($id);
            return view('admin.create_game');
        }
    }

    public function createQuestion()
    {
        $questions = Question::all();
        return view('admin.add_question', ['question' => $questions]);
    }

    public function storeQuestion(Request $request)
    {
        $question = Question::create($request->all());
        $question->save();

        $get_answer = DB::table('questions')->select($request->correct_answer)->latest()->first();
        $collect = collect($get_answer);
        $value = $collect->shift();
        $question->update(['correct_answer' => $value]);

        return back()->with('status', 'Question Created Successfully');
    }

    public function updateQuestion(Request $request)
    {
        $question = Question::where('id', $request->id)->first();
        $question->content = $request->content;
        $question->option_A = $request->option_A;
        $question->option_B = $request->option_B;
        $question->option_C = $request->option_C;
        $question->option_D = $request->option_D;
        $question->correct_answer = $request->correct_answer;
        $collect = collect($question);
        $value = $collect->shift();
        $question->correct_answer = $value;
        $question->save();

        return back()->with('status', 'Question Updated Successfully');
    }

    public function gameStatus($id)
    {

        $game = Games::where('id', $id)->first();
        if ($game->status == '1') {
            $game->status = '0';
            $game->save();
        } else {
            $game->status = '1';
            $game->save();
        }

        return back()->with('status', 'Status Changed Successfully');
    }

    public function gameCreate()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            //$game = Games::find($id);
            return view('admin.create_game');
        }
    }

    public function gameStore(Request $request)
    {
        $slug = Str::slug($request->name);
        $game = Games::create([
            'name' => $request->name,
            'type' => $request->type,
            'number_of_winners' => $request->number_of_winners,
            'slug' => $slug,
            'time_allowed' => 0.25, //$request->time_allowed,
            'number_of_questions' => $request->number_of_questions,
            'status' => 1
        ]);
        return back()->with('status', 'Game Created Successfully');
    }

    public function updateAmount(Request $request)
    {
        $reward = Reward::where('id', $request->id)->first();
        $reward->name = $request->name;
        $reward->amount = $request->amount;
        $reward->save();
        return back()->with('status', 'Amount updated Successfully');
    }

    public function viewAmount()
    {
        $reward = Reward::all();
        return view('admin.update_amount', ['rewards' => $reward]);
    }

    public function listQuestion()
    {
        $questions = Question::orderBy('created_at', 'desc')->paginate('200');
        $question_count = Question::all()->count();
        return view('admin.question_list', ['questions' => $questions, 'question_count' => $question_count]);
    }

    public function viewActivities($id)
    {
        $game = Games::where('id', $id)->first();
        $activities = UserScore::where('game_id', $id)->orderBy('score', 'desc')->get();

        return view('admin.game_activities', ['activities' => $activities, 'game' => $game]);
    }

    public function assignReward(Request $request)
    {
        if (empty($request->id)) {
            return back()->with('error', 'Please Select A Score');
        }
        $reward = Reward::where('name', $request->name)->first()->amount;
        $formattedReward = number_format($reward, 2);
        foreach ($request->id as $id) {
            $score = UserScore::where('id', $id)->first();
            $score->reward_type = $request->name;
            $score->save();

            $phone = '234' . substr($score->user->phone, 1);
            $message = "Hello " . $score->user->name . " you have a " . $request->name . " reward of " . $formattedReward . " from Freebyz.com. Please login to cliam it. Thanks";
            // PaystackHelpers::sendNotificaion($phone, $message);
        }
        return back()->with('status', 'Reward Assigned Successfully');
    }

    public function campaignMetrics()
    {
        //  $metrics = campaignMetrics();
        // $dashbordMetrics = dashboardAnalytics();
        // return view('admin.campaign_metric.index', ['analytics' => $dashbordMetrics]);
    }

    public function campaignDisputes()
    {
        $disputes = CampaignWorker::where('is_dispute', true)->orderBy('created_at', 'DESC')->paginate(100);
        return view('admin.campaign_mgt.disputes', ['disputes' => $disputes]);
    }

    public function campaignDisputesView($id)
    {
        $workdisputed = CampaignWorker::where('id', $id)->first();
        //$workdisputed->campaign;
        // return $disputedJobInfo = DisputedJobs::where('campaign_worker_id', $workdisputed->id)->first();
        return view('admin.campaign_mgt.view_dispute', ['campaign' => $workdisputed]);
    }

    public function campaignDisputesDecision(Request $request)
    {

        $workDone = CampaignWorker::where('id', $request->id)->first();
        if ($workDone->is_dispute_resolve == true) {
            return back()->with('error', 'Dispute has been attended to');
        }
        $workDone->status = $request->status;
        $workDone->is_dispute_resolved = true;
        $workDone->is_dispute = false;
        $workDone->save();

        $campaign = Campaign::where('id', $workDone->campaign_id)->first();

        $disputeJob = DisputedJobs::where('campaign_worker_id', $workDone->id)->first();
        $disputeJob->response = $request->reason;
        $disputeJob->save();

        if ($request->status == 'Approved') {

            $approvedJob = $campaign->completed()->where('status', 'Approved')->count();

            if ($approvedJob >= $campaign->number_of_staff) {
                return back()->with('error', 'Job has reached its maximum number of staff');
            }


            //update completed action
            $campaign->completed_count += 1;
            $campaign->pending_count -= 1;
            $campaign->save();

            setIsComplete($workDone->campaign_id);
            $user = User::where('id', $workDone->user_id)->first();
            if ($campaign->currency == 'NGN') {
                $currency = 'NGN';
                $channel = 'paystack';
                creditWallet($user, $currency, $workDone->amount);
            } elseif ($campaign->currency == 'USD') {
                $currency = 'USD';
                $channel = 'paypal';
                creditWallet($user, $currency, $workDone->amount);
            } else {
                $currency = baseCurrency($user);
                $channel = 'flutterwave';
                creditWallet($user, $currency, $workDone->amount);
            }


            $ref = time();

            PaymentTransaction::create([
                'user_id' =>  $workDone->user_id,
                'campaign_id' =>  $workDone->campaign->id,
                'reference' => $ref,
                'amount' =>  $workDone->amount,
                'balance' => walletBalance($workDone->user_id),
                'status' => 'successful',
                'currency' => $currency,
                'channel' => $channel,
                'type' => 'campaign_payment_dispute_resolved',
                'description' => 'Campaign Dispute Resolution for ' . $workDone->campaign->post_title,
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]);

            $subject = 'Job ' . $request->status;
            $status = $request->status;
            Mail::to($workDone->user->email)->send(new ApproveCampaign($workDone, $subject, $status));


            return back()->with('success', 'Dispute resolved Successfully');
        } else {

            $workDone->status = 'Denied';
            $workDone->save();

            $this->removePendingCountAfterDenial($workDone->campaign_id);

            $campaingOwner = User::where('id', $campaign->user_id)->first();

            if ($campaign->currency == 'NGN') {
                $currency = 'NGN';
                $channel = 'paystack';
            } elseif ($campaign->currency == 'USD') {
                $currency = 'USD';
                $channel = 'paypal';
            } else {
                $currency = baseCurrency($campaingOwner);
                $channel = 'flutterwave';
            }

            creditWallet($campaingOwner, $currency, $workDone->amount);

            $ref = time();

            PaymentTransaction::create([
                'user_id' => $workDone->campaign->user_id,
                'campaign_id' => $workDone->campaign->id,
                'reference' => $ref,
                'amount' => $workDone->amount,
                'balance' => walletBalance($workDone->campaign->user_id),
                'status' => 'successful',
                'currency' => $currency,
                'channel' => $channel,
                'type' => 'campaign_payment_refund',
                'description' => 'Campaign Dispute Resolution for ' . $workDone->campaign->post_title,
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]);

            $subject = 'Disputed Job ' . $request->status;
            $status = $request->status;

            Mail::to($workDone->user->email)->send(new ApproveCampaign($workDone, $subject, $status));

            return back()->with('success', 'Dispute resolved Successfully');


            return back()->with('success', 'Dispute resolved Successfully');
        }
    }

    public function removePendingCountAfterDenial($id)
    {
        $campaign = Campaign::where('id', $id)->first();
        $campaign->pending_count -= 1;
        $campaign->save();
    }

    public function userList()
    {

        $users = User::where(
            'role',
            'regular'
        )->latest()
            ->paginate(100);

        return view('admin.users.list', ['users' => $users]);
    }

    public function userEmailVerified()
    {

        $users = User::where(
            'role',
            'regular'
        )->whereNotNull('email_verified_at')
            ->latest()
            ->paginate(100);

        return view('admin.users.email_verified_list', ['users' => $users]);
    }

    public function userCurrencySearch(Request $request)
    {

        if (isset($request)) {
            $users = User::where('country', $request->currency)->paginate(500); //Wallet::with('user')->where('base_currency', $request->currency)->paginate(100);
        }

        return view('admin.users.user_currency_search', ['users' => $users, 'curr' => $request->currency]);
    }

    // public function userSearch(Request $request)
    // {

    //     if (isset($request)) {
    //         $users = User::where(
    //         'role',
    //         'regular'
    //     )->where([
    //             [function ($query) use ($request) {
    //                 if (($search = $request->q)) {
    //                     $query->orWhere('name', 'LIKE', '%' . $search . '%')
    //                         ->orWhere('email', 'LIKE', '%' . $search . '%')
    //                         ->orWhere('phone', 'LIKE', '%' . $search . '%')
    //                         ->orWhere('referral_code', 'LIKE', '%' . $search . '%')
    //                         ->get();
    //                 }
    //             }]
    //         ])->paginate(100);
    //     }
    //     return view('admin.users.search_result', ['users' => $users]);
    // }

    public function  userSearch(Request $request)
    {
        $search = $request->input('search');
        $search = $request->input('q');

        $users = User::where('role', 'regular')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('referral_code', 'LIKE', "%{$search}%");
                });
            })->latest()
            ->paginate(100);

        return view('admin.users.search_result', compact('users'));
    }


    public function campaignCreatorList()
    {
        //  $usersWithPosts = User::whereIn('id', function ($query) {
        //     $query->select('user_id')
        //         ->from('campaigns')
        //         ->distinct();
        // })->get();

        $usersWithPosts = User::select('users.*', DB::raw('(SELECT COUNT(*) FROM campaigns WHERE campaigns.user_id = users.id) as total_posts'))
            ->whereIn('id', function ($query) {
                $query->select('user_id')
                    ->from('campaigns')
                    ->distinct();
            })->get();
        //->paginate(10);
        return view('admin.users.campaign_creator', ['verifiedUsers' => $usersWithPosts]);
    }
    public function verifiedUserList()
    {
        $verifiedUsers = User::where(
            'role',
            'regular'
        )->where(
            'is_verified',
            '1'
        )->latest()
            ->paginate(100);

        return view(
            'admin.verified_user',
            ['verifiedUsers' => $verifiedUsers]
        );
    }

    // public function verifiedUserList(Request $request)
    // {
    //     set_time_limit(120);
    //     ini_set('memory_limit', '2048M');
    //     $cacheKey = $this->getCacheKey($request);
    //     $page = $request->get('page', 1);

    //     // Cache paginated results for 1 hour
    //     $verifiedUsers = Cache::remember(
    //         "{$cacheKey}_page_{$page}",
    //         now()->addHour(),
    //         function () use ($request) {
    //             return $this->getFilteredUsers($request);
    //         }
    //     );

    //     return view('admin.verified_user_new', ['verifiedUsers' => $verifiedUsers]);
    // }

    public function exportVerifiedUsersCsv(Request $request)
    {
        $exportJob = ExportJob::create([
            'email' => $request->email ?? 'freebyzcom@gmail.com',
            'status' => 'pending'
        ]);

        ExportVerifiedUsersJob::dispatch($exportJob->id, $exportJob->email, [
            'amount_range' => $request->amount_range,
            'date_range'   => $request->date_range
        ]);

        return response()->json([
            'message' => 'Your export is being processed. You will receive an email shortly.',
            'job_id' => $exportJob->id
        ]);
    }

    private function getFilteredUsers(Request $request)
    {
        $query = $this->buildQuery($request);
        return $query->selectRaw('users.*, COALESCE(income_calc.total_income, 0) as income')
            ->latest('users.id')
            ->paginate(100);
    }
    public function downloadVerifiedUsersCsvOld(Request $request)
    {
        set_time_limit(120);
        ini_set('memory_limit', '2048M');

        $cacheKey = $this->getCacheKey($request);

        // Cache full dataset for 1 hour
        $users = Cache::remember(
            "{$cacheKey}_all",
            now()->addHour(),
            function () use ($request) {
                return $this->getAllFilteredUsers($request);
            }
        );

        $fileName = 'freebyz_verified_users_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function () use ($users, $request) {
            $file = fopen('php://output', 'w');

            // Build date range label
            if ($request->filled('date_from') || $request->filled('date_to')) {
                $from = $request->date_from ?? 'Start';
                $to = $request->date_to ?? 'End';
                $dateRangeLabel = "Custom: $from to $to";
            } else {
                $dateRangeLabel = match ($request->date_range) {
                    'last_30' => 'Last 30 Days',
                    'last_6_months' => 'Last 6 Months',
                    'last_1_year' => 'Last 1 Year',
                    default => 'All Time'
                };
            }

            fputcsv($file, ["Users with income from: $dateRangeLabel"]);
            fputcsv($file, []);

            fputcsv($file, ['#', 'Name', 'Email', 'Phone', 'Income', 'Currency', 'Channel']);

            $i = 1;
            foreach ($users as $user) {
                fputcsv($file, [
                    $i++,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->income,
                    $user->wallet->base_currency ?? 'NGN',
                    $user->source,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getAllFilteredUsers(Request $request)
    {
        $query = $this->buildQuery($request);
        return $query->selectRaw('users.*, COALESCE(income_calc.total_income, 0) as income')
            ->latest('users.id')
            ->with('wallet')
            ->get();
    }

    private function buildQuery(Request $request)
    {
        $query = User::where('role', 'regular')
            ->where('is_verified', '1');

        $incomeSubquery = PaymentTransaction::selectRaw('user_id, SUM(amount) as total_income')
            ->where('tx_type', 'Credit')
            ->where('user_type', 'regular')
            ->where('status', 'successful');

        // Handle date filtering
        if ($request->filled('date_from') || $request->filled('date_to')) {
            // Custom date range
            if ($request->filled('date_from')) {
                $incomeSubquery->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $incomeSubquery->whereDate('created_at', '<=', $request->date_to);
            }
        } elseif ($request->filled('date_range')) {
            // Preset date range
            $days = match ($request->date_range) {
                'last_30' => 30,
                'last_6_months' => 180,
                'last_1_year' => 365,
                default => null
            };

            if ($days) {
                $incomeSubquery->whereDate('created_at', '>=', now()->subDays($days));
            }
        }

        $incomeSubquery->groupBy('user_id');

        $query->leftJoinSub($incomeSubquery, 'income_calc', function ($join) {
            $join->on('users.id', '=', 'income_calc.user_id');
        });

        // Handle amount filtering
        if ($request->filled('amount_min') || $request->filled('amount_max')) {
            // Custom amount range
            $min = $request->filled('amount_min') ? $request->amount_min : 0;
            $max = $request->filled('amount_max') ? $request->amount_max : PHP_INT_MAX;

            $query->havingRaw('COALESCE(income_calc.total_income, 0) BETWEEN ? AND ?', [$min, $max]);
        } elseif ($request->filled('amount_range')) {
            // Preset amount range
            [$min, $max] = match ($request->amount_range) {
                'below_10k' => [0, 9999],
                '10k_30k' => [10000, 30000],
                '30k_70k' => [30000, 70000],
                '70k_above' => [70000, PHP_INT_MAX],
                default => [0, PHP_INT_MAX]
            };

            $query->havingRaw('COALESCE(income_calc.total_income, 0) BETWEEN ? AND ?', [$min, $max]);
        }

        return $query;
    }

    private function getCacheKey(Request $request)
    {
        $dateRange = $request->get('date_range', $request->get('date_from') . '_' . $request->get('date_to'));
        $amountRange = $request->get('amount_range', $request->get('amount_min') . '_' . $request->get('amount_max'));

        return "verified_users_" . md5($dateRange . '_' . $amountRange);
    }

    // private function buildQuery(Request $request)
    // {
    //     $query = User::where('role', 'regular')
    //         ->where('is_verified', '1');

    //     $incomeSubquery = PaymentTransaction::selectRaw('user_id, SUM(amount) as total_income')
    //         ->where('tx_type', 'Credit')
    //         ->where('user_type', 'regular')
    //         ->where('status', 'successful');

    //     if ($request->filled('date_range')) {
    //         $days = match ($request->date_range) {
    //             'last_30' => 30,
    //             'last_6_months' => 180,
    //             'last_1_year' => 365,
    //             default => null
    //         };

    //         if ($days) {
    //             $incomeSubquery->whereDate('created_at', '>=', now()->subDays($days));
    //         }
    //     }

    //     $incomeSubquery->groupBy('user_id');

    //     $query->leftJoinSub($incomeSubquery, 'income_calc', function ($join) {
    //         $join->on('users.id', '=', 'income_calc.user_id');
    //     });

    //     if ($request->filled('amount_range')) {
    //         [$min, $max] = match ($request->amount_range) {
    //             'below_10k' => [0, 9999],
    //             '10k_30k' => [10000, 30000],
    //             '30k_70k' => [30000, 70000],
    //             '70k_above' => [70000, PHP_INT_MAX],
    //             default => [0, PHP_INT_MAX]
    //         };

    //         $query->whereBetween('income_calc.total_income', [$min, $max]);
    //     }

    //     return $query;
    // }

    // private function getCacheKey(Request $request)
    // {
    //     $dateRange = $request->get('date_range', 'all_time');
    //     $amountRange = $request->get('amount_range', 'all_amount');
    //     return "verified_users_{$dateRange}_{$amountRange}";
    // }

    public function usdVerifiedUserList()
    {
        $verifiedUsers = Usdverified::latest()->paginate(100);
        //User::with(['USD_verifiedList'])->get(); //User::where('role', 'regular')->where('is_verified', '1')->orderBy('created_at', 'desc')->get();

        return view(
            'admin.users.usd_verified',
            ['verifiedUsers' => $verifiedUsers]
        );
    }

    public function adminTransaction()
    {
        $list = PaymentTransaction::where(
            'user_type',
            'admin'
        )->where('status', 'successful')->latest()->paginate(50);
        return view('admin.admin_transactions', ['lists' => $list]);
    }
    public function userTransaction()
    {
        $list = PaymentTransaction::where(
            'user_type',
            'regular'
        )->where('status', 'successful')->latest()->paginate(50);
        return view('admin.users.user_transactions', ['lists' => $list]);
    }



    public function userInfo($id)
    {
        // $info = User::where('id', $id)->first();
        $info = User::withCount(['myCampaigns', 'referees', 'myJobs'])->where('id', $id)->first();
        @$user = Referral::where('user_id', $id)->first()->referee_id;
        @$referredBy = User::where('id', $user)->first();
        $bankList = bankList();
        return view('admin.users.user_info_new', [
            'info' => $info,
            'referredBy' => $referredBy,
            'bankList' => $bankList
        ]);
        // return view('admin.users.user_info', ['info' => $info, 'referredBy' => $referredBy,
        // 'bankList' => $bankList
        // ]);

    }

    public function toggleBusinessAccount(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $user->is_business = !$user->is_business;
        $user->save();


        if ($user->is_business) {

            do {
                $apiKey = Str::random(70);               // 40 chars ≈ 256-bit entropy
            } while (User::where('api_key', $apiKey)->exists());

            $user->api_key = $apiKey;
            $status = 'activated';
        } else {
            $user->api_key = null;
            $status = 'deactivated';
        }

        $user->save();

        $status = $user->is_business ? 'activated' : 'deactivated';

        return back()->with('success', "Business account {$status} successfully");
    }

    public function adminUserReferrals($id)
    {
        $user = User::where('id', $id)->first();
        $ref = $user->referees()->paginate(50);
        return view('admin.users.referrals', ['ref' => $ref, 'user' => $user]);
    }
    public function adminUserJobs($id)
    {
        $user = User::where('id', $id)->first();
        $jobs = $user->myJobs->sortByDesc('created_at'); //->paginate(50);
        return view('admin.users.jobs', ['jobs' => $jobs, 'user' => $user]);
    }
    public function adminUserTransactions($id)
    {
        // $user = User::where('id', $id)->first();
        // $transactions = [];
        // // $transactions = PaymentTransaction::where('user_id', $id)->where('status', 'successful')->latest()->paginate(20); //$user->transactions->where('status', 'successful')->orderBy('created_at', 'DESC');
        // return view('admin.users.transactions', ['transactions' => $transactions, 'user' => $user]);

        $user = User::findOrFail($id);

        $transactions = $user->transactions()
            ->where('status', 'successful')
            ->latest()
            ->get(); // Get all, DataTables handles pagination

        return view('admin.users.transactions', compact('user', 'transactions'));
    }

    public function verify($id)
    {
        Log::info('Verification started', ['id' => $id]);

        try {
            $transaction = PaymentTransaction::findOrFail($id);

            $result = $transaction->channel === 'kora'
                ? verifyKorayPay($transaction->reference)
                : verifyTransaction($transaction->reference);

            $isVerified = $result['status'] === true || ($result['data']['status'] ?? null) === 'success';

            if ($isVerified) {
                // $transaction->update(['is_verified' => true]);
            }

            return response()->json([
                'success' => true,
                'verified' => $isVerified,
                'message' => $isVerified ? 'Verified' : 'Unverified'
            ]);
        } catch (\Exception $e) {
            Log::error('Verification failed', ['id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Verification Failed'
            ], 500);
        }
    }

    public function adminUserCampaigns($id)
    {
        $user = User::where('id', $id)->first();
        $campaigns = $user->myCampaigns->sortByDesc('created_at');
        return view('admin.users.campaigns', ['campaigns' => $campaigns, 'user' => $user]);
    }

    public function withdrawalRequest()
    {
        $withdrawal = Withrawal::where('status', '1')->orderBy('created_at', 'DESC')->paginate(50);
        return view('admin.withdrawals.sent', ['withdrawals' => $withdrawal]);
    }
    public function withdrawalRequestQueued()
    {
        $withdrawal = Withrawal::where('status', '0')->orderBy('created_at', 'DESC')->paginate(50);
        return view('admin.withdrawals.queued', ['withdrawals' => $withdrawal]);
    }

    public function withdrawalRequestQueuedCurrent()
    {
        $start_week = Carbon::now()->startOfWeek(); //->format('Y-m-d h:i:s');//next('Friday')->format('Y-m-d h:i:s');
        $end_week = Carbon::now()->endOfWeek();
        $withdrawal = Withrawal::where('status', false)->whereBetween('created_at', [$start_week, $end_week])->paginate(10);
        //$withdrawal = Withrawal::where('status', '0')->orderBy('created_at', 'DESC')->paginate(50);
        return view('admin.withdrawals.current_week', ['withdrawals' => $withdrawal]);
    }

    public function upgradeUserNaira($id)
    {

        if (auth()->user()->hasRole('admin')) {

            $getUser = User::where('id', $id)->first();

            $status = $getUser->is_verified;

            switch ($status) {
                case "1":
                    $this->unverifyNairaUser($id);
                    return back()->with('success', 'User unverified!');
                    break;
                case "0":
                    $this->verifyNairaUser($id);
                    return back()->with('success', 'Upgrade Successful');
                    break;
                default:
                    echo "Invalid";
            }
        }
    }

    private function verifyNairaUser($id)
    {
        $getUser = User::where('id', $id)->first();
        $getUser->is_verified = 1;
        $getUser->save();

        $ref = time();

        $userBaseCurrency = baseCurrency($getUser);

        $currencyParams = currencyParameter($userBaseCurrency);
        $upgradeAmount = $currencyParams->upgrade_fee;
        $referral_commission = $currencyParams->referral_commission;


        PaymentTransaction::create([
            'user_id' => $getUser->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => $upgradeAmount,
            'balance' => walletBalance($getUser->id),
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'upgrade_payment',
            'description' => 'Manual Ugrade Payment'
        ]);

        $referee = Referral::where('user_id',  $getUser->id)->first();

        if ($referee) {

            $refereeInfo = Profile::where('user_id', $referee->referee_id)->first()->is_celebrity;

            if (!$refereeInfo) {

                $wallet = Wallet::where('user_id', $referee->referee_id)->first();
                $wallet->balance += $referral_commission;
                $wallet->save();

                $refereeUpdate = Referral::where('user_id', $getUser->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
                $refereeUpdate->is_paid = 1;
                $refereeUpdate->save();

                $referee_user = User::where('id', $referee->referee_id)->first();
                ///Transactions
                PaymentTransaction::create([
                    'user_id' => $referee_user->id, ///auth()->user()->id,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => $referral_commission,
                    'balance' => walletBalance($referee_user->id),
                    'status' => 'successful',
                    'currency' => 'NGN',
                    'channel' => 'paystack',
                    'type' => 'referer_bonus',
                    'description' => 'Referer Bonus from ' . auth()->user()->name
                ]);

                $adminWallet = Wallet::where('user_id', '1')->first();
                $adminWallet->balance += $referral_commission;
                $adminWallet->save();
                //Admin Transaction Table
                PaymentTransaction::create([
                    'user_id' => 1,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => $referral_commission,
                    'balance' => walletBalance('1'),
                    'status' => 'successful',
                    'currency' => 'NGN',
                    'channel' => 'paystack',
                    'type' => 'referer_bonus',
                    'description' => 'Referer Bonus from ' . $getUser->name,
                    'tx_type' => 'Credit',
                    'user_type' => 'admin'
                ]);
            } else {
                $refereeUpdate = Referral::where('user_id', $getUser->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
                $refereeUpdate->is_paid = true;
                $refereeUpdate->save();
            }
        } else {

            $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->balance += 1000;
            $adminWallet->save();
            //Admin Transaction Tablw
            PaymentTransaction::create([
                'user_id' => 1,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => 1000,
                'balance' => walletBalance('1'),
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'paystack',
                'type' => 'direct_referer_bonus',
                'description' => 'Direct Referer Bonus from ' . $getUser->name,
                'tx_type' => 'Credit',
                'user_type' => 'admin'
            ]);
        }
        // systemNotification(Auth::user(), 'success', 'User Verification',  $getUser->name.' was manually verified');

        $name = $getUser->name;

        activityLog($getUser, 'account_verification', $name . ' account verification', 'regular');
        Mail::to($getUser->email)->send(new UpgradeUser($getUser));

        return $getUser;
    }

    private function unverifyNairaUser($id)
    {

        $getUser = User::where('id', $id)->first();
        $getUser->is_verified = 0;
        $getUser->save();
        $referee = Referral::where('user_id',  $getUser->id)->first();
        if ($referee) {
            $referee->delete();
        }

        return $getUser;
    }

    public function upgradeUserDollar($id)
    {

        if (auth()->user()->hasRole('admin')) {

            $getUser = User::where('id', $id)->first();

            $status = $getUser->USD_verified ? '1' : '0';

            switch ($status) {
                case "1":
                    $this->unverifyUsdUser($id);
                    return back()->with('success', 'User Unverified Successfully!');
                    break;
                case "0":
                    $this->verifyUsdUser($id); // handles global verification
                    return back()->with('success', 'Upgrade Successful');
                    break;
                default:
                    echo "Invalid";
            }
        }
    }

    private function verifyUsdUser($id)
    {

        $getUser = User::where('id', $id)->first();

        $userBaseCurrency = baseCurrency($getUser);
        $currencyParams = currencyParameter($userBaseCurrency);
        $upgradeFee = $currencyParams->upgrade_fee;
        $refComission = $currencyParams->referral_commission;

        $getUser->is_verified = 1; //first step of verification
        $getUser->save();

        $usd_Verified = Usdverified::create(['user_id' => $getUser->id]);

        $ref = time();
        PaymentTransaction::create([
            'user_id' => $getUser->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => $upgradeFee,
            'balance' => walletBalance($getUser->id),
            'status' => 'successful',
            'currency' => $userBaseCurrency,
            'channel' => 'paypal',
            'type' => 'upgrade_payment',
            'description' => 'Manual Ugrade Payment - ' . $userBaseCurrency
        ]);

        $referee = Referral::where('user_id',  $getUser->id)->first();

        if ($referee) {

            ///fetch referee user information
            $refereeUserInfor = User::where('id', $referee->referee_id)->first();
            $refereeBaseCurrency = baseCurrency($refereeUserInfor);

            //convert to referral local currency
            $refComBaseAmount = currencyConverter($userBaseCurrency, $refereeBaseCurrency, $refComission);

            //credit referee with referral comission amount
            creditWallet($refereeUserInfor, $refereeBaseCurrency, $refComBaseAmount);

            $usd_Verified->referral_id = $referee->referee_id;
            $usd_Verified->is_paid = true;
            $usd_Verified->amount = $refComBaseAmount;
            $usd_Verified->save();

            ///Transactions for referee
            PaymentTransaction::create([
                'user_id' => $referee->referee_id,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => $refComBaseAmount,
                'balance' => walletBalance($referee->referee_id),
                'status' => 'successful',
                'currency' => $refereeBaseCurrency,
                'channel' => 'paystack',
                'type' => 'usd_referer_bonus',
                'tx_type' => 'Credit',
                'description' => $refereeBaseCurrency . ' Referral Bonus from ' . $getUser->name
            ]);
        }

        // systemNotification(Auth::user(), 'success', 'User Verification',  $getUser->name.' was manually verified');

        $name = $getUser->name;


        activityLog($getUser, 'dollar_account_verification', $name . ' account verification', 'regular');

        Mail::to($getUser->email)->send(new UpgradeUser($getUser));


        // Mail::to($getUser->email)->send(new UpgradeUser($getUser));

        return response()->json();
        $getUser;
    }

    private function unverifyUsdUser($id)
    {
        $getUser = User::where('id', $id)->first();
        $getUser->is_verified = 0;
        $getUser->save();

        $usd_Verified =  Usdverified::where(['user_id' => $getUser->id]);
        $usd_Verified->delete();

        return $getUser;
    }

    public function campaignList()
    {
        $campaigns = Campaign::where('status', 'Live')->orderBy('id', 'DESC')->paginate(200);

        // \DB::select('SELECT * FROM campaigns WHERE status = ? ORDER BY created_at DESC', ['Live']);

        ///paginate(30);
        return view('admin.campaign_list', ['campaigns' => $campaigns]);
    }

    public function priotize($id)
    {
        $campaign = Campaign::find($id);
        if ($campaign->approved == 'Pending') {
            $campaign->approved = 'Priotized';
            $campaign->save();
            return back()->with('success', 'Campaign Priotized!');
        } else {
            $campaign->approved = 'Pending';
            $campaign->save();
            return back()->with('success', 'Campaign Unpriotized!');
        }
    }



    public function campaignInfo($id)
    {
        $campaign = Campaign::where('id', $id)->first();
        $activities = $campaign->attempts;
        return view('admin.campaign_mgt.info', ['campaign' => $campaign, 'activities' => $activities]);
    }

    public function campaignDelete($id)
    {
        Campaign::where('id', $id)->delete();
        return redirect('campaigns/pending'); //redirect()->with('success', 'Campaign Deleted Successfully');

        // return view('admin.campaign_mgt.info', ['campaign' => $campaign, 'activities' => $activities]);
    }


    public function approvedJobs()
    {
        $list = CampaignWorker::where('status', 'Approved')->orderBy('created_at', 'DESC')->paginate(200);
        return view('admin.approved_list', ['campaigns' => $list]);
    }

    public function deniedCampaigns()
    {
        $list = Campaign::where('status', 'Decline')->orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.denied_list', ['campaigns' => $list]);
    }

    public function jobReversal($id)
    {
        $list = CampaignWorker::where('id', $id)->first();
        $list->status = 'Pending';
        $list->save();

        $campaignAmount = Campaign::where('id', $list->campaign_id)->first(); //get campaign information
        //debit worker
        $wallet = Wallet::where('user_id', $list->user_id)->first();
        $wallet->balance -= $campaignAmount->campaign_amount;
        $wallet->save();

        //credit campaigner
        $wallet = Wallet::where('user_id', $campaignAmount->user_id)->first();
        $wallet->balance += $campaignAmount->campaign_amount;
        $wallet->save();

        $ref = time();

        PaymentTransaction::create([
            'user_id' => $list->user_id,
            'campaign_id' => $campaignAmount->id,
            'reference' => $ref,
            'amount' => $campaignAmount->campaign_amount,
            'balance' => walletBalance($list->user_id),
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'campaign_job_reversal',
            'description' => 'Reversal of job revenue on ' . $campaignAmount->post_title,
            'tx_type' => 'Debit',
            'user_type' => 'regular'
        ]);

        PaymentTransaction::create([
            'user_id' =>  $campaignAmount->user_id,
            'campaign_id' => $campaignAmount->id,
            'reference' => $ref,
            'amount' => $campaignAmount->campaign_amount,
            'balance' => walletBalance($campaignAmount->user_id),
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'campaign_job_reversal_credit',
            'description' => 'Reversal of amount spent on ' . $campaignAmount->post_title,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);

        $user = User::where('id', $campaignAmount->user_id)->first();
        $subject = 'Job Reversal';

        $content = 'Your request to for job reversal is successful. A total of NGN' . $campaignAmount->campaign_amount . ' has been credited to your wallet from ' . $campaignAmount->post_title . ' job';
        Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

        return back()->with('success', 'Reversal Successful');
    }

    public function changeCompleted($id)
    {
        $camp = Campaign::where('id', $id)->first();
        if ($camp->is_completed == true) {
            $camp->is_completed = false;
            $camp->save();
        } else {
            $camp->is_completed = true;
            $camp->save();
        }
        return back()->with('success', 'Completed status changed!');
    }

    public function unapprovedJobs(Request $request)
    {

        // $currentTime = Carbon::now();
        // $twentyFourHoursAgo = $currentTime->subHours(24);

        // $list = CampaignWorker::where('status', 'Pending')
        // // ->whereDate('created_at', '<=', $twentyFourHoursAgo)->paginate(200);
        // ->orderBy('created_at', 'DESC')->paginate(200);

        // $request->validate([
        //     'start' => 'required|date',
        //     'end' => 'required|date|after_or_equal:start',
        // ]);

        //     $list = collect();
        // if($request->has(['start', 'end'])){
        //      $startDate = $request->start;
        //      $endDate = $request->end;

        //      $list =  CampaignWorker::where('status', 'Pending')->where('reason', null)
        //        ->whereDate('created_at', '>=', $startDate)
        //        ->whereDate('created_at', '<=', $endDate)
        //        ->paginate(50);
        // }else{
        //      $yesterday = Carbon::yesterday(); //Carbon::today()->subDays(15);
        //      $list =  CampaignWorker::where('status', 'Pending')->where('reason', null)
        //        ->whereDate('created_at', $yesterday)
        //        ->paginate(50);
        // }


        $query = CampaignWorker::where('status', 'Pending')->whereNull('reason');

        if ($request->filled(['start', 'end'])) {
            $startDate = $request->start;
            $endDate = $request->end;

            $query->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        } else {
            $yesterday = Carbon::yesterday()->toDateString();
            $query->whereDate('created_at', $yesterday);
        }

        $list = $query->paginate(50);


        return view('admin.unapproved_list', [
            'campaigns' => $list,
            'start' => $request->start,
            'end' => $request->end
        ]);
    }


    public function massApproval(Request $request)
    {

        $ids = $request->id;
        if (empty($ids)) {
            return back()->with('error', 'Please select at least one item');
        }

        foreach ($ids as $id) {

            $ca = CampaignWorker::where('id', $id)->first();
            $ca->status = 'Approved';
            $ca->reason = 'Auto-approval';
            $ca->save();

            $camp = Campaign::where('id', $ca->campaign_id)->first();
            checkCampaignCompletedStatus($camp->id);

            $user = User::where('id', $ca->user_id)->first();
            $baseCurrency = baseCurrency($user);
            $amountCredited = $ca->amount;
            if ($baseCurrency == 'NGN') {
                $currency = 'NGN';
                $channel = 'paystack';
                $wallet = Wallet::where('user_id', $ca->user_id)->first();
                // $wallet->balance += (int)$amountCredited;
                $wallet->balance += $amountCredited;
                $wallet->save();
            } elseif ($camp->currency == 'USD') {
                $currency = 'USD';
                $channel = 'paypal';
                $wallet = Wallet::where('user_id', $ca->user_id)->first();
                $wallet->usd_balance += $amountCredited;
                $wallet->save();
            } else {
                $currency = baseCurrency($user);
                $channel = 'flutterwave';
                $wallet = Wallet::where('user_id', $ca->user_id)->first();
                $wallet->base_currency_balance += $amountCredited;
                $wallet->save();
            }

            $ref = time();

            PaymentTransaction::create([
                'user_id' => $ca->user_id,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => $amountCredited,
                'balance' => walletBalance($ca->user_id),
                'status' => 'successful',
                'currency' => $currency,
                'channel' => $channel,
                'type' => 'campaign_payment',
                'description' => 'Campaign Payment for ' . $ca->campaign->post_title,
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]);
        }
        return back()->with('success', 'Mass Approval Successful');
    }

    // public function massMail()
    // {
    //     return view('admin.mass_mail');
    // }

    // Controller methods
    public function massMail()
    {
        $countries = User::where('role', 'regular')
            ->distinct()
            ->pluck('country')
            ->filter()
            ->sort()
            ->values();

        return view('admin.mass_mail', compact('countries'));
    }

    public function previewAudience(Request $request)
    {
        $query = User::where('role', 'regular');

        // Filter by audience type
        if ($request->type === 'verified') {
            $query->where('is_verified', 1)->whereNotNull('verified_at');
        } elseif ($request->type === 'test_user') {
            $result = (object)[
                'total' => 1,
                'with_email' => 1,
                'with_phone' => 0
            ];
        } elseif ($request->type === 'email_verified') {
            $query->whereNotNull('email_verified_at');
        }

        // Filter by date range
        if ($request->days) {
            $date = now()->subDays($request->days);

            if ($request->type === 'verified') {
                $query->where('verified_at', '>=', $date);
            } elseif ($request->type === 'email_verified') {
                $query->where('email_verified_at', '>=', $date);
            } else {
                $query->where('created_at', '>=', $date);
            }
        }

        // Filter by country
        if ($request->country) {
            $query->where('country', $request->country);
        }

        $cacheKey = 'audience_preview_' . md5(json_encode($request->only(['type', 'days', 'country'])));

        if (!app()->environment(['local', 'local_test'])) {
            $result = Cache::remember($cacheKey, 300, function () use ($query) {
                return $query->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN email IS NOT NULL THEN 1 ELSE 0 END) as with_email,
                SUM(CASE WHEN phone IS NOT NULL THEN 1 ELSE 0 END) as with_phone
            ')->first();
            });
        } else {
            $result = $query->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN email IS NOT NULL THEN 1 ELSE 0 END) as with_email,
            SUM(CASE WHEN phone IS NOT NULL THEN 1 ELSE 0 END) as with_phone
        ')->first();
        }

        return response()->json([
            'total' => $result->total,
            'with_email' => $result->with_email,
            'with_phone' => $result->with_phone
        ]);
    }


    public function sendMassCommunication(Request $request)
    {
        $query = User::where('role', 'regular');

        if ($request->type === 'verified') {
            $query->where('is_verified', 1)->whereNotNull('verified_at');
        } elseif ($request->type === 'email_verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($request->type === 'test_user') {
            $query = User::where('email', 'morakinyovictor1@gmail.com');
        }


        if ($request->days) {
            $date = now()->subDays($request->days);
            if ($request->type === 'verified') {
                $query->where('verified_at', '>=', $date);
            } elseif ($request->type === 'email_verified') {
                $query->where('email_verified_at', '>=', $date);
            } else {
                $query->where('created_at', '>=', $date);
            }
        }

        if ($request->country) {
            $query->where('country', $request->country);
        }


        // Send email using chunk
        if ($request->send_email) {
            // Create campaign record
            $campaign = MassEmailCampaign::create([
                'subject' => $request->subject,
                'message' => $request->message,
                'audience_type' => $request->type ?? 'registered',
                'days_filter' => $request->days,
                'country_filter' => $request->country,
                'total_recipients' => 0,
                'sent_by' => auth()->id(),
            ]);
            $totalRecipients = 0;

            (clone $query)
                ->whereNotNull('email')
                ->select('id', 'email')
                ->chunk(900, function ($users) use ($request, $campaign, &$totalRecipients) {
                    $totalRecipients += $users->count();

                    // Create log entries
                    $logData = $users->map(fn($user) => [
                        'campaign_id' => $campaign->id,
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])->toArray();

                    MassEmailLog::insert($logData);

                    $userIds = $users->pluck('id')->toArray();
                    dispatch(new SendMassEmail($userIds, $request->message, $request->subject, $campaign->id));
                });

            $campaign->update(['total_recipients' => $totalRecipients]);
        }

        // Send SMS in bulk
        if ($request->send_sms) {
            (clone $query)
                ->whereNotNull('phone')
                ->select('phone')
                ->chunk(100, function ($users) use ($request) {
                    $phones = $users->pluck('phone')->toArray();
                    $phones = formatAndArrange($phones);
                    Log::info($phones);
                    if (!empty($phones)) {
                        $process = sendBulkSMS($phones, $request->sms_message);
                        $code = is_array($process) ? $process['code'] : $process->code;

                        if ($code !== 'ok') {
                            return back()->with('error', 'SMS sending failed: ' . ($process['message'] ?? $process->message ?? 'Unknown error'));
                        }
                    }
                });
        }

        return redirect()->route('mass.mail.campaigns')->with('success', 'Communication sent successfully');
    }

    // List all campaigns
    public function campaigns()
    {
        $campaigns = MassEmailCampaign::with('sentBy')
            ->latest()
            ->paginate(20);

        // Fetch all aggregate stats in a single query
        $stats = MassEmailCampaign::selectRaw('
        COUNT(*) as total_campaigns,
        SUM(total_recipients) as total_sent,
        SUM(delivered) as total_delivered,
        SUM(opened) as total_opened,
        SUM(bounced) as total_bounced,
        SUM(failed) as total_failed
    ')->first();

        return view('admin.mass_mail_campaigns', compact('campaigns', 'stats'));
    }


    // Campaign details
   public function campaignDetails($id)
{
    $campaign = MassEmailCampaign::findOrFail($id);

    // Fetch logs with pagination
    $logs = MassEmailLog::where('campaign_id', $id)
        ->with('user')
        ->latest()
        ->paginate(50);

    // Single optimized aggregate query
    $stats = MassEmailLog::where('campaign_id', $id)
        ->selectRaw("
            COUNT(*) as total_sent,
            SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as delivered,
            SUM(CASE WHEN status = 'opened' THEN 1 ELSE 0 END) as opened,
            SUM(CASE WHEN status IN ('failed', 'bounced') THEN 1 ELSE 0 END) as failed_bounced
        ")
        ->first();

    return view('admin.mass_mail_campaign_details', [
        'campaign' => $campaign,
        'logs' => $logs,
        'totalSent' => $stats->total_sent ?? 0,
        'delivered' => $stats->delivered ?? 0,
        'opened' => $stats->opened ?? 0,
        'failedBounced' => $stats->failed_bounced ?? 0,
    ]);
}



    public function sendMassMail(Request $request)
    {
        if ($request->type == 'all') {
            $users = User::where('is_verified', 0)->where('role', 'regular')->pluck('phone')->toArray();
        } else {
            $users = User::where('is_verified', 1)->where('role', 'regular')->pluck('phone')->toArray();
        }
        // return $users;

        $message = $request->message;
        $subject = $request->subject;
        $number = $users;

        // foreach($users as $user){
        //     dispatch(new SendMassEmail($user, $message, $subject));
        // }

        // $list = [];
        // foreach($users as $key => $value){
        //     $value['phone'];
        //     //$list[++$key] = ['234'.substr($value->phone, 1)];//$value->phone];
        // }
        // return $list;

        // $process = PaystackHelpers::sendBulkSMS($number, $message);
        // if($process['code'] == 'ok'){
        //     return back()->with('success', 'SMS Sent Successful');
        // }else{
        //     return back()->with('error', 'There was an error in transit');
        // }
    }

    public function campaignPending()
    {
        $pendingCampaign = Campaign::orderBy('created_at', 'DESC')->where('status', 'Offline')->orderBy('created_at', 'DESC')->get();
        return view('admin.pending_campaigns', ['campaigns' => $pendingCampaign]);
    }

    public function campaignStatus(Request $request)
    {

        $camp = Campaign::where('id', $request->id)->first(); //find($request->id);

        if ($request->status == 'Decline') {
            // return $status;
            $amount = $camp->total_amount;
            $camp->status = $request->status;
            $camp->save();

            //reverse the money
            $userWallet = Wallet::where('user_id', $camp->user_id)->first();
            $userWallet->balance += $amount;
            $userWallet->save();

            $est_amount = $camp->number_of_staff * $camp->campaign_amount;
            $percent = (60 / 100) * $est_amount;
            $adminCom = $est_amount - $percent;

            $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->balance -= $adminCom;
            $adminWallet->save();

            PaymentTransaction::create([
                'user_id' => $camp->user_id,
                'campaign_id' => $camp->id,
                'reference' => time(),
                'amount' => $amount,
                'balance' => walletBalance($camp->user_id),
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'paystack',
                'type' => 'campaign_reversal',
                'description' => 'Campaign Reversal for ' . $camp->post_title,
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]);
            $user = User::where('id', $camp->user_id)->first();
            $content = 'Reason: ' . $request->reason . '.';
            $subject = 'Campaign Declined';

            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
            return redirect('campaigns/pending')->with('error', 'Campaign is Declined');
        } else {

            $camp->status = $request->status;
            $camp->post_title = $request->post_title;
            $camp->post_link = $request->post_link;
            $camp->description = $request->description;
            $camp->proof = $request->proof;
            $camp->save();
            $user = User::where('id', $camp->user_id)->first();
            $content = 'Your campaign has been approved and it is now Live. Thank you for choosing Freebyz.com';
            $subject = 'Campaign Live!!!';
            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
            return redirect('campaigns/pending')->with('success', 'Campaign is Live!!!');
        }

        // return back()->with('success', 'Campaign Successfully '.$request->status);
    }

    public function marketplaceCreateProduct()
    {
        $product_type = ProductType::all();
        return view('admin.market_place.create', ['product_type' => $product_type]);
    }

    public function storeMarketplace(Request $request)
    {
        //return $request;
        $this->validate($request, [
            'banner' => 'image|mimes:png,jpeg,gif,jpg',
            // 'product' => 'mimes:mp3,mpeg,mp4,3gp,pdf',
            'name' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        //&& $request->hasFile('product')
        if ($request->hasFile('banner')) {

            $fileBanner = $request->file('banner');
            // $fileProduct = $request->file('product');

            $Bannername = time() . $fileBanner->getClientOriginalName();
            // $Productname = time() . $fileProduct->getClientOriginalName();

            $filenameExtensionBanner = $fileBanner->getClientOriginalExtension();
            // $filenameExtensionProduct = $fileProduct->getClientOriginalExtension();

            $filePathBanner = 'banners/' . $Bannername;
            // $filePathProduct = 'products/' . $Productname;

            $storeBanner = Storage::disk('s3')->put($filePathBanner, file_get_contents($fileBanner), 'public');
            $bannerUrl = Storage::disk('s3')->url($filePathBanner);
            // $storeProduct = Storage::disk('s3')->put($filePathProduct, file_get_contents($fileProduct), 'public');
            // $prodductUrl = Storage::disk('s3')->url($filePathProduct);

            $data['user_id'] = auth()->user()->id;
            $data['name'] = $request->name;
            $data['amount'] = $request->amount;
            $data['commission'] = $request->commission;
            $data['total_payment'] = $request->total_payment;
            $data['type'] = 'electronic';
            $data['commission_payment'] = $request->commission_payment;
            $data['banner'] = $bannerUrl;
            $data['views'] = '0';
            $data['product_id'] = Str::random(7);
            $data['product'] = $request->product; //$prodductUrl;
            $data['description'] = $request->description;
            MarketPlaceProduct::create($data);
            return back()->with('success', 'Product Successfully created');
        } else {
            return back()->with('error', 'Please upload an image');
        }
    }

    public function viewMarketplace()
    {
        $list = MarketPlaceProduct::orderBy('created_at', 'ASC')->get();
        return view('admin.market_place.view', ['marketPlaceLists' => $list]);
    }

    public function updateWithdrawalRequest($id)
    {
        $withdrawals = Withrawal::where('id', $id)->first();

        if ($withdrawals->status == '0') {
            if ($withdrawals->base_currency != null || $withdrawals->base_currency != 'NGN' || $withdrawals->base_currency != 'USD') {
                $payload =  $withdrawals->content;

                $trf = flutterwaveTransfer($payload);

                if ($trf['status'] == 'success') {
                    $withdrawals->status = true;
                    $withdrawals->save();
                    $user = User::where('id', $withdrawals->user->id)->first();
                    $name = $user->name;
                    activityLog($user, 'withdrawal_sent', ' ' . $withdrawals->base_currency . '' . $am = number_format($withdrawals->amount * 100) . ' cash withdrawal by ' . $name, 'regular');

                    $content = 'Your withdrawal request has been granted and your acount credited successfully. Thank you for choosing Freebyz.com';
                    $subject = 'Withdrawal Request Granted';
                    Mail::to($withdrawals->user->email)->send(new GeneralMail($user, $content, $subject, ''));
                    return back()->with('success', 'Withdrawals Updated');
                } else {
                    return back()->with('error', 'Withdrawal Error');
                }
            } else {
                $user = User::where('id', $withdrawals->user->id)->first();
                $bankInformation = BankInformation::where('user_id', $withdrawals->user->id)->first();
                $transfer = $this->transferFund($withdrawals->amount * 100, $bankInformation->recipient_code, 'Freebyz Withdrawal');
                if ($transfer['data']['status'] == 'success' || $transfer['data']['status'] == 'pending') {
                    $withdrawals->status = true;
                    $withdrawals->save();
                    //set activity log
                    $am = number_format($withdrawals->amount * 100);

                    $name = $user->name;
                    activityLog($user, 'withdrawal_sent', 'NGN' . $am . ' cash withdrawal by ' . $name, 'regular');
                    //send mail
                    $content = 'Your withdrawal request has been granted and your account credited successfully. Thank you for choosing Freebyz.com';
                    $subject = 'Withdrawal Request Granted';
                    Mail::to($withdrawals->user->email)->send(new GeneralMail($user, $content, $subject, ''));
                    return back()->with('success', 'Withdrawals Updated');
                } else {
                    return back()->with('error', 'Withdrawals Error');
                }
            }
        } else {
            return back()->with('error', 'Payment has already been processed');
        }
    }

    public function updateWithdrawalRequestManual($id)
    {
        $withdrawals = Withrawal::where('id', $id)->first();
        $withdrawals->status = true;
        $withdrawals->save();
        $user = User::where('id', $withdrawals->user->id)->first();

        //set activity log
        $am = number_format($withdrawals->amount);
        $name = $user->name;
        activityLog($user, 'withdrawal_sent', 'NGN' . $am . ' cash withdrawal by ' . $name, 'regular');

        $content = 'Your withdrawal request has been granted and your acount credited successfully. Thank you for choosing Freebyz.com';
        $subject = 'Withdrawal Request Granted';
        Mail::to($withdrawals->user->email)->send(new GeneralMail($user, $content, $subject, ''));
        return back()->with('success', 'Withdrawals Updated');
    }


    public function transferFund($amount, $recipient, $reason)
    {
        return transferFund($amount, $recipient, $reason);
    }

    public function removeMarketplaceProduct($product_id)
    {
        $productInfo = MarketPlaceProduct::where('product_id', $product_id)->first();
        $excludedUrl = explode('https://freebyz.s3.us-east-1.amazonaws.com/banners/', $productInfo->banner);
        $bannerName = $excludedUrl[1];
        Storage::disk('s3')->delete('banners/' . $bannerName);
        $productInfo->delete();
        // return Storage::disk('s3')->download('banners/'.$bannerName);
        return back()->with('success', 'Product removed Successfully');
    }

    public function createDatabundles()
    {
        $databundles = DataBundle::orderby('name', 'ASC')->get();
        return view('admin.databundles.index', ['databundles' => $databundles]);
    }

    public function storeDatabundles(Request $request)
    {
        $created = DataBundle::create($request->all());
        $created->save();
        return back()->with('success', 'Databundle Created Successfully');
    }

    public function adminWalletTopUp(Request $request)
    {

        $user = auth()->user();

        if ($user->hasRole('admin')) {

            if ($request->type == 'credit') {
                $currency = '';
                $channel = '';
                if ($request->currency == 'NGN') {
                    $currency = 'NGN';
                    $channel = 'paystack';
                    $wallet = Wallet::where('user_id', $request->user_id)->first();
                    $wallet->balance += $request->amount;
                    $wallet->save();
                } elseif ($request->currency == 'USD') {
                    $currency = 'USD';
                    $channel = 'paypal';
                    $wallet = Wallet::where('user_id', $request->user_id)->first();
                    $wallet->usd_balance += $request->amount;
                    $wallet->save();
                } else {
                    $currency = $request->currency;
                    $channel = 'flutterwave';
                    $wallet = Wallet::where('user_id', $request->user_id)->first();
                    $wallet->base_currency_balance += $request->amount;
                    $wallet->save();
                }

                PaymentTransaction::create([
                    'user_id' => $request->user_id,
                    'campaign_id' => '1',
                    'reference' => time(),
                    'amount' => $request->amount,
                    'balance' => walletBalance($request->user_id),
                    'status' => 'successful',
                    'currency' => $currency,
                    'channel' => $channel,
                    'type' => 'wallet_topup',
                    'description' => 'Manual Wallet Topup',
                    'tx_type' => 'Credit',
                    'user_type' => 'regular'
                ]);

                // PaystackHelpers::paymentTrasanction($request->user_id, '1', time(), $request->amount, 'successful', 'wallet_topup', 'Manual Wallet Topup', 'Credit', 'regular');
                $content = 'Your wallet has been succesfully credited with NGN' . $request->amount . '. Thank you for choosing Freebyz.com';
                $subject = 'Wallet Topup';
                $user = User::where('id', $request->user_id)->first();
                Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
                return back()->with('success', 'Wallet Successfully Funded');
            } else {
                $currency = '';
                $channel = '';
                if ($request->currency == 'NGN') {
                    $currency = 'NGN';
                    $channel = 'paystack';
                    $wallet = Wallet::where('user_id', $request->user_id)->first();
                    $wallet->balance -= $request->amount;
                    $wallet->save();
                } elseif ($request->currency == 'USD') {
                    $currency = 'USD';
                    $channel = 'paypal';
                    $wallet = Wallet::where('user_id', $request->user_id)->first();
                    $wallet->usd_balance -= $request->amount;
                    $wallet->save();
                } else {
                    $currency = $request->currency;
                    $channel = 'flutterwave';
                    $wallet = Wallet::where('user_id', $request->user_id)->first();
                    $wallet->base_currency_balance -= $request->amount;
                    $wallet->save();
                }

                PaymentTransaction::create([
                    'user_id' => $request->user_id,
                    'campaign_id' => '1',
                    'reference' => time(),
                    'amount' => $request->amount,
                    'balance' => walletBalance($request->user_id),
                    'status' => 'successful',
                    'currency' => $currency,
                    'channel' => $channel,
                    'type' => 'wallet_debit',
                    'description' => 'Admin manual Wallet Debit',
                    'tx_type' => 'Debit',
                    'user_type' => 'regular'
                ]);
                $content = $request->reason;
                $subject = 'Wallet Debit';
                $user = User::where('id', $request->user_id)->first();
                Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
                return back()->with('success', 'Wallet Successfully Debitted');
            }
        } else {


            $currency = '';
            $channel = '';
            if ($request->currency == 'NGN') {
                $currency = 'NGN';
                $channel = 'paystack';
                $wallet = Wallet::where('user_id', $request->user_id)->first();
                $wallet->balance -= $request->amount;
                $wallet->save();
            } elseif ($request->currency == 'USD') {
                $currency = 'USD';
                $channel = 'paypal';
                $wallet = Wallet::where('user_id', $request->user_id)->first();
                $wallet->usd_balance -= $request->amount;
                $wallet->save();
            } else {
                $currency = $request->currency;
                $channel = 'flutterwave';
                $wallet = Wallet::where('user_id', $request->user_id)->first();
                $wallet->base_currency_balance -= $request->amount;
                $wallet->save();
            }

            PaymentTransaction::create([
                'user_id' => $request->user_id,
                'campaign_id' => '1',
                'reference' => time(),
                'amount' => $request->amount,
                'balance' => walletBalance($request->user_id),
                'status' => 'successful',
                'currency' => $currency,
                'channel' => $channel,
                'type' => 'wallet_debit',
                'description' => 'Admin manual Wallet Debit',
                'tx_type' => 'Debit',
                'user_type' => 'regular'
            ]);
            $content = $request->reason;
            $subject = 'Wallet Debit';
            $user = User::where('id', $request->user_id)->first();
            // Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
            return back()->with('success', 'Wallet Successfully Debitted');
        }
    }

    public function adminCelebrity(Request $request)
    {

        $userInfor = User::where('id', $request->user_id)->first();
        $userInfor->referral_code = $request->referral_code;
        $userInfor->save();

        $userInfor->profile()->update(['is_celebrity' => true]);
        return back()->with('success', 'User Updated to Celebrity Successfully');
    }

    public function campaignCompleted()
    {
        $campaigns = Campaign::where('status', 'Live')->orderBy('created_at', 'DESC')->get();
        return view('admin.campaign_completed', ['campaigns' => $campaigns]);
    }

    public function userlocation()
    {
        $userTracker = UserLocation::orderBy('created_at', 'DESC')->paginate(100);
        return view('admin.users.user_location', ['userTracker' => $userTracker]);
    }

    public function blacklist($id)
    {
        User::where('id', $id)->update(['is_blacklisted' => 1]);
        return back()->with('success', 'User Blacklisted');
    }

    public function switch(Request $request)
    {

        $switchWallet = Wallet::where('user_id', $request->user_id)->first();
        $switchWallet->base_currency = $request->currency;
        $switchWallet->save();

        //     if($switchWallet->base_currency == 'Dollar'){
        //         $switchWallet->base_currency == 'Naira';
        //         $switchWallet->save();
        //     }elseif($switchWallet->base_currency == 'Naira'){
        //         $switchWallet->base_currency == 'Dollar';
        //         $switchWallet->save();
        //     }
        return back()->with('success', 'Currency switched successfully');
    }

    public function updateUserAccountDetails(Request $request)
    {
        $accountInformation = resolveBankName($request->account_number, $request->bank_code);
        // Log::info($accountInformation);
        if ($accountInformation['status'] == 'true') {
            $recipientCode = recipientCode($accountInformation['data']['account_name'], $request->account_number, $request->bank_code);
            BankInformation::updateOrCreate(
                ['user_id' => $request->user_id],
                [
                    'name' => $accountInformation['data']['account_name'],
                    'bank_name' => $recipientCode['data']['details']['bank_name'],
                    'account_number' => $request->account_number,
                    'bank_code' => $request->bank_code,
                    'recipient_code' => $recipientCode['data']['recipient_code'],
                ]
            );

            $user = User::where('id', $request->user_id)->first();
            $subject = 'Account Details Updated';
            $content = 'Congratulations, your account details has been updated on Freebyz.';

            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
            return back()->with('success', 'Account Details Upated');
        }
        return back()->with('error', 'Account Updating Failed');
    }

    public function virtualAccountList()
    {
        $virtual = VirtualAccount::where('account_number', null)->orderBy('created_at', 'DESC')->get();
        return view('admin.users.virtual_account', ['virtual' => $virtual]);
    }

    public function reactivateVA($id)
    {

        // return $request;
        // $bankInfor = BankInformation::where('user_id', $id)->first()->name;
        $userPhone = User::where('id', $id)->first();
        reGenerateVirtualAccount($userPhone);
        return back()->with('success', 'Virtual Account Regenerated Successfully');
    }

    public function removeVirtualAccount($id)
    {
        VirtualAccount::where('id', $id)->delete();
        return back()->with('success', 'VA removed - ');
    }

    public function listFlutterwaveTrf()
    {
        return listFlutterwaveTransaction();
    }

    public function test()
    {
        return totalVirtualAccount();
        // $bankList = PaystackHelpers::bankList();



        //CREATE CARD
        // $payload = [
        //     'customerEmail' => 'solo@email.com',
        //     'cardBrand' => 'visa',
        //     'cardType' => 'virtual',
        //     'reference' => Str::random(16),
        //     'amount' => 500,
        //     'firstName' => 'Solo',
        //     'lastName' => 'Tobby'
        // ];
        // return createBrailsVirtualCard($payload);

        //FINALISE PAYOUT
        // $payload = [
        //     'transactionId' => 'f82e8a73-19eb-4209-b95e-826896cd03ec',
        // ];
        // return finalizeBrailsPayout($payload);

        //INITIATE BRAILS PAYOUT
        // $payload = [
        //     'amount' => 1000,
        //     'country' => 'GH',
        //     'reference' => Str::random(16),
        //     'customerEmail' => 'solo@email.com',
        //     'description' => 'Payout Initiation',
        //     'beneficiaryId' => '01b753c1-b664-47f0-b311-608e7f0d5034',
        //     'sourceWalletCurrency' => 'USD'
        // ];
        // return initiateBrailsPayout($payload);
        // "data": {
        //     "id": "2f94631f-0c65-4ce7-acc7-e6312230defe",
        //     "description": "Payout Initiation",
        //     "createdAt": "2024-01-18T12:51:54.906Z",
        //     "updatedAt": "2024-01-18T12:51:54.906Z",
        //     "reference": "fShaYsSY6RCYQrUM",
        //     "exchangeRate": 12.36,
        //     "amount": 0.08,
        //     "action": "ghs_account_payout",
        //     "fees": 0,
        //     "type": "debit",
        //     "status": "initiated",
        //     "channel": "payout",
        //     "companyId": "31e8e24e-a332-408e-a7cc-6c39a20e3aee",
        //     "customerId": "1ef7b7bf-50b7-4784-bf7c-c7f359963ec2",
        //     "beneficiary": {
        //     "type": "MOBILEMONEY",
        //     "network": "MTN",
        //     "accountName": "Toby Solo",
        //     "accountNumber": "0545247030"
        //     },
        //     "payoutAmount": 1,
        //     "payoutCurrency": "GHS"
        // }

        //CREATE BRAILS BENEFICIARY
        // $payload = [
        //     "destination"=> [
        //         "network"=> "MTN",
        //         "type"=> "MOBILEMONEY",
        //         "accountName"=> "Toby Solo",
        //         "accountNumber"=> "0545247030",
        //     ],
        //     'currency' => 'GHS',
        //     'country' => 'GH',
        //     'customerEmail' => 'solo@email.com',
        //     'reference' => time()
        // ];
        // return createBrailsBeneficiary($payload);
        // "data": {
        //     "id": "01b753c1-b664-47f0-b311-608e7f0d5034",
        //     "createdAt": "2024-01-18T12:37:44.607Z",
        //     "updatedAt": "2024-01-18T12:37:44.607Z",
        //     "currency": "GHS",
        //     "country": "GH",
        //     "status": "success",
        //     "destination": {
        //     "type": "MOBILEMONEY",
        //     "network": "MTN",
        //     "accountName": "Toby Solo",
        //     "accountNumber": "0545247030"
        //     },
        //     "reference": 1705581464
        //     }

        // return getCountryRequirement('NG');  //error

        //CREATE BRAILS CUSTOMER
        // $payload = [
        //     'firstName' => 'Tobby',
        //     'lastName' => 'Solo',
        //     'email' => 'solo@email.com'
        //  ];
        //  return createBrailsBasicCustomer($payload);

        // "id": "1ef7b7bf-50b7-4784-bf7c-c7f359963ec2",
        // "createdAt": "2024-01-18T12:13:04.498Z",
        // "updatedAt": "2024-01-18T12:13:04.498Z",
        // "firstName": "Tobby",
        // "lastName": "Solo",
        // "email": "solo@email.com",
        // "phone": null,
        // "countryCode": null,
        // "blacklist": false

        //return getCountriesSupported();
        // return listWellaHealthScriptions();


        // return view('welcome', ['html' => $html]);
    }
}
