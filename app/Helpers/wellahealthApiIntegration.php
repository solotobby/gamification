<?php

use Illuminate\Support\Facades\Http;

if(!function_exists('listWellaHealthScriptions')){
    function listWellaHealthScriptions(){
        
       $subscriptions = Http::withBasicAuth(env('WELLAHEALTH_USER'), env('WELLAHEALTH_KEY'))
        ->get('https://staging.wellahealth.com/public/v1/SubscriptionPlans')->throw();

        return json_decode($subscriptions->getBody()->getContents(), true);
    }
}

if(!function_exists('createWellaHealthScription')){
    function createWellaHealthScription($payload){
        
       $subscriptions = Http::withBasicAuth(env('WELLAHEALTH_USER'), env('WELLAHEALTH_KEY'))
        ->post('https://staging.wellahealth.com/public/v1/Subscriptions', $payload)->throw();

        return json_decode($subscriptions->getBody()->getContents(), true);
    }
}

if(!function_exists('getWellaHealthScription')){
    function getWellaHealthScription($subscriptionCode){
        
       $subscriptions = Http::withBasicAuth(env('WELLAHEALTH_USER'), env('WELLAHEALTH_KEY'))
        ->get('https://staging.wellahealth.com/public/v1/Subscriptions/code/'.$subscriptionCode)->throw();

        return json_decode($subscriptions->getBody()->getContents(), true);
    }
}
