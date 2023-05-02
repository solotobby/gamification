<?php

namespace App\Http\Controllers;

use App\Helpers\CapitalSage;
use App\Helpers\PaystackHelpers;
use App\Mail\UpgradeUser;
use App\Models\DataBundle;
use App\Models\PaymentTransaction;
use App\Models\Referral;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    public function makePayment()
    {
        $ref = time();
        $url = PaystackHelpers::initiateTrasaction($ref, 1050, '/upgrade/payment');
        PaystackHelpers::paymentTrasanction(auth()->user()->id, '1', $ref, 1000, 'unsuccessful', 'upgrade_payment', 'Upgrade Payment', 'Payment_Initiation', 'regular');
        return redirect($url);
       
    }

    public function upgradeCallback()
    {
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);

        $ref = $params['trxref']; //paystack
        $res = PaystackHelpers::verifyTransaction($ref);
      
        $statusVerification = $res['data']['status'];
    
        $checkCount = PaymentTransaction::where('reference', $ref)->first();
        if($checkCount->status == 'unsuccessful'){
           if($statusVerification == 'success'){
            PaystackHelpers::paymentUpdate($ref, 'successful'); //update transaction

                $user = User::where('id', auth()->user()->id)->first();
                $user->is_verified = true;
                $user->save();
    
                $referee = \DB::table('referral')->where('user_id',  auth()->user()->id)->first();
    
               if($referee){

                $wallet = Wallet::where('user_id', $referee->referee_id)->first();
                $wallet->balance += 500;
                $wallet->save();
               
                $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
                $refereeUpdate->is_paid = true;
                $refereeUpdate->save();

                ///Transactions
                $description = 'Referer Bonus from '.auth()->user()->name;
                PaystackHelpers::paymentTrasanction($referee->referee_id, '1', time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'regular');
  
                $adminWallet = Wallet::where('user_id', '1')->first();
                $adminWallet->balance += 500;
                $adminWallet->save();

                //Admin Transaction Table
                $description = 'Referer Bonus from '.auth()->user()->name;
                 PaystackHelpers::paymentTrasanction(1, 1, time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'admin');
               }else{
                $adminWallet = Wallet::where('user_id', '1')->first();
                $adminWallet->balance += 1000;
                $adminWallet->save();
                 //Admin Transaction Tablw
                $description = 'Direct Referer Bonus from '.auth()->user()->name;
                PaystackHelpers::paymentTrasanction(1, '1', time(), 1000, 'successful', 'direct_referer_bonus', $description, 'Credit', 'admin');
               }
               Mail::to(auth()->user()->email)->send(new UpgradeUser($user));
               return redirect('success');

            }else{
            return redirect('upgrade');
            }
    
        }else{
            return redirect('success');
        }
        
    }

    public function makePaymentWallet()
    {
        if(auth()->user()->wallet->balance >= 1050){

        
        $ref = time();
        $userWallet = Wallet::where('user_id', auth()->user()->id)->first();
         //debit  User wallet first
         $userWallet->balance -= '1050';
         $userWallet->save();

        //credit User with 1,000 bonus
        // $bonus->bonus += '1000';
        // $bonus->save();
        PaystackHelpers::paymentTrasanction(auth()->user()->id, '1', $ref, 1000, 'successful', 'upgrade_payment', 'Upgrade Payment', 'Payment_Initiation', 'regular');
        
           $user = User::where('id', auth()->user()->id)->first();
           $user->is_verified = true;
           $user->save();

           $referee = \DB::table('referral')->where('user_id',  auth()->user()->id)->first();
           
           if($referee){
            $wallet = Wallet::where('user_id', $referee->referee_id)->first();
            $wallet->balance += 500;
            $wallet->save();

            $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
            $refereeUpdate->is_paid = true;
            $refereeUpdate->save();

            ///Transactions
            $description = 'Referer Bonus from '.auth()->user()->name;
            PaystackHelpers::paymentTrasanction($referee->referee_id, '1', time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'regular');


            $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->balance += 500;
            $adminWallet->save();

            //Admin Transaction Table
            $description = 'Referer Bonus from '.auth()->user()->name;
            PaystackHelpers::paymentTrasanction(1, 1, time(), 500, 'successful', 'referer_bonus', $description, 'Credit', 'admin');

           }else{
            $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->balance += 1000;
            $adminWallet->save();
             //Admin Transaction Tablw
             PaymentTransaction::create([
                'user_id' => 1,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => 1000,
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'paystack',
                'type' => 'direct_referer_bonus',
                'description' => 'Direct Referer Bonus from '.$user->name,
                'tx_type' => 'Credit',
                'user_type' => 'admin'
            ]);
           }
           Mail::to(auth()->user()->email)->send(new UpgradeUser($user));
           return redirect('success');
        }else{
            return back()->with('error', 'Your balance is too low');
            // return redirect('error');
        }
    }

    public function success()
    {
        
        return view('user.success');
    }

    public function error()
    {
        return view('user.error');
    }

    public function transactions()
    {
        $list = PaymentTransaction::where('user_id', auth()->user()->id)->where('status', 'successful')->where('user_type', 'regular')->orderBy('created_at', 'DESC')->get();
        return view('user.transactions', ['lists' => $list]);
    }

    public function withdrawal_requests()
    {
        $list = Withrawal::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return view('user.wallet.withdrawal_requests', ['lists' => $list]);
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
        //$network;
        $access_token = PaystackHelpers::access_token();
        return PaystackHelpers::loadNetworkData($access_token, $network);
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
        
        // $balance = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json-patch+json',
        //     'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        //  ])->get('https://api.flutterwave.com/v3/balances/NGN')->throw()['data']['available_balance'];

        // if($request->amount > $balance){
        //     return back()->with('error', 'An Error Occour while processing airtime');
        // }
       
        $payload = [
            // "country"=> "NG",
            // "customer"=> '+234'.substr($request->phone, 1),
            // "amount"=> $request->amount,
            // "type"=> "AIRTIME",
            // "reference"=> $ref
            
            "reference"=>Str::random(7),
            "network"=>$request->network,
            "service"=>$request->network."VTU",
            "phone"=>$request->phone,
            "amount"=>$request->amount,
        ];

       
    //   return $res =  Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //         'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
    //     ])->post('https://api.flutterwave.com/v3/bills', $payload)->throw();

        return $access_token = PaystackHelpers::access_token();
        return $res = PaystackHelpers::buyAirtime($payload, $access_token);

        if($res['status'] == 'success'){

            $wallet->balance -= $request->amount;
            $wallet->save();

             //Admin Transaction Table
             PaymentTransaction::create([
                'user_id' => auth()->user()->id,
                'campaign_id' => '1',
                'reference' =>$ref,
                'amount' => $request->amount,
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
        $access_token = PaystackHelpers::access_token();
        $network = $request->network.'DATA';
        $provider = $request->network;
        $response = PaystackHelpers::purchaseData($access_token, $code, $network, $provider, $request->phone, $ref);
        
        if($response['status'] == 'success'){
            $wallet->balance -= $amount; ///debit wallet
            $wallet->save();
            PaymentTransaction::create([
                'user_id' => auth()->user()->id,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => $amount,
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'capital_sage',
                'type' => 'databundle',
                'description' => $provider.' data purchase', //$gig.' sent to '.$request->phone.' for Databundle Purchase',
                'tx_type' => 'Debit',
                'user_type' => 'regular'
            ]);
            return back()->with('success', 'Databundle processed successfully');
        }else{
            return back()->with('error', 'An error Occoured');
        }

       
        
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
