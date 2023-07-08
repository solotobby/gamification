<?php

use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Models\AccountInformation;
use App\Models\Settings;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

if (!function_exists('GetSendmonnyUserId')) {  //sendmonny user idauthenticated user
    function GetSendmonnyUserId()
    {
       return AccountInformation::where('user_id', auth()->user()->id)->first()->_user_id;
    }
}
if (!function_exists('GetSendmonnyUserWalletId')) { //sendmonny wallet id of authenticated user
    function GetSendmonnyUserWalletId()
    {
       return AccountInformation::where('user_id', auth()->user()->id)->first()->wallet_id;
    }
}

if(!function_exists('adminCollection')){
    function adminCollection(){
        $walletId = env('COLLECTIION_WALLET_ID');
        $userId = env('COLLECTIION_USER_ID');

        $data['wallet_id'] = $walletId;
        $data['user_id'] = $userId;

        return $data;
    }
}

if(!function_exists('adminRevenue')){
    function adminRevenue(){
        $walletId = env('REVENUE_WALLET_ID');
        $userId = env('REVENUE_USER_ID');

        $data['wallet_id'] = $walletId;
        $data['user_id'] = $userId;

        return $data;
    }
}

if(!function_exists('accessToken')){
    function accessToken(){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(env('SENDMONNY_URL').'authenticate', [
            "phone_number"=>env('PHONE'),
	        "password"=>env('PASS')
        ]);
        return json_decode($res->getBody()->getContents(), true)['data']['token'];
    }
}

if(!function_exists('userWalletId')){
    function userWalletId($id){
        return AccountInformation::where('user_id', $id)->first()->wallet_id;
    }
}

if(!function_exists('isBlacklisted')){
    function isBlacklisted($user){
        $blackist = User::where('id', $user->id)->is_blacklisted;
        if($blackist == true){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('walletHandler')){
    function walletHandler(){
        return Settings::where('status', true)->first()->name;
    }
}

if(!function_exists('activateSendmonnyWallet')){
    function activateSendmonnyWallet($user, $password){
       
        $initials = SystemActivities::getInitials($user->phone);
            $phone = '';
            if($initials == 0){
                $phone = '234'.substr($user->phone, 1);
            }elseif($initials == '+'){
                $phone = substr($user->phone, 1);
            }elseif($initials == 2){
                $phone = $user->phone;
            }
        $name = explode(" ", $user->name);

        $payload = [
            'first_name' => $name[0],
            'last_name' => (isset($name[1]) ? $name[1] : 'sendmonny'),
            'password' => $password,
            'password_confirmation' => $password,
            'email' => $user->email,
            'username' => Str::random(7),
            'phone_number' => $phone, //'234'.substr(auth()->user()->phone, 1), //substr($request->phone_number['full'], 1),
            'user_type' => "CUSTOMER",
            'mobile_token' => Str::random(7),
            'source' => 'Freebyz'
        ];

       $sendMonny = Sendmonny::sendUserToSendmonny($payload);
       if($sendMonny['status'] == true){
            $account = AccountInformation::create([
                'user_id' => $user->id,
                '_user_id' => $sendMonny['data']['user']['user_id'],
                'wallet_id' => $sendMonny['data']['wallet']['id'],
                'account_name' => $sendMonny['data']['wallet']['account_name'],
                'account_number' => $sendMonny['data']['wallet']['account_number'],
                'bank_name' => $sendMonny['data']['wallet']['bank'],
                'bank_code' => $sendMonny['data']['wallet']['bank_code'],
                'provider' => 'Sendmonny - Sudo',
                'currency' => $sendMonny['data']['wallet']['currency'],
            ]);

            $activate = User::where('id', $user->id)->first();
            $activate->is_wallet_transfered = true;
            $activate->save();

            $wallet = Wallet::where('user_id', $user->id)->first();
             $payload = [
                "sender_wallet_id" => adminCollection()['wallet_id'], //freebyz admin wallet id
                "sender_user_id" => adminCollection()['user_id'], //freebyzadmin sendmonny userid
                "amount" => $wallet->balance,
                "pin"=> "2222",
                "narration" => "Sendmonny Wallet Transfer",
                "islocal" => true,
                "reciever_wallet_id" => userWalletId($user->id)
            ];
        
            $completeTransfer = Sendmonny::transfer($payload, accessToken());
            if($completeTransfer['status'] == true){
                $wallet->balance = 0;
                $wallet->save();
            }
        }
       return $completeTransfer;
    }
    
}