<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Games;
use App\Models\Question;
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
            return 'yiu have played this game before'; //view('completed');
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
            return 'yiu have played this game before'; //view('completed');
        }

        $questions = Question::inRandomOrder()->limit(1)->first();

        $answered = Answer::where('user_id', auth()->user()->id)->where('game_id', $games->id)->count();
        $index = $answered + 1;
        if($answered >= $games->number_of_questions)
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
}
