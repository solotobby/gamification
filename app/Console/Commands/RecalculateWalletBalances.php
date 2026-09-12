<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateWalletBalances extends Command
{
    protected $signature = 'wallet:recalculate-balances
                            {--chunk=5000 : Number of wallets to process per batch}
                            {--report : Show a mismatch report after recalculating}
                            {--tolerance=0.01 : Difference threshold to flag as a mismatch in the report}
                            {--user= : Recalculate a single user_id only, ignoring --chunk}';

    protected $description = 'Recompute each user\'s balance from their payment_transactions history matched against their wallet base_currency and save into wallets.temp_balance.';

    protected string $currencyMapSql = "
        CASE
            WHEN LOWER(currency_col) IN ('naira', 'ngn') THEN 'NGN'
            WHEN LOWER(currency_col) IN ('dollar', 'usd') THEN 'USD'
            ELSE UPPER(currency_col)
        END
    ";

    public function handle(): int
    {
        if ($userId = $this->option('user')) {
            $this->recalculateSingleUser((int) $userId);
            return self::SUCCESS;
        }

        $chunkSize = (int) $this->option('chunk');
        $minId = DB::table('wallets')->min('id');
        $maxId = DB::table('wallets')->max('id');

        if (!$minId) {
            $this->info('No wallets found.');
            return self::SUCCESS;
        }

        $this->info("Recalculating balances for wallets #{$minId}–#{$maxId} in batches of {$chunkSize}...");
        $bar = $this->output->createProgressBar((int) ceil(($maxId - $minId + 1) / $chunkSize));
        $bar->start();

        for ($start = $minId; $start <= $maxId; $start += $chunkSize) {
            $end = min($start + $chunkSize - 1, $maxId);
            $this->recalculateBatch($start, $end);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Recalculation complete.');

        if ($this->option('report')) {
            $this->showMismatchReport((float) $this->option('tolerance'));
        }

        return self::SUCCESS;
    }

    /**
     * Batch calculation using SQL JOIN
     */
    protected function recalculateBatch(int $startId, int $endId): void
    {
        $wCurrency  = str_replace('currency_col', 'w2.base_currency', $this->currencyMapSql);
        $ptCurrency = str_replace('currency_col', 'pt.currency', $this->currencyMapSql);

        DB::statement("
            UPDATE wallets w
            LEFT JOIN (
                SELECT
                    pt.user_id,
                    SUM(
                        CASE
                            WHEN LOWER(pt.tx_type) = 'credit' THEN pt.amount
                            WHEN LOWER(pt.tx_type) = 'debit'  THEN -pt.amount
                            ELSE 0
                        END
                    ) AS computed_balance
                FROM payment_transactions pt
                INNER JOIN wallets w2 ON w2.user_id = pt.user_id
                WHERE pt.status = 'successful'
                  AND w2.id BETWEEN ? AND ?
                  AND {$ptCurrency} = {$wCurrency}
                GROUP BY pt.user_id
            ) totals ON totals.user_id = w.user_id
            SET
                w.temp_balance = COALESCE(totals.computed_balance, 0),
                w.temp_balance_calculated_at = NOW()
            WHERE w.id BETWEEN ? AND ?
        ", [$startId, $endId, $startId, $endId]);
    }

    /**
     * Single user recalculation
     */
    public function recalculateSingleUser(int $userId): float
    {
        $wallet = DB::table('wallets')->where('user_id', $userId)->first();

        if (!$wallet) {
            $this->error("No wallet found for user #{$userId}.");
            return 0.0;
        }

        $mappedWalletCurrency = $this->mapCurrency($wallet->base_currency ?: 'NGN');

        $computed = DB::table('payment_transactions')
            ->where('user_id', $userId)
            ->where('status', 'successful')
            ->get()
            ->filter(fn($tx) => $this->mapCurrency($tx->currency ?: 'NGN') === $mappedWalletCurrency)
            ->sum(fn($tx) => strtolower($tx->tx_type) === 'credit' ? (float) $tx->amount
                : (strtolower($tx->tx_type) === 'debit' ? -(float) $tx->amount : 0));

        DB::table('wallets')->where('user_id', $userId)->update([
            'temp_balance' => $computed,
            'temp_balance_calculated_at' => now(),
        ]);

        $liveBalance = match ($mappedWalletCurrency) {
            'NGN'   => (float) $wallet->balance,
            'USD'   => (float) $wallet->usd_balance,
            default => (float) $wallet->base_currency_balance,
        };

        $this->info("User #{$userId} ({$mappedWalletCurrency}):");
        $this->line("  Live balance:      " . number_format($liveBalance, 2));
        $this->line("  Computed (temp):   " . number_format($computed, 2));
        $this->line("  Difference:        " . number_format(abs($liveBalance - $computed), 2));

        return (float) $computed;
    }

    /**
     * Display mismatch report in console
     */
    protected function showMismatchReport(float $tolerance): void
    {
        $liveBalanceSql = "
            CASE
                WHEN LOWER(COALESCE(base_currency, 'NGN')) IN ('naira', 'ngn') THEN balance
                WHEN LOWER(COALESCE(base_currency, 'NGN')) IN ('dollar', 'usd') THEN usd_balance
                ELSE base_currency_balance
            END
        ";

        $mismatches = DB::table('wallets')
            ->selectRaw("
                user_id,
                COALESCE(NULLIF(base_currency, ''), 'NGN') as base_currency,
                temp_balance,
                {$liveBalanceSql} as live_balance,
                ABS(temp_balance - ({$liveBalanceSql})) as diff
            ")
            ->whereNotNull('temp_balance')
            ->whereRaw("ABS(temp_balance - ({$liveBalanceSql})) > ?", [$tolerance])
            ->orderByDesc('diff')
            ->limit(50)
            ->get();

        $totalMismatchCount = DB::table('wallets')
            ->whereNotNull('temp_balance')
            ->whereRaw("ABS(temp_balance - ({$liveBalanceSql})) > ?", [$tolerance])
            ->count();

        $this->newLine();
        $this->warn("Found {$totalMismatchCount} wallet(s) with a discrepancy > {$tolerance}.");

        if ($mismatches->isNotEmpty()) {
            $this->table(
                ['User ID', 'Currency', 'Live Balance', 'Computed (temp_balance)', 'Diff'],
                $mismatches->map(fn($m) => [
                    $m->user_id,
                    $m->base_currency,
                    number_format($m->live_balance, 2),
                    number_format($m->temp_balance, 2),
                    number_format($m->diff, 2),
                ])
            );

            if ($totalMismatchCount > 50) {
                $this->line('(showing top 50 by size of discrepancy)');
            }
        }
    }

    public function mapCurrency(?string $currency): string
    {
        $currency = $currency ?: 'NGN';
        return match (strtolower($currency)) {
            'naira', 'ngn' => 'NGN',
            'dollar', 'usd' => 'USD',
            default => strtoupper($currency),
        };
    }
}
