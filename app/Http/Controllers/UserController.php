<?php

namespace App\Http\Controllers;

use App\Helpers\CapitalSage;
use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Mail\UpgradeUser;
use App\Models\DataBundle;
use App\Models\PaymentTransaction;
use App\Models\Profile;
use App\Models\Referral;
use App\Models\Usdverified;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withrawal;
use App\Notifications\NewNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upgrade()
    {
        return view('user.upgrade');
    }


    public function upgradeFull($amount){

        if(auth()->user()->wallet->balance < $amount){

            return back()->with('error', 'Insurficent balance, please top up wallet to continue!');
            
        }
        
        $ref = time();
        debitWallet(auth()->user(), 'Naira', $amount);

        $user = User::where('id', auth()->user()->id)->first();
        $user->is_verified = true;
        $user->save(); //naira verification
       
        $usd_Verified = Usdverified::create(['user_id' => auth()->user()->id]);

       
        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => $amount+50,
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'upgrade_payment_naira_dollar',
            'description' => 'Dollar Upgrade Payment - Naira',
            'tx_type' => 'Debit',
            'user_type' => 'regular'
        ]);

        $referee = Referral::where('user_id',  $user->id)->first();
            if($referee){

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
                    'balance' => walletBalance($referee->user_id),
                    'status' => 'successful',
                    'currency' => 'USD',
                    'channel' => 'paystack',
                    'type' => 'usd_referer_bonus',
                    'tx_type' => 'Credit',
                    'description' => 'USD Referral Bonus from '.$user->name
                ]);
            }

            $name = auth()->user()->name;
            activityLog(auth()->user(), 'dollar_account_verification', $name .' account verification', 'regular');
                    
            systemNotification($user, 'success', 'Verification', 'Dollar Account Verification Successful');
    
            Mail::to(auth()->user()->email)->send(new UpgradeUser($user));
        return redirect('success');
      
    }

    public function completeUpgrade(){
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        $ref = $params['trxref']; //paystack
        $res = verifyTransaction($ref);

        $statusVerification = $res['data']['status'];
        $statusAmount = $res['data']['amount'];
    
        $checkTransaction = PaymentTransaction::where('reference', $ref)->first();
        if($checkTransaction->status == 'unsuccessful'){
           if($statusVerification == 'success'){
                paymentUpdate($ref, 'successful'); //update transaction
                
                $user = User::where('id', auth()->user()->id)->first();
                $user->is_verified = true;
                $user->save(); //naira verification
               
                Usdverified::create(['user_id' => auth()->user()->id]); //usd verification

                $name = auth()->user()->name;
                activityLog(auth()->user(), 'account_verification', $name .' account verification', 'regular');
                
                systemNotification($user, 'success', 'Verification', 'Dollar Account Verification Successful');

                Mail::to(auth()->user()->email)->send(new UpgradeUser($user));
               return redirect('success');

                //$referee = \DB::table('referral')->where('user_id',  auth()->user()->id)->first();
    
            //    if($referee){

            //     $wallet = Wallet::where('user_id', $referee->referee_id)->first();
            //     $wallet->balance += 500;
            //     $wallet->save();
               
            //     $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
            //     $refereeUpdate->is_paid = true;
            //     $refereeUpdate->save();

            //     ///Transactions
            //     $description = 'Referer Bonus from '.auth()->user()->name;
            //     PaystackHelpers::paymentTrasanction($referee->referee_id, '1', time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'regular');
  
            //     $adminWallet = Wallet::where('user_id', '1')->first();
            //     $adminWallet->balance += 500;
            //     $adminWallet->save();

            //     //Admin Transaction Table
            //     $description = 'Referer Bonus from '.auth()->user()->name;
            //      PaystackHelpers::paymentTrasanction(1, 1, time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'admin');
            //    }else{
            //     $adminWallet = Wallet::where('user_id', '1')->first();
            //     $adminWallet->balance += 1000;
            //     $adminWallet->save();
            //      //Admin Transaction Tablw
            //     $description = 'Direct Referer Bonus from '.auth()->user()->name;
            //     PaystackHelpers::paymentTrasanction(1, '1', time(), 1000, 'successful', 'direct_referer_bonus', $description, 'Credit', 'admin');
            //    }
               

            }else{
                return redirect('upgrade');
            }
    
        }else{
            return redirect('success');
        }
        


    }

    public function makeUpgradeForeign(){
        $user = Auth::user();
        $currency = baseCurrency();
        $parameter = currencyParameter($currency);

        $checkWalletBalance = checkWalletBalance($user, $currency, $parameter->upgrade_fee);
        if($checkWalletBalance){
         
                $upgrade = userForeignUpgrade($user, $currency, $parameter->upgrade_fee, $parameter->referral_commission);

                if($upgrade){
                    Mail::to(auth()->user()->email)->send(new UpgradeUser(auth()->user()));
                    return back()->with('success', 'Upgrade Successful');
                }else{
                    return back()->with('error', 'An Error occoured while upgrading your Account');
                }
         
        }else{
            return redirect('wallet/fund')->with('error', 'You do not have enough funds in your wallet, kindly top up your wallet below');
        }

    }

    public function makePayment()
    {
       $user = Auth::user();
        @$referee_id = Referral::where('user_id', $user->id)->first()->referee_id;
        @$profile_celebrity = Profile::where('user_id', $referee_id)->first()->is_celebrity;
        $amount = 0;
        
        if($profile_celebrity){
            $amount = 920;
        }else{
            $amount = 1050;
        }
        
        // if(auth()->user()->wallet->base_currency == 'Naira'){
        //     $ref = time();
        //     $url = initiateTrasaction($ref, $amount, '/upgrade/payment');
        //     paymentTrasanction(auth()->user()->id, '1', $ref, $amount, 'unsuccessful', 'upgrade_payment', 'Upgrade Payment-Paystack', 'Payment_Initiation', 'regular');
        //     return redirect($url);
        // }else{
            $user = Auth::user();
            $currency = baseCurrency();
            $parameter = currencyParameter($currency);

            $checkWalletBalance = checkWalletBalance($user, $currency, $parameter->upgrade_fee);
            // $checkWalletBalance = checkWalletBalance(auth()->user(), 'Dollar', 5);
            if($checkWalletBalance){
                $debitWallet = debitWallet($user, $currency, $parameter->upgrade_fee);
                if($debitWallet){
                    $upgrade = userDollaUpgrade($user);
                    if($upgrade){
                        Mail::to(auth()->user()->email)->send(new UpgradeUser(auth()->user()));
                        return back()->with('success', 'Upgrade Successful');
                    }
                }
               
            }else{
                return redirect('wallet/fund')->with('error', 'You do not have enough funds in your wallet, kindly top up your wallet below');

                // return back()->with('error', 'You do not have enough funds in your dollar wallet');
            }
           
            

            // return redirect('https://flutterwave.com/pay/topuponfreebyz');
            
            // $percent = 5/100 * 2;
            // $am = 5 + $percent + 1;
           
            // $result = paypalPayment($am, '/capture/upgrade');
            //  if($result['status'] == 'CREATED'){
            //     $url = $result['links'][1]['href'];
            //     PaystackHelpers::paymentTrasanction(auth()->user()->id, '1', $result['id'], 5, 'unsuccessful', 'upgrade_payment_usd', 'Upgrade Payment - USD', 'Payment_Initiation', 'regular');
            //     return redirect($url);
            //  }
        // } 
    }

    public function captureUpgrade(){
        // $url = request()->fullUrl();
        // $url_components = parse_url($url);
        // parse_str($url_components['query'], $params);

        // $id = $params['token'];

        //   $response = capturePaypalPayment($id);

        // if($response['status'] == 'COMPLETED'){

        //     $ref = $response['purchase_units'][0]['reference_id'];

        //     $update = PaymentTransaction::where('reference', $response['id'])->first();
        //     $update->status = 'successful';
        //     $update->reference = $response['purchase_units'][0]['reference_id'];
        //     $update->save();

        //     $user = User::where('id', auth()->user()->id)->first();
        //     $user->is_verified = true;
        //     $user->save();
        //     Usdverified::create(['user_id' => auth()->user()->id]); //usd verification
        //     $name = SystemActivities::getInitials(auth()->user()->name);
        //     SystemActivities::activityLog(auth()->user(), 'account_verification', $name .' account verification', 'regular');

        //     systemNotification($user, 'success', 'Verification', '$'.$update->amount.' Account Verification Successful');
        //     return redirect('success');
        // }else{
        //     return redirect('error');
        // }
    }

    public function upgradeCallback()
    {
        // $url = request()->fullUrl();
        // $url_components = parse_url($url);
        // parse_str($url_components['query'], $params);

        // $ref = $params['trxref']; //paystack
        // $res = PaystackHelpers::verifyTransaction($ref);
      
        // $statusVerification = $res['data']['status'];
    
        // $checkCount = PaymentTransaction::where('reference', $ref)->first();
        // if($checkCount->status == 'unsuccessful'){
        //    if($statusVerification == 'success'){
        //         PaystackHelpers::paymentUpdate($ref, 'successful'); //update transaction
                
        //         $name = SystemActivities::getInitials(auth()->user()->name);
        //         SystemActivities::activityLog(auth()->user(), 'account_verification', $name .' account verification', 'regular');
        //         $user = User::where('id', auth()->user()->id)->first();
        //         $user->is_verified = true;
        //         $user->save();
    
        //         $referee = \DB::table('referral')->where('user_id',  auth()->user()->id)->first();
    
        //        if($referee){

        //             $refereeInfo = Profile::where('user_id', $referee->referee_id)->first()->is_celebrity;

        //             if(!$refereeInfo){
        //                 $wallet = Wallet::where('user_id', $referee->referee_id)->first();
        //                 $wallet->balance += 500;
        //                 $wallet->save();
                    
        //                 $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
        //                 $refereeUpdate->is_paid = true;
        //                 $refereeUpdate->save();
        
        //                 ///Transactions
        //                 $description = 'Referer Bonus from '.auth()->user()->name;
        //                 PaystackHelpers::paymentTrasanction($referee->referee_id, '1', time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'regular');
        
        //                 $adminWallet = Wallet::where('user_id', '1')->first();
        //                 $adminWallet->balance += 500;
        //                 $adminWallet->save();
        
        //                 //Admin Transaction Table
        //                 $description = 'Referer Bonus from '.auth()->user()->name;
        //                 PaystackHelpers::paymentTrasanction(1, 1, time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'admin');
                    
        //             }else{
        //                 $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
        //                 $refereeUpdate->is_paid = true;
        //                 $refereeUpdate->save();
        //             }


        //         }else{
        //             $adminWallet = Wallet::where('user_id', '1')->first();
        //             $adminWallet->balance += 1000;
        //             $adminWallet->save();
        //             //Admin Transaction Tablw
        //             $description = 'Direct Referer Bonus from '.auth()->user()->name;
        //             PaystackHelpers::paymentTrasanction(1, '1', time(), 1000, 'successful', 'direct_referer_bonus', $description, 'Credit', 'admin');
        //        }
        //             Mail::to(auth()->user()->email)->send(new UpgradeUser($user));
        //             return redirect('success');

        //     }else{
        //         return redirect('upgrade');
        //     }
    
        // }else{
        //     return redirect('success');
        // }
        
    }

    public function makePaymentWallet()
    {

        $user = Auth::user();
        $userBaseCurrency = baseCurrency($user);

        $currencyParams = currencyParameter($userBaseCurrency);
        $upgradeAmount = $currencyParams->upgrade_fee;
        $referral_commission = $currencyParams->referral_commission;

        

        $amount = $user->wallet->balance;

        if($amount >= $upgradeAmount){

            $debitWallet = debitWallet($user, 'NGN', $upgradeAmount);

            if($debitWallet){
                                
                $upgrdate = userNairaUpgrade($user, $upgradeAmount, $referral_commission);

                if($upgrdate){
                    // Mail::to($user->email)->send(new UpgradeUser($user));
                }
                
            }

            return back()->with('success', 'Verification Successfull');
            
        }else{
            return back()->with('error', 'Your balance is too low, please top up your account to get verified');
        }


        
        // $balance = Sendmonny::getUserBalance(GetSendmonnyUserId(), accessToken());
        
        // if($balance >= 1000){
        //     $payload = [
        //         "sender_wallet_id" => GetSendmonnyUserWalletId(),
        //         "sender_user_id" => GetSendmonnyUserId(),
        //         "amount" => 1000,
        //         "pin"=> "2222",
        //         "narration" => "Freebyz Verification - Sendmonny",
        //         "islocal" => true,
        //         "reciever_wallet_id" => adminRevenue()['wallet_id']//"7f23a522-01ca-4337-98e9-83ae80f3b69a"
        //     ];
        //     ///process withdrawal
        //      $transfer = Sendmonny::transfer($payload, accessToken());
        //     if($transfer['status'] == true){
        //         //return 'Successful';
        //         $ref = time(); //$transfer['status']['data']['reference'];
        //         $name = SystemActivities::getInitials(auth()->user()->name);
        //         SystemActivities::activityLog(auth()->user(), 'account_verification', $name .' account verification', 'regular');
        //         PaystackHelpers::paymentTrasanction(auth()->user()->id, '1', $ref, 1000, 'successful', 'upgrade_payment', 'Upgrade Payment', 'Payment_Initiation', 'regular');
                
        //         $user = User::where('id', auth()->user()->id)->first();
        //         $user->is_verified = true;
        //         $user->save();
        //         return redirect('success');
        //     }
        // }else{
        //     return back()->with('error', 'Your balance is too low');
        // }

        // $user = Auth::user();
        // @$referee_id = Referral::where('user_id', $user->id)->first()->referee_id;
        // @$profile_celebrity = Profile::where('user_id', $referee_id)->first()->is_celebrity;
        // $amount = 0;
        // if($profile_celebrity){
        //     $amount = 920;
        // }else{
        //     $amount = 1050;
        // }
        
        // if(auth()->user()->wallet->balance >= $amount){

        
        //     $ref = time();
       
        //  debitWallet(auth()->user(), 'NGN', $amount);

        //  $user = User::where('id', auth()->user()->id)->first();
        //  $user->is_verified = true;
        //  $user->save();

        
        //  paymentTrasanction(auth()->user()->id, '1', $ref, $amount, 'successful', 'upgrade_payment', 'Upgrade Payment', 'Debit', 'regular');
          

        //    $referee = \DB::table('referral')->where('user_id',  auth()->user()->id)->first();
           
        //    if($referee){
        //     $refereeInfo = Profile::where('user_id', $referee->referee_id)->first()->is_celebrity;

        //         if(!$refereeInfo){
        //             $wallet = Wallet::where('user_id', $referee->referee_id)->first();
        //             $wallet->balance += 500;
        //             $wallet->save();
                
        //             $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
        //             $refereeUpdate->is_paid = true;
        //             $refereeUpdate->save();
    
        //             ///Transactions
        //             $description = 'Referer Bonus from '.auth()->user()->name;
        //             paymentTrasanction($referee->referee_id, '1', time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'regular');
    
        //             $adminWallet = Wallet::where('user_id', '1')->first();
        //             $adminWallet->balance += 500;
        //             $adminWallet->save();
    
        //             //Admin Transaction Table
        //             $description = 'Referer Bonus from '.auth()->user()->name;
        //             paymentTrasanction(1, 1, time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'admin');
                
        //         }else{
        //             $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
        //             $refereeUpdate->is_paid = true;
        //             $refereeUpdate->save();
        //         }

            
        //    }else{
        //     $adminWallet = Wallet::where('user_id', '1')->first();
        //     $adminWallet->balance += 1000;
        //     $adminWallet->save();
        //      //Admin Transaction Tablw
        //      PaymentTransaction::create([
        //         'user_id' => 1,
        //         'campaign_id' => '1',
        //         'reference' => $ref,
        //         'amount' => 1000,
        //         'status' => 'successful',
        //         'currency' => 'NGN',
        //         'channel' => 'paystack',
        //         'type' => 'direct_referer_bonus',
        //         'description' => 'Direct Referer Bonus from '.$user->name,
        //         'tx_type' => 'Credit',
        //         'user_type' => 'admin'
        //     ]);
        //    }

        //    $name = auth()->user()->name;
        //    activityLog(auth()->user(), 'account_verification', $name .' account verification', 'regular');
           
        //    Mail::to(auth()->user()->email)->send(new UpgradeUser($user));
        //    return redirect('success');
        // }else{
        //     return back()->with('error', 'Your balance is too low');
        //     // return redirect('error');
        // }


    } 

    

    public function success()
    {
        
        return view('user.success');
    }

    public function error()
    {
        return view('user.error');
    }

    public function info(){
        return view('user.info');
    }

    public function conversion(){

        return view('user.conversion');
    }

    public function transactions()
    {
        $list = PaymentTransaction::where('user_id', auth()->user()->id)->where('status', 'successful')->where('user_type', 'regular')->orderBy('created_at', 'DESC')->paginate(10);
        return view('user.transactions', ['lists' => $list]);
    }

    public function withdrawal_requests()
    {
        $list = Withrawal::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('user.wallet.withdrawal_requests', ['lists' => $list]);
    }

    public function christmasBundle(){
        return view('user.christmas');
    }

    public function storeChristmasBundle(Request $request){
        $request->validate([
            'phone' => 'required|digits:11|numeric',
            'address' => 'required',
        ]);

        $profile = Profile::where('user_id', auth()->user()->id)->first();
        $profile->address = $request->address;
        $profile->is_xmas = true;
        $profile->save();

        return  back()->with('success', 'Info. created successfully');
    }
    
    public function airtimePurchase()
    {
        return view('user.wallet.buy_airtime');
    }

    public function databundlePurchase(){
        //return CapitalSage::access_token();
        $databundles = DataBundle::orderby('name', 'ASC')->get();
        return view('user.wallet.data_bundle', ['databundles'=>$databundles]);
    }

    public function loadData($network){
        //$net;
        // $access_token = PaystackHelpers::access_token();
        // return PaystackHelpers::loadNetworkData($access_token, $network);
    }

    public function buyAirtime(Request $request){
        $request->validate([
            'amount' => 'required|numeric|max:100',
            'phone' => 'required|numeric|digits:11'
        ]);
        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        if($request->amount > $wallet->balance){
            return back()->with('error', 'Insurficient balance...');
        }
        // return back()->with('error', 'The service is currenctly not available, please check back later');
       
        $occurence = PaymentTransaction::where('user_id', auth()->user()->id)->where('type', 'airtime_purchase')->whereDate('created_at', Carbon::today())->sum('amount');
        if($occurence >= 200){
            return back()->with('error', 'You have reached your airtime limit today. Try again tomorrow');
        }

        $ref = time();
        $balance = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->get('https://api.flutterwave.com/v3/balances/NGN')->throw()['data']['available_balance'];

        if($request->amount > $balance){
            return back()->with('error', 'An Error Occour while processing airtime');
        }
       
        $payload = [
            "country"=> "NG",
            "customer"=> '+234'.substr($request->phone, 1),
            "amount"=> $request->amount,
            "type"=> "AIRTIME",
            "reference"=> $ref
            
            // "reference"=>Str::random(7),
            // "network"=>$request->network,
            // "service"=>$request->network."VTU",
            // "phone"=>'234'.$request->phone,
            // "amount"=>$request->amount,
        ];

       
      return $res =  Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/bills', $payload)->throw();

        // $access_token = PaystackHelpers::access_token();
        // $res = PaystackHelpers::buyAirtime($payload, $access_token);

        if($res['status'] == 'success'){

            $wallet->balance -= $request->amount;
            $wallet->save();

             //Admin Transaction Table
             PaymentTransaction::create([
                'user_id' => auth()->user()->id,
                'campaign_id' => '1',
                'reference' =>$ref,
                'amount' => $request->amount,
                'balance' => walletBalance(auth()->user()->id),
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'capital_sage',
                'type' => 'airtime_purchase',
                'description' => 'Airtime Purchase from '.auth()->user()->name,
                'tx_type' => 'Debit',
                'user_type' => 'regular'
            ]);
            return back()->with('success', 'Airtime Successfully sent');
        }else{
            return back()->with('error', 'An error occoured while processing your airtime');
        }
    }

    public function buyDatabundle(Request $request){
       
        $values = explode(':',$request->code);
        $code = $values['0'];
        $amount = $values['1'];

        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        if($wallet->balance <=  $amount)
        {
            return back()->with('error', 'Insufficient fund in your wallet');
        }
        
        $ref = time();
        // $access_token = PaystackHelpers::access_token();
        $network = $request->network.'DATA';
        $provider = $request->network;
        // $response = PaystackHelpers::purchaseData($access_token, $code, $network, $provider, $request->phone, $ref);
        
        // if($response['status'] == 'success'){
        //     $wallet->balance -= $amount; ///debit wallet
        //     $wallet->save();
        //     PaymentTransaction::create([
        //         'user_id' => auth()->user()->id,
        //         'campaign_id' => '1',
        //         'reference' => $ref,
        //         'amount' => $amount,
        //         'status' => 'successful',
        //         'currency' => 'NGN',
        //         'channel' => 'capital_sage',
        //         'type' => 'databundle',
        //         'description' => $provider.' data purchase', //$gig.' sent to '.$request->phone.' for Databundle Purchase',
        //         'tx_type' => 'Debit',
        //         'user_type' => 'regular'
        //     ]);
        //     return back()->with('success', 'Databundle processed successfully');
        // }else{
        //     return back()->with('error', 'An error Occoured');
        // }

       
        
    }

    // public function sendNotification($message)
    // {
    //     $res = Http::withHeaders([
    //         'Accept' => 'application/json',
    //         'Content-Type' => 'application/json',
    //     ])->post('https://api.ng.termii.com/api/sms/send', [
    //         "to"=> '2349153590716',//2349153590716$number,
    //         "from"=> "FREEBYZ",
    //         "sms"=> $message,
    //         "type"=> "plain",
    //         "channel"=> "generic",
    //         "api_key"=> env('TERMI_KEY')
    //     ]);

    //      return json_decode($res->getBody()->getContents(), true);
    // }

}
