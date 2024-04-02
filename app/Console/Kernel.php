<?php

namespace App\Console;

use App\Mail\GeneralMail;
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
        // $schedule->call(function(){
           
        // })->everyMinute();

        $schedule->call(function(){
            $totalUsers = \DB::table('users')
            ->whereRaw('Date(created_at) = CURDATE()')
            ->count();
            
            $user = User::where('id', 1)->first(); //$user['name'] = 'Oluwatobi';
            $subject = 'Today Count';
            $content = 'Total reg..'.$totalUsers;
            Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));



            //////

            $yesterday = Carbon::yesterday();

            $lists =  CampaignWorker::where('status', 'Pending')//->where('reason', '')
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

            $user = User::where('id', 1)->first(); //$user['name'] = 'Oluwatobi';
            $subject = 'Batched Job Approval - Notification';
            $content = 'Job Automatic Approval of '.$lists->count();
            Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));

        })->dailyAt('23:58');

       
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
