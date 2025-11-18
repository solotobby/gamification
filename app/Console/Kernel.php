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
        //     $schedule->command('inspire')->hourly();
        //     $schedule->command('users:delete-unverified')->dailyAt('02:00');

        $schedule->command('command:task')->daily();

        $schedule->command('users:delete-unverified')->dailyAt('02:00');

        $schedule->command('campaigns:send-weekly-broadcast')->daily();

        $schedule->command('campaigns:auto-approve-24hours')->hourly();

        $schedule->command('campaigns:auto-approve-business')->hourly();

        $schedule->command('campaigns:auto-approve-7days')->daily();

        $schedule->command('business:rotate-promotion')->daily();

        $schedule->command('questions:cleanup-invalid')->hourly();
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
