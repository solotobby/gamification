<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $name = explode(" ", auth()->user()->name);
        // $payload = [
        //     "email"=> auth()->user()->email,
        //     "first_name"=> $name[0],
        //     "last_name"=> isset($name[1]) ? $name[1] : 'Freebyz',
        //     "phone"=> auth()->user()->phone
        // ];
        // $res = PaystackHelpers::createCustomer($payload);

        // $data = [
        //     "customer"=> $res['data']['customer_code'], 
        //     "preferred_bank"=>"wema-bank"
        // ];

        $data = [
            "email"=> auth()->user()->email,
            "first_name"=> $name[0],
            "middle_name"=> isset($name[1]) ? $name[1] : 'Dominahl',
            "last_name"=> isset($name[2]) ? $name[2] : 'Technologies',
            "phone"=> auth()->user()->phone,
            "preferred_bank"=> "test-bank",
            "country"=> "NG"
        ];
    
       return $response = PaystackHelpers::virtualAccount($data);

       

        // if($res['status'] == true){
        //     // $VirtualAccount = VirtualAccount::create(['user_id' => auth()->user()->id, 'channel' => 'paystack', 'customer_id'=>$res['data']['customer_code'], 'customer_intgration'=> $res['data']['integration']]);
        //     $data = [
        //             "customer"=> $res['data']['customer_code'], 
        //             "preferred_bank"=>"wema-bank"
        //         ];
            
        //     return $response = PaystackHelpers::virtualAccount($data);

        //     // $VirtualAccount->bank_name = $response['data']['bank']['name'];
        //     // $VirtualAccount->account_name = $response['data']['account_name'];
        //     // $VirtualAccount->account_number = $response['data']['account_number'];
        //     // $VirtualAccount->account_name = $response['data']['account_name'];
        //     // $VirtualAccount->currency = 'NGN';
        //     // $VirtualAccount->save();

        //     return back()->with('success', 'Account Created Succesfully');
        // }else{
        //     return back()->with('error', 'Error occured while processing');
        // }
  
    }
}
