<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaystackHelpers;
use App\Http\Controllers\Controller;
use App\Models\Games;
use App\Models\Question;
use App\Models\Reward;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createGame()
    {
        $user = auth()->user();

        if($user->hasRole('admin'))
        {
            //$game = Games::find($id);
            return view('admin.create_game');
        }
    }

    public function createQuestion()
    {
        $questions = Question::all();
        return view('admin.add_question', ['question' => $questions]);
    }

    publiC function storeQuestion(Request $request)
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

        if($game->status == '1'){
            $game->status = '0';
            $game->save();
        }else{
            $game->status = '1';
            $game->save();
        }

         return back()->with('status', 'Status Changed Successfully');

    }

    public function gameCreate()
    {
        $user = auth()->user();

        if($user->hasRole('admin'))
        {
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
            'time_allowed' => 0.25,//$request->time_allowed, 
            'number_of_questions'=>$request->number_of_questions,
            'status' => 1
        ]);
        // $game->save();

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
        
        if(empty($request->id))
        {
             return back()->with('error', 'Please Select A Score');
        }
        $reward = Reward::where('name', $request->name)->first()->amount;
        $formattedReward = number_format($reward,2);
        foreach($request->id as $id)
        {
            $score = UserScore::where('id', $id)->first();
            $score->reward_type = $request->name;
            $score->save();

            $phone = '234'.substr($score->user->phone, 1);
            $message = "Hello ".$score->user->name. " you have a ".$request->name." reward of ".$formattedReward." from Freebyz.com. Please login to cliam it. Thanks";
            PaystackHelpers::sendNotificaion($phone, $message);
        }

        return back()->with('status', 'Reward Assigned Successfully');

    }

    public function sendAirtime()
    {
        return PaystackHelpers::reloadlyAuth0Token();
    }

   
}
