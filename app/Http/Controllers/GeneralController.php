<?php

namespace App\Http\Controllers;

use App\Helpers\CapitalSage;
use App\Helpers\PaystackHelpers;
use App\Models\Answer;
use App\Models\Games;
use App\Models\PaymentTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function landingPage()
    {
        
        PaystackHelpers::dailyVisit();
        $transactions = PaymentTransaction::inRandomOrder()->limit(10)->where('type', 'cash_withdrawal')->select(['user_id','amount', 'description'])->get();
        return view('landingPage', ['transactions' => $transactions]);// ['prizesWon' => $prizesWon, 'gameplayed' => $gameplayed, 'user' => $user]);
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

    public function terms()
    {
        return view('terms');
    }

    public function privacy()
    {
        return view('privacy');
    }
    
    public function make_money()
    {
        return view('make_money');
    }

    public function trackRecord()
    {
        return view('track_record');
    }

    public function faq()
    {
        return view('faq');
    }

    public function download()
    {
        return view('download');
    }

    public function download_url(Request $request)
    {
        return $request;
    }
}
