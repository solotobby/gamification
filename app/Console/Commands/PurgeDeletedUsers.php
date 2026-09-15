<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurgeDeletedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:purge-deleted {--dry-run : Only list users that would be permanently purged without deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete user accounts that have been soft-deleted for 60 days or longer.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoff = Carbon::now()->subDays(60);
        $dryRun = (bool) $this->option('dry-run');

        $this->info("Scanning for soft-deleted user accounts deleted on or before {$cutoff->toDateTimeString()} (60-day threshold)...");

        $query = User::onlyTrashed()->where('deleted_at', '<=', $cutoff);
        $totalCount = $query->count();

        if ($totalCount === 0) {
            $this->info('No expired soft-deleted user accounts found.');
            return self::SUCCESS;
        }

        $this->info("Found {$totalCount} user account(s) ready for permanent deletion.");

        $purgedCount = 0;
        $failedCount = 0;

        $query->chunkById(100, function ($users) use ($dryRun, &$purgedCount, &$failedCount) {
            foreach ($users as $user) {
                if ($dryRun) {
                    $this->line("[DRY RUN] Would permanently delete User #{$user->id} ({$user->name} - {$user->email}), soft-deleted at {$user->deleted_at}");
                    $purgedCount++;
                    continue;
                }

                try {
                    DB::transaction(function () use ($user) {
                        if (method_exists($user, 'tokens')) {
                            $user->tokens()->delete();
                        }

                        $user->forceDelete();
                    });

                    Log::info("PurgeDeletedUsers: Permanently deleted User #{$user->id} ({$user->email}), soft-deleted at {$user->deleted_at}");
                    $purgedCount++;
                } catch (\Throwable $e) {
                    $failedCount++;
                    Log::error("PurgeDeletedUsers: Failed to permanently delete User #{$user->id} ({$user->email}): " . $e->getMessage());
                    $this->error("Failed to delete User #{$user->id}: {$e->getMessage()}");
                }
            }
        });

        if ($dryRun) {
            $this->info("[DRY RUN] Completed. {$purgedCount} user account(s) would be permanently purged.");
        } else {
            $this->info("Successfully permanently purged {$purgedCount} user account(s). Failed: {$failedCount}.");
        }

        return self::SUCCESS;
    }
}
