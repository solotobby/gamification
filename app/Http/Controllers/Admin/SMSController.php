<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaystackHelpers;
use App\Helpers\SystemActivities;
use App\Http\Controllers\Controller;
use App\Jobs\SendMassEmail;
use App\Mail\GeneralMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $channel = $request->channel;
        if($channel == 'sms'){
            $usersEmail = User::where([
                [function ($query) use ($request) {
                    $query->where('role', 'regular')
                            ->whereBetween('created_at', [$request->start_date, $request->end_date])
                            ->get();
                }]
            ])->get();
            
            foreach($usersEmail as $user){
                    dispatch(new SendMassEmail($user, $request->message, 'Freebyz'));
             }
             return back()->with('success', 'Email Broadcast Sent');

        }else{
            $type = $request->type;
            if($type == 'unverified'){
                $contacts = $this->filter($request, false);
            }elseif($type == 'verified'){
                $contacts = $this->filter($request, true);
            }elseif($type == 'survey'){
                $contacts = $this->filtersurvey($request, true);
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

                $list[] = $formatedPhone;
            }
            
            $response = PaystackHelpers::sendBulkSMS($list, $request->message);
            if($response['code'] == 'ok'){
                return back()->with('success', 'Sms Broadcast Sent');
            }else{
                return back()->with('error', 'An erro occour, broadcast not sent');
            }
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
        }elseif($type == 'survey'){
            $contacts = $this->filtersurvey($request, 'survey');
        }
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

    public function filtersurvey($request, $value){
        $users = User::where([
            [function ($query) use ($request, $value) {
                $query->where('role', 'regular')
                        ->whereBetween('created_at', [$request->start_date, $request->end_date])
                        ->where('age_range', null)
                        ->where('country', 'Nigeria')
                        ->get();
            }]
        ])->select(['phone'])->get();
        return $users;
    }
}
