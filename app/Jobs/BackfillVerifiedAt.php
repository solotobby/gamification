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

    public $startId;
    public $endId;

    public function __construct($startId, $endId)
    {
        $this->startId = $startId;
        $this->endId = $endId;
    }

    public function handle()
    {
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
              AND users.id BETWEEN {$this->startId} AND {$this->endId}
        ");
    }
}
