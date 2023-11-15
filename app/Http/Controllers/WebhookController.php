<?php

namespace App\Http\Controllers;

use App\Mail\GeneralMail;
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
                PaymentTransaction::create([
                    'user_id' => $user->id,
                    'campaign_id' => '1',
                    'reference' => time(), //$reference,
                    'amount' => $amount,
                    'status' => $status,
                    'currency' => $currency,
                    'channel' => $channel,
                    'type' => 'cash_transfer_top',
                    'description' => 'Cash transfer from '.$user->name,
                    'tx_type' => 'Credit',
                    'user_type' => 'regular'
                ]);

                $subject = 'Wallet Credited';
                $content = 'Congratulations, your wallet has been credited with NGN'.$amount;
                Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

            }
            return response()->json(['status' => 'success'], 200);

        }else{
            return response()->json(['status' => 'error'], 500);
        }

    }
}
