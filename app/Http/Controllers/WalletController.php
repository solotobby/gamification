<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Mail\GeneralMail;
use App\Models\BankInformation;
use App\Models\PaymentTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        // $balance = '';
        // if(walletHandler() == 'sendmonny'){
        //     $balance = Sendmonny::getUserBalance(GetSendmonnyUserId(), accessToken());
        // }
        // $location = PaystackHelpers::getLocation();
        return  view('user.wallet.fund');
    }


    public function withdraw()
    {
        return  view('user.wallet.withdraw');
    }

    public function koraPayRedirect(){

        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);

        $ref = $params['reference'];
        $res = verifyKorayPay($ref);

        if($res['data']['status'] == 'success'){
           
            $credit = creditWallet(auth()->user(), 'NGN', $res['data']['amount_paid']);

            if($credit){
                $PaymentTransaction = PaymentTransaction::where('reference', $res['data']['reference'])->first();
                $PaymentTransaction->status = 'successful';
                $PaymentTransaction->save();
            }

            return redirect('wallet/fund')->with('success', 'Wallet successfully funded');

        }else{
            return redirect('wallet/fund')->with('error', 'Action ended');
        }

    }

    public function storeFund(Request $request)
    {

            $baseCurrency = auth()->user()->wallet->base_currency;
            $amount = amountCalculator($request->balance);
            $ref = Str::random(16);

            if($baseCurrency == 'NGN'){

                if($request->channel == 'paystack'){
                    $url = initiateTrasaction(time(), $amount, '/wallet/topup');
                    return redirect($url);
                }else{

                    $payloadNGN = [
                        "amount"=> $request->balance, //$amount,
                        "redirect_url"=> url('wallet/fund/redirect'),
                        "currency"=> "NGN",
                        "reference"=> $ref,
                        "narration"=> "Wallet top up",
                        "channels"=> [
                            "card",
                            "bank_transfer",
                            "pay_with_bank"
                        ],
                        // "default_channel"=> "card",
                        "customer"=> [
                            "name"=> auth()->user()->name,
                            "email"=> auth()->user()->email
                        ],
                        "notification_url"=> "https://webhook.site/8d321d8d-397f-4bab-bf4d-7e9ae3afbd50",
                        // "metadata"=>[
                        //     "key0"=> "test0",
                        //     "key1"=> "test1",
                        //     "key2"=> "test2",
                        //     "key3"=> "test3",
                        //     "key4"=> "test4"
                        // ]
                    ];
    
                    $redirectUrl = initializeKorayPay($payloadNGN);
    
                    PaymentTransaction::create([
                            'user_id' => auth()->user()->id,
                            'campaign_id' => '1',
                            'reference' => $ref,
                            'amount' => $request->balance,
                            'balance' => walletBalance(auth()->user()->id),
                            'status' => 'unsuccessful',
                            'currency' => $baseCurrency,
                            'channel' => 'kora',
                            'type' => 'wallet_topup',
                            'description' => 'Wallet Top Up',
                            'tx_type' => 'Credit',
                            'user_type' => 'regular'
                        ]);
    
                    return redirect($redirectUrl);
                    
                }
                
            }else{


                if($baseCurrency == 'GHS'){
                    $paymentOption = 'mobilemoneyghana';
        
                }elseif($baseCurrency == 'KES'){
                    $paymentOption = 'mpesa';
        
                }elseif($baseCurrency == 'RWF'){
                    $paymentOption = 'mobilemoneyrwanda';
        
                }elseif($baseCurrency == 'TZS'){
                    $paymentOption = 'mobilemoneytanzania';
        
                }elseif($baseCurrency == 'MWK'){
                    $paymentOption = 'mobilemoneymalawi';
                   
                }else{
                    $paymentOption = null;
                }

                $payload = [
                    'tx_ref' => $ref,
                    'amount'=> $amount,
                    'currency'=> $baseCurrency, //"USD",
                    'redirect_url'=> url('flutterwave/wallet/top'),
                    'payment_options'=> 'card ,'. $paymentOption,
                    'meta'=> [
                        'consumer_id' => auth()->user()->id,
                        'consumer_mac'=> ''
                    ],
                    'customer'=> [
                        'email'=> auth()->user()->email,
                        'phonenumber'=> auth()->user()->phone,
                        'name'=> auth()->user()->name,
                    ],
                    'customizations'=>[
                        'title'=> "Wallet Top Up",
                        // 'logo'=> "http://www.piedpiper.com/app/themes/joystick-v27/images/logo.png"
                    ] 
                ];
                $url = flutterwavePaymentInitiation($payload)['data']['link'];

                PaymentTransaction::create([
                    'user_id' => auth()->user()->id,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => $amount,
                    'balance' => walletBalance(auth()->user()->id),
                    'status' => 'unsuccessful',
                    'currency' => $baseCurrency,
                    'channel' => 'flutterwave',
                    'type' => 'wallet_topup',
                    'description' => 'Wallet Top Up',
                    'tx_type' => 'Credit',
                    'user_type' => 'regular'
                ]);
     
                return redirect($url);

            }

            
             


        

        // if($baseCurrency == 'Naira'){
        //     $ref = time();

        //     $percent = 3/100 * $request->balance;
        //     $amount = $request->balance + $percent;

        //     $payload = [
        //         'tx_ref' => time(),
        //         'amount'=> $amount,
        //         'currency'=> "NGN",
        //         'redirect_url'=> url('flutterwave/wallet/top'),
        //         'meta'=> [
        //             'consumer_id' => auth()->user()->id,
        //             'consumer_mac'=> ''
        //         ],
        //         'customer'=> [
        //             'email'=> auth()->user()->email,
        //             'phonenumber'=> auth()->user()->phone,
        //             'name'=> auth()->user()->name,
        //         ],
        //         'customizations'=>[
        //             'title'=> "Wallet Top Up",
        //             'logo'=> "http://www.piedpiper.com/app/themes/joystick-v27/images/logo.png"
        //         ] 
        //     ];
            // $url = flutterwavePaymentInitiation($payload)['data']['link'];
    
            // $url = initiateTrasaction($ref, $amount, '/wallet/topup');
            
            // paymentTrasanction(auth()->user()->id, '1', $ref, $request->balance, 'unsuccessful', 'wallet_topup', 'Wallet Topup', 'Payment_Initiation', 'regular');
            
            // return redirect($url);


        
        
        
        
        //     if($baseCurrency == 'GHS'){

        //     return 'GHS';
        
        // }else{

            // $curLocation = currentLocation();
            
            // if($curLocation == 'Nigeria'){
            //     return back()->with('error', 'You are not allowed to use this feature. Kindly top up with your Virtual Account.');
            // }

        //     $percent = 5/100 * $request->balance;
        //     $amount = $request->balance + $percent + 0.4;
        //     $ref = time();

        // if($request->method == 'flutterwave'){

       
        //     $payload = [
        //         'tx_ref' => Str::random(16),
        //         'amount'=> $amount,
        //         'currency'=> "USD",
        //         'redirect_url'=> url('flutterwave/wallet/top'),
        //         'payment_options'=> "card, mobilemoneyghana",
        //         'meta'=> [
        //             'consumer_id' => auth()->user()->id,
        //             'consumer_mac'=> ''
        //         ],
        //         'customer'=> [
        //             'email'=> auth()->user()->email,
        //             'phonenumber'=> auth()->user()->phone,
        //             'name'=> auth()->user()->name,
        //         ],
        //         'customizations'=>[
        //             'title'=> "Wallet Top Up",
        //             // 'logo'=> "http://www.piedpiper.com/app/themes/joystick-v27/images/logo.png"
        //         ] 
        //     ];
        //     $url = flutterwavePaymentInitiation($payload)['data']['link'];

        //     // $url = PaystackHelpers::initiateTrasaction($ref, $amount, '/wallet/topup');
        //      //Admin Transaction Tablw
        //      PaymentTransaction::create([
        //         'user_id' => auth()->user()->id,
        //         'campaign_id' => '1',
        //         'reference' => $ref,
        //         'amount' => $amount,
        //         'status' => 'unsuccessful',
        //         'currency' => 'USD',
        //         'channel' => 'flutterwave',
        //         'type' => 'wallet_topup',
        //         'description' => 'Wallet Top Up',
        //         'tx_type' => 'Credit',
        //         'user_type' => 'regular'
        //     ]);

        //     //PaystackHelpers::paymentTrasanction(auth()->user()->id, '1', $ref, $request->balance, 'unsuccessful', 'wallet_topup', 'Wallet Topup', 'Credit', 'Payment_Initiation', 'regular');
            
        //     return redirect($url);
        // }else{

        //     $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        //     $redirectUrl = route('stripe.checkout.success').'?session_id={CHECKOUT_SESSION_ID}';
        //     $ref = time();
        //     $response =  $stripe->checkout->sessions->create([
        //         'success_url' => $redirectUrl,
        //         'cancel_url' => url('cancel/transaction/'.$ref),
        //         'customer_email' => auth()->user()->email,
        //         'payment_method_types' => ['link', 'card'],
        //         'locale' => 'auto',
        //         'client_reference_id' => $ref,
        //         'line_items' => [
        //             [
        //                 'price_data'  => [
        //                     'product_data' => [
        //                         'name' => 'Freebyz TopUp',
        //                     ],
        //                     'unit_amount'  => 100 * $amount,
        //                     'currency'     => 'USD',
        //                 ],
        //                 'quantity'    => 1
        //             ],
        //         ],
        //         'mode' => 'payment',
        //         'allow_promotion_codes' => true,
        //         'expires_at' => time() + 3600,
                
        //         // 'automatic_payment_methods' => ['enabled' => true],
        //     ]);

        //     PaymentTransaction::create([
        //         'user_id' => auth()->user()->id,
        //         'campaign_id' => '1',
        //         'reference' => $ref,
        //         'amount' => $request->balance,
        //         'status' => 'unsuccessful',
        //         'currency' => 'USD',
        //         'channel' => 'stripe',
        //         'type' => 'wallet_topup',
        //         'description' => 'Wallet Top Up',
        //         'tx_type' => 'Credit',
        //         'user_type' => 'regular'
        //     ]);
  
        //     return redirect($response['url']);
        // }  
        // }
        
    }

    public function stripeCheckoutSuccess(Request $request){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
  
        $session = $stripe->checkout->sessions->retrieve($request->session_id);

        if($session['payment_status'] == 'paid' && $session['status'] == 'complete'){
            
            // $amount = $session['amount_total']/10;
            // $percent = 2.90/100 * $amount;
            // $formatedAm = $percent;
            // $newamount = $amount - $formatedAm; //verify transaction
            // $creditAmount = $newamount / 100;

            $trx = PaymentTransaction::where('reference', $session['client_reference_id'])->first();
            if($trx){
                $wallet = Wallet::where('user_id', auth()->user()->id)->first();
                $wallet->usd_balance += $trx->amount;
                $wallet->save();

                $trx->status = 'successful';
                $trx->save();
            }
            return redirect()->route('fund')->with('success', 'Payment successful and you wallet credited.');
        }else{
            return redirect('wallet/fund');
        }

        
    //    return info($session);
  
        // return redirect()->route('stripe.index')
        //                  ->with('success', 'Payment successful.');
    }

    public function cancelUrl($ref){
        PaymentTransaction::where('reference', $ref)->delete();

        return redirect()->route('fund')
        ->with('error', 'Payment Cancelled');
    }

    public function capturePaypal(){
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);

        $id = $params['token'];

          $response = capturePaypalPayment($id);

          $user = Auth::user();
        if($response['status'] == 'COMPLETED'){

            //$ref = $response['purchase_units'][0]['reference_id'];
         
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
           
            activityLog(auth()->user(), 'wallet_topup', auth()->user()->name .' topped up wallet ', 'regular');

            systemNotification($user, 'success', 'Wallet Topup', '$'.$update->amount.' Wallet Topup Successful');

            return redirect('success');
        }else{
            return redirect('error');
        }
    }

    public function walletTop()
    {

        return back()->with('success', 'Payment Completed. Your wallet will be credited!');



        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);

        $ref = $params['trxref']; //paystack
        $res = verifyTransaction($ref); //
   
        $amount = $res['data']['amount'];

        $percent = 2.90/100 * $amount;
        $formatedAm = $percent;
        $newamount = $amount - $formatedAm; //verify transaction
        $creditAmount = $newamount / 100;
        
        $user = Auth::user();

       if($res['data']['status'] == 'success') //success - paystack
       {
            paymentUpdate($ref, 'successful'); //update transaction
            
            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            $wallet->balance += $creditAmount;
            $wallet->save();
            
            $name = auth()->user()->name;
            activityLog(auth()->user(), 'wallet_topup', $name .' topped up wallet ', 'regular');
            
            systemNotification($user, 'success', 'Wallet Topup', 'NGN'.$creditAmount.' Wallet Topup Successful');

            return back()->with('success', 'Wallet Topup Successful'); //redirect('success');
       }else{
        return redirect('error');
       }
    }

    public function flutterwaveWalletTopUp(){
       
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        $status = $params['status'];
        if($status == 'cancelled'){
            return back()->with('error', 'Transaction terminated');
        }
        $tx_id = $params['transaction_id'];
        $ref = $params['tx_ref'];
        $res = flutterwaveVeryTransaction($tx_id);

        if($res['status'] == 'success'){
            $ver = paymentUpdate($ref, 'successful', $res['data']['amount_settled']);

            // $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            // $wallet->balance += $res['data']['amount_settled'];//->amount;
            // $wallet->save();

            if($ver){
                $currency = auth()->user()->wallet->base_currency;

                 creditWallet(auth()->user(), $currency, $res['data']['amount_settled']);

                $name = auth()->user()->name;
                activityLog(auth()->user(), 'wallet_topup', $name .' topped up wallet ', 'regular');
                
                systemNotification(auth()->user(), 'success', 'Wallet Topup', 'NGN'.$ver->amount.' Wallet Topup Successful');

                return back()->with('success', 'Wallet Topup Successful'); 

            }
            
        }
    }

    public function storeWithdraw(Request $request)
    {
        $baseCurrency = auth()->user()->wallet->base_currency;
        if( $baseCurrency == 'NGN'){
          

            $request->validate([
                'balance' => 'required|numeric|min:2500',
            ], [
                'balance.min' => 'The amount must be at least 2500.',
            ]);
            

            if($request->balance >= 50000){
                return back()->with('error', 'This transaction is not allowed, contact customer care');
            }

            $check = PaymentTransaction::where('user_id', auth()->user()->id)
                    ->where('type', 'cash_withdrawal')
                    ->whereDate('created_at', Carbon::today())
                    ->get(['id', 'amount', 'type']);
            
            if(count($check) > 1){
                return back()->with('error', 'This transaction is not allowed count, contact customer care');
            }
            
            if($check->sum('amount') >= '50000'){
                return back()->with('error', 'This transaction is not allowed, contact customer care');
            }

            $user = User::where('id', auth()->user()->id)->first();

            $accountCreationDate = new Carbon($user->created_at);

            if($accountCreationDate->diffInDays(Carbon::now()) <= 10){
                return back()->with('error', 'You cannot make withdrawal at the moment');
            }

            $wallet = Wallet::where('user_id', auth()->user()->id)->first();

            if($wallet->balance < $request->balance)
            {
                return back()->with('error', 'Insufficient balance');
            }

            $bankInformation = BankInformation::where('user_id', auth()->user()->id)->first();
            if($bankInformation){
                 $this->processWithdrawals($request, 'NGN', 'paystack', '');
                return back()->with('success', 'Withdrawal Successfully queued');
                //  $bankList = PaystackHelpers::bankList();
                //  return view('user.bank_information', ['bankList' => $bankList]);
            }else{
                return redirect('profile')->with('info', 'Please scroll down to Bank Account Details to update your information');
            }

        }else{

            $request->validate([
                'balance' => 'required',
            ]);

            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            if($baseCurrency == 'USD'){
                if($wallet->usd_balance < $request->balance)
                {
                    return back()->with('error', 'Insufficient balance');
                }
            }else{
                if($wallet->base_currency_balance < $request->balance)
                {
                    return back()->with('error', 'Insufficient balance');
                }
            }
            

            if($baseCurrency == 'GHS'){
                $accountBank = $request->account_bank;
            }else{
                $accountBank = 'MPS';
            }

            $payload = [
                 "account_bank" => $accountBank,
                  "account_number" => $request->account_number,
                  "amount" => $request->balance,
                  "currency" => $baseCurrency,
                  "beneficiary_name" => $request->beneficiary_name,
                  "narration" => 'Freebyz Withdrawal',
                  "meta" => [
                        "sender" => "Flutterwave Developers",
                        "sender_country" => "NG",
                        "mobile_number" => "23457558595"
                   ]
            ];
            
            // return flutterwaveTransfer($payload);

        //    return $this->processForeignWithdrawal($payload);
           $this->processWithdrawals($request, $baseCurrency, 'flutterwave', $payload);

            return back()->with('success', 'Withdrawal Successfully queued');

        }
    }

    public function processWithdrawals($request, $currency, $channel, $payload){

        $amount = $request->balance;
        $percent = 7.5/100 * $amount;
        $formatedAm = $percent;
        $newamount_to_be_withdrawn = $amount - $formatedAm;

        $referral_amount = 2/100 * $amount;
 
        $ref = time();
        
        if(Carbon::now()->format('l') == 'Friday'){
            $nextFriday = Carbon::now()->endOfDay();
        }else{
            $nextFriday = Carbon::now()->next('Friday')->format('Y-m-d h:i:s');
        }

        $debitWallet =  debitWallet(auth()->user(), 'NGN', $request->balance);
        //  return $payload;
        if($debitWallet){
            $withdrawal = Withrawal::create([
                'user_id' => auth()->user()->id, 
                'amount' => $newamount_to_be_withdrawn,
                'next_payment_date' => $nextFriday,
                'paypal_email' => $currency == 'USD' ? $request->paypal_email : null,
                'is_usd' => false,
                'base_currency' => $currency,
                'content' => $payload == '' ? null : $payload
            ]);

            //process dollar withdrawal
            PaymentTransaction::create([
                'user_id' => auth()->user()->id,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => $newamount_to_be_withdrawn,
                'balance' => walletBalance(auth()->user()->id),
                'status' => 'successful',
                'currency' => $currency,
                'channel' => $channel,
                'type' => 'cash_withdrawal',
                'description' => 'Cash Withdrawal from '.auth()->user()->name,
                'tx_type' => 'Debit',
                'user_type' => 'regular'
            ]);




            $referee = \DB::table('referral')->where('user_id',  auth()->user()->id)->first();

                if($referee){
                    
                     $referreUser = User::with('wallet')->where('id', $referee->referee_id)->first();
                    $referreUser->wallet->base_currency;
                    // return [$amount, $percent, $referral_amount];
                    $referral_converted_amount = currencyConverter($currency, $referreUser->wallet->base_currency, $referral_amount);
                    $creditreferral = creditWallet($referreUser, $referreUser->wallet->base_currency, $referral_converted_amount);
                    
                    if($creditreferral){
                        PaymentTransaction::create([
                            'user_id' => $referreUser->id,
                            'campaign_id' => '1',
                            'reference' => $ref,
                            'amount' => (int) $referral_converted_amount,
                            'balance' => walletBalance($referreUser->id),
                            'status' => 'successful',
                            'currency' => $referreUser->wallet->base_currency,
                            'channel' => $channel,
                            'type' => 'referral_withdrawal_commission',
                            'description' => 'Referral Withdrwal Commission from '.auth()->user()->name,
                            'tx_type' => 'Credit',
                            'user_type' => 'regular'
                        ]);
                    }

                    $adminUser = User::find('1');
                    
                      $adjustedAdminAmount = (int) $percent - (int) $referral_converted_amount;
                     
                     $admin_referral_converted_amount = currencyConverter($currency, $adminUser->wallet->base_currency, $adjustedAdminAmount);
               
                    $creditAdmin = creditWallet($adminUser, $adminUser->wallet->base_currency, $admin_referral_converted_amount);
                     
                    
                    if($creditAdmin){
                        PaymentTransaction::create([
                            'user_id' => 1,
                            'campaign_id' => '1',
                            'reference' => $ref,
                            'amount' => (int) $admin_referral_converted_amount,
                            'balance' => walletBalance('1'),
                            'status' => 'successful',
                            'currency' => $adminUser->wallet->base_currency,
                            'channel' => $channel,
                            'type' => 'withdrawal_commission',
                            'description' => 'Withdrwal Commission from '.auth()->user()->name,
                            'tx_type' => 'Credit',
                            'user_type' => 'admin'
                        ]);
                    }
                   
                }else{

                    //if there is no referral
                    $adminUser = User::find('1');
                    // $adminWallet = Wallet::where('user_id', '1')->first();
                      $adjustedAdminAmount = $percent;
                     
                     $admin_referral_converted_amount = currencyConverter($currency, $adminUser->wallet->base_currency, $adjustedAdminAmount);
               
                    $creditAdmin = creditWallet($adminUser, $adminUser->wallet->base_currency, $admin_referral_converted_amount);
                     
                    
                    if($creditAdmin){
                        PaymentTransaction::create([
                            'user_id' => 1,
                            'campaign_id' => '1',
                            'reference' => $ref,
                            'amount' => (int) $admin_referral_converted_amount,
                            'balance' => walletBalance('1'),
                            'status' => 'successful',
                            'currency' => $adminUser->wallet->base_currency,
                            'channel' => $channel,
                            'type' => 'withdrawal_commission',
                            'description' => 'Withdrwal Commission from '.auth()->user()->name,
                            'tx_type' => 'Credit',
                            'user_type' => 'admin'
                        ]);

                    }
                }
             
        
            activityLog(auth()->user(), 'withdrawal_request', auth()->user()->name .'sent a withdrawal request of NGN'.number_format($amount), 'regular');
            $cur = $currency == 'USD' ? '$' : 'NGN';
            systemNotification(Auth::user(), 'success', 'Withdrawal Request', $cur.$request->balance.' was debited from your wallet');
        
            return $withdrawal;

        }else{
            return false;
        }     
    }

 

    public function switchWallet(Request $request){
        auth()->user()->wallet()->update(['base_currency' => $request->currency]);
        systemNotification(Auth::user(), 'success', 'Currency Switch', 'Currency switched to '.$request->currency);
        
        return back()->with('success', 'Currency switched successfully');
    }
}
