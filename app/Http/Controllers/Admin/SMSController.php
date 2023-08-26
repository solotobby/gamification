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


// use SendGrid\Mail\To;
// use SendGrid\Mail\Cc;
// use SendGrid\Mail\Bcc;
// use SendGrid\Mail\From;
// use SendGrid\Mail\Content;
// use SendGrid\Mail\Mail;
// use SendGrid\Mail\Personalization;
// use SendGrid\Mail\Subject;
// use SendGrid\Mail\Header;
// use SendGrid\Mail\CustomArg;
// use SendGrid\Mail\SendAt;
// use SendGrid\Mail\Attachment;
// use SendGrid\Mail\Asm;
// use SendGrid\Mail\MailSettings;
// use SendGrid\Mail\BccSettings;
// use SendGrid\Mail\SandBoxMode;
// use SendGrid\Mail\BypassListManagement;
// use SendGrid\Mail\Footer;
// use SendGrid\Mail\SpamCheck;
// use SendGrid\Mail\TrackingSettings;
// use SendGrid\Mail\ClickTracking;
// use SendGrid\Mail\OpenTracking;
// use SendGrid\Mail\SubscriptionTracking;
// use SendGrid\Mail\Ganalytics;
// use SendGrid\Mail\ReplyTo;



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
        if($channel == 'mail'){
            $usersEmail = User::where([
                [function ($query) use ($request) {
                    $query->where('role', 'regular')
                            ->where('created_at', '>=', $request->start_date)
                            ->where('created_at', '<=', $request->end_date)
                            //->whereBetween('created_at', [$request->start_date, $request->end_date])
                            ->get();
                }]
            ])->get('email');

            // $list =[];
            foreach($usersEmail as  $key => $value){
                $list[] = [$value];
            }

            // return $list;

            $payload = [
                'personalizations' => [
                    [
                       'to' => $list
                       
                    //    [
                        
                    //         // ['email' => 'solotobby@gmail.com'],
                    //         // ['email' => 'solotobz5@gmail.com'],
                    //     ]
                    ]
                ],
                'from' => [
                    'email' => 'freebyzcom@gmail.com'
                ],
                'subject' => 'Freebyz!',
                'content' => [
                    [
                        'type' => 'text/html',
                        'value' => @$this->emailTemplate($request->message)
                    ]
                ]
            ];
            
            sendGridEmails($payload);
              
            
            // foreach($usersEmail as $user){
            //         dispatch(new SendMassEmail($user, $request->message, 'Freebyz'));
            //  }
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

    public function emailTemplate($message){

        $list3 = '
        <!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,600" rel="stylesheet" type="text/css">
    <!-- Web Font / @font-face : BEGIN -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->

    <!--[if !mso]>
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,600" rel="stylesheet" type="text/css">
    <![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    
    
    <style>
        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: sans-serif !important;
            font-size: 14px;
            margin-bottom: 10px;
            line-height: 24px;
            color:#8094ae;
            font-weight: 400;
        }
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }
        a {
            text-decoration: none;
        }
        img {
            -ms-interpolation-mode:bicubic;
        }
    </style>

</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f5f6fa;">
	<center style="width: 100%; background-color: #f5f6fa;">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f5f6fa">
            <tr>
               <td style="padding: 40px 0;">

                <table style="width:100%;max-width:620px;margin:0 auto;">
                    <tbody>
                        <tr>
                            <td style="text-align:center;padding-bottom:25px">
                                <center>
                             
                                <p style="font-size: 34px; color: #6576ff; padding-top: 12px;">Freebyz.com</p>
                                </center>
                            </td>
                        </tr>
                    </tbody>
                </table>



                                
                <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
                <tbody>
                    <tr>
                        <td style="padding: 30px 30px 15px 30px;">
                            <h2 style="font-size: 18px; color: #6576ff; font-weight: 600; margin: 0;">Upgrade Successful</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px 30px 20px">
                            <p style="margin-bottom: 10px;">Hi</p>
                            <p style="margin-bottom: 10px;">'.
                                $message;
                            '</p>
                            <p style="margin-bottom: 10px;">
                                Click the button below to access more jobs... <br><br>
                                <a href="https://freebyz.com/user/home" target="_blank" style="background-color:#6576ff;border-radius:4px;color:#ffffff;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform: uppercase; padding: 0 30px">
                                    Get Started Now
                                </a>
                            </p>
                            <p style="margin-top: 45px; margin-bottom: 15px;">---- <br> Regards, <br><i>Freebyz Team.</i></p>
                        </td>
                    </tr>
                </tbody>
                </table>

                    
                <table style="width:100%;max-width:620px;margin:0 auto;">
                    <tbody>
                        <tr>
                            <td style="text-align:center;padding:25px 0 0;">
                                <center><p style="font-size: 13px;">Copyright © Freebyz.com . All rights reserved.</p></center>
                               
                            </td>
                        </tr>
                    </tbody>
                </table>

               </td>
            </tr>
        </table>
    </center>
</body>
</html>

        
        ';
       
       
        return $list3;

       



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
