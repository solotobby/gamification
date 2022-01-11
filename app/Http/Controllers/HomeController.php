<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use App\Models\Answer;
use App\Models\BankInformation;
use App\Models\Games;
use App\Models\Question;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Http\Request;

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
            $games = Games::all();
            return view('admin.index', ['games' => $games]);
        }
        return view('regular.index');
    }

    public function instruction()
    {
        $games = Games::where('status', '1')->first();

        return view('instruction', ['games' => $games]);
    }

    public function takeQuiz()
    {


        $games = Games::where('status', '1')->first();

        $userScore = UserScore::where('user_id', auth()->user()->id)->where('game_id', $games->id)->get();

        if(count($userScore) > 0)
        {

            return 'you have played this game before';
            // return '<script type="text/javascript">alert("You have played this game before. Games can only be played once.");</script>';
            //return redirect()->back()->with('alert','You have played this game before. Games can only be played once.'); //return 'you have played this game before';
        }

        $questions = Question::inRandomOrder()->limit(1)->first();
        return view('play', ['question' => $questions, 'game' => $games]);
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
            return 'you have played this game before';
            // return back()->('<script type="text/javascript">alert("You have played this game before. Games can only be played once.");</script>');
        }

        $questions = Question::inRandomOrder()->limit(1)->first();

        $answered = Answer::where('user_id', auth()->user()->id)->where('game_id', $games->id)->count();
        $index = $answered + 1;
        if($answered == $games->number_of_questions)
        {
            return redirect('submit/answers');
        }

        return view('next', ['question' => $questions, 'game' => $games, 'index' => $index]);
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

        return view('completed', ['score' => $percentage]);
    }

    public function scores()
    {
        $scores = UserScore::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return view('scores', ['scores' => $scores]);
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
                $amount = 100 * 100;
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
                    return redirect('score/list')->with('status', 'Money successfully send to your account');
                }else{
                    return redirect('score/list')->with('error', 'There was an error while sending cash, please try again later');
                }

            }else{
                return redirect('score/list')->with('error', 'There was an error while sending cash, please try again later!!!');
            }
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
                $amount = 100*100;
                //transfer the fund
                $transfer = $this->transferFund($amount, $recipientCode['data']['recipient_code']);
                if($transfer['status'] == 'false'){
               
                    if($transfer['data']['status'] == 'success' || $transfer['data']['status'] == 'pending')
                    {
                        $userScore = UserScore::where('id', $request->user_score_id)->first();
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
                        return redirect('score/list')->with('status', 'Account Information saved successfully and money successfully send');
                    }
                }else{
                    return redirect('score/list')->with('error', 'There was an error while sending cash, please try again later!!!');
                }
        }else{
            return back()->with('error', 'Sorry We could not save your bank information');
        }
    }

    public function transferFund($amount, $recipient)
    {
           return $fundTransfer = PaystackHelpers::transferFund($amount, $recipient); 
    }
}
