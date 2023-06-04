<?php 

namespace App\Helpers;

use App\Models\PaymentTransaction;
use App\Models\Statistics;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;

class CapitalSage{
    public static function access_token(){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://sagecloud.ng/api/v2/merchant/authorization', [
            "email"=>"farohunbi.st@gmail.com",
	        "password"=>"Solomon001."
        ]);
        return json_decode($res->getBody()->getContents(), true);//['data']['token']['access_token'];
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
}