<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewAll()
    {
        $user = auth()->user();
        $list = $user->referees;
        return view('user.referral.all', ['lists' => $list]);
    }
}
