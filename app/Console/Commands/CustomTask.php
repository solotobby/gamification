<?php

namespace App\Console\Commands;

use App\Models\OTP;
use App\Models\Question;
use Illuminate\Console\Command;

class CustomTask extends Command
{

    protected $signature = 'command:task';


    protected $description = 'Command description';



    public function handle()
    {
        $count = OTP::where('is_verified', 0)
            ->where('created_at', '<=', now()->subMinutes(10))
            ->delete();

        $this->info('OTP Cleanup: Deleted ' . $count . ' expired OTPs.');
    }

    // old handle

    //////////////////////////////////////////////////////////////////////////////////////////

    //remove after tonight
    // $schedule->call(function(){

    //     $lists =  CampaignWorker::where('status', 'Pending')->where('reason', null)->get();

    //     foreach($lists as $list){

    //         $ca = CampaignWorker::where('id', $list->id)->first();
    //         $ca->status = 'Approved';
    //         $ca->reason = 'Auto-approval';
    //         $ca->save();

    //         $camp = Campaign::where('id', $ca->campaign_id)->first();
    //         $camp->completed_count += 1;
    //         $camp->pending_count -= 1;
    //         $camp->save();

    //         $user = User::where('id', $ca->user_id)->first();
    //         $baseCurrency = baseCurrency($user);

    //         $amountCredited =$ca->amount;
    //         if($baseCurrency == 'NGN'){
    //             $currency = 'NGN';
    //             $channel = 'paystack';
    //             $wallet = Wallet::where('user_id', $ca->user_id)->first();
    //             $wallet->balance += (int)$amountCredited;
    //             $wallet->save();
    //         }elseif($camp->currency == 'USD'){
    //             $currency = 'USD';
    //             $channel = 'paypal';
    //             $wallet = Wallet::where('user_id', $ca->user_id)->first();
    //             $wallet->usd_balance += (int)$amountCredited;
    //             $wallet->save();
    //         }else{
    //             $currency = baseCurrency($user);
    //             $channel = 'flutterwave';
    //             $wallet = Wallet::where('user_id', $ca->user_id)->first();
    //             $wallet->base_currency_balance += (int)$amountCredited;
    //             $wallet->save();
    //         }

    //         setIsComplete($ca->campaign_id);


    //         $ref = time();

    //         PaymentTransaction::create([
    //             'user_id' => $ca->user_id,
    //             'campaign_id' => '1',
    //             'reference' => $ref,
    //             'amount' => $ca->amount,
    //             'status' => 'successful',
    //             'currency' => $currency,
    //             'channel' => $channel,
    //             'type' => 'campaign_payment',
    //             'description' => 'Campaign Payment for '.$ca->campaign->post_title,
    //             'tx_type' => 'Credit',
    //             'user_type' => 'regular'
    //         ]);
    //     }

    //     $user = User::where('id', 4)->first(); //$user['name'] = 'Oluwatobi';
    //     $subject = 'Batched Job Approval(Missed Jobs) -  Notification';
    //     $content = 'Job Automatic Approval of '.$lists->count();
    //     Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));

    // })->dailyAt('18:10');

    // $schedule->call(function(){
    // //credit all dispute from July 2024 upward
    //     $disputes = CampaignWorker::where('is_dispute', true)->where('created_at', '<=', Carbon::create(2024, 7, 31))->get();//->sum('amount');
    // $list =[];
    // $totalAmount = 0;
    // $totalReducedAmount = 0;
    // foreach($disputes as $dip){
    //    $reduced =  number_format(0.2 * $dip->amount,2);
    //    $list[] = ['id' => $dip->id, 'user_id' => $dip->user_id, 'amount' => $dip->amount, 'reduced_amount' => $reduced];
    //    $totalAmount += $dip->amount;
    //    $totalReducedAmount += $reduced;
    //     // Update is_dispute to false
    //     $dip->is_dispute = false;
    //     $dip->is_dispute_resolved = true;
    //     $dip->save();

    //     $user = User::find($dip->user_id);
    //     creditWallet($user, 'Naira', $reduced);

    //     PaymentTransaction::create([
    //         'user_id' => $dip->user_id,
    //         'campaign_id' => 1,
    //         'reference' => time().rand(99,99999),
    //         'amount' =>$reduced,
    //         'status' => 'successful',
    //         'currency' => 'NGN',
    //         'channel' => 'paystack',
    //         'type' => 'auto_credit_dispute',
    //         'tx_type' => 'Credit',
    //         'description' => 'Auto-credit on Job Dispute'
    //     ]);
    // }
    // // $result = [
    // //     'list' => $list,
    // //     'total_amount' => number_format($totalAmount, 2),
    // //     'total_reduced_amount' => number_format($totalReducedAmount, 2)
    // // ];

    // $user = User::where('id', '4')->first();
    // $subject = 'Dispute Auto - credit';
    // $content = 'Sent a total of reduced NGN'.number_format($totalReducedAmount, 2). ' of total value NGN'.number_format($totalAmount, 2);
    // Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));

    // // return $result;


    // })->dailyAt('21:50');


}
