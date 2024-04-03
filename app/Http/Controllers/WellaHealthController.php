<?php

namespace App\Http\Controllers;

use App\Models\PartnerSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WellaHealthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {    
      return view('user.partner.wellahealth.index');
    }

   
}
