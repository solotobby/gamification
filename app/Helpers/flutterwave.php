<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('listBanks')) {
    function listBanks($payload)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/charges?type=mobile_money_ghana', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    
    }
}

if (!function_exists('flutterwaveListBanks')) {
    function flutterwaveListBanks($countryCode)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->get('https://api.flutterwave.com/v3/banks/'.$countryCode)->throw();

        return json_decode($res->getBody()->getContents(), true);
    
    }
}


if (!function_exists('createCard')) {
    function createCard($payload)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/virtual-cards', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    
    }
}

if (!function_exists('bvnVerification')) {
    function bvnVerification($payload)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/bvn/verifications', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    
    }
}



