<?php

use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Mail\UpgradeUser;
use App\Models\AccountInformation;
use App\Models\ActivityLog;
use App\Models\Banner;
use App\Models\BannerImpression;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\ConversionRate;
use App\Models\Currency;
use App\Models\Notification;
use App\Models\PaymentTransaction;
use App\Models\Preference;
use App\Models\Profile;
use App\Models\Referral;
use App\Models\Settings;
use App\Models\Statistics;
use App\Models\Usdverified;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use App\Models\Withrawal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;



if (!function_exists('isBlacklisted')) {
    function isBlacklisted($user)
    {
        $blackist = User::where('id', $user->id)->is_blacklisted;
        if ($blackist == true) {
            return true;
        } else {
            return false;
        }
    }
}



if (!function_exists('setWalletBaseCurrency')) {
    function setWalletBaseCurrency()
    {
        $location = getLocation();
        $wall = Wallet::where('user_id', auth()->user()->id)->first();
        if ($wall->base_currency_set != 1) {
            $wall->base_currency = $location == "Nigeria" ? 'NGN' : 'USD';
            $wall->save();
        }

        return $wall;
    }
}

// if(!function_exists('updateWalletBaseCurrency')){
//     function updateWalletBaseCurrency(){
//         $wall = Wallet::where('user_id', auth()->user()->id)->first();

//         if($wall->base_currency == null){
//             $location = getLocation();
//             $wall->base_currency = $location == "Nigeria" ? 'Naira' : 'Dollar';
//             $wall->save();
//         }

//        return $wall;
//     }
// }


if (!function_exists('setProfile')) {
    function setProfile($user)
    {
        if (config('services.env.env') == 'local') {
            // $ip = '48.188.144.248';
            $ip = '41.203.78.171';
        } else {
            $ip = request()->ip();
        }
        $location = Location::get($ip);

        $profile = Profile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile =  Profile::create(['user_id' => $user->id, 'country' => $location->countryName, 'country_code' => $location->countryCode]);
        } else {
            $profile->country = $location->countryName;
            $profile->country_code = $location->countryCode;
            $profile->save();
        }
        return $profile;
    }
}


if (!function_exists('conversionRate')) {
    function conversionRate()
    {
        return ConversionRate::where('status', true)->first()->rate; //Settings::where('status', true)->first()->name;
    }
}

if (!function_exists('convertDollar')) {
    function convertDollar($amount)
    {
        $rate = ConversionRate::where('from', 'Dollar')->first()->amount;

        return number_format($amount / $rate, 2);
    }
}

if (!function_exists('paypalPayment')) {
    function paypalPayment($amount, $url)
    {

        $res = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'))
            ->post(env('PAYPAL_URL') . 'checkout/orders', [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        // "items"=> [
                        //     [
                        //         "name"=> $name,
                        //         "description"=> $description,
                        //         "quantity"=> "1",
                        //         "unit_amount"=> [
                        //             "currency_code"=> "USD",
                        //             "value"=> $amount
                        //         ]
                        //     ]
                        // ],
                        "reference_id" => time(),
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $amount,
                            "breakdown" => [
                                "item_total" => [
                                    "currency_code" => "USD",
                                    "value" => $amount
                                ]
                            ]
                        ]
                    ]
                ],
                "application_context" => [
                    "return_url" => url($url),
                    "cancel_url" => url('/home')
                ]
            ]);
        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('capturePaypalPayment')) {
    function capturePaypalPayment($id)
    {

        $url = env('PAYPAL_URL') . 'checkout/orders/' . $id . '/capture';

        // Request payload
        $data = [];

        // Basic Authorization credentials
        $client_id = env('PAYPAL_CLIENT_ID');
        $client_secret = env('PAYPAL_CLIENT_SECRET');

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret),
        ]);

        // Execute the request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            // Handle the error
        } else {
            return json_decode($response, true); //return response()->json([$response], 201);
        }
        // Close cURL resource
        curl_close($ch);
    }
}

if (!function_exists('systemNotification')) {
    function systemNotification($user, $category, $title, $message)
    {

        $notification = Notification::create([
            'user_id' => $user->id,
            'category' => $category,
            'title' => $title,
            'message' => $message
        ]);

        return $notification;
    }
}

if (!function_exists('checkWalletBalance')) {
    function checkWalletBalance($user, $type, $amount)
    {

        if ($type == 'NGN') {
            $wallet =  Wallet::where('user_id', $user->id)->first();
            if ((int) $wallet->balance >= $amount) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == 'USD') {

            $wallet =  Wallet::where('user_id', $user->id)->first();

            if ((int) $wallet->usd_balance >= $amount) {
                return true;
            } else {
                return false;
            }
        } else {
            $wallet =  Wallet::where('user_id', $user->id)->first();

            if ((int) $wallet->base_currency_balance >= $amount) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('creditWallet')) {
    function creditWallet($user, $type, $amount)
    {

        if ($type == 'NGN') {


            $wallet = Wallet::where('user_id', $user->id)->first();
            $wallet->balance += (int) $amount;
            // $wallet->balance += (int) $amount;
            $wallet->save();
            return $wallet;
        } elseif ($type == 'USD') {

            $wallet = Wallet::where('user_id', $user->id)->first();
            $wallet->usd_balance += $amount;
            $wallet->save();
            return $wallet;
        } else {


            $wallet = Wallet::where('user_id', $user->id)->first();
            $wallet->base_currency_balance += $amount;
            $wallet->save();
            return $wallet;
        }
    }
}

if (!function_exists('debitWallet')) {
    function debitWallet($user, $type, $amount)
    {

        if ($type == 'NGN') {
            $wallet =  Wallet::where('user_id', $user->id)->first();
            $wallet->balance -= $amount;
            $wallet->save();
            return $wallet;
        } elseif ($type == 'USD') {
            $wallet =  Wallet::where('user_id', $user->id)->first();
            $wallet->usd_balance -= $amount;
            $wallet->save();
            return $wallet;
        } else {
            $wallet =  Wallet::where('user_id', $user->id)->first();
            $wallet->base_currency_balance -= $amount;
            $wallet->save();
            return $wallet;
        }
    }
}

if (!function_exists('dollar_naira')) {
    function dollar_naira()
    {
        return ConversionRate::where('from', 'Dollar')->first()->amount;
    }
}


if (!function_exists('naira_dollar')) {
    function naira_dollar()
    {
        return ConversionRate::where('from', 'NGN')->first()->amount;
    }
}

if (!function_exists('nairaConversion')) {
    function nairaConversion($currency)
    {
        return ConversionRate::where('from', 'NGN')->where('to', $currency)->first()->amount;
    }
}


if (!function_exists('short_name')) {
    function short_name($name)
    {
        $name = explode(" ", $name);
        return $name['0'];
    }
}

if (!function_exists('initializeKorayPay')) {
    function initializeKorayPay($payloadNGN)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('services.env.kora_sec')
        ])->post('https://api.korapay.com/merchant/api/v1/charges/initialize', $payloadNGN)->throw();

        return json_decode($res->getBody()->getContents(), true)['data']['checkout_url'];
    }
}

if (!function_exists('verifyKorayPay')) {
    function verifyKorayPay($referee)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('services.env.kora_sec')
        ])->get('https://api.korapay.com/merchant/api/v1/charges/' . $referee)->throw();

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('flutterwaveVirtualAccount')) {
    function flutterwaveVirtualAccount($payload)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/virtual-account-numbers', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('flutterwavePaymentInitiation')) {
    function flutterwavePaymentInitiation($payload)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/payments', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('flutterwaveVeryTransaction')) {
    function flutterwaveVeryTransaction($id)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FL_SECRET_KEY')
        ])->get('https://api.flutterwave.com/v3/transactions/' . $id . '/verify')->throw();

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('flutterwaveTransfer')) {
    function flutterwaveTransfer($payload)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/transfers', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('sendGridEmails')) {
    function sendGridEmails($payload)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('SENDGRID_API_KEY')
        ])->post('https://api.sendgrid.com/v3/mail/send', $payload)->throw();
    }
}


///campaign handler

if (!function_exists('setIsComplete')) {
    function setIsComplete($id)
    {
        $cam = Campaign::where('id', $id)->first();
        $cam->number_of_staff;
        if ($cam->completed_count == $cam->number_of_staff) {
            $cam->is_completed = true;
            $cam->save();
            return 'OK';
        } else {
            return 'NOT_OK';
        }
    }
}
if (!function_exists('setPendingCount')) {
    function setPendingCount($id)
    {
        $campaign = Campaign::where('id', $id)->first();
        $campaign->number_of_staff;
        if ($campaign->pending_count == $campaign->number_of_staff) {
            $campaign->is_completed = true;
            $campaign->save();
            return 'OK';
        } else {
            return 'NOT OK';
        }
    }
}



if (!function_exists('listPreferences')) {
    function listPreferences()
    {
        $lists = Preference::all();
        $totalInterest = DB::table('user_interest')->count();
        $lis = [];
        foreach ($lists as $list) {
            $count = $list->users()->count();
            $percentage = ($count / $totalInterest) * 100;
            $lis[] = ['id' => $list->id, 'name' => $list->name, 'count' => $count, 'percentage' => number_format($percentage)];
        }

        // const sumOfPercentages = data.reduce((total, item) => total + item.percentage, 0);
        // $totalPercentage = 0;

        // foreach ($lis as $item) {
        //     $totalPercentage += $item['percentage'];
        // }
        // $data['data'] = $lis;
        // $data['sumpercentage'] = $totalPercentage;

        return $lis;
    }
}

if (!function_exists('countryList')) {
    function countryList()
    {
        $users = User::select('country', DB::raw('COUNT(*) as total'))->where('role', 'regular')->groupBy('country')->get();
        return $users;
    }
}

if (!function_exists('sendSMS')) {
    function sendSMS($phone)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/send', [
            "to" => $phone,
            "from" => "FREEBYZ",
            "sms" => 'Congrats! Your wallet at Freebyz is verified. Login to start working& earning.Click https://vm.tiktok.com/ZMM8PGFEN/ to learn  more. Earn up to 50k & more today',
            "type" => "plain",
            "channel" => "generic",
            "api_key" => env('TERMI_KEY')
        ]);

        return json_decode($res->getBody()->getContents(), true);


        // $payload = [
        //     "api_key" => env('TERMI_KEY'),
        //     "message_type" => "NUMERIC",
        //     "to" => $phone,
        //     "from" => "FREEBYZ",
        //     "channel" => "generic",
        //     "pin_attempts" => 3,
        //     "pin_time_to_live" =>  5,
        //     "pin_length" => 6,
        //     "pin_placeholder" => "< 1234 >",
        //     "message_text" => "Your Freebyz OTP pin is < 1234 >",
        //     "pin_type" => "NUMERIC"
        // ];

        // $res = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        // ])->post('https://api.ng.termii.com/api/sms/otp/send', $payload);

        //  return json_decode($res->getBody()->getContents(), true);


    }
}

if (!function_exists('sendOTP')) {
    function sendOTP($phone)
    {

        $payload = [
            "api_key" => env('TERMI_KEY'),
            "message_type" => "NUMERIC",
            "to" => $phone,
            "from" => "FREEBYZ",
            "channel" => "generic",
            "pin_attempts" => 3,
            "pin_time_to_live" =>  5,
            "pin_length" => 6,
            "pin_placeholder" => "< 1234 >",
            "message_text" => "Your Freebyz OTP pin is < 1234 >",
            "pin_type" => "NUMERIC"
        ];

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/otp/send', $payload);

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('OTPVerify')) {
    function OTPVerify($pin_id, $otp)
    {

        $payload = [
            "api_key" => env('TERMI_KEY'),
            "pin_id" => $pin_id,
            "pin" => $otp
        ];

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/otp/verify', $payload);

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('adBanner')) {
    function adBanner()
    {
        $banner = Banner::inRandomOrder()->limit(1)->where('status', true)->where('live_state', 'Started')->first(['id', 'banner_id', 'banner_url', 'status', 'live_state', 'click_count', 'clicks', 'impression', 'user_id', 'external_link']);
        if (!$banner) {
            return '';
        } else {
            ///check if current date and time is equal of greater than banner_end_date, then deactivate the banner
            // $currentTime = Carbon::now()->format('Y-m-d h:i:s');
            // if($currentTime > $banner->banner_end_date){
            //     $banner->live_state = 'Ended';
            //     $banner->save();
            // }

            $banner->impression += 1;
            $banner->save();
            //enter the impression infor
            BannerImpression::create(['user_id' => auth()->user()->id, 'banner_id' => $banner->id]);
            return $banner;
        }
    }
}


if (!function_exists('generateVirtualAccountOnboarding')) {
    function generateVirtualAccountOnboarding($user, $phone_number)
    {
        // $splitedName = explode(" ", $name);

        //check if user exist, if yes, update informatioon
        $fetchCustomer = fetchCustomer($user->email);

        if ($fetchCustomer['status'] == true) {

            //update customer
            $customerPayload = [
                "first_name" => $user->name, //auth()->user()->name,
                "last_name" => 'Freebyz',
                "phone" => "+" . $phone_number
            ];

            $updateCustomer = updateCustomer($user->email, $customerPayload);

            if ($updateCustomer['status'] == true) {

                $data = [
                    "customer" => $updateCustomer['data']['customer_code'],
                    "preferred_bank" => env('PAYSTACK_BANK')
                ];

                $response = virtualAccount($data);

                $VirtualAccount = VirtualAccount::where('user_id', $user->id)->first();
                if ($VirtualAccount) {

                    $VirtualAccount->bank_name = $response['data']['bank']['name'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->account_number = $response['data']['account_number'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->currency = 'NGN';
                    $VirtualAccount->save();
                } else {


                    $VirtualAccount = VirtualAccount::create([
                        'user_id' => $user->id,
                        'channel' => 'paystack',
                        'customer_id' => $updateCustomer['data']['customer_code'],
                        'customer_intgration' => $updateCustomer['data']['integration'],
                        'bank_name' => $response['data']['bank']['name'],
                        'account_name' => $response['data']['account_name'],
                        'account_number' => $response['data']['account_number'],
                        // 'account_name' => $response['data']['account_name'],
                        'currency' => 'NGN'
                    ]);
                }

                $data['res'] = $response;
                $data['va'] = $VirtualAccount; //back()->with('success', 'Account Created Succesfully');
                return $data;
            }
        } else {

            $payload = [
                "email" => $user->email,
                "first_name" => $user->name,
                "last_name" => 'Freebyz',
                "phone" => "+" . $phone_number
            ];
            $res = createCustomer($payload);

            if ($res['status'] == true) {

                $VirtualAccount = VirtualAccount::where('user_id', $user->id)->first();

                $data = [
                    "customer" => $res['data']['customer_code'],
                    "preferred_bank" => env('PAYSTACK_BANK') //"wema-bank"
                ];

                $response = virtualAccount($data);

                if ($VirtualAccount) {

                    $VirtualAccount->bank_name = $response['data']['bank']['name'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->account_number = $response['data']['account_number'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->currency = 'NGN';
                    $VirtualAccount->save();
                } else {


                    $VirtualAccount = VirtualAccount::create([
                        'user_id' => $user->id,
                        'channel' => 'paystack',
                        'customer_id' => $res['data']['customer_code'],
                        'customer_intgration' => $res['data']['integration'],
                        'bank_name' => $response['data']['bank']['name'],
                        'account_name' => $response['data']['account_name'],
                        'account_number' => $response['data']['account_number'],
                        // 'account_name' => $response['data']['account_name'],
                        'currency' => 'NGN'
                    ]);
                }

                $data['res'] = $response;
                $data['va'] = $VirtualAccount; //back()->with('success', 'Account Created Succesfully');
                return $data;
            } else {
                return back()->with('error', 'Error occured while processing');
            }
        }
    }
}
if (!function_exists('generateVirtualAccount')) {
    function generateVirtualAccount($name,  $phone_number)
    {
        $splitedName = explode(" ", $name);

        //check if user exist, if yes, update informatioon
        $fetchCustomer = fetchCustomer(auth()->user()->email);

        if ($fetchCustomer['status'] == true) {

            //update customer
            $customerPayload = [
                "first_name" => $name, //auth()->user()->name,
                "last_name" => 'Freebyz',
                "phone" => "+" . $phone_number
            ];

            $updateCustomer = updateCustomer(auth()->user()->email, $customerPayload);

            if ($updateCustomer['status'] == true) {

                $data = [
                    "customer" => $updateCustomer['data']['customer_code'],
                    "preferred_bank" => env('PAYSTACK_BANK')
                ];

                $response = virtualAccount($data);

                $VirtualAccount = VirtualAccount::where('user_id', auth()->user()->id)->first();
                if ($VirtualAccount) {

                    $VirtualAccount->bank_name = $response['data']['bank']['name'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->account_number = $response['data']['account_number'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->currency = 'NGN';
                    $VirtualAccount->save();
                } else {


                    $VirtualAccount = VirtualAccount::create([
                        'user_id' => auth()->user()->id,
                        'channel' => 'paystack',
                        'customer_id' => $updateCustomer['data']['customer_code'],
                        'customer_intgration' => $updateCustomer['data']['integration'],
                        'bank_name' => $response['data']['bank']['name'],
                        'account_name' => $response['data']['account_name'],
                        'account_number' => $response['data']['account_number'],
                        // 'account_name' => $response['data']['account_name'],
                        'currency' => 'NGN'
                    ]);
                }

                $data['res'] = $response;
                $data['va'] = $VirtualAccount; //back()->with('success', 'Account Created Succesfully');
                return $data;
            }
        } else {

            $payload = [
                "email" => auth()->user()->email,
                "first_name" => auth()->user()->name,
                "last_name" => 'Freebyz',
                "phone" => "+" . $phone_number
            ];
            $res = createCustomer($payload);

            if ($res['status'] == true) {

                $VirtualAccount = VirtualAccount::where('user_id', auth()->user()->id)->first();

                $data = [
                    "customer" => $res['data']['customer_code'],
                    "preferred_bank" => env('PAYSTACK_BANK') //"wema-bank"
                ];

                $response = virtualAccount($data);

                if ($VirtualAccount) {

                    $VirtualAccount->bank_name = $response['data']['bank']['name'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->account_number = $response['data']['account_number'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->currency = 'NGN';
                    $VirtualAccount->save();
                } else {

                    $VirtualAccount = VirtualAccount::create([
                        'user_id' => auth()->user()->id,
                        'channel' => 'paystack',
                        'customer_id' => $res['data']['customer_code'],
                        'customer_intgration' => $res['data']['integration'],
                        'bank_name' => $response['data']['bank']['name'],
                        'account_name' => $response['data']['account_name'],
                        'account_number' => $response['data']['account_number'],
                        // 'account_name' => $response['data']['account_name'],
                        'currency' => 'NGN'
                    ]);
                }

                $data['res'] = $response;
                $data['va'] = $VirtualAccount; //back()->with('success', 'Account Created Succesfully');
                return $data;
            } else {
                return back()->with('error', 'Error occured while processing');
            }
        }
    }
}

if (!function_exists('reGenerateUpdatedVirtualAccount')) {
    function reGenerateUpdatedVirtualAccount($email, $phone, $user)
    {

        try {

            // $data = \DB::transaction(function () use ($email, $phone, $user) {
            $payload = [
                "email" => $email,
                "first_name" => $user->name,
                "last_name" => 'Freebyz',
                "phone" => "+" . $phone
            ];
            $res = createCustomer($payload);

            if ($res['status'] == true) {

                $VirtualAccount = VirtualAccount::where('user_id', $user->id)->first();

                $data = [
                    "customer" => $res['data']['customer_code'],
                    "preferred_bank" => env('PAYSTACK_BANK') //"wema-bank"
                ];

                $response = virtualAccount($data);



                if ($VirtualAccount) {
                    // customer_code
                    $VirtualAccount->customer_id = $res['data']['customer_code'];
                    $VirtualAccount->bank_name = $response['data']['bank']['name'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->account_number = $response['data']['account_number'];
                    $VirtualAccount->account_name = $response['data']['account_name'];
                    $VirtualAccount->currency = 'NGN';
                    $VirtualAccount->save();
                }
            }

            $data['updated_user'] = $res;
            $data['updated_va'] = $VirtualAccount;

            // }, 2);

            // Return the object as JSON
            return $data;
            //  return response()->json([
            //     'status' => true,
            //     'data' => $VirtualAccount,
            // ], 200);


        } catch (\Exception $e) {
            // Handle errors and roll back the transaction
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
}

if (!function_exists('reGenerateVirtualAccount')) {
    function reGenerateVirtualAccount($user)
    {


        try {

            $data = DB::transaction(function () use ($user) {
                // Fetch the object within the transaction

                //check if user exist, if yes, update informatioon
                $fetchCustomer = fetchCustomer($user->email);

                if ($fetchCustomer['status'] == true) {
                    $phone = '234' . substr($user->phone, 1);
                    //update customer
                    $customerPayload = [
                        "first_name" => $user->name, //auth()->user()->name,
                        "last_name" => 'Freebyz',
                        "phone" => "+" . $phone
                    ];

                    $updateCustomer = updateCustomer($user->email, $customerPayload);

                    if ($updateCustomer['status'] == true) {

                        $data = [
                            "customer" => $updateCustomer['data']['customer_code'],
                            "preferred_bank" => env('PAYSTACK_BANK')
                        ];

                        $response = virtualAccount($data);

                        $VirtualAccount = VirtualAccount::where('user_id', $user->id)->first();
                        if ($VirtualAccount) {

                            $VirtualAccount->bank_name = $response['data']['bank']['name'];
                            $VirtualAccount->account_name = $response['data']['account_name'];
                            $VirtualAccount->account_number = $response['data']['account_number'];
                            $VirtualAccount->account_name = $response['data']['account_name'];
                            $VirtualAccount->currency = 'NGN';
                            $VirtualAccount->save();
                        } else {


                            $VirtualAccount = VirtualAccount::create([
                                'user_id' => $user->id,
                                'channel' => 'paystack',
                                'customer_id' => $updateCustomer['data']['customer_code'],
                                'customer_intgration' => $updateCustomer['data']['integration'],
                                'bank_name' => $response['data']['bank']['name'],
                                'account_name' => $response['data']['account_name'],
                                'account_number' => $response['data']['account_number'],
                                // 'account_name' => $response['data']['account_name'],
                                'currency' => 'NGN'
                            ]);
                        }

                        $data['res'] = $response;
                        $data['va'] = $VirtualAccount; //back()->with('success', 'Account Created Succesfully');
                        return $data;
                    }
                } else {
                    $phone = '234' . substr($user->phone, 1);
                    $payload = [
                        "email" => $user->email,
                        "first_name" => $user->name,
                        "last_name" => 'Freebyz',
                        "phone" => "+" . $phone
                    ];
                    $res = createCustomer($payload);

                    if ($res['status'] == true) {

                        $VirtualAccount = VirtualAccount::where('user_id', $user->id)->first();

                        $data = [
                            "customer" => $res['data']['customer_code'],
                            "preferred_bank" => env('PAYSTACK_BANK') //"wema-bank"
                        ];

                        $response = virtualAccount($data);



                        if ($VirtualAccount) {

                            $VirtualAccount->bank_name = $response['data']['bank']['name'];
                            $VirtualAccount->account_name = $response['data']['account_name'];
                            $VirtualAccount->account_number = $response['data']['account_number'];
                            $VirtualAccount->account_name = $response['data']['account_name'];
                            $VirtualAccount->currency = 'NGN';
                            $VirtualAccount->save();
                        } else {


                            $VirtualAccount = VirtualAccount::create([
                                'user_id' => $user->id,
                                'channel' => 'paystack',
                                'customer_id' => $res['data']['customer_code'],
                                'customer_intgration' => $res['data']['integration'],
                                'bank_name' => $response['data']['bank']['name'],
                                'account_name' => $response['data']['account_name'],
                                'account_number' => $response['data']['account_number'],
                                // 'account_name' => $response['data']['account_name'],
                                'currency' => 'NGN'
                            ]);
                        }



                        $data['res'] = $response;
                        $data['va'] = $VirtualAccount; //back()->with('success', 'Account Created Succesfully');
                        return $data;
                    } else {
                        throw new \Exception('An error occoured while processing');
                    }
                }
            }, 2);

            // Return the object as JSON
            return response()->json([
                'status' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            // Handle errors and roll back the transaction
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
}
if (!function_exists('totalVirtualAccount')) {
    function totalVirtualAccount()
    {
        return VirtualAccount::all()->count();
    }
}

if (!function_exists('transactionProcessor')) {
    function transactionProcessor($user, $reference, $amount, $status, $currency, $channel, $type, $description, $tx_type, $user_type)
    {

        return PaymentTransaction::create([
            'user_id' => $user->id,
            'campaign_id' => '1',
            'reference' => $reference,
            'amount' => $amount,
            'balance' => walletBalance($user->id),
            'status' => $status,
            'currency' => $currency,
            'channel' => $channel,
            'type' => $type, //'cash_transfer_top',
            'description' => $description, //'Cash transfer from '.$user->name,
            'tx_type' => $tx_type, //'Credit',
            'user_type' => $user_type //'regular'
        ]);
    }
}

if (!function_exists('currentLocation')) {
    function currentLocation()
    {

        if (config('services.env.env') == 'local') {
            // $ip =  '41.210.11.223';//'48.188.144.248';
            $ip =  '41.203.78.171'; //'48.188.144.248';
        } else {
            $ip = request()->ip();
        }
        //  dd($ip);
        $location = Location::get($ip);
        // Log::info($location);
        return $location->countryName;
    }
}
if (!function_exists('userForeignUpgrade')) {
    function userForeignUpgrade($user, $currency, $amount, $referral_amount = null)
    {

        $debitWallet = debitWallet($user, $currency, $amount);
        if ($debitWallet) {
            $getUser = User::where('id', $user->id)->first();
            $getUser->is_verified = 1;
            $getUser->save();

            $usd_Verified =  Usdverified::create(['user_id' => $getUser->id]);

            $ref = time();

            $tx = PaymentTransaction::create([
                'user_id' => $getUser->id,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => $amount,
                'balance' => walletBalance($getUser->id),
                'status' => 'successful',
                'currency' => $currency,
                'channel' => 'flutterwave',
                'type' => 'upgrade_payment',
                'tx_type' => 'Debit',
                'description' => 'Ugrade Payment - ' . $currency
            ]);

            $referee = Referral::where('user_id',  $getUser->id)->first();

            if ($referee) {


                $wallet = Wallet::where('user_id', $referee->referee_id)->first();
                if ($wallet->base_currency == 'NGN') {
                    $baseCur = 'NGN';
                } elseif ($wallet->base_currency == 'USD') {
                    $baseCur = 'USD';
                } else {
                    $baseCur = $wallet->base_currency;
                }

                //convert the referral commission to the referrer base currency and credit the wallet
                $referral_converted_amount = currencyConverter($currency, $baseCur, $referral_amount);

                $referralInfo = User::find($referee->referee_id);

                creditWallet($referralInfo, $baseCur, $referral_converted_amount);

                $usd_Verified->referral_id = $referee->referee_id;
                $usd_Verified->is_paid = true;
                $usd_Verified->amount = $referral_converted_amount;
                $usd_Verified->save();

                ///Transactions
                $ref_tx = PaymentTransaction::create([
                    'user_id' => $referee->referee_id, ///auth()->user()->id,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => $referral_converted_amount,
                    'balance' => walletBalance($referee->referee_id),
                    'status' => 'successful',
                    'currency' => $baseCur,
                    'channel' => 'paystack',
                    'type' => 'foreign_referer_bonus',
                    'tx_type' => 'Credit',
                    'description' => $baseCur . ' Referral Bonus from ' . $getUser->name
                ]);
            } else {

                $walletAdmin = Wallet::where('user_id', 1)->first();

                if ($walletAdmin->base_currency == 'Naira') {
                    $baseCur = 'NGN';
                } elseif ($walletAdmin->base_currency == 'Dollar') {
                    $baseCur = 'USD';
                } else {
                    $baseCur = $walletAdmin->base_currency;
                }
                $referral_converted_amount = currencyConverter($currency, $baseCur, $referral_amount);

                // $referralInfo = User::find($referee->referee_id);

                creditWallet($walletAdmin, $baseCur, $referral_converted_amount);

                $ref_tx = PaymentTransaction::create([
                    'user_id' => 1, ///auth()->user()->id,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => $referral_converted_amount,
                    'balance' => walletBalance('1'),
                    'status' => 'successful',
                    'currency' => $baseCur,
                    'channel' => 'flutterwave',
                    'type' => 'direct_foreign_referer_bonus',
                    'tx_type' => 'Credit',
                    'description' => $baseCur . ' Referral Bonus from ' . $getUser->name
                ]);
            }

            systemNotification($getUser, 'success', 'User Verification',  $getUser->name . ' was verified');
            $name = $getUser->name;
            activityLog($getUser, 'foreign_account_verification', $name . ' account verification', 'regular');

            return [$usd_Verified, $getUser, $tx, $ref_tx];
        } else {
            return false;
        }
    }
}


if (!function_exists('userDollaUpgrade')) {
    function userDollaUpgrade($user)
    {


        $getUser = User::where('id', $user->id)->first();
        $getUser->is_verified = 1;
        $getUser->save();

        $usd_Verified =  Usdverified::create(['user_id' => $getUser->id]);

        $ref = time();

        $tx = PaymentTransaction::create([
            'user_id' => $getUser->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => 5,
            'balance' => walletBalance($getUser->id),
            'status' => 'successful',
            'currency' => 'USD',
            'channel' => 'paypal',
            'type' => 'upgrade_payment',
            'tx_type' => 'Debit',
            'description' => 'Ugrade Payment - USD'
        ]);

        $referee = Referral::where('user_id',  $getUser->id)->first();
        if ($referee) {

            $wallet = Wallet::where('user_id', $referee->referee_id)->first();
            $wallet->usd_balance += 1.5;
            $wallet->save();

            $usd_Verified->referral_id = $referee->referee_id;
            $usd_Verified->is_paid = true;
            $usd_Verified->amount = 1.5;
            $usd_Verified->save();

            ///Transactions
            PaymentTransaction::create([
                'user_id' => $referee->referee_id, ///auth()->user()->id,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => 1.5,
                'balance' => walletBalance($referee->referee_id),
                'status' => 'successful',
                'currency' => 'USD',
                'channel' => 'paystack',
                'type' => 'usd_referer_bonus',
                'tx_type' => 'Credit',
                'description' => 'USD Referral Bonus from ' . $getUser->name
            ]);
        }
        systemNotification($getUser, 'success', 'User Verification',  $getUser->name . ' was verified');
        $name = $getUser->name;
        activityLog($getUser, 'dollar_account_verification', $name . ' account verification', 'regular');

        return [$usd_Verified, $getUser, $tx];
    }
}

if (!function_exists('userNairaUpgrade')) {
    function userNairaUpgrade($user, $upgradeAmount, $referral_commission)
    {

        // @$referee_id = Referral::where('user_id', $user->id)->first()->referee_id;
        // @$profile_celebrity = Profile::where('user_id', $referee_id)->first()->is_celebrity;
        // $amount = 0;
        // if($profile_celebrity){
        //     $amount = 920;
        // }else{
        //     $amount = 1050;
        // }

        $ref = time();
        $userInfo = User::where('id', $user->id)->first();
        $userInfo->is_verified = 1;
        $userInfo->save();

        $transaction =  PaymentTransaction::create([
            'user_id' => $user->id,
            'campaign_id' => 1,
            'reference' => $ref,
            'amount' => $upgradeAmount,
            'balance' => walletBalance($user->id),
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'upgrade_payment',
            'description' => 'Upgrade Payment',
            'tx_type' => 'Debit',
            'user_type' => 'regular'
        ]);


        $referee = DB::table('referral')->where('user_id',  $user->id)->first();

        if ($referee) {
            $refereeInfo = Profile::where('user_id', $referee->referee_id)->first()->is_celebrity;

            if (!$refereeInfo) {
                $wallet = Wallet::where('user_id', $referee->referee_id)->first();
                $wallet->balance += $referral_commission;
                $wallet->save();

                $refereeUpdate = Referral::where('user_id',  $user->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
                $refereeUpdate->is_paid = true;
                $refereeUpdate->save();

                ///Transactions
                $description = 'Referer Bonus from ' . $user->name;
                // paymentTrasanction($referee->referee_id, '1', time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'regular');

                PaymentTransaction::create([
                    'user_id' => $referee->referee_id,
                    'campaign_id' => 1,
                    'reference' => $ref,
                    'amount' => $referral_commission,
                    'balance' => walletBalance($referee->referee_id),
                    'status' => 'successful',
                    'currency' => 'NGN',
                    'channel' => 'paystack',
                    'type' => 'referer_bonus',
                    'description' => $description,
                    'tx_type' => 'Credit',
                    'user_type' => 'regular'
                ]);

                $adminWallet = Wallet::where('user_id', '1')->first();
                $adminWallet->balance += $referral_commission;
                $adminWallet->save();

                //Admin Transaction Table
                $description = 'Referer Bonus from ' . $user->name;
                // paymentTrasanction(1, 1, time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'admin');

                PaymentTransaction::create([
                    'user_id' => 1,
                    'campaign_id' => 1,
                    'reference' => $ref,
                    'amount' => $referral_commission,
                    'balance' => walletBalance('1'),
                    'status' => 'successful',
                    'currency' => 'NGN',
                    'channel' => 'paystack',
                    'type' => 'referer_bonus',
                    'description' => $description,
                    'tx_type' => 'Credit',
                    'user_type' => 'admin'
                ]);
            } else {
                $refereeUpdate = Referral::where('user_id', $user->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
                $refereeUpdate->is_paid = true;
                $refereeUpdate->save();
            }
        } else {
            $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->balance += $upgradeAmount;
            $adminWallet->save();
            //Admin Transaction Tablw
            PaymentTransaction::create([
                'user_id' => 1,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => $upgradeAmount,
                'balance' => walletBalance('1'),
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'paystack',
                'type' => 'direct_referer_bonus',
                'description' => 'Direct Referer Bonus from ' . $user->name,
                'tx_type' => 'Credit',
                'user_type' => 'admin'
            ]);
        }

        $name = $user->name;
        activityLog($user, 'account_verification', $name . ' account verification', 'regular');


        return $transaction;
    }
}


if (!function_exists('topEarners')) {
    function topEarners()
    {
        $highestPayoutUser = Withrawal::with(['user:id,name'])->select('user_id', DB::raw('SUM(amount) as total_payout'))
            ->groupBy('user_id')
            ->orderByDesc('total_payout')
            ->take('10')->get();

        return $highestPayoutUser;
    }
}

if (!function_exists('dailyVisit')) {
    function dailyVisit($type)
    {
        $date = Carbon::today()->toDateString();

        Statistics::updateOrCreate(
            ['date' => $date, 'type' => $type],
            ['count' => DB::raw('count + 1')]
        );
    }
}


if (!function_exists('dailyActivities')) {
    function dailyActivities()
    {
        return Cache::remember('daily_activities', 3600, function () {
            $data = ActivityLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN activity_type = "google_account_creation" THEN 1 ELSE 0 END) as google_reg_count'),
                DB::raw('SUM(CASE WHEN activity_type = "account_creation" THEN 1 ELSE 0 END) as reg_count'),
                DB::raw('SUM(CASE WHEN activity_type = "account_verification" THEN 1 ELSE 0 END) as verified_count')
            )
                ->whereBetween('created_at', [Carbon::now()->subMonths(2)->startOfDay(), Carbon::now()])
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

            $result = [['Year', 'Registered', 'Verified']];
            foreach ($data as $value) {
                $result[] = [
                    $value->date,
                    (int)$value->reg_count + (int)$value->google_reg_count,
                    (int)$value->verified_count
                ];
            }

            return $result;
        });
    }
}

if (!function_exists('dailyStats')) {
    function dailyStats()
    {
        return Cache::remember('daily_stats', 1800, function () {
            $data = Statistics::select(DB::raw('DATE(date) as date'))
                ->selectRaw('SUM(CASE WHEN type = "visits" THEN count ELSE 0 END) as visits')
                ->selectRaw('SUM(CASE WHEN type = "LandingPage" THEN count ELSE 0 END) as landing_page_count')
                ->selectRaw('SUM(CASE WHEN type = "Dashboard" THEN count ELSE 0 END) as dashboard_count')
                ->whereBetween('created_at', [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()])
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

            $result = [['Year', 'Visits**', 'LandingPage', 'Dashboard', 'Total Visit']];
            foreach ($data as $value) {
                $total = (int)$value->landing_page_count + (int)$value->dashboard_count;
                $result[] = [
                    $value->date,
                    (int)$value->visits,
                    (int)$value->landing_page_count,
                    (int)$value->dashboard_count,
                    $total
                ];
            }
            return $result;
        });
    }
}

if (!function_exists('monthlyVisits')) {
    function monthlyVisits()
    {
        return Cache::remember('monthly_visits', 3600, function () {
            $data = User::select(
                DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'),
                DB::raw('COUNT(*) as user_per_month'),
                DB::raw('SUM(is_verified) as verified_users')
            )
                ->whereBetween('created_at', [Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()])
                ->groupBy('month')
                ->orderByRaw('MIN(created_at) ASC')
                ->get();

            $result = [['Month', 'Users', 'Verified']];
            foreach ($data as $value) {
                $result[] = [
                    $value->month,
                    (int)$value->user_per_month,
                    (int)$value->verified_users
                ];
            }
            return $result;
        });
    }
}

if (!function_exists('registrationChannel')) {
    function registrationChannel()
    {
        return Cache::remember('registration_channel', 7200, function () {
            $data = User::select('source', DB::raw('COUNT(*) as total'))
                ->groupBy('source')
                ->orderBy('total', 'DESC')
                ->get();

            $result = [['Channel', 'Total']];
            foreach ($data as $value) {
                $result[] = [
                    $value->source ?? 'Organic',
                    (int)$value->total
                ];
            }
            return $result;
        });
    }
}

if (!function_exists('revenueChannel')) {
    function revenueChannel()
    {
        return Cache::remember('revenue_channel', 3600, function () {
            $data = PaymentTransaction::select('type', DB::raw('SUM(amount) as amount'))
                ->where('tx_type', 'Credit')
                ->where('type', '!=', 'direct_referer_bonus_naira_usd')
                ->where('user_id', 1)
                ->groupBy('type')
                ->orderBy('amount', 'DESC')
                ->get();

            $result = [['Revenue Channel', 'Total']];
            foreach ($data as $value) {
                $result[] = [$value->type, (int)$value->amount];
            }
            return $result;
        });
    }
}

if (!function_exists('weeklyRegistrationChannel')) {
    function weeklyRegistrationChannel()
    {
        return Cache::remember('weekly_registration_channel', 1800, function () {
            $data = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS day'),
                DB::raw('COUNT(CASE WHEN source = "Youtube" THEN 1 END) AS youtube'),
                DB::raw('COUNT(CASE WHEN source = "Facebook" THEN 1 END) AS facebook'),
                DB::raw('COUNT(CASE WHEN source = "Instagram" THEN 1 END) AS instagram'),
                DB::raw('COUNT(CASE WHEN source = "Whatsapp" THEN 1 END) AS whatsapp'),
                DB::raw('COUNT(CASE WHEN source = "TikTok" THEN 1 END) AS tiktok'),
                DB::raw('COUNT(CASE WHEN source = "Twitter" THEN 1 END) AS twitter'),
                DB::raw('COUNT(CASE WHEN source = "Online Ads" THEN 1 END) AS online_ads'),
                DB::raw('COUNT(CASE WHEN source = "Referred by a Friend" THEN 1 END) AS referred')
            )
                ->whereBetween('created_at', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()])
                ->groupBy('day')
                ->orderBy('day', 'ASC')
                ->get();

            $result = [['day', 'Youtube', 'Facebook', 'Instagram', 'Whatsapp', 'TikTok', 'Twitter', 'Online Ads', 'Referrals']];
            foreach ($data as $value) {
                $result[] = [
                    $value->day,
                    (int)$value->youtube,
                    (int)$value->facebook,
                    (int)$value->instagram,
                    (int)$value->whatsapp,
                    (int)$value->tiktok,
                    (int)$value->twitter,
                    (int)$value->online_ads,
                    (int)$value->referred
                ];
            }
            return $result;
        });
    }
}

if (!function_exists('weeklyVerificationChannel')) {
    function weeklyVerificationChannel()
    {
        return Cache::remember('weekly_verification_channel', 1800, function () {
            $data = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS day'),
                DB::raw('COUNT(CASE WHEN source = "Youtube" THEN 1 END) AS youtube'),
                DB::raw('COUNT(CASE WHEN source = "Facebook" THEN 1 END) AS facebook'),
                DB::raw('COUNT(CASE WHEN source = "Instagram" THEN 1 END) AS instagram'),
                DB::raw('COUNT(CASE WHEN source = "Whatsapp" THEN 1 END) AS whatsapp'),
                DB::raw('COUNT(CASE WHEN source = "TikTok" THEN 1 END) AS tiktok'),
                DB::raw('COUNT(CASE WHEN source = "Twitter" THEN 1 END) AS twitter'),
                DB::raw('COUNT(CASE WHEN source = "Online Ads" THEN 1 END) AS online_ads'),
                DB::raw('COUNT(CASE WHEN source = "Referred by a Friend" THEN 1 END) AS referred')
            )
                ->where('is_verified', true)
                ->whereBetween('created_at', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()])
                ->groupBy('day')
                ->orderBy('day', 'ASC')
                ->get();

            $result = [['day', 'Youtube', 'Facebook', 'Instagram', 'Whatsapp', 'TikTok', 'Twitter', 'Online Ads', 'Referrals']];
            foreach ($data as $value) {
                $result[] = [
                    $value->day,
                    (int)$value->youtube,
                    (int)$value->facebook,
                    (int)$value->instagram,
                    (int)$value->whatsapp,
                    (int)$value->tiktok,
                    (int)$value->twitter,
                    (int)$value->online_ads,
                    (int)$value->referred
                ];
            }
            return $result;
        });
    }
}

if (!function_exists('monthlyRevenue')) {
    function monthlyRevenue()
    {
        return Cache::remember('monthly_revenue', 3600, function () {
            $data = PaymentTransaction::select(
                DB::raw('DATE_FORMAT(created_at, "%b %Y") AS month'),
                DB::raw('SUM(CASE WHEN type = "direct_referer_bonus" THEN amount ELSE 0 END) AS direct_referer_bonus'),
                DB::raw('SUM(CASE WHEN type = "referer_bonus" THEN amount ELSE 0 END) AS referer_bonus'),
                DB::raw('SUM(CASE WHEN type = "campaign_revenue" THEN amount ELSE 0 END) AS campaign_revenue'),
                DB::raw('SUM(CASE WHEN type = "withdrawal_commission" THEN amount ELSE 0 END) AS withdrawal_commission'),
                DB::raw('SUM(CASE WHEN type = "campaign_revenue_add" THEN amount ELSE 0 END) AS campaign_revenue_add')
            )
                ->whereNotIn('user_id', [84]) // Exclude admin accounts
                ->whereBetween('created_at', [Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()])
                ->groupBy('month')
                ->orderByRaw('MIN(created_at) ASC')
                ->get();

            $result = [['Month', 'Direct Referral', 'Referral Bonus', 'Campaign Rev', 'Campaign Rev(Added)', 'Withdrawal Commission']];
            foreach ($data as $value) {
                $result[] = [
                    $value->month,
                    (int)$value->direct_referer_bonus,
                    (int)$value->referer_bonus,
                    (int)$value->campaign_revenue,
                    (int)$value->campaign_revenue_add,
                    (int)$value->withdrawal_commission
                ];
            }
            return $result;
        });
    }
}

if (!function_exists('countryDistribution')) {
    function countryDistribution()
    {
        return Cache::remember('country_distribution', 7200, function () {
            $data = User::select('country', DB::raw('COUNT(*) as total'))
                ->groupBy('country')
                ->orderBy('total', 'DESC')
                ->get();

            $result = [['Country', 'Total']];
            foreach ($data as $value) {
                $result[] = [
                    $value->country ?? 'Unassigned',
                    (int)$value->total
                ];
            }
            return $result;
        });
    }
}

if (!function_exists('ageDistribution')) {
    function ageDistribution()
    {
        return Cache::remember('age_distribution', 7200, function () {
            $data = User::select('age_range', DB::raw('COUNT(*) as total'))
                ->groupBy('age_range')
                ->orderBy('total', 'DESC')
                ->get();

            $result = [['Age Range', 'Total']];
            foreach ($data as $value) {
                $result[] = [
                    $value->age_range ?? 'Unassigned',
                    (int)$value->total
                ];
            }
            return $result;
        });
    }
}

if (!function_exists('currencyDistribution')) {
    function currencyDistribution()
    {
        return Cache::remember('currency_distribution', 7200, function () {
            $data = Wallet::select('base_currency', DB::raw('COUNT(*) as total'))
                ->groupBy('base_currency')
                ->orderBy('total', 'DESC')
                ->get();

            $result = [['Currency', 'Total']];
            foreach ($data as $value) {
                $result[] = [
                    $value->base_currency ?? 'Unassigned',
                    (int)$value->total
                ];
            }
            return $result;
        });
    }
}

if (!function_exists('numberFormat')) {
    function numberFormat($number, $plus = true)
    {
        if ($number >= 1000000000) {
            $formatted = number_format(($number / 1000000000), 1);
            return $formatted > (int)$formatted && $plus ? (int)$formatted . 'B+' : (int)$formatted . 'B';
        }
        if ($number >= 1000000) {
            $formatted = number_format(($number / 1000000), 1);
            return $formatted > (int)$formatted && $plus ? (int)$formatted . 'M+' : (int)$formatted . 'M';
        }
        if ($number >= 1000) {
            $formatted = number_format(($number / 1000), 1);
            return $formatted > (int)$formatted && $plus ? (int)$formatted . 'K+' : (int)$formatted . 'K';
        }
        return $number;
    }
}

if (!function_exists('getInitials')) {
    function getInitials($name)
    {
        $names = explode(' ', trim($name));
        $initials = '';
        foreach ($names as $word) {
            $initials .= isset($word[0]) ? strtoupper($word[0]) : '';
        }
        return $initials;
    }
}

if (!function_exists('activityLog')) {
    function activityLog($user, $activity_type, $description, $user_type)
    {
        return ActivityLog::create([
            'user_id' => $user->id,
            'activity_type' => $activity_type,
            'description' => $description,
            'user_type' => $user_type
        ]);
    }
}


if (!function_exists('showActivityLog')) {
    function showActivityLog()
    {

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        return ActivityLog::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('user_type', 'regular')->get();
    }
}

if (!function_exists('currencyList')) {
    function currencyList($exempt = null, $status = null)
    {
        if ($status == true) {
            return Currency::where('code', '!=', $exempt)->where('is_active', true)->orderBy('country', 'ASC')->get();
        } else {
            return Currency::where('code', '!=', $exempt)->orderBy('country', 'ASC')->get();
        }
    }
}

if (!function_exists('filterCampaign')) {
    function filterCampaign($categoryID)
    {

        $user = Auth::user();
        // $jobfilter = '';
        $campaigns = '';

        $query = Campaign::query()
            ->where('status', 'Live')
            ->where('is_completed', false)
            ->whereNotIn('id', function ($query) use ($user) {
                $query->select('campaign_id')
                    ->from('campaign_workers')
                    ->where('user_id', $user->id);
            });

        if ($categoryID != 0) {
            $query->where('campaign_type', $categoryID);
        }

        // Optimize sorting and paginate
        $campaigns = $query->orderByRaw("CASE WHEN approved = 'Priotized' THEN 1 ELSE 2 END")
            ->orderBy('created_at', 'DESC')
            ->paginate(15);


        // Add extra fields (handled by Accessors in the Model)
        $campaigns->getCollection()->transform(function ($campaign) use ($user) {
            $baseCurrency = $user->wallet->base_currency;
            $c = $campaign->pending_count + $campaign->completed_count;

            $campaign->local_currency = $baseCurrency;
            $campaign->progress = $campaign->number_of_staff > 0
                ? round(($c / $campaign->number_of_staff) * 100, 2)
                : 0;

            $from = $campaign->currency; //from
            $to = $baseCurrency; //to user local currency
            $campaign->local_converted_amount = currencyConverter($from, $to, $campaign->campaign_amount); //$convertedAmount,
            $campaign->local_converted_currency = $baseCurrency;
            $campaign->completed = $c;
            $campaign->is_completed = $c >= $campaign->number_of_staff ? true : false;
            $campaign->type = $campaign->campaignType->name;
            // $campaign->local_converted_currency_code = $baseCurrency;
            return $campaign;
        });

        // // Remove objects where 'is_completed' is true
        // $filteredArray = array_filter($campaigns, function ($item) {
        //     return $item['is_completed'] !== true;
        // });

        return response()->json($campaigns);
        // return $filteredArray;




        // if($categoryID == 0){
        //     ///without filter

        //     $campaigns = Campaign::where('status', 'Live')
        //         ->where('is_completed', false)
        //         ->whereNotIn('id', function($query) use ($user) {

        //             $query->select('campaign_id')
        //             ->from('campaign_workers')
        //             ->where('user_id', $user->id);

        //     })
        //     ->orderByRaw("CASE WHEN approved = 'Priotize' THEN 1 ELSE 2 END")
        //     ->orderBy('created_at', 'DESC')->paginate(5);
        // }else{
        //     //with filter
        //     $campaigns = Campaign::where('status', 'Live')
        //         ->where('is_completed', false)
        //         ->where('campaign_type', $categoryID)
        //         ->whereNotIn('id', function($query) use ($user) {

        //             $query->select('campaign_id')
        //             ->from('campaign_workers')
        //             ->where('user_id', $user->id);

        //     })
        //     ->orderByRaw("CASE WHEN approved = 'Priotize' THEN 1 ELSE 2 END")
        //     ->orderBy('created_at', 'DESC')->paginate(5);
        // }

        // return response()->json($campaigns);

        // $campaigns = Campaign::where('status', 'Live')->where('is_completed', false)->orderBy('created_at', 'DESC')->get();

        // $list = [];
        // foreach($campaigns as $key => $value){
        //     $c = $value->pending_count + $value->completed_count;//
        //     // $campaignStatus = checkCampaignCompletedStatus($value->id);
        //     // $c = $campaignStatus['Pending'] ?? 0 + $campaignStatus['Approved'] ?? 0;
        //     $div = $c / $value->number_of_staff;
        //     $progress = $div * 100;

        //     //jobCurrency
        //     $from = $value->currency; //from
        //     $to = $baseCurrency; //to user local currency

        //     $list[] = [
        //         'id' => $value->id,
        //         'job_id' => $value->job_id,

        //         'post_title' => $value->post_title,
        //         'number_of_staff' => $value->number_of_staff,
        //         'type' => $value->campaignType->name,
        //         'category' => $value->campaignCategory->name,
        //         //'attempts' => $attempts,
        //         'completed' => $c, //$value->completed_count+$value->pending_count,
        //         'is_completed' => $c >= $value->number_of_staff ? true : false,
        //         'progress' => $progress,

        //         'campaign_amount' => $value->campaign_amount,
        //         'currency' => $value->currency,
        //         'currency_code' => $value->currency == 'NGN' ? '&#8358;' : '$',

        //         'local_converted_amount' => currencyConverter($from, $to, $value->campaign_amount), //$convertedAmount,
        //         'local_converted_currency' => $baseCurrency,
        //         'local_converted_currency_code' => $baseCurrency,

        //         'priotized' => $value->approved,
        //         'from' => $from,
        //         'to' => $to,
        //         'baseCurrency' => baseCurrency(),
        //         // 'completed_status' => checkCampaignCompletedStatus($value->id)

        //     ];
        // }



        // // Remove objects where 'is_completed' is true
        // $filteredArray = array_filter($list, function ($item) {
        //     return $item['is_completed'] !== true;
        // });

        // // Sort the array to prioritize 'Priotized'
        // usort($filteredArray, function ($a, $b) {
        //     return strcmp($b['priotized'], $a['priotized']);
        // });

        //  return  $filteredArray;

    }
}

if (!function_exists('currencyParameter')) {
    function currencyParameter($currency)
    {
        return $currency = Currency::where('code', $currency)->where('is_active', true)->first();
    }
}

if (!function_exists('fetchUpgradeFee')) {
    function fetchUpgradeFee($currency)
    {
        return  Currency::where('code', $currency)->where('is_active', true)->first()->upgrade_fee;
    }
}

if (!function_exists('testCampaign')) {
    function testCampaign($categoryID)
    {

        $user = Auth::user();
        // $jobfilter = '';
        $campaigns = '';

        $baseCurrency = $user->wallet->base_currency;


        if ($categoryID == 0) {
            ///without filter

            $campaigns = Campaign::where('status', 'Live')
                ->where('is_completed', false)
                ->withCount([
                    'completed as total_approved' => function ($query) {
                        $query->where('status', 'Approved');
                    },
                    'completed as total_pending' => function ($query) {
                        $query->where('status', 'Pending');
                    },
                    'completed as total_denied' => function ($query) {
                        $query->where('status', 'Denied');
                    }
                ])->havingRaw("(total_approved + total_pending) < number_of_staff")

                ->whereNotIn('id', function ($query) use ($user) {

                    $query->select('campaign_id')
                        ->from('campaign_workers')
                        ->where('user_id', $user->id);
                })->orderBy('created_at', 'DESC')->paginate(10);
        } else {
            //with filter
            $campaigns = Campaign::where('status', 'Live')
                ->where('is_completed', false)
                ->where('campaign_type', $categoryID)
                ->withCount([
                    'completed as total_approved' => function ($query) {
                        $query->where('status', 'Approved');
                    },
                    'completed as total_pending' => function ($query) {
                        $query->where('status', 'Pending');
                    },
                    'completed as total_denied' => function ($query) {
                        $query->where('status', 'Denied');
                    }
                ])->havingRaw("(total_approved + total_pending) < number_of_staff")

                ->whereNotIn('id', function ($query) use ($user) {

                    $query->select('campaign_id')
                        ->from('campaign_workers')
                        ->where('user_id', $user->id);
                })->orderBy('created_at', 'DESC')->paginate(10);
        }



        // $campaigns = Campaign::where('status', 'Live')->where('is_completed', false)->orderBy('created_at', 'DESC')->get();



        $list = [];
        foreach ($campaigns as $key => $value) {
            // $campaignStatus = checkCampaignCompletedStatus($value->id);

            // $c = $campaignStatus['Pending'] ?? 0 + $campaignStatus['Approved'] ?? 0;//

            $c = $value->total_approved + $value->total_pending;

            $div = $c / $value->number_of_staff;
            $progress = $div * 100;



            //jobCurrency
            $from = $value->currency; //from
            $to = $baseCurrency; //to user local currency

            $list[] = [
                'id' => $value->id,
                'job_id' => $value->job_id,

                'post_title' => $value->post_title,
                'number_of_staff' => $value->number_of_staff,
                'type' => $value->campaignType->name,
                'category' => $value->campaignCategory->name,

                'completed' => $c,
                'is_completed' => $c >= $value->number_of_staff ? true : false,
                'progress' => $progress,

                'campaign_amount' => $value->campaign_amount,
                'currency' => $value->currency,
                'currency_code' => $value->currency == 'NGN' ? '&#8358;' : '$',

                'local_converted_amount' => currencyConverter($from, $to, $value->campaign_amount), //$convertedAmount,
                'local_converted_currency' => $baseCurrency,
                'local_converted_currency_code' => $baseCurrency,

                'priotized' => $value->approved,
                'from' => $from,
                'to' => $to,
                'baseCurrency' => baseCurrency(),
                'total_approved_count' => $value->total_approved,
                'total_pending_count' => $value->total_pending,
                'total_denied_count' => $value->total_denied,

                // 'completed_status' => checkCampaignCompletedStatus($value->id)
                // 'local_converted_amount' => $rates * $value->campaign_amount,
                // 'created_at' => $value->created_at
            ];
        }



        //$sortedList = collect($list)->sortBy('is_completed')->values()->all();//collect($list)->sortByDesc('is_completed')->values()->all(); //collect($list)->sortBy('is_completed')->values()->all();

        // Remove objects where 'is_completed' is true

        // $filteredArray = array_filter($list, function ($item) {
        //     return $item['is_completed'] !== true;
        // });

        // Sort the array to prioritize 'Priotized'

        usort($list, function ($a, $b) {
            return strcmp($b['priotized'], $a['priotized']);
        });

        return  $list;

        //return $list;


    }
}

if (!function_exists('checkCampaignCompletedStatus')) {
    function checkCampaignCompletedStatus($campaignId)
    {

        $counts = array_merge([
            'Approved' => 0,
            'Pending' => 0,
            'Denied' => 0,
        ], DB::table('campaign_workers')
            ->where('campaign_id', $campaignId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')->toArray());

        $campaign = Campaign::where('id', $campaignId)->first();
        $campaign->pending_count = $counts['Pending'];
        $campaign->completed_count = $counts['Approved'];
        $campaign->save();

        $totalCampaign = $counts['Approved'] + $counts['Pending'];

        if ($totalCampaign >= $campaign->number_of_staff) {
            $campaign->is_completed = 1;
            $campaign->save();
        } else {
            $campaign->is_completed = 0;
            $campaign->save();
        }

        // $data['campaign'] = $campaign;
        $data['is_completed'] = $campaign->is_completed; //$totalCampaign;
        $data['counts'] = $counts;

        return $data; //$counts;

    }
}

if (!function_exists('availableJobs')) {
    function availableJobs()
    {

        $user = Auth::user();
        $jobfilter = '';
        $campaigns = '';

        if ($user) {
            $jobfilter = $user->wallet->base_currency == 'Naira' ? 'NGN' : 'USD';
        }

        if ($user->USD_verified) { //if user is usd verified, they see all jobs
            $campaigns = Campaign::where('status', 'Live')->where('is_completed', false)->orderBy('created_at', 'DESC')->get();
        } else {
            $campaigns = Campaign::where('status', 'Live')->where('currency', $jobfilter)->where('is_completed', false)->orderBy('created_at', 'DESC')->get();
        }

        $list = [];
        foreach ($campaigns as $key => $value) {
            $c = $value->pending_count + $value->completed_count; //
            $div = $c / $value->number_of_staff;
            $progress = $div * 100;

            $list[] = [
                'id' => $value->id,
                'job_id' => $value->job_id,
                'campaign_amount' => $value->campaign_amount,
                'post_title' => $value->post_title,
                'number_of_staff' => $value->number_of_staff,
                'type' => $value->campaignType->name,
                'category' => $value->campaignCategory->name,
                //'attempts' => $attempts,
                'completed' => $c, //$value->completed_count+$value->pending_count,
                'is_completed' => $c >= $value->number_of_staff ? true : false,
                'progress' => $progress,
                'currency' => $value->currency,
                'priotized' => $value->approved,
                // 'created_at' => $value->created_at
            ];
        }

        //$sortedList = collect($list)->sortBy('is_completed')->values()->all();//collect($list)->sortByDesc('is_completed')->values()->all(); //collect($list)->sortBy('is_completed')->values()->all();

        // Remove objects where 'is_completed' is true
        $filteredArray = array_filter($list, function ($item) {
            return $item['is_completed'] !== true;
        });

        // Sort the array to prioritize 'Priotized'
        usort($filteredArray, function ($a, $b) {
            return strcmp($b['priotized'], $a['priotized']);
        });

        return  $filteredArray;
    }
}


if (!function_exists('badgeCount')) {
    function badgeCount()
    {

        $currentDate = Carbon::now()->subMonth();
        return Referral::where('referee_id', auth()->user()->id)
            ->whereMonth('updated_at', $currentDate->month)
            // ->whereDate('updated_at', today())
            ->count();
    }
}


if (!function_exists('badge')) {
    function badge()
    {

        $currentDate = Carbon::now()->subMonth();
        $count = Referral::where('referee_id', auth()->user()->id)->whereMonth('updated_at', $currentDate->month)->count();

        $color = '';
        $membership = '';
        $amount = '';

        if ($count >= 10 && $count <= 20) {
            $color = '#E5E4E2';
            $membership = 'Platinum';
            $amount = 500;
        } elseif ($count >= 21 && $count <= 49) {
            $color = 'silver';
            $membership = 'Silver';
            $amount = 1500;
        } elseif ($count >= 50) {
            $color = 'gold';
            $membership = 'Gold';
            $amount = 500;
        } else {
            $color = 'grey';
            $membership = 'Standard';
            $amount = 0;
        }
        $data['count'] = $count;
        $data['color'] = $color;
        $data['badge'] = $membership;
        $data['amount'] = $amount;
        $data['duration'] = Carbon::now()->subMonth()->format('M, Y');

        return $data;
    }
}


if (!function_exists('viewCampaign')) {
    function viewCampaign($campaign_id)
    {

        if ($campaign_id == null) {
            return false;
        }



        $campaign = Campaign::with(['campaignType', 'campaignCategory'])->where('job_id', $campaign_id)->first();


        // $campaign->pending_count = $campaignStatus['Pending'] ?? 0;
        // $campaign->completed_count = $campaignStatus['Approved'] ?? 0;
        // $campaign->save();

        if ($campaign) {

            $campaign->impressions += 1;
            $campaign->save();

            checkCampaignCompletedStatus($campaign->id);

            $data = $campaign;
            $data['current_user_id'] = auth()->user()->id;
            $data['is_attempted'] = $campaign->completed()->where('user_id', auth()->user()->id)->first() != null ? true : false;
            $data['attempts'] = $campaign->completed()->count();
            $data['local_converted_amount'] = currencyConverter($campaign->currency, baseCurrency(), $campaign->campaign_amount);
            $data['local_converted_currency'] = baseCurrency();
            return $data;
        } else {
            return false;
        }
    }
}

if (!function_exists('baseCurrency')) {
    function baseCurrency($user = null)
    {

        if ($user == null) {
            $user = Auth::user();
            return Wallet::where('user_id', $user->id)->first()->base_currency; //$user->wallet->base_currency;
        } else {
            $user = User::find($user->id);
            return Wallet::where('user_id', $user->id)->first()->base_currency; //$user->wallet->base_currency;
        }
    }
}


if (!function_exists('countryList')) {
    function countryList()
    {
        $url = 'https://api.paystack.co/country';
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->get($url)->throw();

        return json_decode($res->getBody()->getContents(), true)['data'];
    }
}

if (!function_exists('bankList')) {
    function bankList()
    {
        // country=nigeria
        $url = 'https://api.paystack.co/bank?country=nigeria';
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->get($url)->throw();

        return $bankList = json_decode($res->getBody()->getContents(), true)['data'];
    }
}

if (!function_exists('resolveBankName')) {
    function resolveBankName($account_number, $bank_code)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->get('https://api.paystack.co/bank/resolve?account_number=' . $account_number . '&bank_code=' . $bank_code);
        return json_decode($res->getBody()->getContents(), true);
    }
}



if (!function_exists('recipientCode')) {
    function recipientCode($name, $account_number, $bank_code)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transferrecipient', [
            "type" => "nuban",
            "name" => $name,
            "account_number" => $account_number,
            "bank_code" => $bank_code,
            "currency" => "NGN"
        ]);

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('transferFund')) {
    function transferFund($amount, $recipient, $reason)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transfer', [
            "source" => "balance",
            "amount" => $amount,
            "recipient" => $recipient,
            "reason" => $reason
        ]);

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('bulkFundTransfer')) {
    function  bulkFundTransfer($transfers)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transfer/bulk', [
            "currency" => "NGN",
            "source" => "balance",
            "transfers" => $transfers
        ]);

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('initiateTrasaction')) {
    function  initiateTrasaction($ref, $amount, $redirect_url)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('paystack.secretKey')
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => auth()->user()->email,
            'amount' => $amount * 100,
            'channels' => ['card', 'bank', 'ussd', 'transfer'], //'ussd', 'bank', 'transfer', 'opay', 'payattitude', 'pocket_app', 'visa_qr'
            'currency' => 'NGN',
            'reference' => $ref,
            'callback_url' => url($redirect_url),
            "metadata" => [
                "user_id" => auth()->user()->id,
            ]
        ]);
        return $res['data']['authorization_url'];
    }
}

if (!function_exists('verifyTransaction')) {
    function  verifyTransaction($ref)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('paystack.secretKey')
        ])->get('https://api.paystack.co/transaction/verify/' . $ref)->throw();

        return json_decode($res->getBody()->getContents(), true);
    }
}
if (!function_exists('virtualAccount')) {
    function  virtualAccount($data)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('paystack.secretKey')
        ])->post('https://api.paystack.co/dedicated_account', $data);

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('createCustomer')) {
    function  createCustomer($data)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('paystack.secretKey')
        ])->post('https://api.paystack.co/customer', $data);

        return json_decode($res->getBody()->getContents(), true);
    }
}


if (!function_exists('fetchCustomer')) {
    function  fetchCustomer($email)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('paystack.secretKey')
        ])->get('https://api.paystack.co/customer/' . $email);

        return json_decode($res->getBody()->getContents(), true);
    }
}


if (!function_exists('updateCustomer')) {
    function  updateCustomer($email, $payload)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('paystack.secretKey')
        ])->put('https://api.paystack.co/customer/' . $email, $payload);

        return json_decode($res->getBody()->getContents(), true);
    }
}

//fluterwave apis

if (!function_exists('listFlutterwaveTransaction')) {
    function  listFlutterwaveTransaction()
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FL_SECRET_KEY')
        ])->get('https://api.flutterwave.com/v3/transactions')->throw();

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('initiateFlutterwavePayment')) {
    function  initiateFlutterwavePayment($payload)
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FL_SECRET_KEY')
        ])->post('hhttps://api.flutterwave.com/v3/payments', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    }
}

if (!function_exists('sendBulkSMS')) {
    function sendBulkSMS($number, $message)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://v3.api.termii.com/api/sms/send/bulk', [
            // "to"=> $number,
            "to" => $number,
            "from" => "Freebyz",
            "sms" => $message,
            "type" => "plain",
            "channel" => "generic",
            "api_key" => config('services.env.termii')
        ]);
        Log::info($res->json());

        return $res->json();
    }
}

if (!function_exists('formatAndArrange')) {
    function formatAndArrange($phones)
    {
        $formatted = [];

        foreach ($phones as $phone) {
            if (empty($phone)) {
                continue;
            }

            $clean = preg_replace('/\D/', '', $phone);
            $formattedPhone = Str::startsWith($clean, '234')
                ? '+' . $clean
                : '+234' . ltrim($clean, '0');

            $formatted[] = $formattedPhone;
        }

        return array_values(array_unique($formatted));
    }
}

if (!function_exists('getLocation')) {
    function  getLocation()
    {
        if (config('services.env.env') == 'local') {
            // $ip = '48.188.144.248';
            $ip = '41.203.78.171';
        } else {
            $ip = request()->ip();
            $location = Location::get($ip);
            return $location->countryName;
        }
    }
}

if (!function_exists('geo')) {
    function  geo()
    {
        if (config('services.env.env') == 'local') {
            // $ip = '48.188.144.248';
            $ip = '41.203.78.171';
        } else {
            $ip = request()->ip();
            return Location::get($ip);
        }
    }
}


if (!function_exists('userLocation')) {
    function  userLocation($type)
    {

        if (config('services.env.env') == 'local') {
            // $ip = '48.188.144.248';
            $ip = '41.203.78.171';
        } else {
            $ip = request()->ip();
        }

        if ($type == 'Login') {
            $check = UserLocation::where('user_id', auth()->user()->id)->whereDate('created_at', today())->first();

            if (!$check) {
                $location = Location::get($ip);
                UserLocation::create([
                    'user_id' => auth()->user()->id,
                    'activity' => $type,
                    'ip' => $ip,
                    'countryName' => $location->countryName,
                    'countryCode' => $location->countryCode,
                    'regionName' => $location->regionName,
                    'regionCode' => $location->regionCode,
                    'cityName' => $location->cityName,
                    'zipCode' => $location->zipCode,
                    'areaCode' => $location->areaCode,
                    'timezone' => $location->timezone
                ]);
            }
        } else {
            $location = Location::get($ip);

            UserLocation::create([
                'user_id' => auth()->user()->id,
                'activity' => $type,
                'ip' => $ip,
                'countryName' => $location->countryName,
                'countryCode' => $location->countryCode,
                'regionName' => $location->regionName,
                'regionCode' => $location->regionCode,
                'cityName' => $location->cityName,
                'zipCode' => $location->zipCode,
                'areaCode' => $location->areaCode,
                'timezone' => $location->timezone
            ]);
        }
    }
}

if (!function_exists('paymentTrasanction')) {
    function  paymentTrasanction($userId, $campaign_id, $ref, $amount, $status, $type, $description, $tx_type, $user_type)
    {
        return PaymentTransaction::create([
            'user_id' => $userId,
            'campaign_id' => $campaign_id,
            'reference' => $ref,
            'amount' => $amount,
            'balance' => walletBalance($userId),
            'status' => $status,
            'currency' => auth()->user()->wallet->base_currency == 'Naira' ? 'NGN' : 'USD',
            'channel' => auth()->user()->wallet->base_currency == 'Naira' ? 'paystack' : 'paypal',
            'type' => $type,
            'description' => $description,
            'tx_type' => $tx_type,
            'user_type' => $user_type
        ]);
    }
}


if (!function_exists('paymentUpdate')) {
    function  paymentUpdate($ref, $status, $amount = null)
    {
        $fetchPaymentTransaction = PaymentTransaction::where('reference', $ref)->first();
        $fetchPaymentTransaction->status = $status;
        $fetchPaymentTransaction->amount = $amount;
        $fetchPaymentTransaction->save();
        return $fetchPaymentTransaction;
    }
}


if (!function_exists('amountCalculator')) {
    function  amountCalculator($amount)
    {
        $percent = 5 / 100 * $amount;
        return $amount + $percent + 0.4;
    }
}

if (!function_exists('baseRates')) {
    function  baseRates()
    {

        $currencies = Currency::query()->where('is_active', '1')->get(['id', 'code', 'base_rate']);

        // Initialize results array
        $pairsWithRates = [];

        foreach ($currencies as $base) {

            foreach ($currencies as $target) {
                // if($base !== $target){
                $rate = $target['base_rate'] / $base['base_rate'];
                $pairsWithRates[] = [
                    'from' => $base['code'],
                    'to' => $target['code'],
                    'rate' => sprintf("%.6g", $rate),
                ];
                // }

            }
        }

        return $pairsWithRates;
    }
}

if (!function_exists('getBaseRate')) {
    function  getBaseRate($currencyCode)
    {
        return Currency::where('code', $currencyCode)->first()->base_rate;
    }
}

if (!function_exists('currencyConverter')) {
    function  currencyConverter($from, $to, $amount)
    {
        $baseCurr = getBaseRate($from);
        $target = getBaseRate($to);

        $rate = $target / $baseCurr;

        $convertedAmount = $rate * $amount;

        // return ceil($convertedAmount);
        return number_format($convertedAmount, 2);
    }
}

if (!function_exists('walletBalance')) {
    function  walletBalance($userId)
    {

        $wallet = Wallet::where('user_id', $userId)->first();

        if ($wallet->base_currency == 'NGN') {
            return $wallet->balance;
        } elseif ($wallet->base_currency == 'USD') {
            return $wallet->usd_balance;
        } else {
            return $wallet->base_currency_balance;
        }
    }
}

if (!function_exists('fraudDetection')) {
    function  fraudDetection($userId)
    {

        //check last 3 months transaction
        return  $recentUsers = DB::select("
            SELECT * FROM payment_transactions
            WHERE user_id = " . $userId . ",
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 3 MONTH)
        ");
    }
}
