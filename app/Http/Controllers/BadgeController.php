<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        //return auth()->user()->id;
        //$currentDate =  //last month
        $date = Carbon::now()->subMonth()->format('M, Y');
        // $count = Referral::where('referee_id', auth()->user()->id)
        // ->whereMonth('updated_at', Carbon::now()->subMonth()->month)->count();
        return view('user.badge.index', ['date' => $date]); 
    }

    public function redeemBadge(){
        
    }
}
