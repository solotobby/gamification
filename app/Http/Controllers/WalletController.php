<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Wallet;
use App\Models\Withrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        //
    }

    public function fund()
    {
        return  view('user.wallet.fund');
    }


    public function withdraw()
    {
        return  view('user.wallet.withdraw');
    }

    public function storeFund(Request $request)
    {
        $ref = time();

        $percent = 3/100 * $request->balance;
        $amount = $request->balance + $percent;
        
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => auth()->user()->email,
            'amount' => $amount*100,
            'channels' => ['card', 'bank'],
            'currency' => 'NGN',
            'reference' => $ref,
            'callback_url' => env('PAYSTACK_CALLBACK_URL').'/wallet/topup'
        ]);
        $url = $res['data']['authorization_url'];
        
        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => $request->balance,
            'status' => 'unsuccessful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'wallet_topup',
            'description' => 'Wallet Top Up'
        ]);
        // return $url;
        return redirect($url);
    }

    public function walletTop()
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
       $paystack_amount = $res['data']['amount'];
       $amount = $paystack_amount / 100;

       $percent = 2.90/100 * $amount;
       $formatedAm = number_format($percent, 0);
       $newamount = $amount - $formatedAm;

       if($status == 'success')
       {
            $fetchPaymentTransaction = PaymentTransaction::where('reference', $ref)->first();
            $fetchPaymentTransaction->status = 'successful';
            $fetchPaymentTransaction->save();

            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            $wallet->balance += $newamount;
            $wallet->save();
       }

       return redirect('success');

    }

    public function storeWithdraw(Request $request)
    {
        
        $nextFriday = Carbon::now()->endOfWeek('-2'); //get the friday of the week
        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        if($wallet->balance < $request->balance)
        {
            return back()->with('error', 'Insufficient balance');
        }
        $wallet->balance -= $request->balance;
        $wallet->save();

        Withrawal::create([
            'user_id' => auth()->user()->id, 
            'amount' => $request->balance,
            'next_payment_date' => $nextFriday
        ]);

        return back()->with('success', 'Withdrawal Successfully queued');

    }
}
