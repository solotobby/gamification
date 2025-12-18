<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignWorker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecalculateCampaignPricing extends Command
{
    protected $signature = 'campaign:recalculate {campaign_id} {new_worker_amount} {new_admin_commission}';
    protected $description = 'Recalculate campaign pricing and reduce number of workers based on remaining budget';

    public function handle()
    {
        $campaignId = $this->argument('campaign_id');
        $newWorkerAmount = (float) $this->argument('new_worker_amount');
        $newAdminCommission = (float) $this->argument('new_admin_commission');

        $campaign = Campaign::find($campaignId);

        if (!$campaign) {
            $this->error("Campaign not found!");
            return 1;
        }

        // Get completed/pending workers count
        $completedWorkers = CampaignWorker::where('campaign_id', $campaignId)
            ->whereIn('status', ['Approved', 'Pending'])
            ->count();

        $this->info("Campaign: {$campaign->post_title}");
        $this->info("Original workers: {$campaign->number_of_staff}");
        $this->info("Workers already done: {$completedWorkers}");
        $this->info("Original worker amount: {$campaign->campaign_amount}");
        $this->info("New worker amount: {$newWorkerAmount}");
        $this->info("New admin commission: {$newAdminCommission}");

        if (!$this->confirm('Do you want to continue?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        DB::beginTransaction();

        try {
            // Calculate original costs
            $originalWorkerCost = $campaign->number_of_staff * $campaign->campaign_amount;

            // Calculate admin commission (60% for regular, 100% for business)
            $isBusinessAccount = $campaign->user->is_business ?? false;
            $adminPercent = $isBusinessAccount ? 100 : 60;
            $originalAdminCommission = ($adminPercent / 100) * $originalWorkerCost;

            // Calculate upload cost
            $uploadCost = 0;
            if ($campaign->allow_upload) {
                $uploadAmount = currencyParameter($campaign->currency)->allow_upload;
                $uploadCost = $campaign->number_of_staff * $uploadAmount;
            }

            // Calculate priority cost
            $priorityCost = 0;
            if ($campaign->approved == 'Priotize' || $campaign->approved == 'Priotized') {
                $priorityCost = currencyParameter($campaign->currency)->priotize;
            }

            // Original total paid
            $originalTotalPaid = $originalWorkerCost + $originalAdminCommission + $uploadCost + $priorityCost;

            $this->info("\n--- Original Breakdown ---");
            $this->info("Worker cost: {$originalWorkerCost}");
            $this->info("Admin commission: {$originalAdminCommission}");
            $this->info("Upload cost: {$uploadCost}");
            $this->info("Priority cost: {$priorityCost}");
            $this->info("Total paid: {$originalTotalPaid}");

            // Calculate amount already spent on completed workers
            $amountSpentOnWorkers = $completedWorkers * $campaign->campaign_amount;
            $amountSpentOnAdmin = $completedWorkers * ($originalAdminCommission / $campaign->number_of_staff);

            // Calculate remaining budget
            $remainingBudget = $originalTotalPaid - $amountSpentOnWorkers - $amountSpentOnAdmin;

            // Subtract fixed costs (upload and priority remain the same)
            $budgetForNewWorkers = $remainingBudget - $uploadCost - $priorityCost;

            $this->info("\n--- Spent So Far ---");
            $this->info("Spent on workers: {$amountSpentOnWorkers}");
            $this->info("Spent on admin: {$amountSpentOnAdmin}");
            $this->info("Remaining budget: {$remainingBudget}");
            $this->info("Budget for new workers: {$budgetForNewWorkers}");

            if ($budgetForNewWorkers <= 0) {
                $this->error("No remaining budget for new workers!");
                DB::rollBack();
                return 1;
            }

            // Calculate new number of workers
            $costPerNewWorker = $newWorkerAmount + $newAdminCommission;
            $newTotalWorkers = floor($budgetForNewWorkers / $costPerNewWorker);

            if ($newTotalWorkers <= 0) {
                $this->error("Budget too low for even 1 worker at new rate!");
                DB::rollBack();
                return 1;
            }

            // Total workers = completed + new available slots
            $totalWorkersAfterRecalculation = $completedWorkers + $newTotalWorkers;

            $this->info("\n--- New Breakdown ---");
            $this->info("Cost per new worker (worker + admin): {$costPerNewWorker}");
            $this->info("New available worker slots: {$newTotalWorkers}");
            $this->info("Total workers after recalculation: {$totalWorkersAfterRecalculation}");

            if (!$this->confirm('Proceed with update?')) {
                DB::rollBack();
                $this->info('Operation cancelled.');
                return 0;
            }

            // Update campaign
            $campaign->campaign_amount = $newWorkerAmount;
            $campaign->number_of_staff = $totalWorkersAfterRecalculation;

            // Recalculate total_amount
            $newWorkerCost = $totalWorkersAfterRecalculation * $newWorkerAmount;
            $newAdminTotal = $totalWorkersAfterRecalculation * $newAdminCommission;
            // $campaign->total_amount = $newWorkerCost + $newAdminTotal + $uploadCost + $priorityCost;

            $campaign->save();

            DB::commit();

            $this->info("\n✓ Campaign updated successfully!");
            $this->info("New worker amount: {$campaign->campaign_amount}");
            $this->info("New total workers: {$campaign->number_of_staff}");
            $this->info("New total amount: {$campaign->total_amount}");

            Log::info("Campaign {$campaignId} recalculated", [
                'old_worker_amount' => $campaign->campaign_amount,
                'new_worker_amount' => $newWorkerAmount,
                'old_workers' => $campaign->number_of_staff,
                'new_workers' => $totalWorkersAfterRecalculation,
                'completed_workers' => $completedWorkers
            ]);

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            Log::error("Campaign recalculation failed: " . $e->getMessage());
            return 1;
        }
    }
}
