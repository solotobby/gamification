<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\BackfillVerifiedAt;
use Illuminate\Support\Facades\DB;

class DispatchBackfillVerifiedAt extends Command
{
    protected $signature = 'backfill:verified-at {chunkSize=50000}';
    protected $description = 'Dispatch jobs to backfill verified_at for verified users in chunks';

    public function handle()
    {
        $minId = DB::table('users')->min('id');
        $maxId = DB::table('users')->max('id');
        $chunkSize = (int) $this->argument('chunkSize');

        $this->info("Dispatching jobs from ID {$minId} to {$maxId} in chunks of {$chunkSize}...");

        for ($startId = $minId; $startId <= $maxId; $startId += $chunkSize) {
            $endId = $startId + $chunkSize - 1;

            BackfillVerifiedAt::dispatch($startId, $endId);

            $this->line("Dispatched chunk: {$startId} - {$endId}");
        }

        $this->info("All jobs dispatched!");
    }
}
