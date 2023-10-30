<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Question;
use App\Models\User;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request){

        Question::create(['content' => $request]);

        $event = $request['event'];

        if($event == 'charge.success'){
            $amount = $request['data']['amount'] / 100;
            $status = $request['data']['status'];
            $reference = $request['data']['reference'];
            $channel = $request['data']['channel'];
            $currency = $request['data']['currency'];
            $email = $request['data']['customer']['email'];
            $customer_code = $request['data']['customer']['customer_code'];

            $virtualAccount = VirtualAccount::where('customer_code', $customer_code)->first();

            $user = User::where('id', $virtualAccount->user_id)->first();

            creditWallet($user, 'Naira', $amount);

            
            PaymentTransaction::create([
                'user_id' => $user->id,
                'campaign_id' => '1',
                'reference' => $reference,
                'amount' => $amount,
                'status' => $status,
                'currency' => $currency,
                'channel' => $channel,
                'type' => 'cash_transfer_top',
                'description' => 'Cash Withdrawal from '.auth()->user()->name,
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]);

            return response()->json(['status' => 'success']);

        }else{
            return response()->json(['status' => 'error']);
        }

    }
}
