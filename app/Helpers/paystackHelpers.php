<?php 

namespace App\Helpers;

use App\Models\PaymentTransaction;
use Illuminate\Support\Env;
use App\Models\Statistics;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PaystackHelpers{

    public static function bankList()
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->get('https://api.paystack.co/bank')->throw();

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
            "reason"=> "Freebyz Withdrawal" 
        ]);

         return json_decode($res->getBody()->getContents(), true);
    }

    public static function initiateTrasaction($ref, $amount, $redirect_url){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => auth()->user()->email,
            'amount' => $amount*100,
            'channels' => ['card'],
            'currency' => 'NGN',
            'reference' => $ref,
            'callback_url' => env('PAYSTACK_CALLBACK_URL').$redirect_url
        ]);
       return $res['data']['authorization_url'];
    }

    public static function verifyTransaction($ref){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->get('https://api.paystack.co/transaction/verify/'.$ref)->throw();

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function paymentTrasanction($userId, $campaign_id, $ref, $amount, $status, $type, $description, $tx_type, $user_type)
    {
       return PaymentTransaction::create([
            'user_id' => $userId,
            'campaign_id' => $campaign_id,
            'reference' => $ref,
            'amount' => $amount,
            'status' => $status,
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => $type,
            'description' => $description,
            'tx_type' => $tx_type,
            'user_type' => $user_type
        ]);
    }

    public static function paymentUpdate($ref, $status){
        $fetchPaymentTransaction = PaymentTransaction::where('reference', $ref)->first();
        $fetchPaymentTransaction->status = $status;
        $fetchPaymentTransaction->save();
        return $fetchPaymentTransaction;
    }

    public static function sendNotificaion($number, $message)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/send', [
            "to"=> $number,
            "from"=> "FREEBYZ",
            "sms"=> $message,
            "type"=> "plain",
            "channel"=> "generic",
            "api_key"=> env('TERMI_KEY')
        ]);
        
         return json_decode($res->getBody()->getContents(), true);
    }

    public static function listFlutterwaveTransaction(){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->get('https://api.flutterwave.com/v3/transactions')->throw();

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function dailyVisit(){

        $date = \Carbon\Carbon::today()->toDateString();

        $check = Statistics::where('date', $date)->first();
        if($check == null)
        {
            Statistics::create(['type' => 'visits', 'date' => $date, 'count' => '1']);
        }else{
            $check->count += 1;
            $check->save();
        }
    } 

    ///////////////////

    public static function access_token(){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://sagecloud.ng/api/v2/merchant/authorization', [
            "email"=>"farohunbi.st@gmail.com",
	        "password"=>"Solomon001."
        ]);
        return json_decode($res->getBody()->getContents(), true)['data']['token']['access_token'];
    }

    public static function loadNetworkData($access_token, $network){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$access_token
        ])->get('https://sagecloud.ng/api/v2/internet/data/lookup?provider='.$network)->throw();
        return json_decode($res->getBody()->getContents(), true)['data'];
    }

    public static function purchaseData($access_token, $code, $network_type, $provider, $phone, $ref){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$access_token
        ])->post('https://sagecloud.ng/api/v2/internet/data', [
            "reference"=>$ref,
            "type"=>$network_type,
            "code"=>$code,
            "network"=>$provider,
            "phone"=>$phone,
            "provider"=>$provider
        ])->throw();
        return json_decode($res->getBody()->getContents(), true);
    }

    public static function buyAirtime($payload, $access_token){
        $res =  Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$access_token
        ])->post('https://sagecloud.ng/api/v2/epin/purchase', $payload)->throw();
        return json_decode($res->getBody()->getContents(), true);
    }

    ///////////////////////////

    public static function dailyActivities(){
        $data = User::select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as total_reg'), \DB::raw('SUM(is_verified) as verified'))
        ->where('created_at', '>=', Carbon::now()->subMonths(5))->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();
       
        $result[] = ['Year','Registered','Verified'];
        foreach ($data as $key => $value) {
            $result[++$key] = [$value->date, (int)$value->total_reg, (int)$value->verified];
        }

        return $result;
    }

    public static function dailyStats(){
        $data = Statistics::select(\DB::raw('DATE(date) as date'), \DB::raw('sum(count) as visits'))
        ->where('created_at', '>=', Carbon::now()->subMonths(2))->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();
        // DATE_FORMAT(created_at, "%d-%b-%Y")
        $dailyVisitresult[] = ['Year','Visits'];
        foreach ($data as $key => $value) {
            $dailyVisitresult[++$key] = [$value->date, (int)$value->visits];
        }

        return $dailyVisitresult;
    }
    
    public static function monthlyVisits(){
        $MonthlyVisitresult = User::select(\DB::raw('DATE_FORMAT(created_at, "%b %Y") as month, COUNT(*) as user_per_month, SUM(is_verified) as verified_users'))
         ->where('created_at', '>=', Carbon::now()->subMonths(5))->groupBy('month')->get();
        $MonthlyVisit[] = ['Month', 'Users','Verified'];
        foreach ($MonthlyVisitresult as $key => $value) {
            $MonthlyVisit[++$key] = [$value->month, (int)$value->user_per_month, (int)$value->verified_users ];
        }
        return $MonthlyVisit;
    }

    public static function registrationChannel(){
        $registrationChannel = User::select('source', \DB::raw('count(*) as total'))->groupBy('source')->get();
        $list[] = ['Channel', 'Total'];
         foreach($registrationChannel as $key => $value){
            $list[++$key] = [$value->source, (int)$value->total ];
         }
         return $list;
    }

    public static function sendBulkSMS($number, $message){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/send/bulk', [
            "to"=> $number,
            "from"=> "FREEBYZ",
            "sms"=> $message,
            "type"=> "plain",
            "channel"=> "generic",
            "api_key"=> env('TERMI_KEY')
        ]);

        return json_decode($res->getBody()->getContents(), true);
    }

    public static function numberFormat($number, $plus = true){
        if($number >= 1000000000){
            $number = number_format(($number/1000000000), 1);
            $number = $number > (int)$number && $plus ? (int)$number.'B+':(int)$number.'B';
            return $number;
        }
        if($number >= 1000000){
            $number = number_format(($number/1000000), 1);
            $number = $number > (int)$number && $plus ? (int)$number.'M+':(int)$number.'M';
            return $number;
        }

        if($number >= 1000){
            $number = number_format(($number/1000), 1);
            $number = $number > (int)$number && $plus ? (int)$number.'K+':(int)$number.'K';
            return $number;
        }
        return $number;
    }
// add_filter("wdm_before_fetch_template", function($vars, $template, $type){
//     $vars['download_count'] = number_format($vars['download_count']);
//     return $vars;
// }, 10, 3);
}