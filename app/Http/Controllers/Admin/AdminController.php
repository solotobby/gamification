<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Games;
use App\Models\Question;
use App\Models\Reward;
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
            'number_of_questions'=>$request->number_of_questions
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
        $questions = Question::orderBy('created_at', 'desc')->paginate('20');
        return view('admin.question_list', ['questions' => $questions]);
    }

}
