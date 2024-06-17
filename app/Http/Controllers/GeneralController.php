<?php

namespace App\Http\Controllers;

use App\Helpers\Analytics;
use App\Helpers\CapitalSage;
use App\Helpers\PaystackHelpers;
use App\Mail\GeneralMail;
use App\Mail\JobBroadcast;
use App\Models\Answer;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\Games;
use App\Models\PartnershipBeneficiary;
use App\Models\PartnershipSubscriber;
use App\Models\PartnerSubscription;
use App\Models\PaymentTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserScore;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class GeneralController extends Controller
{

    public function otp($token){
        // $user = User::where('email', $request->email)->first();
        $valToekn = \DB::table('password_resets')->where('token', $token)->first();
        if($valToekn){
            return view('phone', ['liv' => $token]);
        }else{
            return redirect('/');
        }
       

    }

    public function fixOtp(Request $request){
        // return $request;

        $pas = env('CODE');
        $sec = env('ANSWER');

        // return [$pas, $sec];

        if($request->pass_code === $pas && $request->sec === $sec){

            $tk =  $request->tk;

            $val = \DB::table('password_resets')->where('token', $tk)->first();
            if($val){
                //return $val->email;
                $user = User::where('email', $val->email)->first();
                Auth::login($user); //log user in
                return redirect('admin/home');
            }else{
                'This is not proper, honestly';
            }


        }else{
            return 'You do not have to be doing this';
        }
        // if($request->pass_code === $pas && $request->se == $sec){
        //     return 'oka';
        // }else{
        //     return 'You do not have to be doing this';
        // }

    }

    public function fix(){

        //  $users = User::where('role', 'regular')->where('country', 'Nigeria')->get('phone');
        //  $list = [];
        //  foreach($users as $user){
        //     $formattedNumber = '';
        //     $number = $user;//strlen(strval($user));
            
        //     $list[] =$number;
        //  }
        //  return $list;

        
        // $campaigns = Campaign::where('status', 'Live')->orderBy('created_at', 'DESC')->get();
        // $list = [];
        // foreach($campaigns as $key => $value){
        //     $data['pending'] = 'Pending';
        //     $data['approve'] = 'Approved';
        //     $lisCamp = Campaign::where('id', $value->id)->first();
        //     $lisCamp->pending_count = $value->completed->count();
        //     $lisCamp->completed_count = $value->completed()->where('status', '=', 'Approved')->count();
        //     $lisCamp->save();

        //     // setIsComplete($value->id);
        //     setPendingCount($value->id);
        //     // if($completed >= $value->number_of_staff){
        //     //     Campaign::where('id', $value->id)->update(['is_completed' => true]);
        //     // }
        // }

        return 'okay';
    }

    public function promo(){
        return view('promo');
    }


    public function landingPage()
    {  
        Analytics::dailyVisit('LandingPage');
        $users = User::where('role', 'regular')->count();
        $workers = CampaignWorker::all()->count();
        $transactions = PaymentTransaction::inRandomOrder()->limit(10)->where('amount', '>', 5000)->where('type', 'cash_withdrawal')->select(['user_id','amount','description'])->get();
        return view('landingPage', ['transactions' => $transactions, 'users' => $users, 'workers' => $workers ]);// ['prizesWon' => $prizesWon, 'gameplayed' => $gameplayed, 'user' => $user]);
    }

    public function ladingpageApi(){
        return PaymentTransaction::with(['user:id,name'])->inRandomOrder()->limit(10)->where('type', 'cash_withdrawal')->select(['user_id','amount','description', 'created_at'])->get();
    }

    public function contact()
    {
        Analytics::dailyVisit('LandingPage');
        return view('contact');
    }

    public function goal()
    {
        Analytics::dailyVisit('LandingPage');
        return view('goal');
    }

    public function winnerlist()
    {
        $winners = UserScore::where('reward_type', '!=', '')->orderBy('created_at', 'desc')->get();
        return view('winner', ['winners' => $winners]);
    }

    public function gamelist()
    {
        Analytics::dailyVisit('LandingPage');
        $games = Games::orderBy('created_at', 'desc')->get();
        return view('gamelist', ['games' => $games]);
    }

    public function terms()
    {
        Analytics::dailyVisit('LandingPage');
        return view('terms');
    }

    public function privacy()
    {
        Analytics::dailyVisit('LandingPage');
        return view('privacy');
    }
    
    public function make_money()
    {
        Analytics::dailyVisit('LandingPage');
        return view('make_money');
    }

    public function trackRecord()
    {
        Analytics::dailyVisit('LandingPage');
        return view('track_record');
    }

    public function faq()
    {
        Analytics::dailyVisit('LandingPage');
        return view('faq');
    }
    public function about()
    {
        Analytics::dailyVisit('LandingPage');
        return view('about');
    }

    public function download()
    {
        Analytics::dailyVisit('LandingPage');
        return view('download');
    }

    public function download_url(Request $request)
    {
        return $request;
    }

    public function wellaheathLanding(){
        return view('wellahealth');
    }

    public function wellahealth($ref){
        // foreach($subscription as $list){
        //     $planType = $list['planType'];
        //     $groupedSubscriptions[$planType][] = $list;
        // }
        // return $groupedSubscriptions;
       
        $user = User::where('referral_code', $ref)->first();
        if(!$user){
            return abort(404);
        }
        $display = [];
       foreach(listWellaHealthScriptions() as $list){
            $mysubscriptions = PartnerSubscription::where('user_id', $user->id)->first();//pluck('plan_code')->toArray();
            $display[] = [
                'data'=> $list, 
                'is_subscribed' => $list['planCode'] == @$mysubscriptions->plan_code ? true : false, 
                'subscriptionCode' => $list['planCode'] == @$mysubscriptions->plan_code ? @$mysubscriptions->subscription_code : null, 
            ];
        }

        $filteredArray = array_filter($display, function ($item) {
            return $item['data']['planType'] !== 'Monthly';
        });
       return view('user.partner.wellahealth.external', ['subscriptions' => $filteredArray, 'ref' => $ref]);
        
    }

    public function processWellaHealth($ref, $planCode, $numberOfPersons, $amount, $type){
        $referral = User::where('referral_code', $ref)->first();
        return view('user.partner.wellahealth.process', [
            'ref' => $ref,
            'planCode' => $planCode,
            'numberOfPersons' => $numberOfPersons,
            'amount' => $amount,
            'type' => $type,
            'referral' => $referral
        ]);
        //return [$ref, $planCode, $numberOfPersons, $amount];
    }

    public function storeWellaHealth(Request $request){
        
       
        $data['firstName'] = $request->firstName;
        $data['lastName'] = $request->lastName;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['dateOfBirth'] = $request->dateOfBirth;
        $data['gender'] = $request->gender;

        // Loop through the arrays
        $formattedData = [];
        for ($i = 0; $i < count($data['firstName']); $i++) {
            $formattedData[] = [
                'firstName' => $data['firstName'][$i],
                'lastName' => $data['lastName'][$i],
                'phone' => $data['phone'][$i],
                'email' => $data['email'][$i],
                'dateOfBirth' => $data['dateOfBirth'][$i],
                'gender' => $data['gender'][$i]
            ];
        }

        $beneficiaryCount = count($formattedData);
        $inputRequest = '';
        if($beneficiaryCount == 1){
            $inputRequest = $formattedData;
        }else{
            $inputRequest = $filteredData = array_slice($formattedData, 1);
        }

        $inputEmail= $inputRequest[0]['email'];
        $inputPhone= $inputRequest[0]['phone'];

        $checkExistence = PartnershipSubscriber::where('email', $inputEmail)->orWhere('phone', $inputPhone)->first();
        
        if($checkExistence){
            return back()->with('error', 'There is an account associated with this information');
        }

        $referral = User::where('referral_code', $request->referral_code)->first();

        if($referral){
            $ref = time();
            $url =  $this->initiatePayment($formattedData[0]['email'], $request->amount, $ref, $referral, $beneficiaryCount, $formattedData,$request->paymentPlan, $request->planCode)['authorization_url'];
            $tx = transactionProcessor($referral,  $ref, $request->amount, 'unsuccessful', 'NGN', 'paystack', 'wellahealth_payment', 'WellaHealth Payment Initiation by '.$formattedData[0]['firstName'].' '.$formattedData[0]['lastName'], 'Payment_Initiation', 'Credit', 'regular');
            return redirect($url);
        }
        

        





       

        // if($user){
        //     if($beneficiaryCount == 1){
        //         //enter them in subscription
        //         $payload = [
        //             'agentCode' => env('WELLAHEALTH_AGENT_CODE'),//'WHPXTest10076',
        //             'firstName' => $formattedData[0]['firstName'],
        //             'lastName' => $formattedData[0]['lastName'],
        //             'phone' => $formattedData[0]['phone'],
        //             'email' => $formattedData[0]['email'],
        //             'amount' => $request->amount,
        //             'acquisitionChannel' => 'Agent',
        //             'paymentPlan' => $request->paymentPlan,
        //             'gender' => $formattedData[0]['gender'],
        //             'dateOfBirth' => $formattedData[0]['dateOfBirth']
        //         ];

        //         $createSubscription = createWellaHealthScription($payload);
        //         $getWellaHealth = getWellaHealthScription($createSubscription['subscriptionCode']);
        //         // return [$createSubscription, $getWellaHealth];

        //         if($createSubscription){
                    
        //            $response = $this->completeWellaHealthSubscription($createSubscription,  $getWellaHealth, $formattedData, $request->referral_code, $request->amount, $user, $request->planCode);
        //             $ref = time();
        //             $url =  $this->initiatePayment($formattedData[0]['email'], $request->amount, $response, $ref)['authorization_url'];
        //             transactionProcessor($user,  $ref, $request->amount, 'unsuccessful', 'NGN', 'paystack', 'wellahealth_payment', 'WellaHealth Payment Initiation by '.$formattedData[0]['firstName'].' '.$formattedData[0]['lastName'], 'Payment_Initiation', 'Credit', 'regular');
                
        //             // PaystackHelpers::paymentTrasanction($user->id, '1', $ref, $request->amount, 'unsuccessful', 'NGN', 'paystack', 'wellahealth_payment', 'WellaHealth Payment Initiation by '.$formattedData[0]['firstName'].' '.$formattedData[0]['lastName'], 'Payment_Initiation', 'regular');
        //             return redirect($url);
        //         }


        //     }else{

        //         $filteredData = array_slice($formattedData, 1);

        //         $payload = [
        //             'agentCode' => 'WHPXTest10076',
        //             'firstName' => $formattedData[0]['firstName'],
        //             'lastName' => $formattedData[0]['lastName'],
        //             'phone' => $formattedData[0]['phone'],
        //             'email' => $formattedData[0]['email'],
        //             'amount' => $request->amount,
        //             'acquisitionChannel' => 'Agent',
        //             'paymentPlan' => $request->paymentPlan,
        //             'gender' => $formattedData[0]['gender'],
        //             'dateOfBirth' => $formattedData[0]['dateOfBirth'], 
        //             'beneficiaryList' => $filteredData
        //         ];

                
        //         $createSubscription = createWellaHealthScription($payload);
        //         $getWellaHealth = getWellaHealthScription($createSubscription['subscriptionCode']);
             
        //         if($createSubscription){
        //             // $this->completeWellaHealthSubscription($createSubscription, $formattedData, $request->referral_code, $request->amount, $user, $request->planCode);
        //             $response = $this->completeWellaHealthSubscription($createSubscription,  $getWellaHealth, $formattedData, $request->referral_code, $request->amount, $user, $request->planCode);
                     
        //            $ref = time();
        //            $url =  $this->initiatePayment($formattedData[0]['email'], $request->amount, $response, $ref)['authorization_url'];
                    
          
        //             // $url =  $this->initiatePayment($formattedData[0]['email'], $request->amount, $response, $ref);
                    
        //             transactionProcessor($user,  $ref, $request->amount, 'unsuccessful', 'NGN', 'paystack', 'wellahealth_payment', 'WellaHealth Payment Initiation by '.$formattedData[0]['firstName'].' '.$formattedData[0]['lastName'], 'Payment_Initiation', 'Credit', 'regular');
                
        //             // PaystackHelpers::paymentTrasanction($user->id, '1', $ref, $request->amount, 'unsuccessful', 'NGN', 'paystack', 'wellahealth_payment', 'WellaHealth Payment Initiation by '.$formattedData[0]['firstName'].' '.$formattedData[0]['lastName'], 'Payment_Initiation', 'regular');
        //             return redirect($url);
        //         }

        //     }
        // }else{
        //     dd('not available');
        // }
          
    }

    public function completeWellaHealthSubscription($createSubscription,  $getWellaHealth, $formattedData, $referral, $amount, $userId, $planCode){
       
        if($referral){
            $affiliate_commission = 0.07 * $amount;
            $commission = 0.03 * $amount;
           //$referral = User::where('referral_code', $request->referral)->first();
            $affiliate_referral_id = $userId;
           // creditWallet($referral, 'Naira', $affiliate_commission);
            //transactionProcessor($referral,$ref,$request->amount, 'successful', 'NGN', 'system', 'wellahealth_subscription_affiliate_commission', auth()->user()->name.' WellaHealth affiliate commission', 'Credit', 'regular');

        }else{
            $affiliate_commission = 0;
            $commission = 0.1 * $amount;
            $affiliate_referral_id = null;
        }

        $data['user_id'] = $affiliate_referral_id;
        $data['plan_code'] = $planCode;
        $data['subscription_code'] = $createSubscription['subscriptionCode'];
        $data['amount'] = $createSubscription['amount'];
        $data['commission'] = $commission;
        $data['affiliate_commission'] = $affiliate_commission;
        $data['affiliate_referral_id'] =  $affiliate_referral_id;
        $data['payment_plan'] = $createSubscription['paymentPlan'];
        $data['numberOfSubscribers'] = $createSubscription['numberOfSubscribers'];
        $data['nextPayment'] = $createSubscription['nextPayment'];
        $data['product'] = $createSubscription['product'];
        $data['partner'] = 'WELLAHEALTH';
        $data['is_paid'] = true;

        $partnership = PartnerSubscription::create($data);

        $subscriber['partnership_subscription_id'] = $partnership->id;
        $subscriber['subscriptionCode'] =  $getWellaHealth['subscriptionCode'];
        $subscriber['firstName'] = $getWellaHealth['firstName'];
        $subscriber['lastName'] = $getWellaHealth['lastName'];
        $subscriber['email'] = $getWellaHealth['email'];
        $subscriber['phone'] = $getWellaHealth['phone'];
        $subscriber['status'] = $getWellaHealth['status'];
        $subscriber['amount'] = $getWellaHealth['amount'];
        $subscriber['product'] = $getWellaHealth['product'];

        $subscriberInfo = PartnershipSubscriber::create($subscriber);
        // ['partnership_subscription_id', 'subcriptionCode', 'firstName',
        // 'lastName', 'email', 'phone', 'status', 'amount', 'product'];

        foreach($formattedData as $formatted){
            PartnershipBeneficiary::create([
                'partnership_subscriptions_id' => $partnership->id,
                'firstName' => $formatted['firstName'],
                'lastName' => $formatted['lastName'],
                'email' => $formatted['email'],
                'phone' => $formatted['phone'],
                'dateOfBirth' => $formatted['dateOfBirth'],
                'gender' => $formatted['gender'],
            ]);
        }
        return $partnership;
    }

    public function initiatePayment($email, $amount, $ref, $referral, $beneficiaryCount, $formattedData, $paymentPlan, $planCode){

       
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $email, //$formattedData[0]['email'],
            'amount' => $amount*100,
            'channels' => ['card'],
            'currency' => 'NGN',
            'reference' => $ref,
            'callback_url' => url('agent/wellahealth/payment'),
            "metadata"=> [
                "referral_id"=> $referral->id,
                "referral_code"=> $referral->referral_code,
                'paymentPlan' => $paymentPlan,
                'planCode' => $planCode,
                "beneficiary_count"=> $beneficiaryCount,
                "formatted_data"=> $formattedData,
            ]
               
        ])->throw();

        return json_decode($res->getBody()->getContents(), true)['data'];
       
    }

    public function agentPayment(){
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);

         $ref = $params['trxref']; //paystack
         $res = PaystackHelpers::verifyTransaction($ref); 
         $amount = $res['data']['amount']/100;
        if($res['status'] == true){
            PaystackHelpers::paymentUpdate($ref, 'successful');
            $referral_id =  $res['data']['metadata'];
            
          
          
            // $planCode = $res['data']['metadata']['planCode']
            // $referral_id =  $res['data']['metadata']['referral_id'];

             if($res['data']['metadata']['beneficiary_count'] == 1){
                $formattedData =  $res['data']['metadata']['formatted_data'];
                $payload = [
                        'agentCode' => env('WELLAHEALTH_AGENT_CODE'),//'WHPXTest10076',
                        'firstName' => $formattedData[0]['firstName'],
                        'lastName' => $formattedData[0]['lastName'],
                        'phone' => $formattedData[0]['phone'],
                        'email' => $formattedData[0]['email'],
                        'amount' => $amount,
                        'acquisitionChannel' => 'Agent',
                        'paymentPlan' => $res['data']['metadata']['paymentPlan'],
                        'gender' => $formattedData[0]['gender'],
                        'dateOfBirth' => $formattedData[0]['dateOfBirth']
                    ];

                    $createSubscription = createWellaHealthScription($payload);
                    $getWellaHealth = getWellaHealthScription($createSubscription['subscriptionCode']);

                    if($createSubscription){
                        $response = $this->completeWellaHealthSubscription(
                            $createSubscription,  
                            $getWellaHealth, 
                            $formattedData, 
                            $res['data']['metadata']['referral_code'], 
                            $amount, 
                            $res['data']['metadata']['referral_id'], 
                            $res['data']['metadata']['planCode']
                        );

                        if( $response){
                            $affiliate = User::where('id', $res['data']['metadata']['referral_id'])->first();
            
                            $credit = creditWallet($affiliate, 'Naira', (int)$response->affiliate_commission);
                            
                            $tx = transactionProcessor($affiliate, time(),$response->affiliate_commission, 'successful', 'NGN', 'paystack', 'wellahealth_affiliate_commission', 'WellaHealth Affiliate Payment Completed', 'Payment_Completed', 'Credit', 'regular');
                          
                        }
                          return redirect('wellahealthsuccess');

                       
                    }else{

                        return back('error', 'An error occoured while processing');
                    }

                    

             }else{
                $formattedData =  $res['data']['metadata']['formatted_data'];
                $firstData =  $formattedData[0];

                
                $filteredData = array_slice($formattedData, 1);

                $payload = [
                    'agentCode' => env('WELLAHEALTH_AGENT_CODE'),
                    'firstName' => $firstData['firstName'],
                    'lastName' => $firstData['lastName'],
                    'phone' => $firstData['phone'],
                    'email' => $firstData['email'],
                    'amount' => $amount,
                    'acquisitionChannel' => 'Agent',
                    'paymentPlan' => $res['data']['metadata']['paymentPlan'],
                    'gender' => $firstData['gender'],
                    'dateOfBirth' => $firstData['dateOfBirth'], 
                    'beneficiaryList' => $filteredData
                ];

                $createSubscription = createWellaHealthScription($payload);
                $getWellaHealth = getWellaHealthScription($createSubscription['subscriptionCode']);

                if($createSubscription){
                    $response = $this->completeWellaHealthSubscription(
                        $createSubscription,  
                        $getWellaHealth, 
                        $formattedData, 
                        $res['data']['metadata']['referral_code'], 
                        $amount, 
                        $res['data']['metadata']['referral_id'], 
                        $res['data']['metadata']['planCode']
                    );

                    if( $response){
                        $affiliate = User::where('id', $res['data']['metadata']['referral_id'])->first();
        
                        $credit = creditWallet($affiliate, 'Naira', (int)$response->affiliate_commission);
                        
                        $tx = transactionProcessor($affiliate, time(),$response->affiliate_commission, 'successful', 'NGN', 'paystack', 'wellahealth_affiliate_commission', 'WellaHealth Affiliate Payment Completed', 'Payment_Completed', 'Credit', 'regular');
                      
                    }
                      return redirect('wellahealthsuccess');

                   
                }else{
                    return back('error', 'An error occoured while processing');

                }

                
             }
      

        

              // if($user){
        //     if($beneficiaryCount == 1){
        //         //enter them in subscription
        //         $payload = [
        //             'agentCode' => env('WELLAHEALTH_AGENT_CODE'),//'WHPXTest10076',
        //             'firstName' => $formattedData[0]['firstName'],
        //             'lastName' => $formattedData[0]['lastName'],
        //             'phone' => $formattedData[0]['phone'],
        //             'email' => $formattedData[0]['email'],
        //             'amount' => $request->amount,
        //             'acquisitionChannel' => 'Agent',
        //             'paymentPlan' => $request->paymentPlan,
        //             'gender' => $formattedData[0]['gender'],
        //             'dateOfBirth' => $formattedData[0]['dateOfBirth']
        //         ];

        //         $createSubscription = createWellaHealthScription($payload);
        //         $getWellaHealth = getWellaHealthScription($createSubscription['subscriptionCode']);
        //         // return [$createSubscription, $getWellaHealth];

        //         if($createSubscription){
                    
        //            $response = $this->completeWellaHealthSubscription($createSubscription,  $getWellaHealth, $formattedData, $request->referral_code, $request->amount, $user, $request->planCode);
        //             $ref = time();
        //             $url =  $this->initiatePayment($formattedData[0]['email'], $request->amount, $response, $ref)['authorization_url'];
        //             transactionProcessor($user,  $ref, $request->amount, 'unsuccessful', 'NGN', 'paystack', 'wellahealth_payment', 'WellaHealth Payment Initiation by '.$formattedData[0]['firstName'].' '.$formattedData[0]['lastName'], 'Payment_Initiation', 'Credit', 'regular');
                
        //             // PaystackHelpers::paymentTrasanction($user->id, '1', $ref, $request->amount, 'unsuccessful', 'NGN', 'paystack', 'wellahealth_payment', 'WellaHealth Payment Initiation by '.$formattedData[0]['firstName'].' '.$formattedData[0]['lastName'], 'Payment_Initiation', 'regular');
        //             return redirect($url);
        //         }


        //     }else{

        //         $filteredData = array_slice($formattedData, 1);

        //         $payload = [
        //             'agentCode' => 'WHPXTest10076',
        //             'firstName' => $formattedData[0]['firstName'],
        //             'lastName' => $formattedData[0]['lastName'],
        //             'phone' => $formattedData[0]['phone'],
        //             'email' => $formattedData[0]['email'],
        //             'amount' => $request->amount,
        //             'acquisitionChannel' => 'Agent',
        //             'paymentPlan' => $request->paymentPlan,
        //             'gender' => $formattedData[0]['gender'],
        //             'dateOfBirth' => $formattedData[0]['dateOfBirth'], 
        //             'beneficiaryList' => $filteredData
        //         ];

                
        //         $createSubscription = createWellaHealthScription($payload);
        //         $getWellaHealth = getWellaHealthScription($createSubscription['subscriptionCode']);
             
        //         if($createSubscription){
        //             // $this->completeWellaHealthSubscription($createSubscription, $formattedData, $request->referral_code, $request->amount, $user, $request->planCode);
        //             $response = $this->completeWellaHealthSubscription($createSubscription,  $getWellaHealth, $formattedData, $request->referral_code, $request->amount, $user, $request->planCode);
                     
        //            $ref = time();
        //            $url =  $this->initiatePayment($formattedData[0]['email'], $request->amount, $response, $ref)['authorization_url'];
                    
          
        //             // $url =  $this->initiatePayment($formattedData[0]['email'], $request->amount, $response, $ref);
                    
        //             transactionProcessor($user,  $ref, $request->amount, 'unsuccessful', 'NGN', 'paystack', 'wellahealth_payment', 'WellaHealth Payment Initiation by '.$formattedData[0]['firstName'].' '.$formattedData[0]['lastName'], 'Payment_Initiation', 'Credit', 'regular');
                
        //             // PaystackHelpers::paymentTrasanction($user->id, '1', $ref, $request->amount, 'unsuccessful', 'NGN', 'paystack', 'wellahealth_payment', 'WellaHealth Payment Initiation by '.$formattedData[0]['firstName'].' '.$formattedData[0]['lastName'], 'Payment_Initiation', 'regular');
        //             return redirect($url);
        //         }

        //     }
        // }else{
        //     dd('not available');
        // }
            
           
        }else{

            return back('error', 'An error occoured while processing');
           // return redirect('wellahealthsuccess');

        }
    }

    public function wellahealthsuccess(){
        return view('user.partner.wellahealth.success');
    }


    public function testy(){

         $campaigns = Campaign::where('status', 'Live')->where('is_completed', false)->orderBy('created_at', 'DESC')->take(10)->get();
        
        $list = [];
        foreach($campaigns as $key => $value){
            
           $c = $value->pending_count + $value->completed_count;
            //$div = $c / $value->number_of_staff;
            // $progress = $div * 100;

            $list[] = [ 
                'id' => $value->id, 
                'job_id' => $value->job_id, 
                'campaign_amount' => $value->campaign_amount,
                'post_title' => $value->post_title, 
                //'number_of_staff' => $value->number_of_staff, 
                'type' => $value->campaignType->name, 
                'category' => $value->campaignCategory->name,
                //'attempts' => $attempts,
                //'completed' => $c, //$value->completed_count+$value->pending_count,
                'is_completed' => $c >= $value->number_of_staff ? true : false,
                //'progress' => $progress,
                'currency' => $value->currency,
                //'created_at' => $value->created_at
            ];
        }

        //$sortedList = collect($list)->sortBy('is_completed')->values()->all();//collect($list)->sortByDesc('is_completed')->values()->all(); //collect($list)->sortBy('is_completed')->values()->all();

        // Remove objects where 'is_completed' is true
        $filteredArray = array_filter($list, function ($item) {
            return $item['is_completed'] !== true;
        });
      
        // return $filteredArray;

        $user = User::where('id', 1)->first();
        $subject = 'Fresh Campaign';
        Mail::to('solotobby@gmail.com')->send(new JobBroadcast($user, $subject, $filteredArray)); 

        return 'okay';

        // $now = Carbon::now();
        // $twentyFourHoursAgo = Carbon::now()->subHours(24);

        // $minusday = Carbon::now()->subDay();


        // $startYesterday = Carbon::yesterday()->startOfDay();
        // $endYesterday = Carbon::yesterday()->endOfDay();

        // $yesterday = Carbon::yesterday();

        //  $lists =  CampaignWorker::where('status', 'Pending')//->where('reason', '')
        //     //->whereBetween('created_at', [$twentyFourHoursAgo, $now])
        //     ->whereDate('created_at', $yesterday)
        //     ->get();

        // return [$lists, $lists->count()];

        // foreach($lists as $list){

        //     $ca = CampaignWorker::where('id', $list->id)->first();
        //     $ca->status = 'Approved';
        //     $ca->reason = 'Auto-approval';
        //     $ca->save();

        //     $camp = Campaign::where('id', $ca->campaign_id)->first();
        //     $camp->completed_count += 1;
        //     $camp->pending_count -= 1;
        //     $camp->save();

        //     if($camp->currency == 'NGN'){
        //         $currency = 'NGN';
        //         $channel = 'paystack';
        //         $wallet = Wallet::where('user_id', $ca->user_id)->first();
        //         $wallet->balance += $ca->amount;
        //         $wallet->save();
        //     }else{
        //         $currency = 'USD';
        //         $channel = 'paypal';
        //         $wallet = Wallet::where('user_id', $ca->user_id)->first();
        //         $wallet->usd_balance += $ca->amount;
        //         $wallet->save();
        //     }

        //     $ref = time();

        //     setIsComplete($ca->campaign_id);
    
        //     PaymentTransaction::create([
        //         'user_id' => $ca->user_id,
        //         'campaign_id' => '1',
        //         'reference' => $ref,
        //         'amount' => $ca->amount,
        //         'status' => 'successful',
        //         'currency' => $currency,
        //         'channel' => $channel,
        //         'type' => 'campaign_payment',
        //         'description' => 'Campaign Payment for '.$ca->campaign->post_title,
        //         'tx_type' => 'Credit',
        //         'user_type' => 'regular'
        //     ]);

        // }



        // $str = '10928';
        // return $first_character = substr($str, 0, 3);
        // $first_character = $str[0]; 
        // echo substr($myStr, 0, 5);
        // return PaystackHelpers::flutterwaveCreateCard();

        // $payload = [
        //     "currency"=> "USD",
        //     "amount"=>5,
        //     "debit_currency"=> "NGN",
        //     "billing_name"=> "Example User.",
        //     "billing_address"=> "333, Fremont Street",
        //     "billing_city"=> "San Francisco",
        //     "billing_state"=> "CA",
        //     "billing_postal_code"=> "94105",
        //     "billing_country"=> "US",
        //     "first_name"=> "Samuel",
        //     "last_name"=> "Farohunbi",
        //     "date_of_birth"=> "1996/12/30",
        //     "email"=> "farohunbi.st@gmail.com",
        //     "phone"=> "07030000000",
        //     "title"=> "MR",
        //     "gender"=> "M",
        //     "callback_url"=> "https://webhook.site/b67965fa-e57c-4dda-84ce-0f8d6739b8a5"
        // ];
        // return createCard($payload);
        // $payload = [
        //     "customer_id"=> "65c2adf0cd5ee96c010dbec0",
        //     "preferred_bank"=> "Banc Corp",
        //     "alias"=> "Freebyz",
        //     "collection_rules"=> [
        //         "amount"=> 30000,
        //         "frequency"=> 2
        //     ]
        // ];

        // return bloqCreateAccount($payload);

        // "id": "65c2bafdcd5ee96c010dbf1d",

        // $payload = [
        //     "customer_id"=> "65c2adf0cd5ee96c010dbec0",
        //     'brand' => 'MasterCard'
        // ];

        // return bloqIssueCard($payload);

        // $payload1 = [
        //     'email' => 'solotobby@gmail.com',
        //     'phone_number' => '08137331282',
        //     'first_name' => 'Oluwatobi',
        //     'last_name' => 'Solomon',
        //     'customer_type' => 'Personal',
        //     'bvn' => '1234567890'
        //  ];

        //  $res = bloqCreateCustomer($payload1);

        //  $payload2 = [
        //     'place_of_birth' => 'lagos', 
        //     'dob' => '1992-03-30',
        //     'gender' => 'Male',
        //     'address' => [
        //         'street' => '10 Lagos Way',
        //         'city' => 'Lekki',
        //         'state' => 'Lagos',
        //         'country' => 'Nigeria', 
        //         "postal_code"=>"1000101"
        //     ],
        //     'image' => 'image_src',
        //  ];

        //  return bloqUpgradeCustomerKYC1($payload2, "65c2adf0cd5ee96c010dbec0");





        //  "id": "65c2adf0cd5ee96c010dbec0",

        // $payload = [
        //     "tx_ref"=>"MC-158523s09v505090",
        //     "amount"=>"190",
        //     "currency"=>"GHS",
        //     "voucher"=>"143256743",
        //     "network"=>"MTN",
        //     "email"=>"solotobby@gmail.com",
        //     "phone_number"=>"054709929220",
        //     "fullname"=>"Yolande Aglaé Colbert",
        //     // "client_ip":"154.123.220.1",
        // ];
        // return listBanks($payload);


        // $payload = [
        //     "currency"=> "USD",
        //     "amount"=> 5,
        //     "debit_currency"=> "NGN",
        //     "billing_name"=> "Oluwatobi Solomon",
        //     "billing_address"=> "333, Fremont Street",
        //     "billing_city"=> "San Francisco",
        //     "billing_state"=> "CA",
        //     "billing_postal_code"=> "94105",
        //     "billing_country"=> "US",
        //     "first_name"=> "Oluwatobi",
        //     "last_name"=> "Solomon",
        //     "date_of_birth"=> "1996/12/30",
        //     "email"=> "solotobby@gmail.com",
        //     "phone"=> "08137331282",
        //     "title"=> "MR",
        //     "gender"=> "M",
        //     "callback_url"=> "https://webhook.site/b67965fa-e57c-4dda-84ce-0f8d6739b8a5"
        // ];

        // return createCard($payload);
        // return $this->initiatePaystackTx();
        // $location =  currentLocation();

        // return $this->paystackApi('ghana');
    }

    public function paystackApi($location){
        //locations can be  nigeria, ghana, kenya
        $lowerCaseLocation = strtolower($location);
        $url = 'https://api.paystack.co/bank?country='.$lowerCaseLocation;
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->get($url)->throw();

        return $bankList = json_decode($res->getBody()->getContents(), true)['data'];

    }

    public function initiatePaystackTx(){
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => 'solotobby@gmail.com', //auth()->user()->email,
            'amount' => 1050*100,
            'channels' => ['mobile_money'],
            'currency' => 'GH',
            'reference' => time(),
            'callback_url' => '/' //url($redirect_url)
        ]);
        return json_decode($res->getBody()->getContents(), true);
    //    return $res['data']['authorization_url'];
    }

    public function access(){
        return view('access');
    }


}
