<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listBanks($countryCode){
        if($countryCode == 'GH'){
            $data = [
                ['code' => 'MTN', 'name' => 'MTN Mobile Money'],
                ['code' => 'AIRTELTIGO', 'name' => 'AIRTELTIGO Mobile Money'],
                ['code' => 'VODAFONE', 'name' => 'VODAFONE Mobile Money']
            ];
        }elseif($countryCode == 'KE'){
            $data = [
                ['code' => 'MPS', 'name' => 'M-Pesa'],
                ['code' => 'MPX', 'name' => 'Airtel Kenya'], 
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
