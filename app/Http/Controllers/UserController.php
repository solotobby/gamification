<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Referral;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => auth()->user()->email,
            'amount' => 515*100,
            'channels' => ['card'],
            'currency' => 'NGN',
            'reference' => $ref,
            'callback_url' => env('PAYSTACK_CALLBACK_URL').'/upgrade/payment'
        ]);
        $url = $res['data']['authorization_url'];
        
        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => 500,
            'status' => 'unsuccessful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'campaign_payment',
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
        $ref = $params['trxref'];

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->get('https://api.paystack.co/transaction/verify/'.$ref)->throw();

       $status = $res['data']['status'];

       if($status == 'success')
       {
            $fetchPaymentTransaction = PaymentTransaction::where('reference', $ref)->first();
            $fetchPaymentTransaction->status = 'successful';
            $fetchPaymentTransaction->save();

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
           return redirect('success');

       }else{
            return redirect('error');
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
        $list = PaymentTransaction::where('user_id', auth()->user()->id)->where('user_type', 'regular')->orderBy('created_at', 'desc')->get();
        return view('user.transactions', ['lists' => $list]);
    }

    public function withdrawal_requests()
    {
        $list = Withrawal::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return view('user.wallet.withdrawal_requests', ['lists' => $list]);
    }

}
