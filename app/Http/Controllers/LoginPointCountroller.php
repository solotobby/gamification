<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use App\Models\LoginPoints;
use App\Models\LoginPointsRedeemed;
use App\Models\Wallet;
use Illuminate\Http\Request;

class LoginPointCountroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $loginPoints = LoginPoints::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(100);
        $point = LoginPoints::where('user_id', auth()->user()->id)->get();
        return view('user.points.index', ['loginpoints' => $loginPoints, 'point' => $point]);
    }

    public function redeemPoint(){
        $loginPoints = LoginPoints::where('user_id', auth()->user()->id)->where('is_redeemed', false)->get();
        
        if($loginPoints->sum('point')  <= 1000){ //
            return back()->with('error', 'Points cannot be redeemed until you have 1,000');
        }

        $total_point = $loginPoints->sum('point');
        $countable = $total_point / 50;
        $amount = $countable * 2.5; //2.5naira per 50 points
            
        //credit wallet
        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        $wallet->balance += $amount;
        $wallet->save();

        LoginPoints::where('user_id', auth()->user()->id)->where('is_redeemed', '0')
         ->update(['is_redeemed'=>'1']);

        //record
        LoginPointsRedeemed::create(['user_id'=>auth()->user()->id, 'point' => $total_point, 'amount' => $amount]);

        PaystackHelpers::paymentTrasanction(auth()->user()->id, '1', time(), $amount, 'successful', 'login_point_redemption', auth()->user()->name.' redeemed login points', 'Credit', 'regular');
        return back()->with('success', 'Points redeemed successfully');
    }
}