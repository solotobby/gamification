<?php

namespace App\Http\Controllers;

use App\Helpers\Analytics;
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
        
        Analytics::dailyVisit();
        //$users = User::where('role', 'regular')->count();
        $transactions = PaymentTransaction::inRandomOrder()->limit(10)->where('type', 'cash_withdrawal')->select(['user_id','amount','description'])->get();
        return view('landingPage', ['transactions' => $transactions]);// ['prizesWon' => $prizesWon, 'gameplayed' => $gameplayed, 'user' => $user]);
    }

    public function contact()
    {
        Analytics::dailyVisit();
        return view('contact');
    }

    public function goal()
    {
        Analytics::dailyVisit();
        return view('goal');
    }

    public function winnerlist()
    {
        $winners = UserScore::where('reward_type', '!=', '')->orderBy('created_at', 'desc')->get();
        return view('winner', ['winners' => $winners]);
    }

    public function gamelist()
    {
        Analytics::dailyVisit();
        $games = Games::orderBy('created_at', 'desc')->get();
        return view('gamelist', ['games' => $games]);
    }

    public function terms()
    {
        Analytics::dailyVisit();
        return view('terms');
    }

    public function privacy()
    {
        Analytics::dailyVisit();
        return view('privacy');
    }
    
    public function make_money()
    {
        Analytics::dailyVisit();
        return view('make_money');
    }

    public function trackRecord()
    {
        Analytics::dailyVisit();
        return view('track_record');
    }

    public function faq()
    {
        Analytics::dailyVisit();
        return view('faq');
    }
    public function about()
    {
        Analytics::dailyVisit();
        return view('about');
    }

    public function download()
    {
        Analytics::dailyVisit();
        return view('download');
    }

    public function download_url(Request $request)
    {
        return $request;
    }
}
