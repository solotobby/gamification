<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateUserNamesFromBankInfo extends Command
{
    protected $signature = 'users:update-names-from-bank';
    protected $description = 'Update user names from their active bank information';

    public function handle()
    {
        $startTime = microtime(true);
        $this->info('Starting user name updates...');
        Log::info('Starting user name updates...');

        $totalUpdated = 0;

        User::whereHas('accountDetails', function ($query) {
            $query->where('status', true);
        })
        ->with('accountDetails')
        ->chunk(50, function ($users) use (&$totalUpdated) {
            foreach ($users as $user) {
                if ($user->accountDetails && $user->accountDetails->name) {
                    $user->update(['name' => $user->accountDetails->name]);
                    $totalUpdated++;
                }
            }
            $this->info("Processed {$totalUpdated} users...");
            log::info("Processed {$totalUpdated} users...");
        });

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);

        $this->info("Completed! Updated {$totalUpdated} users in {$duration} seconds.");
        log::info("Completed! Updated {$totalUpdated} users in {$duration} seconds.");
    }
}
