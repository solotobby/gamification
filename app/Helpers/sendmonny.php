<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class Sendmonny{

    public static function index(){
        //return 'ok';
        $url = 'https://superwaih.github.io/-web-gis-portal/'; // Replace with the desired URL

        $client = new Client();
        $response = $client->get($url);

        return $response->getBody()->getContents();
        // $response = Http::get('http://example.com');
    }
}