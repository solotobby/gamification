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

        $url = PaystackHelpers::initiateTrasaction($ref, $amount, '/wallet/topup');
        PaystackHelpers::paymentTrasanction(auth()->user()->id, '1', $ref, $request->balance, 'unsuccessful', 'wallet_topup', 'Wallet Topup', 'Payment_Initiation', 'regular');
        return redirect($url);
    }

    public function walletTop()
    {
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);

        $ref = $params['trxref']; //paystack
        $res = PaystackHelpers::verifyTransaction($ref); //
   
       $amount = $res['data']['amount'];

        $percent = 2.90/100 * $amount;
        $formatedAm = $percent;
        $newamount = $amount - $formatedAm; //verify transaction
        $creditAmount = $newamount / 100;

       if($res['data']['status'] == 'success') //success - paystack
       {

            PaystackHelpers::paymentUpdate($ref, 'successful'); //update transaction
            
            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            $wallet->balance += $creditAmount;
            $wallet->save();
            return redirect('success');
       }else{
        return redirect('error');
       }
    }

    public function storeWithdraw(Request $request)
    {
       $amount = $request->balance;
       $percent = 5/100 * $amount;
       $formatedAm = $percent;
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
