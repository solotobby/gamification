<?php

namespace App\Console;

use App\Mail\GeneralMail;
use App\Mail\JobBroadcast;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\OTP;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('task')->everyMinute();//->dailyAt('00:00');
        
        $schedule->call(function(){
            $campaigns = Campaign::where('status', 'Live')->where('is_completed', false)->orderBy('created_at', 'DESC')->take(20)->get();
        
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
            $startOfWeek = Carbon::now()->startOfWeek()->subWeek();
            $endOfWeek = Carbon::now()->endOfWeek()->subWeek();
            
            // Query users registered within last week
            $usersLastWeek = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
            
            // $user = User::where('id', 1)->first();
            foreach($usersLastWeek as $user){
                $subject = 'Fresh Campaign';
                Mail::to($user->email)->send(new JobBroadcast($user, $subject, $filteredArray)); 
            }
           
        })->daily(); //does this daily

        $schedule->call(function(){
            $yesterday = Carbon::yesterday();

            $lists =  CampaignWorker::where('status', 'Pending')->where('reason', null)
               ->whereDate('created_at', $yesterday)
               ->get();

            foreach($lists as $list){

                $ca = CampaignWorker::where('id', $list->id)->first();
                $ca->status = 'Approved';
                $ca->reason = 'Auto-approval';
                $ca->save();
    
                
                $camp = Campaign::where('id', $ca->campaign_id)->first();
                $camp->completed_count += 1;
                $camp->pending_count -= 1;
                $camp->save();
    
                
                if($camp->currency == 'NGN'){
                    $currency = 'NGN';
                    $channel = 'paystack';
                    $wallet = Wallet::where('user_id', $ca->user_id)->first();
                    $wallet->balance += $ca->amount;
                    $wallet->save();
                }else{
                    $currency = 'USD';
                    $channel = 'paypal';
                    $wallet = Wallet::where('user_id', $ca->user_id)->first();
                    $wallet->usd_balance += $ca->amount;
                    $wallet->save();
                }
    
                $ref = time();
    
                setIsComplete($ca->campaign_id);
        
                PaymentTransaction::create([
                    'user_id' => $ca->user_id,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => $ca->amount,
                    'status' => 'successful',
                    'currency' => $currency,
                    'channel' => $channel,
                    'type' => 'campaign_payment',
                    'description' => 'Campaign Payment for '.$ca->campaign->post_title,
                    'tx_type' => 'Credit',
                    'user_type' => 'regular'
                ]);
    
            }

            $user = User::where('id', 4)->first(); //$user['name'] = 'Oluwatobi';
            $subject = 'Batched Job Approval - Notification';
            $content = 'Job Automatic Approval of '.$lists->count();
            Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));

        })->dailyAt('23:30');


        $schedule->call(function(){
            $lists =  CampaignWorker::where('status', 'Pending')->where('reason', null)
            ->get();

            foreach($lists as $list){

                $ca = CampaignWorker::where('id', $list->id)->first();
                $ca->status = 'Approved';
                $ca->reason = 'Auto-approval';
                $ca->save();
    
                $camp = Campaign::where('id', $ca->campaign_id)->first();
                $camp->completed_count += 1;
                $camp->pending_count -= 1;
                $camp->save();
    
                setIsComplete($ca->campaign_id);

                if($camp->currency == 'NGN'){
                    $currency = 'NGN';
                    $channel = 'paystack';
                    $wallet = Wallet::where('user_id', $ca->user_id)->first();
                    $wallet->balance += $ca->amount;
                    $wallet->save();
                }else{
                    $currency = 'USD';
                    $channel = 'paypal';
                    $wallet = Wallet::where('user_id', $ca->user_id)->first();
                    $wallet->usd_balance += $ca->amount;
                    $wallet->save();
                }
    
                $ref = time();
    
                PaymentTransaction::create([
                    'user_id' => $ca->user_id,
                    'campaign_id' => '1',
                    'reference' => $ref,
                    'amount' => $ca->amount,
                    'status' => 'successful',
                    'currency' => $currency,
                    'channel' => $channel,
                    'type' => 'campaign_payment',
                    'description' => 'Campaign Payment for '.$ca->campaign->post_title,
                    'tx_type' => 'Credit',
                    'user_type' => 'regular'
                ]);    
            }

            $user = User::where('id', 4)->first(); //$user['name'] = 'Oluwatobi';
            $subject = 'Batched Job Approval(Missed Jobs) -  Notification';
            $content = 'Job Automatic Approval of '.$lists->count();
            Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));

        })->dailyAt('22:00');
        
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

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
