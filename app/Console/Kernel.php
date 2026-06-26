<?php

namespace App\Console;

use App\Mail\GeneralMail;
use App\Mail\JobBroadcast;
use App\Models\Business;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\DisputedJobs;
use App\Models\OTP;
use App\Models\PaymentTransaction;
use App\Models\Question;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('task')->everyMinute();//->dailyAt('00:00');

        // $schedule->command('users:delete-unverified')->dailyAt('02:00');


        $schedule->command('campaign:release-expired-slots')
            ->everyThirtyMinutes()
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command('disputes:resolve-completed')->hourly();

        $schedule->command('campaigns:update-completed')->twiceDaily(9, 21);

        $schedule->command('campaigns:auto-approve')->everyThirtyMinutes();

        $schedule->command('campaigns:auto-approve-business')->hourly();

        $schedule->command('campaigns:auto-approve-7days')->weeklyOn(4, '04:00');

        $schedule->command('questions:cleanup-invalid')->dailyAt('03:00');

        $schedule->command('campaigns:flag-abusive')->twiceDaily(8, 20);

        // $schedule->command('command:task')->daily();

        // $schedule->command('jobs:send-broadcast')->dailyAt('9:00');

        // $schedule->command('campaigns:send-weekly-broadcast')->dailyAt('10:30');

        // $schedule->command('users:testing-supervisor')->everyMinute();
        // $schedule->command('business:rotate-promotion')->dailyAt('03:00');
        //test
        // $schedule->call(function () {
        //     Log::info('Test scheduled task executed.');
        //     Mail::to('morakinyovictor1@gmail.com')->send(new GeneralMail(User::find(1), 'This is a test email from the scheduled task.', 'Test Email', ''));
        // })->everyMinute();


    }
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
