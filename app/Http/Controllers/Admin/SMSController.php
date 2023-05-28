<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaystackHelpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function massSMS(){
        return view('admin.broadcast_sms.index');
    }

    public function send_massSMS(Request $request){
        $type = $request->type;
       
        if($type == 'unverified'){
            $contacts = User::where('role', 'regular')->where('is_verified', false)->where('country', 'Nigeria')->select(['phone'])->get();
        }elseif($type == 'verified'){
            $contacts = User::where('role', 'regular')->where('is_verified', true)->where('country', 'Nigeria')->select(['phone'])->get();
        }
        $list = [];
        foreach($contacts as $key=>$value){
            $initials = PaystackHelpers::getInitials($value->phone);
            if($initials == 0){
                $phone = '234'.substr($value->phone, 1);
            }elseif($initials == '+'){
                $phone = substr($value->phone, 1);
            }
            $list[] = $phone;
        }
        return $list;
    }
}
