<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Mail\GeneralMail;
use App\Models\BankInformation;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $balance = '';
        if(walletHandler() == 'sendmonny'){
            $balance = Sendmonny::getUserBalance(GetSendmonnyUserId(), accessToken());
        }
        $location = PaystackHelpers::getLocation();
        return  view('user.wallet.fund', ['location' => $location]);
    }


    public function withdraw()
    {
        return  view('user.wallet.withdraw');
    }

    public function storeFund(Request $request)
    {
        // $location = PaystackHelpers::getLocation();
        if(auth()->user()->wallet->base_currency == 'Naira'){
            $ref = time();

            $percent = 3/100 * $request->balance;
            $amount = $request->balance + $percent;
    
            $url = PaystackHelpers::initiateTrasaction($ref, $amount, '/wallet/topup');
            PaystackHelpers::paymentTrasanction(auth()->user()->id, '1', $ref, $request->balance, 'unsuccessful', 'wallet_topup', 'Wallet Topup', 'Payment_Initiation', 'regular');
            return redirect($url);
        
        }else{
            $percent = 5/100 * $request->balance;
            $am = $request->balance + $percent + 1;
             $result = paypalPayment($am, '/paypal/return');
             if($result['status'] == 'CREATED'){
                $url = $result['links'][1]['href'];
                PaystackHelpers::paymentTrasanction(auth()->user()->id, '1', $result['id'], $request->balance, 'unsuccessful', 'wallet_topup', 'Wallet Topup', 'Payment_Initiation', 'regular');
                return redirect($url);
             }
           
        }
        
    }

    public function capturePaypal(){
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);

        $id = $params['token'];

          $response = capturePaypalPayment($id);

          $user = Auth::user();
        if($response['status'] == 'COMPLETED'){

            $ref = $response['purchase_units'][0]['reference_id'];
         
            // $sellerReceivableBreakdown = $response['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown'];

            // Access individual values
            // $grossAmount = $sellerReceivableBreakdown['gross_amount']['value'];
            // $paypalFee = $sellerReceivableBreakdown['paypal_fee']['value'];
            // $netAmount = $sellerReceivableBreakdown['net_amount']['value'];

            // $currency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];

            // $data['ref'] = $ref;
            // $data['currency'] = $currency;
            // $data['net'] = $netAmount;
            // $data['amount'] = $grossAmount;
            // $data['fee'] = $paypalFee;

            $update = PaymentTransaction::where('reference', $response['id'])->first();
            $update->status = 'successful';
            $update->reference = $response['purchase_units'][0]['reference_id'];
            $update->save();

            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            $wallet->usd_balance += $update->amount;
            $wallet->save();

            systemNotification($user, 'success', 'Wallet Topup', '$'.$update->amount.' Wallet Topup Successful');

            return redirect('success');
        }else{
            return redirect('error');
        }
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
        
        $user = Auth::user();

       if($res['data']['status'] == 'success') //success - paystack
       {

            PaystackHelpers::paymentUpdate($ref, 'successful'); //update transaction
            
            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            $wallet->balance += $creditAmount;
            $wallet->save();
            
            $name = SystemActivities::getInitials(auth()->user()->name);
            SystemActivities::activityLog(auth()->user(), 'wallet_topup', $name .' topped up wallet ', 'regular');
            
            systemNotification($user, 'success', 'Wallet Topup', 'NGN'.$creditAmount.' Wallet Topup Successful');

            return redirect('success');
       }else{
        return redirect('error');
       }
    }

    public function storeWithdraw(Request $request)
    {
        if(auth()->user()->wallet->base_currency == 'Naira' ){

            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            if($wallet->balance < $request->balance)
            {
                return back()->with('error', 'Insufficient balance');
            }
       
           

            $bankInformation = BankInformation::where('user_id', auth()->user()->id)->first();
            if($bankInformation){
                $this->processWithdrawals($request, 'NGN', 'paystack');
                return back()->with('success', 'Withdrawal Successfully queued');
                //  $bankList = PaystackHelpers::bankList();
                //  return view('user.bank_information', ['bankList' => $bankList]);
            }else{
                return redirect('profile')->with('info', 'Please scroll down to Bank Account Details to update your information');
            }

           
     
        }else{

            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            if($wallet->usd_balance < $request->balance)
            {
                return back()->with('error', 'Insufficient balance');
            }
            $this->processWithdrawals($request, 'USD', 'paystack');
            return back()->with('success', 'Withdrawal Successfully queued');

        }
      
    }

    public function processWithdrawals($request, $currency, $channel){
        $amount = $request->balance;
        $percent = 5/100 * $amount;
        $formatedAm = $percent;
        $newamount_to_be_withdrawn = $amount - $formatedAm;
 
        $ref = time();
        
        if(Carbon::now()->format('l') == 'Friday'){
         $nextFriday = Carbon::now()->endOfDay();
        }else{
         $nextFriday = Carbon::now()->next('Friday')->format('Y-m-d h:i:s');
        }

         $wallet = Wallet::where('user_id', auth()->user()->id)->first();
         if($currency == 'USD'){
            $wallet->usd_balance -= $request->balance;
            $wallet->save();
         }else{
            $wallet->balance -= $request->balance;
            $wallet->save();
         }
        
 
        $withdrawal = Withrawal::create([
             'user_id' => auth()->user()->id, 
             'amount' => $newamount_to_be_withdrawn,
             'next_payment_date' => $nextFriday,
             'paypal_email' => $currency == 'USD' ? $request->paypal_email : null,
             'is_usd' => $currency == 'USD' ? true : false,
         ]);
        //process dollar withdrawal
        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => '1',
            'reference' => time(),
            'amount' => $newamount_to_be_withdrawn,
            'status' => 'successful',
            'currency' => $currency,
            'channel' => $channel,
            'type' => 'cash_withdrawal',
            'description' => 'Cash Withdrawal from '.auth()->user()->name,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);

        //admin commission
            $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->usd_balance += $percent;
            $adminWallet->save();
            //Admin Transaction Tablw
            PaymentTransaction::create([
                'user_id' => 1,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => $percent,
                'status' => 'successful',
                'currency' => $currency,
                'channel' => $channel,
                'type' => 'withdrawal_commission',
                'description' => 'Withdrwal Commission from '.auth()->user()->name,
                'tx_type' => 'Credit',
                'user_type' => 'admin'
            ]);
            SystemActivities::activityLog(auth()->user(), 'withdrawal_request', auth()->user()->name .'sent a withdrawal request of NGN'.number_format($amount), 'regular');
        // $bankInformation = BankInformation::where('user_id', auth()->user()->id)->first();
        $cur = $currency == 'USD' ? '$' : 'NGN';
        systemNotification(Auth::user(), 'success', 'Withdrawal Request', $cur.$request->balance.' was debited from your wallet');
        
        $user = User::where('id', '1')->first();
        $subject = 'Withdrawal Request Queued!!';
        $content = 'A withdrwal request has been made and it being queued';
        Mail::to('freebyzcom@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));

        return $withdrawal;
    }

    public function switchWallet(Request $request){
        auth()->user()->wallet()->update(['base_currency' => $request->currency]);
        systemNotification(Auth::user(), 'success', 'Currency Switch', 'Currency switched to '.$request->currency);
        
        return back()->with('success', 'Currency switched successfully');
    }
}
