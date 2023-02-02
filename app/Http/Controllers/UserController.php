<?php

namespace App\Http\Controllers;

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
        
        // $res = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        // ])->post('https://api.paystack.co/transaction/initialize', [
        //     'email' => auth()->user()->email,
        //     'amount' => 515*100,
        //     'channels' => ['card'],
        //     'currency' => 'NGN',
        //     'reference' => $ref,
        //     'callback_url' => env('PAYSTACK_CALLBACK_URL').'/upgrade/payment'
        // ]);
        // $url = $res['data']['authorization_url'];

        $res = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
            ])->post('https://api.flutterwave.com/v3/payments', [

                // 'email' => auth()->user()->email,
                // 'amount' => 515*100,
                // 'channels' => ['card'],
                // 'currency' => 'NGN',
                // 'reference' => $ref,
                // 'callback_url' => env('PAYSTACK_CALLBACK_URL').'/upgrade/payment'


                'tx_ref'=> $ref,
                'amount' => "515",
                'currency' => "NGN",
                'redirect_url' => env('PAYSTACK_CALLBACK_URL').'/upgrade/payment',//"https://webhook.site/9d0b00ba-9a69-44fa-a43d-a82c33c36fdc",
                'meta'=> [
                    'consumer_id'=> auth()->user()->id,
                    'consumer_mac'=> "92a3-912ba-1192a"
                ],
                
                
                'customer' => [
                    'email'=> auth()->user()->email,
                    'phonenumber'=> auth()->user()->phone,
                    'name'=> auth()->user()->name
                ],
                'customizations' => [
                'title'=> "Account Activation Fee",
                'logo'=> "https://scontent-lhr8-2.xx.fbcdn.net/v/t39.30808-6/299480030_186914963695163_5730832757031573548_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=YorxiJMZ-TYAX-ozGv0&_nc_ht=scontent-lhr8-2.xx&oh=00_AfDNs_jlMvbCpF2_uZz0Fjh0G0J-jp5Fg3eWWkJ_YE953Q&oe=63E11ACD"
                ]
            ]);

             $url = $res['data']['link'];
        

        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => 500,
            'status' => 'unsuccessful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'upgrade_payment',
            'description' => 'Ugrade Payment'
        ]);
         return redirect($url);
        //return $res;
    }

    public function upgradeCallback()
    {
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        $ref = $params['tx_ref'];
        $status = $params['status'];
       

        // $ref = $params['trxref']; //paystack
        //https://api.flutterwave.com/v3/hosted/pay/f524c1196ffda5556341
        // $res = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        // ])->get('https://api.paystack.co/transaction/verify/'.$ref)->throw();

        if($status == 'successful'){

            $transactionId = $params['transaction_id'];
            $res = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
            ])->get('https://api.flutterwave.com/v3/transactions/'.$transactionId.'/verify')->throw();
    
            $statusVerification = $res['data']['status'];
    
           if($statusVerification == 'successful')
           {
                $fetchPaymentTransaction = PaymentTransaction::where('reference', $ref)->first();
                $fetchPaymentTransaction->status = 'successful';
                $fetchPaymentTransaction->save();
    
                //credit User with 1,000 bonus
                $bonus = Wallet::where('user_id', auth()->user()->id)->first();
                $bonus->bonus += '1000';
                $bonus->save();
    
                if($bonus){
                     //user transction table for bonus 
                    PaymentTransaction::create([
                        'user_id' => auth()->user()->id,
                        'campaign_id' => '1',
                        'reference' => time(),
                        'amount' => 1000,
                        'status' => 'successful',
                        'currency' => 'NGN',
                        'channel' => 'paystack',
                        'type' => 'upgrade_bonus',
                        'description' => 'Verification Bonus for '.auth()->user()->name,
                        'tx_type' => 'Credit',
                        'user_type' => 'regular'
                    ]);
                }
               
                $user = User::where('id', auth()->user()->id)->first();
                $user->is_verified = true;
                $user->save();
    
               $referee = \DB::table('referral')->where('user_id',  auth()->user()->id)->first();
    
               if($referee){
                $wallet = Wallet::where('user_id', $referee->referee_id)->first();
                $wallet->balance += '250';
                $wallet->save();
    
                $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
                $refereeUpdate->is_paid = true;
                $refereeUpdate->save();
    
                $referee_user = User::where('id', $referee->referee_id)->first();
                ///Transactions
                PaymentTransaction::create([
                    'user_id' => $referee_user->id,///auth()->user()->id,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => 250,
                    'status' => 'successful',
                    'currency' => 'NGN',
                    'channel' => 'paystack',
                    'type' => 'referer_bonus',
                    'description' => 'Referer Bonus from '.auth()->user()->name
                ]);
    
                $adminWallet = Wallet::where('user_id', '1')->first();
                $adminWallet->balance += 250;
                $adminWallet->save();
                //Admin Transaction Tablw
                PaymentTransaction::create([
                    'user_id' => 1,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => 250,
                    'status' => 'successful',
                    'currency' => 'NGN',
                    'channel' => 'paystack',
                    'type' => 'referer_bonus',
                    'description' => 'Referer Bonus from '.$user->name,
                    'tx_type' => 'Credit',
                    'user_type' => 'admin'
                ]);
    
               }else{
    
                $adminWallet = Wallet::where('user_id', '1')->first();
                $adminWallet->balance += 500;
                $adminWallet->save();
                 //Admin Transaction Tablw
                 PaymentTransaction::create([
                    'user_id' => 1,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => 500,
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
                return redirect('error');
           }

        }else{
            return redirect('upgrade');
        }
         

    }

    public function makePaymentWallet()
    {
        $ref = time();
        $bonus = Wallet::where('user_id', auth()->user()->id)->first();
         //debit  User wallet first
         $bonus->balance -= '500';
         $bonus->save();
        //credit User with 1,000 bonus
        $bonus->bonus += '1000';
        $bonus->save();

        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => 500,
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'wallet',
            'type' => 'upgrade_payment_wallet',
            'description' => 'Ugrade Payment',
            'tx_type' => 'Debit',
            'user_type' => 'regular'
        ]);

        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => '1',
            'reference' => time(),
            'amount' => 1000,
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'upgrade_bonus',
            'description' => 'Verification Bonus for '.auth()->user()->name,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);


           $user = User::where('id', auth()->user()->id)->first();
           $user->is_verified = true;
           $user->save();

           $referee = \DB::table('referral')->where('user_id',  auth()->user()->id)->first();
           
           if($referee){
            $wallet = Wallet::where('user_id', $referee->referee_id)->first();
            $wallet->balance += '250';
            $wallet->save();

            $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
            $refereeUpdate->is_paid = true;
            $refereeUpdate->save();

            $referee_user = User::where('id', $referee->referee_id)->first();
            ///Transactions
            PaymentTransaction::create([
                'user_id' => $referee_user->id,///auth()->user()->id,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => 250,
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'paystack',
                'type' => 'referer_bonus',
                'description' => 'Referer Bonus from '.auth()->user()->name
            ]);

            $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->balance += 250;
            $adminWallet->save();
            //Admin Transaction Tablw
            PaymentTransaction::create([
                'user_id' => 1,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => 250,
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'paystack',
                'type' => 'referer_bonus',
                'description' => 'Referer Bonus from '.$user->name,
                'tx_type' => 'Credit',
                'user_type' => 'admin'
            ]);

           }else{

            $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->balance += 500;
            $adminWallet->save();
             //Admin Transaction Tablw
             PaymentTransaction::create([
                'user_id' => 1,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => 500,
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
        $list = PaymentTransaction::where('user_id', auth()->user()->id)->where('user_type', 'regular')->orderBy('created_at', 'DESC')->get();
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
        $databundles = DataBundle::orderby('name', 'ASC')->get();
        return view('user.wallet.data_bundle', ['databundles'=>$databundles]);
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
        ];
       $res =  Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/bills', $payload)->throw();
        // return $res;

        if($res['status'] == 'success'){
            $wallet->balance -= $request->amount;
            $wallet->save();

             //Admin Transaction Tablw
             PaymentTransaction::create([
                'user_id' => auth()->user()->id,
                'campaign_id' => '1',
                'reference' =>$ref,
                'amount' => $request->amount,
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'flutterwave',
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
        $values = explode(':',$request->name);
        $gig = $values['0'];
        $amount = $values['1'];

        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        if($wallet->balance <=  $amount)
        {
            return back()->with('error', 'Insufficient fund in your wallet');
        }
        $wallet->balance -= $amount; ///debit wallet
        $wallet->save();
        $ref = time();
        $message = auth()->user()->name." REQUEST ".$gig." DATABUNDLE FOR  ".$request->phone.". WITH .".$ref." REF HAS BEEN QUEUED"; //"A ".$gig. " GIG SME DATA REQUEST FROM ".$request->phone." AT ".$amount." NGN HAS BEEN QUEUED";
        return $this->sendNotification($message);

        PaymentTransaction::create([
            'user_id' => 1,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => $amount,
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'termii',
            'type' => 'databundle',
            'description' => $gig.' sent to '.$request->phone.' for Databundle Purchase',
            'tx_type' => 'Debit',
            'user_type' => 'regular'
        ]);
        return back()->with('error', 'Databundle has been queued, you will recieve it shortly');
    }

    public function sendNotification($message)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/send', [
            "to"=> '2349153590716',//2349153590716$number,
            "from"=> "FREEBYZ",
            "sms"=> $message,
            "type"=> "plain",
            "channel"=> "generic",
            "api_key"=> env('TERMI_KEY')
        ]);

         return json_decode($res->getBody()->getContents(), true);
    }

}
