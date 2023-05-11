<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function registerUser(Request $request){
       
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            // 'phone_number' => ['required', 'numeric'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        if($request->country == '' || $request->phone_number['full'] == ''){
            return back()->with('error', 'Please Enter Phone Number');
        }
       
        $ref_id = $request->ref_id;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country,
            'phone' => $request->phone_number['full'],
            'source' => $request->source,
            'password' => Hash::make($request->password),
        ]);
        $user->referral_code = Str::random(7);
        $user->save();
        Wallet::create(['user_id'=> $user->id, 'balance' => '0.00']);
        if($ref_id != 'null'){
            \DB::table('referral')->insert(['user_id' => $user->id, 'referee_id' => $ref_id]);
        }

        if($user){
            Auth::login($user);
            return redirect('/home');
        }

    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            // 'phone_number' => ['required', 'numeric'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        
        $ref_id = $data['ref_id'];
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'country' => $data['country'],
            'phone' => $data['phone_number']['full'],
            'source' => $data['source'],
            'password' => Hash::make($data['password']),
        ]);
        $user->referral_code = Str::random(7);
        $user->save();
        Wallet::create(['user_id'=> $user->id, 'balance' => '0.00']);
        if($ref_id != 'null'){
            \DB::table('referral')->insert(['user_id' => $user->id, 'referee_id' => $ref_id]);
        }
       
        return $user;
    }

    public function referral_register($referral_code)
    {
        $name = User::where('referral_code', $referral_code)->first();
        if(!$name){
            return view('auth.error', ['error' => 'Invalid referral code']);
        }
        return view('auth.ref_register', ['name' => $name]);
    }

    
}
