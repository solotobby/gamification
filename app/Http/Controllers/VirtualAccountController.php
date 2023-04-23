<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data = [
            "email"=> auth()->user()->email,
            "first_name"=>auth()->user()->name,
            "middle_name"=> "Freebyz",
            "last_name"=> "Technology",
            "phone"=> auth()->user()->phone,
            "preferred_bank"=> "test-bank",
            "country"=> "NG"
        ];
       
       return PaystackHelpers::virtualAccount($data);
    }
}
