<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Games;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function landingPage()
    {
        $prizes = Transaction::where('reward_type', 'CASH')->sum('amount') / 100;
        $otherPrizes = Transaction::where('reward_type', 'DATA')->where('reward_type', 'AIRTIME')->sum('amount');
        $prizesWon = $prizes + $otherPrizes;
        $gameplayed = Answer::select('id')->count();
        $user = User::where('role', 'regular')->count();
        return view('landingPage', ['prizesWon' => $prizesWon, 'gameplayed' => $gameplayed, 'user' => $user]);
    }
    public function contact()
    {
        return view('contact');
    }

    public function goal()
    {
        return view('goal');
    }

    public function winnerlist()
    {
        $winners = UserScore::where('reward_type', '!=', '')->orderBy('created_at', 'desc')->get();
        return view('winner', ['winners' => $winners]);
    }

    public function gamelist()
    {
        $games = Games::orderBy('created_at', 'desc')->get();
        return view('gamelist', ['games' => $games]);
    }
}
