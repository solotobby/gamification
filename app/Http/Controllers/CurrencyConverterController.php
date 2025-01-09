<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class CurrencyConverterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('user.converter.index');
    }

    public function nairaDollar(){
        return naira_dollar();
    }

    public function dollarNaira(){
        return dollar_naira();
    }

   

    public function makeConversion(Request $request){
     
        $label = $request->label;
        if($label == 'naira'){

            $request->validate([
                'amount' => 'required|numeric|min:2000'
            ]);

            $balance =auth()->user()->wallet->balance;
            if($request->amount > $balance){
                return back()->with('error', 'Insufficient fund');
            }

            $debit = debitWallet(auth()->user(), 'Naira', $request->amount); //debit naira wallet

            PaymentTransaction::create([
                'user_id' => auth()->user()->id,
                'campaign_id' => '1',
                'reference' => time(),
                'amount' => $request->amount,
                'balance' => walletBalance(auth()->user()->id),
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'currency_conversion',
                'type' => 'naira_dollar_exchange',
                'description' => 'Currency exchange - Naira Debit',
                'tx_type' => 'Debit',
                'user_type' => 'regular'
            ]);

            if($debit){
                creditWallet(auth()->user(), 'Dollar', $request->usd); //credit usd wallet

                PaymentTransaction::create([
                    'user_id' => auth()->user()->id,
                    'campaign_id' => '1',
                    'reference' => time(),
                    'amount' => $request->usd,
                    'balance' => walletBalance(auth()->user()->id),
                    'status' => 'successful',
                    'currency' => 'USD',
                    'channel' => 'currency_conversion',
                    'type' => 'naira_dollar_exchange',
                    'description' => 'Currency exchange - USD Credit',
                    'tx_type' => 'Credit',
                    'user_type' => 'regular'
                ]);
            }

            
           
 
            return back()->with('success', 'Conversion Successful');


        }else{
            $request->validate([
                'usd' => 'required|numeric|min:2'
            ]);
    
            $balance=auth()->user()->wallet->usd_balance;
            if($request->usd > $balance){
                return back()->with('error', 'Insufficient fund');
            }

            $debit = debitWallet(auth()->user(), 'Dollar', $request->usd); //debit dollar wallet
            PaymentTransaction::create([
                'user_id' => auth()->user()->id,
                'campaign_id' => '1',
                'reference' => time(),
                'amount' => $request->usd,
                'balance' => walletBalance(auth()->user()->id),
                'status' => 'successful',
                'currency' => 'USD',
                'channel' => 'currency_conversion',
                'type' => 'naira_dollar_exchange',
                'description' => 'Currency exchange - USD Debit',
                'tx_type' => 'Debit',
                'user_type' => 'regular'
            ]);

            if($debit){
                creditWallet(auth()->user(), 'Naira', $request->naira); //credit naira wallet
                PaymentTransaction::create([
                    'user_id' => auth()->user()->id,
                    'campaign_id' => '1',
                    'reference' => time(),
                    'amount' => $request->naira,
                    'balance' => walletBalance(auth()->user()->id),
                    'status' => 'successful',
                    'currency' => 'NGN',
                    'channel' => 'currency_conversion',
                    'type' => 'naira_dollar_exchange',
                    'description' => 'Currency exchange - Naira Credit',
                    'tx_type' => 'Credit',
                    'user_type' => 'regular'
                ]);
            }

            

           return back()->with('success', 'Conversioin Successful');
        }


    }
}
