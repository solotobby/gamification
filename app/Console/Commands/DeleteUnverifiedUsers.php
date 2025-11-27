<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-unverified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users who attempted verification but still unverified after 60days, in chunks';

    /**
     * Execute the console command.
     */
  public function handle()
    {
        $this->info('Starting deletion of unverified users...');

        // Log start
        Log::info(message: 'User cleanup started.');

        $count = 0;

        User::whereNull('email_verified_at')
            ->where('is_verified', 0)
            ->whereDate('email_verification_attempted_at', today())
            ->chunkById(900, function ($users) use (&$count) {
                $ids = $users->pluck('id')->toArray();
                User::whereIn('id', $ids)->delete();
                $count += count($ids);

                Log::info("Deleted batch of " . count($ids) . " users.");
            });

        $this->info("Deletion complete. Total users deleted: {$count}");
        Log::info("User cleanup finished. Total deleted: {$count}");
    }
}
