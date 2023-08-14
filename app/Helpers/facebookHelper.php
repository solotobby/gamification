<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FacebookHelper{

    public static function index(){
        return 'ok';
    }

    public static function generateAccessToken(){
        $url = "https://graph.facebook.com/oauth/access_token?client_id=272122624271485&client_secret=6a6f874f71c3b19271a9cd2418972dc1&grant_type=client_credentials";

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get($url);
        return json_decode($res->getBody()->getContents(), true)['access_token'];

    }


    public static function getPosts($token){
        // 267978826632355
        
        $post_id = '267978826632355';//{post-id}';
        $access_token = $token; //$this->generateAccessToken(); //'EAAD3fnxa5H0BOzgreKA0TSivonYvIZC8NWZAPHZBuAkYs5OPls8pNm7R4NDS5Kci6agesy5yzGV797qcp42uOuAegiJUZC9FZBIF7VSBCYmRhjsMxLbbUSuQjUZCoM68XPuwdFbr8bWvr7oOJwTMP2gahsDj4ddKcNKZBoxCl6ZCI35MEfoWE8uCzWqQDmj5u2Kte8SZAGeGCWJ8WZCQZDZD';

        $response = Http::withToken($access_token)
            ->get("https://graph.facebook.com/{$post_id}/likes");

        return $response->json();

        // return json_decode($res->getBody()->getContents(), true);

    }
}