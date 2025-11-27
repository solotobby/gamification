<?php

namespace App\Console\Commands;

use App\Models\BankInformation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteDuplicateBankInfo extends Command
{
    protected $signature = 'bank-info:clean-duplicates';
    protected $description = 'Delete duplicate bank information, keeping only the latest for each user';

    public function handle()
    {
        $startTime = microtime(true);
        $this->info('Starting duplicate bank info cleanup...');
        Log::info('Starting duplicate bank info cleanup...');

        $totalDeleted = 0;

        // Get user IDs with multiple bank information records
        $usersWithDuplicates = BankInformation::select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('user_id');

        $this->info("Found {$usersWithDuplicates->count()} users with duplicate bank info.");
        Log::info("Found {$usersWithDuplicates->count()} users with duplicate bank info.");

        $usersWithDuplicates->chunk(50)->each(function ($userIds) use (&$totalDeleted) {
            foreach ($userIds as $userId) {
                $bankInfos = BankInformation::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $deleted = $bankInfos->skip(1)->each(function ($bankInfo) {
                    $bankInfo->delete();
                })->count();

                $totalDeleted += $deleted;
            }
            $this->info("Deleted {$totalDeleted} duplicate records...");
            Log::info("Deleted {$totalDeleted} duplicate records...");
        });

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);

        $this->info("Completed! Deleted {$totalDeleted} duplicate records in {$duration} seconds.");
        Log::info("Completed! Deleted {$totalDeleted} duplicate records in {$duration} seconds.");
    }
}
