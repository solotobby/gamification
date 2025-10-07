<?php

namespace App\Http\Controllers;

use App\Models\Usdverified;
use Illuminate\Http\Request;

class ReferralController extends Controller
{

    public function __construct()
    {
         // $this->middleware(['auth', 'email']);
        $this->middleware('auth');
    }

    public function viewAll()
    {
        $user = auth()->user();
        $list = $user->referees;
        $verified = $user->referees()->where('is_verified', true)->count();
        return view('user.referral.naira', ['lists' => $list, 'verified' => $verified]);
    }

    public function usdReferee(){

        $user = auth()->user();

    //     $ids = Usdverified::all()->pluck('user_id')->toArray();  //where('user_id', $user->id)->first();
    //    return $user->referees()->whereIn('referee_id', $ids)->get();
        // foreach($user->referees as $ref){
        //     $check = Usdverified::where('user_id', $user->id)->first();
        // }
        //Usdverified::where('user_id', $user->id)->first();

        $list = $user->usd_referees;
        //$verified = $user->referees()->where('is_verified', true)->count();
        return view('user.referral.usd', ['lists' => $list]);
    }
}
