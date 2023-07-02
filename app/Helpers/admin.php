<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class Admin{
    public static function index(){
        return 'ok';
    }

    public static function revenueAccount(){
        
    }
    
    public static function collectionAccount(){
        $walletId = env('COLLECTIION_WALLET_ID');
        $userId = env('COLLECTIION_USER_ID');

        $data['wallet_id'] = $walletId;
        $data['user_id'] = $userId;
        
        return $data; //json_decode($data->getBody()->getContents(), true);
    }

}