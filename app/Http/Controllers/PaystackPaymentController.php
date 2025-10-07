<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaystackPaymentController extends Controller
{
    public function __construct()
    {
         // $this->middleware(['auth', 'email']);
        $this->middleware('auth');
    }


    public function goLive($job_id)
    {
        $campaign = Campaign::where('job_id', $job_id)->first();

        $ref = time();
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => auth()->user()->email,
            'amount' => $campaign->total_amount * 100,
            'channels' => ['card'],
            'currency' => 'NGN',
            'reference' => $ref,
            'callback_url' => env('PAYSTACK_CALLBACK_URL').'/callback',
        ]);

        $url = $res['data']['authorization_url'];
        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => $campaign->id,
            'reference' => $ref,
            'amount' => $campaign->total_amount,
            'status' => 'unsuccessful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'campaign_payment',
            'description' => 'Payment for '.$campaign->post_title
        ]);
        return redirect($url);

         //json_decode($res->getBody()->getContents(), true);

    }

    public function paystackCallback()
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

        if($status == 'success'){
            $fetchPaymentTransaction = PaymentTransaction::where('reference', $ref)->first();
            $fetchPaymentTransaction->status = 'successful';
            $fetchPaymentTransaction->save();

            $updateCampaign = Campaign::where('id', $fetchPaymentTransaction->campaign_id)->first();
            $updateCampaign->status = 'Live';
            $updateCampaign->save();
            return redirect('my/campaigns')->with('success', 'Payment Successful');
        }else{
            return back()->with('error', 'Payment Not Successful');
        }


    }


}
