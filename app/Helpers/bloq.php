<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('bloqCreateCustomer')) {
    function bloqCreateCustomer($payload)
    {
        
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('BLOQ_SECRET_KEY')
        ])->post(env('BLOQ_URL').'customers', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    
    }
}

if (!function_exists('bloqCreateWallet')) {
    function bloqCreateWallet($payload)
    {
        
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('BLOQ_SECRET_KEY')
        ])->post(env('BLOQ_URL').'wallets', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);

    }
}

if (!function_exists('bloqUpgradeCustomerKYC1')) {
    function bloqUpgradeCustomerKYC1($payload, $customerID)
    {
        
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('BLOQ_SECRET_KEY')
        ])->put(env('BLOQ_URL').'customers/upgrade/t1/'.$customerID, $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    
    }
}

if (!function_exists('bloqIssueCard')) {
    function bloqIssueCard($payload)
    {
        
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('BLOQ_SECRET_KEY')
        ])->post(env('BLOQ_URL').'cards', $payload)->throw();

        return json_decode($res->getBody()->getContents(), true);
    
    }
}