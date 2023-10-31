<?php

namespace App\Http\Controllers;

use App\Models\BankInformation;
use App\Models\OTP;
use App\Models\Profile;
use App\Models\User;
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

        $phone_number = '234'.substr($request->phone_number, 1);
         
        $response =  sendOTP($phone_number)['data'];

        OTP::create(['user_id' => auth()->user()->id, 'pinId' => $response['pin_id'], 'otp' => $response['otp'], 'phone_number' => $response['phone_number'], 'is_verified' => false]);

        return back()->with('success', 'OTP sent to the phone number supplied!');
        //$this->sendOTP($request->phone_number);
    }

    public function verifyPhoneOTP(Request $request){
        $this->validate($request, [
            'otp' => 'numeric|required|digits:6'
        ]);

        $chekOtp = OTP::where('user_id', auth()->user()->id)->where('is_verified', false)->latest()->first();

        if($request->otp == $chekOtp->otp){

            $chekOtp->is_verified = true;
            $chekOtp->save();

            $user = User::where('id', auth()->user()->id)->first();
            $user->phone = $chekOtp->phone_number;
            $user->save();

            $profile = Profile::where('user_id', $user->id)->first();
            $profile->phone_verified = true;
            $profile->save();

            $bankInformation = BankInformation::where('user_id', auth()->user()->id)->first();
            if($bankInformation){
                generateVirtualAccount($bankInformation->name, $chekOtp->phone_number);
            }

            return back()->with('success', 'Phone Number Verified!');

        }else{
            return back()->with('error', 'An error ocoured while verifying your number');
        }
        return $request;
    }

   
}
