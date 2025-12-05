<?php

namespace App\Console;

use App\Mail\GeneralMail;
use App\Mail\JobBroadcast;
use App\Mail\MassMail;
use App\Models\Business;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\OTP;
use App\Models\PaymentTransaction;
use App\Models\Question;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withrawal;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
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

        // $schedule->command('users:delete-unverified')->dailyAt('02:00');

        // $schedule->command('disputes:resolve-completed')->hourly();

        $schedule->command('command:task')->daily();

        $schedule->command('campaigns:send-weekly-broadcast')->dailyAt('08:30');

        $schedule->command('campaigns:auto-approve-24hours')->hourly();

        $schedule->command('campaigns:auto-approve-business')->hourly();

        $schedule->command('campaigns:auto-approve-7days')->dailyAt('04:00');

        $schedule->command('business:rotate-promotion')->dailyAt('03:00');

        $schedule->command('questions:cleanup-invalid')->dailyAt('03:00');

        $schedule->command('campaigns:flag-abusive')->twiceDaily(8, 20);

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
