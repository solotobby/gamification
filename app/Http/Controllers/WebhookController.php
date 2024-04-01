<?php

namespace App\Http\Controllers;

use App\Mail\GeneralMail;
use App\Mail\UpgradeUser;
use App\Models\PaymentTransaction;
use App\Models\Question;
use App\Models\User;
use App\Models\VirtualAccount;
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

            $virtualAccount = VirtualAccount::where('customer_id', $customer_code)->first();

            $user = User::where('id', $virtualAccount->user_id)->first();

            $creditUser = creditWallet($user, 'Naira', $amount);
            if($creditUser){

                $transaction = transactionProcessor($user, $reference, $amount, 'successful', $currency, $channel, 'transfer_topup', 'Cash transfer from '.$user->name, 'Credit', 'regular');
                
                if($transaction){
                    $subject = 'Wallet Credited';
                    $content = 'Congratulations, your wallet has been credited with NGN'.$amount;
                    Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));    
                }

                //check wallet stat

                if($user->is_verified == false){
                    if($amount >= 1050){
                        $debitWallet = debitWallet($user, 'Naira', 1050);
                        
                        if($debitWallet){
                            
                            $upgrdate = userNairaUpgrade($user);

                            if($upgrdate){
                                Mail::to($user->email)->send(new UpgradeUser($user));
                                
                                // sendSMS($phone_number);
                            }
                            
                        }
                    }
                }

                $phone_number = '';
                $first_character = substr($user->phone, 0, 3);
                if($first_character == '234'){
                    $phone_number = $user->phone;
                }else{
                    $phone_number = '234'.substr($user->phone, 1);
                }
                               
                sendSMS($phone_number);
                
                
              
                
            }
            return response()->json(['status' => 'success'], 200);

        }else{
            return response()->json(['status' => 'error'], 500);
        }

    }
}
