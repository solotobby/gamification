<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\BackfillVerifiedAt;

class DispatchBackfillVerifiedAt extends Command
{
    protected $signature = 'backfill:verified-at {--chunk-size=20000}';
    protected $description = 'Start the self-chaining backfill process for verified_at';

    public function handle()
    {
        $chunkSize = (int) $this->option('chunk-size');

        $this->info("Starting backfill process with chunk size of {$chunkSize}...");

        BackfillVerifiedAt::dispatch(0, $chunkSize);

        $this->info("Initial job dispatched! The job will automatically process all records in chunks.");
        $this->line("Monitor your queue workers to track progress.");
    }
}
