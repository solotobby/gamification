<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaystackHelpers;
use App\Helpers\SystemActivities;
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
            $contacts = $this->filter($request, false);
        }elseif($type == 'verified'){
           $contacts = $this->filter($request, true);
        }

        $list = [];
        foreach($contacts as $key=>$value){
            $formatedPhone = '';
            $initials = $this->getInitials($value->phone); 

            if($initials == 0){
                $formatedPhone = '234'.substr($value->phone, 1);
            }elseif($initials == '+'){
                $formatedPhone = substr($value->phone, 1);
            }elseif($initials == 2){
                $formatedPhone = $value->phone;
            }else{
                $formatedPhone = '';
            }

            // $firstThreeChars = substr($phone, 0, 3);
            // if($firstThreeChars == 234){
            //     $phone = substr($phone, 3);
            // }

            $list[] = $formatedPhone;
        }

       

        $response = PaystackHelpers::sendBulkSMS($list, $request->message);
        if($response['code'] == 'ok'){
            return back()->with('success', 'Broadcast Sent');
        }else{
            return back()->with('error', 'An erro occour, broadcast not sent');
        }
    }

    public static function getInitials($phoneNumber){
        
        // Get the first digit
        $firstDigit = $phoneNumber[0];
    
        return $firstDigit; 
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
