<?php 

namespace App\Helpers;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;

class PaystackHelpers{

    public static function bankList()
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->get('https://api.paystack.co/bank');

        return json_decode($res->getBody()->getContents(), true)['data'];
    }

    public static function resolveBankName($account_number, $bank_code)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->get('https://api.paystack.co/bank/resolve?account_number='.$account_number.'&bank_code='.$bank_code);
        return json_decode($res->getBody()->getContents(), true);
    }

    public static function recipientCode($name, $account_number, $bank_code)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transferrecipient', [
            "type"=> "nuban",
            "name"=> $name,
            "account_number"=> $account_number,
            "bank_code"=> $bank_code,
            "currency"=> "NGN"
        ]);

        return json_decode($res->getBody()->getContents(), true);

    }

    public static function transferFund($amount, $recipient)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transfer', [
            "source"=> "balance", 
            "amount"=> $amount, 
            "recipient"=> $recipient, 
            "reason"=> "Freebyz Cash Reward" 
        ]);

         return json_decode($res->getBody()->getContents(), true);
    }

    public static function reloadlyAuth0Token()
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            //'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://auth.reloadly.com/oauth/token', [
            "client_id"=> env('RELOADLY_CLIENT_ID'),
            "client_secret"=> env('RELOADLY_CLIENT_SECRET'),
            "grant_type"=>"client_credentials",
            "audience"=>"https://topups.reloadly.com"
        ]);

         return json_decode($res->getBody()->getContents(), true);
    }

    public static function getRealoadlyMobileOperator($bearerToken, $phone)
    {
        $res = Http::withHeaders([
            'Accept'=> 'application/com.reloadly.topups-v1+json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$bearerToken,
        ])->get('https://topups.reloadly.com/operators/auto-detect/phone/'.$phone.'/countries/NG?suggestedAmountsMap=true&SuggestedAmounts=true')->throw();

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function initiateReloadlyAirtime($bearerToken, $phone, $operatorId, $amount)
    {
        
        $res = Http::withHeaders([
            // 'Accept' => 'application/json',
            'Accept'=> 'application/com.reloadly.topups-v1+json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$bearerToken,
        ])->post('https://topups.reloadly.com/topups', [
            // "client_id"=>"bHVLIFVUZCiUnuRu8wlWGrzpBADxJCys",
            // "client_secret"=>"XqtUp4EciM-opj2pYWE52R4qh98iHe-NshjBtMzfcFok4jq2YnEIg0XWohnerSu",
            // "grant_type"=>"client_credentials",
            // "audience"=>"https://topups.reloadly.com"

            "operatorId"=>$operatorId,
            "amount"=>$amount,
            "customIdentifier"=> "This is example identifier 092",
            "recipientPhone"=> [
                "countryCode"=> "NG",
                "number"=> $phone,
            ],
            "senderPhone"=> [
                "countryCode"=> "CA",
                "number"=> "1231231231"
            ]
        ]);

        return json_decode($res->getBody()->getContents(), true);

    }

    public static function sendNotificaion($number, $message)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/send', [
            "to"=> $number,
            "from"=> "HOD",
            "sms"=> $message,
            "type"=> "plain",
            "channel"=> "generic",
            "api_key"=> env('TERMI_KEY')
        ]);

         return json_decode($res->getBody()->getContents(), true);
    }

    

}