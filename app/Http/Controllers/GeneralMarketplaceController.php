<?php

namespace App\Http\Controllers;

use App\Models\MarketPlacePayment;
use App\Models\MarketPlaceProduct;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GeneralMarketplaceController extends Controller
{
    public function index($referral_code, $product_id){
        $user = User::where('referral_code', $referral_code)->first();
        $product = MarketPlaceProduct::where('product_id', $product_id)->first();
        if($product && $user){
            $product->views += 1;
            $product->save();
            return view('market_place', ['product' => $product, 'user' => $user]);
        }else{
            return 'error__ssss';
        }
        // return [$referral_code, $product_id];
    }

    public function enter_info(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);
        $user = User::where('referral_code', $request->referral_code)->first();
        $ref = time();

        MarketPlacePayment::create([
        'market_place_product_id' => $request->product_id,
        'name' => $request->name,
        'amount' => $request->amount,
        'email' => $request->email,
        'ref' => $ref,
        'user_id' => $user->id
       ]);

       return redirect('marketplace/payment/'.$user->referral_code.'/'.$request->product_id.'/'.$ref);

    }

    public function processPayment($referral_code, $product_id, $ref){

        $prd = MarketPlaceProduct::where('id', $product_id)->first();
        $user = User::where('referral_code', $referral_code)->first();
        $customerInfo = MarketPlacePayment::where('ref', $ref)->first();
      
        
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $customerInfo->email,
            'amount' => $prd->total_payment*100,
            'channels' => ['card'],
            'currency' => 'NGN',
            'reference' => $ref,
            'callback_url' => env('PAYSTACK_CALLBACK_URL').'/marketplace/payment/callback' // url('marketplace/payment/callback') 
        ]);
        $url = $res['data']['authorization_url'];


        
        PaymentTransaction::create([
            'user_id' => $user->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => $prd->total_payment,
            'status' => 'unsuccessful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'market_place_payment',
            'description' => 'Market Place Sales'
        ]);
        return redirect($url);
    }

    public function marketPlacePaymentCallBack()
    {       
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        $ref = $params['trxref'];
        $payment = PaymentTransaction::where('reference', $ref)->first();
        $payment->status = 'successful';
        $payment->save();

        $updatePayment = MarketPlacePayment::where('ref', $ref)->first();
        $updatePayment->is_complete = true;
        $updatePayment->save();

        $product = MarketPlaceProduct::where('id', $updatePayment->market_place_product_id)->first();
        //credit referral commission
        $updateRefRev = Wallet::where('user_id', $updatePayment->user_id)->first();
        $updateRefRev->balance += $product->commission_payment;
        $updateRefRev->save();

        PaymentTransaction::create([
            'user_id' => $updatePayment->user_id,///auth()->user()->id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => $product->commission_payment,
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'market_place_commission',
            'description' => 'Market Place Commission for '.$product->name
        ]);

        $excludedUrl = explode('https://freebyz.s3.us-east-1.amazonaws.com/banners/', $product->banner);
        $bannerName = $excludedUrl[1];
        // Storage::disk('s3')->download('banners/'.$bannerName);
        return redirect('marketplace/payment/completion');
    }

    public function marketplaceCompletePayment()
    {
       return view('market_place_completed');
    }
}
