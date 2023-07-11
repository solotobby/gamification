<?php

namespace App\Http\Controllers;

use App\Helpers\Admin;
use App\Helpers\Analytics;
use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Models\AccountInformation;
use App\Models\Answer;
use App\Models\BankInformation;
use App\Models\Games;
use App\Models\Question;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\LoginPoints;
use App\Models\PaymentTransaction;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\Statistics;
use App\Models\UserLocation;
use App\Models\Wallet;
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
        
        if($user->hasRole('admin')){
            // return 'admin';
            return redirect()->route('admin.home');
        }elseif($user->hasRole('staff')){
            // return 'staff';
            return redirect()->route('staff.home');
        }else{
            // return 'user';
            return redirect()->route('user.home');
        } 
    }

    public function userHome()
    {
        //Sendmonny::accessToken();
        Analytics::dailyVisit();
        if(auth()->user()->phone == '' || auth()->user()->country == ''){
            return view('phone');
        }

        if(auth()->user()->age_range == '' || auth()->user()->gender == ''){ //compell people to take survey
            return redirect('survey');
        }
        $balance = '';
        if(walletHandler() == 'sendmonny' && auth()->user()->is_wallet_transfere == true){
            $balance = Sendmonny::getUserBalance(GetSendmonnyUserId(), accessToken());
        }
    
        $activity_log = SystemActivities::showActivityLog();
        $available_jobs = SystemActivities::availableJobs();
        $completed = CampaignWorker::where('user_id', auth()->user()->id)->where('status', 'Approved')->count();

        return view('user.home', ['available_jobs' => $available_jobs, 'completed' => $completed, 'activity_log' => $activity_log, 'user'=>auth()->user(), 'balance' => $balance]);
    }

    public function howTo(){
        return view('user.documentation.how_to_approve');
    }

    public function adminHome(Request $request)
    {

        // return PaystackHelpers::getPosts();
        // $campaigns = Campaign::where('status', 'Live')->get();
        // $campaignWorker = CampaignWorker::where('status', 'Approved')->sum('amount');
        // $user = User::where('role', 'regular')->get();
        // $loginPoints = LoginPoints::where('is_redeemed', false)->get();
        $wallet = Wallet::all()->sum('balance');
        //$ref_rev = Referral::where('is_paid', true)->count();
        //$transactions = PaymentTransaction::where('user_type', 'admin')->get();
        //$Wal = Wallet::where('user_id', auth()->user()->id)->first();

    
        // $campaignMetric = Analytics::campaignMetrics();
        //users registered
        $dailyActivity = Analytics::dailyActivities();

        //monthly visits
        $start_date = \Carbon\Carbon::today()->subDays(30);
        $end_date = \Carbon\Carbon::now()->format('Y-m-d');
        $MonthlyVisit = Analytics::monthlyVisits($start_date, $end_date);

        ///daily visits
        $dailyVisits = Analytics::dailyStats();

        //registration channel
        $registrationChannel = Analytics::registrationChannel();

        //revenue channel
        $revenueChannel = Analytics::revenueChannel();

        //country distribution
        $countryDistribution = Analytics::countryDistribution();

        //age distribution
        $ageDistribution = Analytics::ageDistribution();
        
        return view('admin.index', ['wallet' => $wallet]) // ['users' => $user, 'campaigns' => $campaigns, 'workers' => $campaignWorker, 'loginPoints' => $loginPoints]) // 'wallet' => $wallet, 'ref_rev' => $ref_rev, 'tx' => $transactions, 'wal'=>$Wal])
        ->with('visitor',json_encode($dailyActivity))
        ->with('daily',json_encode($dailyVisits))
        ->with('monthly', json_encode($MonthlyVisit))
        ->with('channel', json_encode($registrationChannel))
        ->with('revenue', json_encode($revenueChannel))
        ->with('country', json_encode($countryDistribution))
        ->with('age', json_encode($ageDistribution));
    }

    public function adminApi(Request $request){
        $data = [];
            // $data['campaigns'] = Campaign::where('status', 'Live')->whereBetween('created_at',[$request->start_date, $request->end_date])->get();
            // $data['campaignWorker'] = CampaignWorker::where('status', 'Approved')->whereBetween('created_at',[$request->start_date, $request->end_date])->sum('amount');
            // $data['user'] = User::where('role', 'regular')->whereBetween('created_at',[$request->start_date, $request->end_date])->get();
            // $data['loginPoints'] = LoginPoints::where('is_redeemed', false)->whereBetween('created_at',[$request->start_date, $request->end_date])->get();

            $camp = Campaign::where('status', 'Live')->whereBetween('created_at',[$request->start_date, $request->end_date])->get();
            $data['campaigns'] = $camp->count();
            $data['campaignValue'] = $camp->sum('total_amount');
            $data['campaignWorker'] = CampaignWorker::where('status', 'Approved')->whereBetween('created_at',[$request->start_date, $request->end_date])->sum('amount');
            $data['registeredUser'] = User::where('role', 'regular')->whereBetween('created_at',[$request->start_date, $request->end_date])->count();
            $data['verifiedUser'] = User::where('role', 'regular')->where('is_verified', true)->whereBetween('created_at',[$request->start_date, $request->end_date])->count();
            $data['loginPoints'] = LoginPoints::where('is_redeemed', false)->whereBetween('created_at',[$request->start_date, $request->end_date])->sum('point');
            $data['loginPointsValue'] = $data['loginPoints']/5;
        
        return $data;
    }

    public function adminApiDefault(Request $request){
        $period = $request->period;
        
         $data = [];
        
        $date = \Carbon\Carbon::today()->subDays($period);
        $start_date = \Carbon\Carbon::today()->subDays($period);
        $end_date = \Carbon\Carbon::now()->format('Y-m-d');

        $camp = Campaign::where('status', 'Live')->where('created_at','>=',$date)->get();
        $data['campaigns'] = $camp->count();
        $data['campaignValue'] = $camp->sum('total_amount');
        $data['campaignWorker'] = CampaignWorker::where('status', 'Approved')->where('created_at','>=',$date)->sum('amount');
        $data['registeredUser'] = User::where('role', 'regular')->where('created_at','>=',$date)->count();
        $data['verifiedUser'] = User::where('role', 'regular')->where('is_verified', true)->where('created_at','>=',$date)->count();
        $data['loginPoints'] = LoginPoints::where('is_redeemed', false)->where('created_at','>=',$date)->sum('point');
        $data['loginPointsValue'] = $data['loginPoints']/5;
       
        $data['monthlyVisits'] = Analytics::monthlyVisits($start_date, $end_date);
        return $data;
    }
        

    public function staffHome(){
        $campaigns = Campaign::where('status', 'Live')->get();
        $campaignWorker = CampaignWorker::all();
        $user = User::where('role', 'regular')->get();
        $wallet = Wallet::all();
        $ref_rev = Referral::where('is_paid', true)->count();
        $transactions = PaymentTransaction::where('user_type', 'admin')->get();
        $Wal = Wallet::where('user_id', auth()->user()->id)->first();

        //users registered
        $dailyActivity = Analytics::dailyActivities();

        //monthly visis
        $MonthlyVisit = Analytics::monthlyVisits();

        ///daily visits
        $dailyVisits = Analytics::dailyStats();

        //registration channel
        $registrationChannel = Analytics::registrationChannel();

        return view('staff.home', ['users' => $user, 'campaigns' => $campaigns, 'workers' => $campaignWorker, 'wallet' => $wallet, 'ref_rev' => $ref_rev, 'tx' => $transactions, 'wal'=>$Wal])
                ->with('visitor',json_encode($dailyActivity))
                ->with('daily',json_encode($dailyVisits))
                ->with('monthly', json_encode($MonthlyVisit))
                ->with('channel', json_encode($registrationChannel));

    }

    public function savePhoneInformation(Request $request)
    {
        // $this->validate($request, [
        //     // 'phone' => 'numeric|required|digits:11|unique:users'
        // ]);
        
        $user = User::where('id', auth()->user()->id)->first();
        $user->phone = $request->phone_number['full'];
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

        if(count($userScore) > 0)
        {
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

        if($userAnswer == $correctAnswer)
        {
            $isCorrect = 1;
        }else{
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

        if(count($userScore) > 0)
        {
            return view('error');
        }

        $questions = Question::inRandomOrder()->limit(1)->first();

        $answered = Answer::where('user_id', auth()->user()->id)->where('game_id', $games->id)->count();
        $index = $answered + 1;
        if($answered == $games->number_of_questions)
        {
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

        if(count($userScore) > 0)
        {
            return view('completed', ['score' => $percentage]);
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
        if($reward_type->reward_type == 'CASH' && $reward_type->is_redeem == '0')
        {
            $bankInformation = BankInformation::where('user_id', auth()->user()->id)->first();
            if($bankInformation == null){
                $bankList = PaystackHelpers::bankList();
                return view('bank_information', ['bankList' => $bankList, 'id' => $id]);
            }

                $parameters = Reward::where('name', 'CASH')->first();
                $amount = $parameters->amount * 100;
                //transfer the fund
                $transfer = $this->transferFund($amount, $bankInformation->recipient_code);


               if($transfer['status'] == 'false'){
                if($transfer['data']['status'] == 'success' || $transfer['data']['status'] == 'pending')
                {
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
                }else{
                    return redirect('score/list')->with('error', 'There was an error while sending cash, please try again later');
                }

            }else{
                return redirect('score/list')->with('error', 'There was an error while sending cash, please try again later!!!');
            }
        }elseif($reward_type->reward_type == 'AIRTIME' && $reward_type->is_redeem == '0')
        {
            
            $parameters = Reward::where('name', 'AIRTIME')->first();
            //$phone = '+234'.substr(auth()->user()->phone, 1);
            $amount = $parameters->amount;
            $phone = auth()->user()->phone;
            return $airtime = $this->sendAirtime($phone, $amount);//['data'];              
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

        }else{
            return 'nothing dey happen';
        }

    }

    public function saveBankInformation(Request $request)
    {
        $this->validate($request, [
            'account_number' => 'numeric|required'
        ]);
        $accountInformation = PaystackHelpers::resolveBankName($request->account_number, $request->bank_code);

        if($accountInformation['status'] == 'true')
        {
             $recipientCode = PaystackHelpers::recipientCode($accountInformation['data']['account_name'], $request->account_number, $request->bank_code);
                BankInformation::create([
                    'user_id' => auth()->user()->id,
                    'name' => $accountInformation['data']['account_name'],
                    'bank_name' => $recipientCode['data']['details']['bank_name'],
                    'account_number' => $request->account_number,
                    'bank_code' => $request->bank_code,
                    'recipient_code' => $recipientCode['data']['recipient_code'],
                    'currency' => 'NGN'
                ]);
                return redirect('wallet/withdraw')->with('success', 'Withdrawal Successfully queued');
        }else{
            return back()->with('error', 'Your bank account is not valid');
        }
       
    }

    public function transferFund($amount, $recipient)
    {
           return PaystackHelpers::transferFund($amount, $recipient);
    }

    public function sendAirtime($phone, $amount)
    {
        // $bearerToken = PaystackHelpers::reloadlyAuth0Token();
        // $bearerToken['access_token'];

        // $operator = PaystackHelpers::getRealoadlyMobileOperator($bearerToken['access_token'], $phone);
        // $operatorId = $operator['operatorId'];

        // return PaystackHelpers::initiateReloadlyAirtime($bearerToken['access_token'], $phone, $operatorId, $amount);

        //return PaystackHelpers::reloadlyAuth0Token();

        // $username = "solotob";
        //  $apiKey = env('AFRICA_TALKING_LIVE');

        //  $AT = new AfricasTalking($username, $apiKey);

        //  $airtime = $AT->airtime();

        //  // Use the service
        //  $recipients = [[
        //      "phoneNumber"  => $phone,
        //      "currencyCode" => "NGN",
        //      "amount"       => $amount
        //  ]];

        //  try {
        //      // That's it, hit send and we'll take care of the rest
        //      $results = $airtime->send([
        //          "recipients" => $recipients
        //      ]);


        //  } catch(\Exception $e) {
        //      echo "Error: ".$e->getMessage();
        //  }
        //  return $results;//response()->json($results);  //json_decode($results->getBody()->getContents(), true);
    }
}
