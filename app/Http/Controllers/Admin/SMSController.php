<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaystackHelpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;



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
            $phone = '';
            if($initials == 0){
                $phone = '234'.substr($value->phone, 1);
            }elseif($initials == '+'){
                $phone = substr($value->phone, 1);
            }elseif($initials == 2){
                $phone = $value->phone;
            }

            $firstThreeChars = substr($phone, 0, 3);
            if($firstThreeChars == 234){
                $phone = substr($phone, 3);
            }

            if ($phone) {
                $list[] = $phone;
            }
        }
        $response = PaystackHelpers::sendBulkSMS($list, $request->message);
        if($response['code'] == 'ok'){
            return back()->with('success', 'Broadcast Sent');
        }else{
            return back()->with('success', 'An erro occour, broadcast not sent');
        }
    }

    public function massSMSPreview(Request $request){
        $type = $request->type;
        if($type == 'unverified'){
            $contacts = $this->filter($request, false);
        }elseif($type == 'verified'){
           $contacts = $this->filter($request, true);
        }
        
       // Alert::success('Success Title', 'Success Message');
        return $contacts;
    }

    public function filter($request, $value){
        $users = User::where([
            [function ($query) use ($request, $value) {
                $query->where('role', 'regular')
                        ->whereBetween('created_at', [$request->start_date, $request->end_date])
                        ->where('is_verified', $value)
                        ->where('country', 'Nigeria')
                        ->get();
            }]
        ])->select(['phone'])->get();
        return $users;
    }
}
