<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class BackfillVerifiedAt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $lastProcessedId;
    public $chunkSize;

    public function __construct($lastProcessedId = 0, $chunkSize = 20000)
    {
        $this->lastProcessedId = $lastProcessedId;
        $this->chunkSize = $chunkSize;
    }

    public function handle()
    {
        // Get the next chunk of user IDs that need processing
        $nextChunk = DB::table('users')
            ->where('id', '>', $this->lastProcessedId)
            ->where('is_verified', 1)
            ->whereNull('verified_at')
            ->orderBy('id')
            ->limit($this->chunkSize)
            ->pluck('id');

        if ($nextChunk->isEmpty()) {
            // No more records to process
            return;
        }

        $minId = $nextChunk->first();
        $maxId = $nextChunk->last();

        // Process this chunk using prepared statement
        DB::statement("
            UPDATE users
            JOIN (
                SELECT user_id, MIN(created_at) as verified_date
                FROM payment_transactions
                WHERE status = 'successful'
                  AND type = 'upgrade_payment'
                GROUP BY user_id
            ) pt ON users.id = pt.user_id
            SET users.verified_at = pt.verified_date
            WHERE users.is_verified = 1
              AND users.verified_at IS NULL
              AND users.id BETWEEN ? AND ?
        ", [$minId, $maxId]);

        // Dispatch next chunk
        self::dispatch($maxId, $this->chunkSize);
    }
}
