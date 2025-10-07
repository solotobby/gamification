<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    public function __construct()
    {
         // $this->middleware(['auth', 'email']);
        $this->middleware('auth');
    }

    public function index(Request $request){
        // $request->validate([
        //     'bvn' => 'required|numeric|digits:10'
        // ]);

        $payload = [
            // "email"=> auth()->user()->email,
            // "first_name"=> $name[0],
            // "last_name"=> isset($name[1]) ? $name[1] : 'Freebyz',
            // "phone"=> auth()->user()->phone

            "email"=> "solotobz5@gmail.com",
            "first_name"=> "Oluwatobi",
            "last_name"=> "Solomon",
            "phone"=> "+2348137331282"
        ];
        $res = createCustomer($payload);

        $data = [
            "customer"=> $res['data']['customer_code'],
            "preferred_bank"=>"test-bank"
        ];

        // $payload = [
        //     "email"=> auth()->user()->email,
        //     "first_name"=> $name[0],
        //     "middle_name"=> isset($name[1]) ? $name[1] : 'Dominahl',
        //     "last_name"=> isset($name[2]) ? $name[2] : 'Technologies',
        //     "phone"=> auth()->user()->phone,
        //     "preferred_bank"=> "test-bank",
        //     "country"=> "NG"
        // ];




      $response = PaystackHelpers::virtualAccount($data);

      $datas['res'] = $res;
      $datas['response'] = $response;

      return $datas;

      return back()->with('success', 'Account Created Succesfully');



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

    function RandomString($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }

}
