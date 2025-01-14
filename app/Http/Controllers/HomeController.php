<?php

namespace App\Http\Controllers;

use App\Helpers\Admin;
use App\Helpers\Analytics;
use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Models\AccountInformation;
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
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Random;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // return 'admin';
            return redirect()->route('admin.home');
        } elseif ($user->hasRole('staff')) {
            // return 'staff';
            return redirect()->route('staff.home');
        } else {
            // return 'user';
            return redirect()->route('user.home');
        }
    }

    public function userHome()
    {
        
        dailyVisit('Dashboard');
        if (env('APP_ENV') == 'production') {
            setProfile(auth()->user());
            setWalletBaseCurrency();
        }

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

    public function listCategories(){
        return Category::query()->orderBy('name', 'ASC')->get();
    }

    public function filterCampaignByCategories($category_id){
        return filterCampaign($category_id);
    }

    public function testCampaignList(){
        // testCampaign('0');
        //46  43 : 0,0,0
        return checkCampaignCompletedStatus('6059');
        // return filterCampaign('0');
    }

    public function howTo()
    {
        return view('user.documentation.how_to_approve');
    }

    public function adminHome()
    {
        // $retention = retentionRate();
        // return getPosts();
        // $campaigns = Campaign::where('status', 'Live')->get();
        // $campaignWorker = CampaignWorker::where('status', 'Approved')->sum('amount');
        // $user = User::where('role', 'regular')->get();
        // $loginPoints = LoginPoints::where('is_redeemed', false)->get();

        $wallet = 
        \DB::select('
                SELECT 
                SUM(balance) AS total_balance,
                SUM(CASE WHEN balance > 2500 THEN balance ELSE 0 END) AS balance_gt_200,
                SUM(usd_balance) AS total_usd_balance
                FROM wallets
        ');
        //Wallet::where('user_id', '!=', '1')->get();
      
        //this wwee
        $start_week = Carbon::now()->startOfWeek(); //->format('Y-m-d h:i:s');//next('Friday')->format('Y-m-d h:i:s');
        $end_week = Carbon::now()->endOfWeek();
        $withdrawal = Withrawal::get(['status', 'amount', 'is_usd', 'created_at']); //Date('')
        $thisWeekPayment = $withdrawal->where('status', false)->whereBetween('created_at', [$start_week, $end_week])->sum('amount');
        $totalPayout = $withdrawal->where('is_usd', false)->sum('amount');
        $transactions = PaymentTransaction::where('status', 'successful')->sum('amount');
        $available_jobsCount = count(filterCampaign('0'));
        $totalPayout_ = $withdrawal->where('status', false)->whereBetween('created_at', ['2024-11-01 11:00:27', '2025-01-14 11:00:27'])->sum('amount');

        $transactions = \DB::select('
            SELECT SUM(amount) AS total_successful_transactions
            FROM payment_transactions
            WHERE status = ?
        ', ['successful']);

        //$ref_rev = Referral::where('is_paid', true)->count();
        //$transactions = PaymentTransaction::where('user_type', 'admin')->get();
        //$Wal = Wallet::where('user_id', auth()->user()->id)->first();


        // return $campaignMetric = campaignMetrics();
        //users registered

        $dailyActivity = dailyActivities();

        //monthly visits
        // $start_date = \Carbon\Carbon::today()->subDays(30);
        // $end_date = \Carbon\Carbon::now()->format('Y-m-d');

        $MonthlyVisit = monthlyVisits();

        //daily visits
        $dailyVisits = dailyStats();

        //registration channel
        // $registrationChannel = registrationChannel();

        //revenue channel
        $revenueChannel = revenueChannel();

        //revenue 
        $revenue = monthlyRevenue();

        $weeklyRegistrationChannel = weeklyRegistrationChannel();
        $weeklyVerificationChannel = weeklyVerificationChannel();

        //country distribution
        $countryDistribution = countryDistribution();

        //age distribution
        $ageDistribution = ageDistribution();

        $currencyDistribution = currencyDistribution();

        // $christmas = Profile::where('is_xmas', true)->count();

        return view('admin.index', [
            'wallet' => $wallet,
            'weekPayment' => $thisWeekPayment,
            'totalPayout' => $totalPayout,
            'transactions' => $transactions,
            'totalPayout_' => $totalPayout_,
            // 'xmas' => $christmas,
            'av_count' => $available_jobsCount
        ]) // ['users' => $user, 'campaigns' => $campaigns, 'workers' => $campaignWorker, 'loginPoints' => $loginPoints]) // 'wallet' => $wallet, 'ref_rev' => $ref_rev, 'tx' => $transactions, 'wal'=>$Wal])
            ->with('visitor', json_encode($dailyActivity))
            ->with('daily', json_encode($dailyVisits))
            ->with('monthly', json_encode($MonthlyVisit))
            // ->with('channel', json_encode($registrationChannel))
            ->with('revenue', json_encode($revenueChannel))
            ->with('country', json_encode($countryDistribution))
            ->with('age', json_encode($ageDistribution))
            ->with('currency', json_encode($currencyDistribution))
            ->with('monthlyRevenue', json_encode($revenue))
            ->with('weeklyRegistrationChannel', json_encode($weeklyRegistrationChannel))
            ->with('weeklyVerificationChannel', json_encode($weeklyVerificationChannel));
            // ->with('retention', json_encode($retention));
    }

    public function adminApi(Request $request)
    {
        $data = [];
        // $data['campaigns'] = Campaign::where('status', 'Live')->whereBetween('created_at',[$request->start_date, $request->end_date])->get();
        // $data['campaignWorker'] = CampaignWorker::where('status', 'Approved')->whereBetween('created_at',[$request->start_date, $request->end_date])->sum('amount');
        // $data['user'] = User::where('role', 'regular')->whereBetween('created_at',[$request->start_date, $request->end_date])->get();
        // $data['loginPoints'] = LoginPoints::where('is_redeemed', false)->whereBetween('created_at',[$request->start_date, $request->end_date])->get();

        $camp = Campaign::where('status', 'Live')->whereBetween('created_at', [$request->start_date, $request->end_date])->get();
        $data['campaigns'] = $camp->count();
        $data['campaignValue'] = $camp->sum('total_amount');
        $data['campaignWorker'] = CampaignWorker::where('status', 'Approved')->whereBetween('created_at', [$request->start_date, $request->end_date])->sum('amount');
        $data['registeredUser'] = User::where('role', 'regular')->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
        $data['verifiedUser'] = User::where('role', 'regular')->where('is_verified', true)->whereBetween('created_at', [$request->start_date, $request->end_date])->count();
        // $data['loginPoints'] = LoginPoints::where('is_redeemed', false)->whereBetween('created_at',[$request->start_date, $request->end_date])->sum('point');
        // $data['loginPointsValue'] = $data['loginPoints']/5;

        return $data;
    }

    public function adminApiDefault(Request $request)
    {
        $period = $request->period;

        $data = [];

        $date = \Carbon\Carbon::today()->subDays($period);
        $start_date = \Carbon\Carbon::today()->subDays($period);
        $end_date = \Carbon\Carbon::now()->format('Y-m-d');

        $camp = Campaign::where('status', 'Live')->where('created_at', '>=', $date)->get();
        $data['campaigns'] = $camp->count();
        $data['campaignValue'] = $camp->sum('total_amount');
        $data['campaignWorker'] = CampaignWorker::where('status', 'Approved')->where('created_at', '>=', $date)->sum('amount');
        $data['registeredUser'] = User::where('role', 'regular')->where('created_at', '>=', $date)->count();
        $data['verifiedUser'] = User::where('role', 'regular')->where('is_verified', true)->where('created_at', '>=', $date)->count();
        // $data['loginPoints'] = LoginPoints::where('is_redeemed', false)->where('created_at','>=',$date)->sum('point');
        // $data['loginPointsValue'] = $data['loginPoints']/5;

        // $data['monthlyVisits'] = monthlyVisits();
        return $data;
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
            'wal' => $Wal])
            ->with('visitor', json_encode($dailyActivity))
            ->with('daily', json_encode($dailyVisits))
            ->with('monthly', json_encode($MonthlyVisit))
            ->with('channel', json_encode($registrationChannel));
    }

    public function generateVirtualAccount(){
        
        $res = reGenerateVirtualAccount(auth()->user());
        $responseData = $res->getData(true);

        if($responseData['status'] == true){
            return back()->with('success', 'Freebyz Personal Account Created Successfully');
        }else{
            return back()->with('error', 'An error occoured while creating account, please contact admin by clicking Talk to Us on the side menu');
        }
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

    public function saveBankInformation(Request $request)
    {

        $this->validate($request, [
            'account_number' => 'numeric|required|digits:10'
        ]);
        $accountInformation = resolveBankName($request->account_number, $request->bank_code);

        if ($accountInformation['status'] == 'true') {
            $recipientCode = recipientCode($accountInformation['data']['account_name'], $request->account_number, $request->bank_code);
            $bankInfor = BankInformation::create([
                'user_id' => auth()->user()->id,
                'name' => $accountInformation['data']['account_name'],
                'bank_name' => $recipientCode['data']['details']['bank_name'],
                'account_number' => $request->account_number,
                'bank_code' => $request->bank_code,
                'recipient_code' => $recipientCode['data']['recipient_code'],
                'currency' => 'NGN'
            ]);

            if (auth()->user()->profile->phone_verified == true && $bankInfor) {
                generateVirtualAccount($accountInformation['data']['account_name'], auth()->user()->phone);
            }

            return back()->with('success', 'Account Details Added');
            //return redirect('wallet/withdraw')->with('success', 'Withdrawal Successfully queued');
        } else {
            return back()->with('error', 'Your bank account is not valid');
        }
    }

    public function transferFund($amount, $recipient)
    {
        return transferFund($amount, $recipient, 'Freebyz Withdrawal');
    }

    public function continueCountry(Request $request){

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
}
