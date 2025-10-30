<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Http\Controllers\Controller;
use App\Jobs\SendMassEmail;
use App\Mail\GeneralMail;
use App\Mail\Welcome;
use App\Mail\WelcomeMail;
use App\Models\AccountInformation;
use App\Models\AuthCheck;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

    public function registerUser(Request $request)
    {
        $ref_id = $request->referral_code;
        if ($ref_id != '') {
            $name = User::where('referral_code', $ref_id)->first();
            if (!$name) {

                return view('auth.error', ['error' => 'Invalid referral code']);
            }
        }

        $curLocation = '';
        if (env('APP_ENV')  == 'local_test') {
            $curLocation = 'United Kingdom';
        } else {
            $curLocation = currentLocation();
        }

        if ($curLocation == 'Nigeria') {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'country' => ['required', 'string', 'max:255'],
                'source' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', 'digits:11', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        } else {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'country' => ['required', 'string', 'max:255'],
                'source' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }


        $user = $this->createUser($request);
        if ($user == 'Error') {
            return view('auth.error', ['error' => 'Invalid Referral code']);
        } else {
            // return $user;
            Auth::login($user);
            setProfile($user); //set profile page
            activityLog($user, 'account_creation', $user->name . ' Registered ', 'regular');

            $job_id = $request->query('redirect');
            if ($job_id && auth()->check()) {
                return redirect('campaign/' . $job_id);
                // return redirect()->route('campaign.view', ['job_id' => $job_id]);
            }
            return redirect('/home');
        }
    }

    public function createUser($request)
    {

        $ref_id = $request->referral_code;
        $name = $request->first_name . ' ' . $request->last_name;
        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'country' => $request->country,
            'phone' => $request->phone,
            'source' => $request->source,
            'password' => Hash::make($request->password),
        ]);

        $user->referral_code = Str::random(7);
        // $user->base_currency = $location == "Nigeria" ? 'Naira' : 'Dollar';
        $user->save();
        Wallet::create(['user_id' => $user->id, 'balance' => '0.00']);

        if ($ref_id != '') {
            $name = User::where('referral_code', $ref_id)->first();
            if (!$name) {
                return 'Error';
                //return view('auth.error', ['error' => 'Invalid referral code']);
            }

            DB::table('referral')->insert(['user_id' => $user->id, 'referee_id' =>  $name->id]);
        }

        $location = getLocation(); //get user location dynamically
        $wall = Wallet::where('user_id', $user->id)->first();
        $wall->base_currency = $location == "Nigeria" ? 'NGN' : 'USD';
        $wall->base_currency_set = $location == "Nigeria" ? true : false;
        $wall->save();


        // if(env('APP_ENV') == 'production'){
        //     userLocation('Registration');
        // }

        // if ($location == 'Nigeria') {
        //     $phone = '234' . substr($request->phone, 1);
        //     generateVirtualAccountOnboarding($user, $phone);
        // }

        // $subject = 'Welcome to Freebyz';
        // Mail::to($request->email)->send(new Welcome($user,  $subject, ''));

        try {
            Mail::to($user->email)->send(new WelcomeMail($user->name));
        } catch (Exception $e) {
            // Optionally log the error
            Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        // if ($location == 'Nigeria') {
        //     $phone = '234' . substr($request->phone, 1);
        // generateVirtualAccountOnboarding($user, $phone);
        // }

        // $content = 'Your withdrawal request has been granted and your account credited successfully. Thank you for choosing Freebyz.com';
        // if(env('APP_ENV')  == 'local_test'){
        //    // $curLocation = 'United Kingdom';
        // }else{

        // }


        return $user;
    }


    // public function loginUser(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|max:255',
    //         'password' => 'required',
    //     ]);
    //     // $location = PaystackHelpers::getLocation(); //get user location dynamically
    //     $user = User::where('email', $request->email)->first();

    //     if ($user) {

    //         $role = $user->role;

    //         switch ($role) {
    //             case "admin" || "super_admin":
    //                 // return "Admin";

    //                 if ($user) {

    //                     if ($user->is_blacklisted == true) {
    //                         return view('blocked');
    //                     }

    //                     if ($user->id == '1') {
    //                         $token = Str::random(36);
    //                         AuthCheck::create(['email' => $user->email, 'token' => $token]);
    //                         //\DB::table('password_resets')->insert(['email' => $user->email, 'token' => $token]);
    //                         // $token =
    //                         return redirect('login/otp/' . $token);
    //                     } else {
    //                         return redirect('/');
    //                     }
    //                 } else {
    //                     return back()->with('error', 'Email or Password is incorrect');
    //                 }


    //                 break;
    //             case "regular":

    //                 // return "Regular ";
    //                 if ($user) {

    //                     if ($user->is_blacklisted == true) {
    //                         return view('blocked');
    //                     }

    //                     if ($user->referral_code == null) {
    //                         $user->referral_code = Str::random(7);
    //                         $user->save();
    //                     }
    //                     if (Hash::check($request->password, $user->password)) {

    //                         Auth::login($user); //log user in

    //                         if ($user->role == 'staff') {
    //                             null;
    //                         } else {

    //                             // setWalletBaseCurrency();
    //                         }

    //                         //set base currency if not set
    //                         if (env('APP_ENV') == 'production') {
    //                             setProfile($user); //set profile page
    //                             userLocation('Login');
    //                             // setWalletBaseCurrency();

    //                         }
    //                         // setProfile($user);

    //                         activityLog($user, 'login', $user->name . ' Logged In', 'regular');
    //                         return redirect('home'); //redirect to home

    //                     } else {
    //                         return back()->with('error', 'Email or Password is incorrect');
    //                     }
    //                 } else {
    //                     return back()->with('error', 'Email or Password is incorrect');
    //                 }

    //                 break;
    //             case "staff":
    //                 return "Staff Account";
    //                 break;
    //             default:
    //                 return "Not applicable";
    //         }
    //     } else {
    //         return back()->with('error', 'Email or Password is incorrect');
    //     }


    //     // if ($user) {
    //     //     if ($user->is_blacklisted == true) {
    //     //         return view('blocked');
    //     //     }

    //     //     if ($user->referral_code == null) {
    //     //         $user->referral_code = Str::random(7);
    //     //         $user->save();
    //     //     }
    //     //     if (Hash::check($request->password, $user->password)) {
    //     //         // if($user->role != 'admin'){
    //     //         //     $location = PaystackHelpers::getLocation(); //get user specific location
    //     //         //     if($location == "United States"){ //check if the person is in Nigeria
    //     //         //         if($user->is_wallet_transfered == false){
    //     //         //             //activate sendmonny wallet and fund wallet
    //     //         //             if(walletHandler() == 'sendmonny'){
    //     //         //                 if($user->is_wallet_transfered == false){
    //     //         //                     activateSendmonnyWallet($user, $request->password); //hand sendmonny
    //     //         //                 }
    //     //         //             }
    //     //         //         }
    //     //         //     }
    //     //         // }

    //     //         Auth::login($user); //log user in

    //     //         if ($user->role == 'staff') {
    //     //             null;
    //     //         } else {

    //     //             setWalletBaseCurrency();
    //     //         }




    //     //         //set base currency if not set
    //     //         if(env('APP_ENV') == 'production'){
    //     //             setProfile($user); //set profile page
    //     //             PaystackHelpers::userLocation('Login');
    //     //         }

    //     //         // SystemActivities::loginPoints($user);

    //     //         SystemActivities::activityLog($user, 'login', $user->name . ' Logged In', 'regular');
    //     //         return redirect('home'); //redirect to home

    //     //     } else {
    //     //         return back()->with('error', 'Email or Password is incorrect');
    //     //     }
    //     // } else {
    //     //     return back()->with('error', 'Email or Password is incorrect');
    //     // }
    // }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('error', 'Email or Password is incorrect');
        }

        // Handle blacklisted users
        if ($user->is_blacklisted) {
            return view('blocked');
        }

        switch ($user->role) {
            case 'admin':
            case 'super_admin':
                if (! Hash::check($request->password, $user->password)) {
                    return back()->with('error', 'Email or Password is incorrect');
                }

                $token = Str::random(36);
                AuthCheck::create([
                    'email' => $user->email,
                    'token' => $token,
                ]);

                return redirect('login/otp/' . $token);

            case 'regular':
                // Regular user logic
                if (! Hash::check($request->password, $user->password)) {
                    return back()->with('error', 'Email or Password is incorrect');
                }

                if (is_null($user->referral_code)) {
                    $user->referral_code = Str::random(7);
                    $user->save();
                }

                Auth::login($user);

                if (env('APP_ENV') === 'production') {
                    setProfile($user);
                    userLocation('Login');
                }

                activityLog($user, 'login', $user->name . ' Logged In', 'regular');

                $job_id = $request->query('redirect');
                if ($job_id && auth()->check()) {
                    return redirect('campaign/' . $job_id);
                    // return redirect()->route('campaign.view', ['job_id' => $job_id]);
                }

                return redirect('home');

            case 'staff':
                return "Staff Account";

            default:
                return "Not applicable";
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
        Wallet::create(['user_id' => $user->id, 'balance' => '0.00']);
        if ($ref_id != 'null') {
            DB::table('referral')->insert(['user_id' => $user->id, 'referee_id' => $ref_id]);
        }

        return $user;
    }

    public function referral_register($referral_code)
    {
        $name = User::where('referral_code', $referral_code)->first();
        if (!$name) {
            return view('auth.error', ['error' => 'Invalid referral code']);
        }
        return view('auth.ref_register', ['name' => $name]);
    }
}
