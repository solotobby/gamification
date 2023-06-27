<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class Sendmonny{

    public static function index(){
        return 'ok';
        // $url = 'https://superwaih.github.io/-web-gis-portal/'; // Replace with the desired URL

        // $client = new Client();
        // $response = $client->get($url);

        // return $response->getBody()->getContents();
        // $response = Http::get('http://example.com');
    }
    public static function sendUserToSendmonny($payload){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(env('SENDMONNY_URL').'user/single/registration', $payload);
        return json_decode($res->getBody()->getContents(), true);
    }


    public static function accessToken(){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(env('SENDMONNY_URL').'authenticate', [
            "phone_number"=>"2348137331455",
	        "password"=>"solomon001"
        ]);
        return json_decode($res->getBody()->getContents(), true)['data']['token'];
    }

    //requires authentication
    public static function getUser(){

    } 


     
}