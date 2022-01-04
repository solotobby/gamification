<?php

namespace App\Http\Controllers;

use App\Models\Games;
use App\Models\Question;
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
        return view('instruction');
    }

    public function takeQuiz()
    {
        $games = Games::where('status', '1')->first();

        $questions = Question::all()->random($games->number_of_questions);
        //return response()->json($questions);

        return view('play');
    }
}
