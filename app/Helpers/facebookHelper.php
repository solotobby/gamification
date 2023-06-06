<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FacebookHelper{

    public static function index(){
        return 'ok';
    }

    public static function generateAccessToken(){
        
    }


    public static function getPosts(){
        // 267978826632355
        
        $post_id = '267978826632355';//{post-id}';
        $access_token = 'EAAD3fnxa5H0BAPZCjkMSCIUr1PG7o6Tvz2KBl7bZCixTZCCfOwoJYMi3n71G2XGtu9yQRtrEqTx1aRKuaHaBvggDQ9tTgxnRZAaqnVxvcpxXMCWlh8ez0faZCoVo05PplkV3EIQfYxZBslP8fatnAZCALIfR0zAsHvo0nxOrokIuYOFRcO97drBWZAHXWfVcKvZA4PH204YX4LgZDZD';

        $response = Http::withToken($access_token)
            ->get("https://graph.facebook.com/{$post_id}/likes");

        return $response->json();

        // return json_decode($res->getBody()->getContents(), true);

    }
}