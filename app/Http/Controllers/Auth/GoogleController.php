<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\PaystackHelpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
     public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


     public function handleGoogleCallback()
    {
        try {
    
            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->first();
     
            if($finduser){
                Auth::login($finduser);
                $get = User::where('id', auth()->user()->id)->first();
                $wallet = Wallet::where('user_id', auth()->user()->id)->first();
                if(!$wallet){
                    //create the wallet
                    Wallet::create(['user_id'=> $get->id, 'balance' => '0.00']);
                }
                if($get->referral_code == '')
                {
                    $get->referral_code = Str::random(7);
                    $get->save();
                }
                if($get->phone == '')
                {
                    return view('phone');
                }
                
                PaystackHelpers::loginPoints($finduser);
                return redirect('/home');
     
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456dummy'), 
                    'avatar' => $user->avatar
                ]);
                $newUser->referral_code = Str::random(7);
                $newUser->save();
                Wallet::create(['user_id'=> $newUser->id, 'balance' => '0.00']);
    
                Auth::login($newUser);
                $get = User::where('id', auth()->user()->id)->first();
                if($get->phone == '')
                {
                    return view('phone');
                }
                PaystackHelpers::loginPoints($newUser);
                return redirect('/home');
            }
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
