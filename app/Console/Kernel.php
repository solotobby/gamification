<?php

namespace App\Console;

use App\Mail\GeneralMail;
use App\Models\OTP;
use App\Models\User;
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
            $totalUsers = \DB::table('users')
            ->whereRaw('Date(created_at) = CURDATE()')
            ->count();
            
            $user = User::where('id', 1)->first(); //$user['name'] = 'Oluwatobi';
            $subject = 'Today Count';
            $content = 'Total reg..'.$totalUsers;
            Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));
            
           // OTP::where('is_verified', 0)->delete();
        })->everyMinute();

        // $schedule->call(function(){
        //     $totalUsers = \DB::table('users')
        //     ->whereRaw('Date(created_at) = CURDATE()')
        //     ->count();

        //     $user['name'] = 'Oluwatobi';
        //     $subject = 'Today Count';
        //     $content = 'Total reg..'.$totalUsers;
        //     Mail::to('solotobby@gmail.com')->send(new GeneralMail($user, $content, $subject, ''));
            
        // })->dailyAt('00:00');
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
