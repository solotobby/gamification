<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use App\Mail\GeneralMail;
use App\Models\BankInformation;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

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
        
        // $res = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        // ])->post('https://api.paystack.co/transaction/initialize', [
        //     'email' => auth()->user()->email,
        //     'amount' => $amount*100,
        //     'channels' => ['card'],
        //     'currency' => 'NGN',
        //     'reference' => $ref,
        //     'callback_url' => env('PAYSTACK_CALLBACK_URL').'/wallet/topup'
        // ]);
        // $url = $res['data']['authorization_url'];

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/payments', [
            'tx_ref'=> $ref,
            'amount' => $amount,
            'currency' => "NGN",
            'redirect_url' => env('PAYSTACK_CALLBACK_URL').'/wallet/topup',//"https://webhook.site/9d0b00ba-9a69-44fa-a43d-a82c33c36fdc",
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
       // $ref = $params['trxref'];  paystack 
        
        $ref = $params['tx_ref'];
        $status = $params['status'];
        $transactionId = $params['transaction_id'];

        // $res = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        // ])->get('https://api.paystack.co/transaction/verify/'.$ref)->throw();

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->get('https://api.flutterwave.com/v3/transactions/'.$transactionId.'/verify')->throw();

        $status = $res['data']['status'];
   
       $paystack_amount = $res['data']['amount'];
       $amount = $paystack_amount; /// 100;

       $percent = 2.90/100 * $amount;
       $formatedAm = number_format($percent, 0);
       $newamount = $amount - $formatedAm;

       if($status == 'successful') //success - paystack
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
       $amount = $request->balance;
       $percent = 5/100 * $amount;
       $formatedAm = number_format($percent, 0);
       $newamount_to_be_withdrawn = $amount - $formatedAm;

       $ref = time();

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
            'amount' => $newamount_to_be_withdrawn,
            'next_payment_date' => $nextFriday
        ]);

        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => '1',
            'reference' => time(),
            'amount' => $newamount_to_be_withdrawn,
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'cash_withdrawal',
            'description' => 'Cash Withdrawal from '.auth()->user()->name,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);



        //admin commission
        $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->balance += $percent;
            $adminWallet->save();
            //Admin Transaction Tablw
            PaymentTransaction::create([
                'user_id' => 1,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => $percent,
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'paystack',
                'type' => 'withdrawal_commission',
                'description' => 'Withdrwal Commission from '.auth()->user()->name,
                'tx_type' => 'Credit',
                'user_type' => 'admin'
            ]);


        $bankInformation = BankInformation::where('user_id', auth()->user()->id)->first();
        if($bankInformation == null){
            $bankList = PaystackHelpers::bankList();
            return view('user.bank_information', ['bankList' => $bankList]);
        }
        $user = User::where('id', '1')->first();
        $subject = 'Withdrawal Request Queued!!';
        $content = 'A withdrwal request has been made and it being queued';
        Mail::to('freebyzcom@gmail.com')->send(new GeneralMail($user, $content, $subject));
        return back()->with('success', 'Withdrawal Successfully queued');

    }
}
