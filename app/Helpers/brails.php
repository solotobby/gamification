<?php

use Illuminate\Support\Facades\Http;

// if(!function_exists('getCountriesSupported')){
//     function getCountriesSupported(){
    
//         $res = Http::withHeaders([
//             'Accept' => 'application/json',
//             'Content-Type' => 'application/json',
//             'Authorization' => 'Bearer '.env('BRAILS_API_KEY')
//         ])->get(env('BRAILS_BASE_URL').'wallets/payout/supported-countries/');
//         return json_decode($res->getBody()->getContents(), true);
//     }
// }

// if(!function_exists('getCountryRequirement')){
//     function getCountryRequirement($countryCode){
    
//         $res = Http::withHeaders([
//             'Accept' => 'application/json',
//             'Content-Type' => 'application/json',
//             'Authorization' => 'Bearer '.env('BRAILS_API_KEY')
//         ])->get(env('BRAILS_BASE_URL').'wallets/payout/supported-countries/'.$countryCode);
//         return json_decode($res->getBody()->getContents(), true);
//     }
// }

// if(!function_exists('createBrailsBasicCustomer')){
//     function createBrailsBasicCustomer($payload){

//         $res = Http::withHeaders([
//             'Accept' => 'application/json',
//             'Content-Type' => 'application/json',
//             'Authorization' => 'Bearer '.env('BRAILS_API_KEY')
//         ])->post(env('BRAILS_BASE_URL').'customers', $payload);
//         return json_decode($res->getBody()->getContents(), true);

//     }
// }

// if(!function_exists('createBrailsBeneficiary')){
//     function createBrailsBeneficiary($payload){ 

//         $res = Http::withHeaders([
//             'Accept' => 'application/json',
//             'Content-Type' => 'application/json',
//             'Authorization' => 'Bearer '.env('BRAILS_API_KEY')
//         ])->post(env('BRAILS_BASE_URL').'beneficiaries', $payload);
//         return json_decode($res->getBody()->getContents(), true);

//     }
// }

// if(!function_exists('initiateBrailsPayout')){
//     function initiateBrailsPayout($payload){ 

//         $res = Http::withHeaders([
//             'Accept' => 'application/json',
//             'Content-Type' => 'application/json',
//             'Authorization' => 'Bearer '.env('BRAILS_API_KEY')
//         ])->post(env('BRAILS_BASE_URL').'wallets/payout/initialize', $payload);
//         return json_decode($res->getBody()->getContents(), true);

//     }
// }

// if(!function_exists('finalizeBrailsPayout')){
//     function finalizeBrailsPayout($payload){ 

//         $res = Http::withHeaders([
//             'Accept' => 'application/json',
//             'Content-Type' => 'application/json',
//             'Authorization' => 'Bearer '.env('BRAILS_API_KEY')
//         ])->post(env('BRAILS_BASE_URL').'wallets/payout/finalize', $payload);
//         return json_decode($res->getBody()->getContents(), true);

//     }
// }

// ///Card mgt
// if(!function_exists('createBrailsVirtualCard')){
//     function createBrailsVirtualCard($payload){
//         $res = Http::withHeaders([
//             'Accept' => 'application/json',
//             'Content-Type' => 'application/json',
//             'Authorization' => 'Bearer '.env('BRAILS_API_KEY')
//         ])->post(env('BRAILS_BASE_URL').'virtualcards/create', $payload);
//         return json_decode($res->getBody()->getContents(), true);
//      }

// }

if(!function_exists('brailRates')){
    function brailRates(){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('BRAILS_API_KEY')
        ])->get(env('BRAILS_BASE_URL').'wallets/payout/rates');
        return json_decode($res->getBody()->getContents(), true);
     }

}


https://sandboxapi.onbrails.com/api/v1/wallets/payout/rates