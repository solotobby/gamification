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
    //   $subscriptions = listWellaHealthScriptions();
    //   $mysubscriptions = PartnerSubscription::where('user_id', auth()->user()->id)->pluck('plan_code')->toArray();
    //   //istWellaHealthScriptions();
      $display = [];
      foreach(listWellaHealthScriptions() as $list){
        $mysubscriptions = PartnerSubscription::where('user_id', auth()->user()->id)->first();//pluck('plan_code')->toArray();
        $display[] = [
            'data'=> $list, 
            'is_subscribed' => $list['planCode'] == $mysubscriptions->plan_code ? true : false, 
            'subscriptionCode' => $list['planCode'] == $mysubscriptions->plan_code ? $mysubscriptions->subscription_code : null, 
        ];
       }
       
      return view('user.partner.wellahealth.index', ['subscriptions' => $display, 'mysubscriptions' => $mysubscriptions]);
    }

    public function create(Request $request){
        $amount = $request->amount;
        $ref = time();
        
        // $list['amount'] = $amount;
        // $list['affiliate_commission'] = $affiliate_commission;
        // $list['commission'] = $commission;
        // $list['affiliate_referral_id'] = $affiliate_referral_id;
        // return $list;

       
        $splitedName = explode(" ", auth()->user()->name);
        $checkWalletBalance = checkWalletBalance(auth()->user(), 'Naira', $request->amount);
        if($checkWalletBalance){
            
            $payload = [
                'agentCode' => 'WHPXTest10076',
                'firstName' => $splitedName[0],
                'lastName' => isset($splitedName[1]) ? $splitedName[1] : 'Freebyz',
                'phone' => auth()->user()->phone,
                'email' => auth()->user()->email,
                'amount' => $request->amount,
                'acquisitionChannel' => 'Agent',
                'paymentPlan' => $request->paymentPlan,
                'gender' => auth()->user()->gender,
                'dateOfBirth' => '1990-12-09'
            ];

            debitWallet(auth()->user(), 'Naira', $request->amount);
            transactionProcessor(auth()->user(),$ref, $request->amount, 'successful', 'NGN', 'system', 'wellahealth_subscription', auth()->user()->name.' subscribe to WellaHealth', 'Debit', 'regular');
             $createSubscription = createWellaHealthScription($payload);
            if($createSubscription){

                if($request->referral){

                    $affiliate_commission = 0.035 * $amount;
                    $commission = 0.015 * $amount;
                    $referral = User::where('referral_code', $request->referral)->first();
                    $affiliate_referral_id = $referral->id;
                    creditWallet($referral, 'Naira', $affiliate_commission);
                    transactionProcessor($referral,$ref,$request->amount, 'successful', 'NGN', 'system', 'wellahealth_subscription_affiliate_commission', auth()->user()->name.' WellaHealth affiliate commission', 'Credit', 'regular');
        
                }else{
                    $affiliate_commission = 0;
                    $commission = 0.05 * $amount;
                    $affiliate_referral_id = null;
                }


                $data['user_id'] = auth()->user()->id;
                $data['plan_code'] = $request->planCode;
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

                PartnerSubscription::create($data);  
                return back()->with('success', 'Subscription successful Completed');              

                // {
                //     "subscriptionCode": "1391d757694",
                //     "firstName": "Oluwatobi",
                //     "lastName": "Daniel",
                //     "phone": "2348123331282",
                //     "email": null,
                //     "status": "Active",
                //     "amount": 600,
                //     "paymentPlan": "Monthly",
                //     "numberOfSubscribers": 1,
                //     "subscriptionAccount": null,
                //     "healthSavingsAccount": null,
                //     "nextPayment": "2024-02-10T03:13:44.6081632Z",
                //     "product": "Malaria"
                // }

                //pass into partner settlement table
            }else{
                creditWallet(auth()->user(), 'Naira', $request->amount);
                transactionProcessor(auth()->user(),$ref, $request->amount, 'successful', 'NGN', 'system', 'wellahealth_subscription_reversal', auth()->user()->name.' subscription to WellaHealth Reversed', 'Credit', 'regular');
                return back()->with('error', 'An error occoured, please try again');
            }

        }else{
            
            return back()->with('error', 'Insuficient fund');
        }
        

    }
}
