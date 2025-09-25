<?php

namespace App\Http\Controllers;

use App\Mail\GeneralMail;
use App\Mail\UpgradeUser;
use App\Models\PaymentTransaction;
use App\Models\Question;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    public function handle(Request $request){

        Question::create(['content' => $request]);

        $event = $request['event'];

        if($event == 'charge.success'){
            $amount = $request['data']['amount']/100;
            $status = $request['data']['status'];
            $reference = $request['data']['reference'];
            $channel = $request['data']['channel'];
            $currency = $request['data']['currency'];
            $email = $request['data']['customer']['email'];
            $customer_code = $request['data']['customer']['customer_code'];

            $validateTransaction = PaymentTransaction::where('reference', $reference)->first();
            if(!$validateTransaction){

                $virtualAccount = VirtualAccount::where('customer_id', $customer_code)->first();
                $user = User::where('id', $virtualAccount->user_id)->first();

                // $creditUser = creditWallet($user, 'NGN', $amount);
                
                $walletCredit =  Wallet::where('user_id', $user->id)->first();
                $walletCredit->balance += $amount;
                $walletCredit->save();
               


                if($walletCredit){

                    // $transaction = transactionProcessor($user, $reference, $amount, 'successful', $currency, $channel, 'transfer_topup', 'Cash transfer from '.$user->name, 'Credit', 'regular');
                    
                    $transaction =  PaymentTransaction::create([
                        'user_id' => $user->id,
                        'campaign_id' => 1,
                        'reference' => $reference,
                        'amount' => $amount,
                        'balance' => walletBalance($user->id),
                        'status' => 'successful',
                        'currency' => 'NGN',
                        'channel' => 'paystack',
                        'type' => 'transfer_topup',
                        'description' => 'Cash transfer from '.$user->name,
                        'tx_type' => 'Credit',
                        'user_type' => 'regular'
                    ]);

                    if($transaction){
                        $subject = 'Wallet Credited';
                        $content = 'Congratulations, your wallet has been credited with ₦'.$amount;
                         Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));    
                    }

                    //check wallet stat

                    $userBaseCurrency = baseCurrency($user);

                    $currencyParams = currencyParameter($userBaseCurrency);
                    $upgradeAmount = $currencyParams->upgrade_fee;
                    $referral_commission = $currencyParams->referral_commission;

                    if($user->is_verified == false){
                        if($amount >= $upgradeAmount){
                            
                            $debitWallet = debitWallet($user, 'NGN', $upgradeAmount);
                            
                            if($debitWallet){
                                
                                $upgrdate = userNairaUpgrade($user, $upgradeAmount, $referral_commission);

                                if($upgrdate){
                                     Mail::to($user->email)->send(new UpgradeUser($user));
                                }
                                
                            }
                        }else{

                            $walletCredit =  Wallet::where('user_id', $user->id)->first();
                            if($walletCredit->balance >= $upgradeAmount){
                                $debitWallet = debitWallet($user, 'NGN', $upgradeAmount);
                                if($debitWallet){
                                
                                    $upgrdate = userNairaUpgrade($user, $upgradeAmount, $referral_commission);
    
                                    if($upgrdate){
                                         Mail::to($user->email)->send(new UpgradeUser($user));
                                    }
                                    
                                }
                            }

                        }
                    }
                    
                }
                 
            }
            return response()->json(['status' => 'success'], 200);

        }else{
            return response()->json(['status' => 'error'], 500);
        }

    }

    public function korayPayWebhook(Request $request){
        
        $event = $request['event'];
        Question::create(['content' => $request]);

        return response()->json(['status' => 'success'], 200);

        // if($event == 'charge.success'){

        // }


    }
}
