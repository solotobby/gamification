<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __construct()
    {
         $this->middleware(['auth', 'email']);
        // $this->middleware('auth');
    }

    public function listBanks($countryCode){
        if($countryCode == 'GH'){
            $data = [
                ['code' => 'MTN', 'name' => 'MTN Mobile Money', 'currency' => 'GHS'],
                ['code' => 'AIRTELTIGO', 'name' => 'AIRTELTIGO Mobile Money', 'currency' => 'GHS'],
                ['code' => 'VODAFONE', 'name' => 'VODAFONE Mobile Money', 'currency' => 'GHS']
            ];
        }elseif($countryCode == 'KE'){
            $data = [
                ['code' => 'MPS', 'name' => 'M-Pesa', 'currency' => 'KES'],
                ['code' => 'MPX', 'name' => 'Airtel Kenya', 'currency' => 'KES'],
            ];
        }else{
            $data = [
                ['code' => 'MPS', 'name' => 'M-Pesa']
            ];
        }

        return response()->json(['data'=>$data, 'message' => 'Bank List'], 200);

        // return flutterwaveListBanks($countryCode);
    }

    public function rates(){
        return brailRates();
    }
}
