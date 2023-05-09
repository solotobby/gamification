<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use Exception;
use App\Models\User;
use Facebook\Facebook;
use Illuminate\Support\Facades\Auth;

class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        // $fb = new Facebook([
        //     'app_id' => env('FACEBOOK_CLIENT_ID'),
        //     'app_secret' => env('FACEBOOK_CLIENT_SECRET'),
        //     'default_graph_version' => 'v11.0',
        // ]);

        // return $fb;
        // $url = Socialite::driver('facebook')->redirect();
        // dd($url);
        //         'name', 'first_name', 'last_name', 'email', 'gender', 'birthday', 'avatar'
        //     ])->scopes([
        //         'email', 'user_birthday'
        //     ])->redirect();

        // dd($url);
        
        // fields([
        //     'name', 'first_name', 'last_name', 'email', 'gender', 'birthday', 'avatar'
        // ])->scopes([
        //     'email', 'user_birthday'
        // ])->redirect();

    }

    public function handleFacebookCallback()
    {
        try {
        
            return $user = Socialite::driver('facebook')->user();
         
            $finduser = User::where('facebook_id', $user->id)->first();
         
            // if($finduser){
         
            //     Auth::login($finduser);
       
            //     return redirect()->intended('dashboard');
         
            // }else{
            //     $newUser = User::updateOrCreate(['email' => $user->email],[
            //             'name' => $user->name,
            //             'facebook_id'=> $user->id,
            //             'password' => encrypt('123456dummy')
            //         ]);
        
            //     Auth::login($newUser);
        
            //     return redirect()->intended('dashboard');
            // }
       
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
