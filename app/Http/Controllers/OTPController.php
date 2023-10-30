<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OTPController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sendPhoneOTP(Request $request){
        $this->validate($request, [
            'phone_number' => 'numeric|required|digits:11'
        ]);
        return $request;
    }
}
