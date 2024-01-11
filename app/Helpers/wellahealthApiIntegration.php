<?php

use Illuminate\Support\Facades\Http;

if(!function_exists('listWellaHealthScriptions')){
    function listWellaHealthScriptions(){
        
       $subscriptions = Http::withBasicAuth('Test_WHKFlXCAs', 'RqAKd7cXiSRxcA5fD4Uwzw')
        ->get('https://staging.wellahealth.com/public/v1/SubscriptionPlans')->throw();

        return json_decode($subscriptions->getBody()->getContents(), true);
    }
}

if(!function_exists('createWellaHealthScription')){
    function createWellaHealthScription($payload){
        
       $subscriptions = Http::withBasicAuth('Test_WHKFlXCAs', 'RqAKd7cXiSRxcA5fD4Uwzw')
        ->post('https://staging.wellahealth.com/public/v1/Subscriptions', $payload)->throw();

        return json_decode($subscriptions->getBody()->getContents(), true);
    }
}