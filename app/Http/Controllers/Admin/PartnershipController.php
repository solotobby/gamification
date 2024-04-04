<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnershipSubscriber;
use App\Models\PartnerSubscription;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(){
        $lists = PartnerSubscription::orderBy('created_at', 'DESC')->get(); //PartnershipSubscriber::orderBy('created_at', 'DESC')->get();
        return view('admin.partners.list', ['lists' => $lists]);
    }
}
