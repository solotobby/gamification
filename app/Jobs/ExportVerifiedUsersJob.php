<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\PaymentTransaction;
use App\Models\ExportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ExportVerifiedUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $exportJobId;
    protected $email;
    protected $filters;

    public $timeout = 600;
    public $tries = 1;

    public function __construct($exportJobId, $email, $filters = [])
    {
        $this->exportJobId = $exportJobId;
        $this->filters = $filters;
        $this->email = $email;
    }

    public function handle()
    {
        $exportJob = ExportJob::find($this->exportJobId);
        if (!$exportJob) return;

        try {
            $exportJob->update(['status' => 'processing']);

            $users = $this->getAllFilteredUsers();

            if ($users->isEmpty()) {
                $exportJob->update(['status' => 'failed', 'error_message' => 'No data found']);
                Mail::raw('No verified users found.', function ($message) {
                    $message->to($this->email)->subject('Export - No Data');
                });
                return;
            }

            $fileName = 'freebyz_verified_users_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $folder = storage_path('app/public/exports');

            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            $filePath = "{$folder}/{$fileName}";
            $file = fopen($filePath, 'w');

            if (!$file) {
                throw new \Exception('Failed to create CSV file');
            }

            $dateRangeLabel = match ($this->filters['date_range'] ?? null) {
                'last_30' => 'Last 30 Days',
                'last_3_months' => 'Last 3 Months',
                'last_6_months' => 'Last 6 Months',
                'last_1_year' => 'Last 1 Year',
                default => 'All Time'
            };

            $amountRange = match ($this->filters['amount_range'] ?? null) {
                'below_10k' => 'Below 10,000',
                '10k_30k' => 'Between 10,000 and 30,000',
                '30k_70k' => 'Between 30,000 and 70,000',
                '50k_above' => 'Above 50,000',
                '70k_above' => 'Above 70,000',
                default => 'Total income in selected date range'
            };

            fputcsv($file, ["Users with income from: $dateRangeLabel of Amount: $amountRange"]);
            fputcsv($file, []);
            fputcsv($file, ['', '#', 'UserId', 'Name', 'Email', 'Income', 'Currency']);

            $i = 1;
            foreach ($users as $user) {
                fputcsv($file, [
                    '',
                    $i++,
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->income,
                    $user->wallet->base_currency ?? 'NGN',
                ]);
            }
            fclose($file);

            $downloadUrl = rtrim(config('services.env.url'), '/') . '/storage/exports/' . $fileName;

            Mail::raw("Your verified users CSV export is ready.\n\nDownload here: {$downloadUrl}", function ($message) {
                $message->to($this->email)->subject('Freebyz Verified Users Export');
            });

            $exportJob->update([
                'status' => 'completed',
                'file_path' => "exports/{$fileName}",
                'completed_at' => now()
            ]);
        } catch (\Exception $e) {
            $exportJob->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            Mail::raw("Export failed: " . $e->getMessage(), function ($message) {
                $message->to($this->email)->subject('Export Failed');
            });
        }
    }

    private function getAllFilteredUsers()
    {
        $query = $this->buildQuery();
        return $query->selectRaw('users.*, COALESCE(income_calc.total_income, 0) as income')
            ->latest('users.id')
            ->with('wallet')
            ->get();
    }

    private function buildQuery()
    {
        $query = User::where('role', 'regular')->where('is_verified', '1');

        $incomeSubquery = PaymentTransaction::selectRaw('user_id, SUM(amount) as total_income')
            ->where('tx_type', 'Credit')
            ->where('user_type', 'regular')
            ->where('status', 'successful');

        if (!empty($this->filters['date_range'])) {
            $days = match ($this->filters['date_range']) {
                'last_30' => 30,
                'last_3_months' => 90,
                'last_6_months' => 180,
                'last_1_year' => 365,
                default => null
            };
            if ($days) {
                $incomeSubquery->whereDate('created_at', '>=', now()->subDays($days));
            }
        }

        $incomeSubquery->groupBy('user_id');

        $query->leftJoinSub($incomeSubquery, 'income_calc', function ($join) {
            $join->on('users.id', '=', 'income_calc.user_id');
        });

        if (!empty($this->filters['amount_range'])) {
            [$min, $max] = match ($this->filters['amount_range']) {
                'below_10k' => [0, 9999],
                '10k_30k' => [10000, 30000],
                '30k_70k' => [30000, 70000],
                '50k_above' => [50000, PHP_INT_MAX],
                '70k_above' => [70000, PHP_INT_MAX],
                default => [0, PHP_INT_MAX]
            };
            $query->whereBetween('income_calc.total_income', [$min, $max]);
        }

        return $query;
    }
}
