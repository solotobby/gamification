<?php

namespace App\Console;

use App\Mail\GeneralMail;
use App\Mail\JobBroadcast;
use App\Models\Business;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\DisputedJobs;
use App\Models\OTP;
use App\Models\PaymentTransaction;
use App\Models\Question;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
          // $schedule->command('inspire')->hourly();
        // $schedule->command('task')->everyMinute();//->dailyAt('00:00');

        // $schedule->command('users:delete-unverified')->dailyAt('02:00');

        $schedule->command('disputes:resolve-completed')->hourly();

        $schedule->command('command:task')->daily();

          $schedule->command('jobs:send-broadcast')->dailyAt('9:00');

        $schedule->command('campaigns:send-weekly-broadcast')->dailyAt('10:30');

        $schedule->command('campaigns:auto-approve-24hours')->hourly();

        // $schedule->command('campaigns:auto-approve-business')->hourly();

        $schedule->command('campaigns:auto-approve-7days')->weeklyOn(4,'04:00');

        // $schedule->command('business:rotate-promotion')->dailyAt('03:00');

        // $schedule->command('questions:cleanup-invalid')->dailyAt('03:00');

        // $schedule->command('campaigns:flag-abusive')->twiceDaily(8, 20);

        // Auto-approve campaign workers after 24 hours (non-business)
        $schedule->call(function () {
            Log::info('Auto-approve pending campaign workers after 24 hours (excluding business accounts) started');

            $cutoffTime = Carbon::now()->subHours(24);

            $lists = CampaignWorker::where('status', 'Pending')
                ->whereNull('reason')
                ->whereHas('campaign.user', function ($query) {
                    $query->where('is_business', false);
                })
                ->where('created_at', '<=', $cutoffTime)
                ->get();

            Log::info('Found ' . $lists->count() . ' campaign workers to auto-approve.');

            foreach ($lists as $list) {
                try {
                    $this->approveCampaignWorker24Hours($list);
                } catch (\Exception $e) {
                    Log::error('Failed to approve campaign worker ID ' . $list->id . ': ' . $e->getMessage());
                }
            }

            Log::info('Auto-approved ' . $lists->count() . ' campaign workers (24+ hours old).');
        })->hourly();

        // Auto-approve business account campaigns
        // $schedule->call(function () {
        //     Log::info('Auto-approve business account campaigns based on their approval time setting started');

        //     $campaigns = Campaign::whereHas('user', function ($query) {
        //         $query->where('is_business', true);
        //     })->where('approval_time', '>', 0)->get();

        //     $totalApproved = 0;

        //     foreach ($campaigns as $campaign) {
        //         $approvalTime = $campaign->approval_time;
        //         $cutoffTime = Carbon::now()->subHours($approvalTime);

        //         $lists = CampaignWorker::where('campaign_id', $campaign->id)
        //             ->where('status', 'Pending')
        //             ->whereNull('reason')
        //             ->where('created_at', '<=', $cutoffTime)
        //             ->get();

        //         foreach ($lists as $list) {
        //             $this->approveCampaignWorkerBusiness($list);
        //             $totalApproved++;
        //         }
        //     }

        //     Log::info('Auto-approved ' . $totalApproved . ' business campaign workers.');
        // })->hourly();

        // Cleanup invalid questions
        $schedule->call(function () {
            $count = Question::where('option_A', null)->count();
            Question::where('option_A', null)->delete();
            Log::info('Deleted ' . $count . ' invalid questions.');
        })->hourly();

        // Delete expired OTPs
        $schedule->call(function () {
            $count = OTP::where('is_verified', 0)
                ->where('created_at', '<=', now()->subMinutes(10))
                ->delete();
            Log::info('OTP Cleanup: Deleted ' . $count . ' expired OTPs.');
        })->dailyAt('02:00');

        // Delete duplicate bank information
        // $schedule->command('bank-info:clean-duplicates')->daily();


        // Flag abusive campaigns
        $schedule->call(function () {
            $liveCampaigns = Campaign::where('status', 'Live')
                ->where('is_completed', false)
                ->where('flagging_resolved', false)
                ->get();

            $flaggedCount = 0;

            foreach ($liveCampaigns as $campaign) {
                $totalWorkers = CampaignWorker::where('campaign_id', $campaign->id)->count();

                if ($totalWorkers < 10) continue;

                $deniedWorkers = CampaignWorker::where('campaign_id', $campaign->id)
                    ->where('status', 'Denied')
                    ->count();

                $denialPercentage = ($deniedWorkers / $totalWorkers) * 100;

                if ($denialPercentage >= 70) {
                    $campaign->status = 'Flagged';
                    $campaign->flagged_at = now();
                    $campaign->flagged_reason = "Campaign flagged: {$denialPercentage}% denial rate ({$deniedWorkers}/{$totalWorkers} workers denied)";
                    $campaign->save();

                    $user = User::find($campaign->user_id);
                    if ($user) {
                        $subject = 'Campaign Flagged - High Denial Rate';
                        $content = "Your campaign '{$campaign->post_title}' has been flagged due to excessive worker denials.";
                        try {
                            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
                        } catch (\Exception $e) {
                            Log::error("Failed to send email to user {$user->id}: " . $e->getMessage());
                        }
                    }

                    $flaggedCount++;
                }
            }

            if ($flaggedCount > 0) {
                $adminUser = User::where('id', 1)->first();
                if ($adminUser && config('app.env') == 'Production') {
                    $subject = 'Campaigns Flagged - Automated Report';
                    $content = "{$flaggedCount} campaigns were flagged for excessive worker denials.";
                    Mail::to('hello@freebyztechnologies.com')->cc('blessing@freebyztechnologies.com')
                        ->send(new GeneralMail($adminUser, $content, $subject, ''));
                }
            }

            Log::info("Flagged {$flaggedCount} abusive campaigns.");
        })->daily();

        // Resolve disputes for completed campaigns
        $schedule->call(function () {
            $disputes = CampaignWorker::where('is_dispute', true)
                ->where('is_dispute_resolved', false)
                ->whereHas('campaign', function ($query) {
                    $query->where('is_completed', true);
                })
                ->get();

            $count = 0;

            foreach ($disputes as $workDone) {
                $workDone->is_dispute_resolved = true;
                $workDone->is_dispute = false;
                $workDone->save();

                $disputeJob = DisputedJobs::where('campaign_worker_id', $workDone->id)->first();
                if ($disputeJob) {
                    $disputeJob->response = 'Auto-resolved: Campaign already completed';
                    $disputeJob->save();
                }

                $count++;
            }

            Log::info("Resolved {$count} disputes for completed campaigns");
        })->daily();

        // Rotate business promotion
        $schedule->call(function () {
            Log::info('Rotate business promotion started');

            Business::query()->where('status', 'ACTIVE')->update(['is_live' => false]);

            $randomBusiness = Business::where('status', 'ACTIVE')->inRandomOrder()->first();

            if ($randomBusiness) {
                $randomBusiness->update(['is_live' => true]);

                $user = User::where('id', $randomBusiness->user_id)->first();
                $subject = 'Freebyz Business Promotion - Business Selected';
                $content = 'Your business has been selected for Freebyz Business Promotion. This will last for 24hours';

                Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

                Log::info('Business promotion rotated. Selected: ' . $randomBusiness->name ?? $randomBusiness->id);
            } else {
                Log::warn('No active business found for promotion.');
            }
        })->daily();

        // Send weekly campaign broadcast
        $schedule->call(function () {
            Log::info('Send weekly campaign broadcast started');

            $campaigns = Campaign::where('status', 'Live')
                ->where('is_completed', false)
                ->orderBy('created_at', 'DESC')
                ->take(20)
                ->get();

            $list = [];
            foreach ($campaigns as $value) {
                $c = $value->pending_count + $value->completed_count;

                $list[] = [
                    'id' => $value->id,
                    'job_id' => $value->job_id,
                    'campaign_amount' => $value->campaign_amount,
                    'post_title' => $value->post_title,
                    'type' => $value->campaignType->name,
                    'category' => $value->campaignCategory->name,
                    'is_completed' => $c >= $value->number_of_staff ? true : false,
                    'currency' => $value->currency,
                ];
            }

            $filteredArray = array_filter($list, function ($item) {
                return $item['is_completed'] !== true;
            });

            $startOfWeek = Carbon::now()->startOfWeek()->subWeek();
            $endOfWeek = Carbon::now()->endOfWeek()->subWeek();
            $usersLastWeek = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();

            foreach ($usersLastWeek as $user) {
                $subject = 'Fresh Campaign';
                Mail::to($user->email)->send(new JobBroadcast($user, $subject, $filteredArray));
            }

            Log::info('Weekly broadcast sent to ' . $usersLastWeek->count() . ' users.');
        })->daily();

        // Update user names from bank info
        // $schedule->command('users:update-names-from-bank')->daily();
    }

    private function approveCampaignWorker7Days($ca)
    {
        $ca->status = 'Approved';
        $ca->reason = 'Auto-approval';
        $ca->save();

        $camp = Campaign::where('id', $ca->campaign_id)->first();
        checkCampaignCompletedStatus($camp->id);

        $user = User::where('id', $ca->user_id)->first();
        $baseCurrency = baseCurrency($user);
        $amountCredited = (float) $ca->amount;

        $wallet = Wallet::where('user_id', $ca->user_id)->first();
        $wallet->balance = (float) ($wallet->balance ?? 0);
        $wallet->usd_balance = (float) ($wallet->usd_balance ?? 0);
        $wallet->base_currency_balance = (float) ($wallet->base_currency_balance ?? 0);

        if ($baseCurrency == 'NGN') {
            $currency = 'NGN';
            $channel = 'paystack';
            $wallet->balance += $amountCredited;
        } elseif ($camp->currency == 'USD') {
            $currency = 'USD';
            $channel = 'paypal';
            $wallet->usd_balance += $amountCredited;
        } else {
            $currency = $baseCurrency;
            $channel = 'flutterwave';
            $wallet->base_currency_balance += $amountCredited;
        }

        $wallet->save();

        PaymentTransaction::create([
            'user_id' => $ca->user_id,
            'campaign_id' => '1',
            'reference' => time(),
            'amount' => $amountCredited,
            'balance' => walletBalance($ca->user_id),
            'status' => 'successful',
            'currency' => $currency,
            'channel' => $channel,
            'type' => 'campaign_payment',
            'description' => 'Campaign Payment for ' . $ca->campaign->post_title,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);
    }

    private function approveCampaignWorker24Hours($ca)
    {
        $ca->status = 'Approved';
        $ca->reason = 'Auto-approval after 24 hours';
        $ca->save();

        $camp = Campaign::where('id', $ca->campaign_id)->first();
        checkCampaignCompletedStatus($camp->id);

        $user = User::where('id', $ca->user_id)->first();
        $baseCurrency = baseCurrency($user);
        $amountCredited = $ca->amount;

        if ($baseCurrency == 'NGN') {
            $currency = 'NGN';
            $channel = 'paystack';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->balance += $amountCredited;
            $wallet->save();
        } elseif ($camp->currency == 'USD') {
            $currency = 'USD';
            $channel = 'paypal';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->usd_balance += $amountCredited;
            $wallet->save();
        } else {
            $currency = baseCurrency($user);
            $channel = 'flutterwave';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->base_currency_balance += $amountCredited;
            $wallet->save();
        }

        PaymentTransaction::create([
            'user_id' => $ca->user_id,
            'campaign_id' => $ca->campaign_id,
            'reference' => time() . '_' . $ca->id,
            'amount' => $amountCredited,
            'balance' => walletBalance($ca->user_id),
            'status' => 'successful',
            'currency' => $currency,
            'channel' => $channel,
            'type' => 'campaign_payment',
            'description' => 'Auto-approved payment for ' . $ca->campaign->post_title,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);
    }

    private function approveCampaignWorkerBusiness($ca)
    {
        $ca->status = 'Approved';
        $ca->reason = 'Auto-approval';
        $ca->save();

        $camp = Campaign::where('id', $ca->campaign_id)->first();
        checkCampaignCompletedStatus($camp->id);

        $user = User::where('id', $ca->user_id)->first();
        $baseCurrency = baseCurrency($user);
        $amountCredited = $ca->amount;

        if ($baseCurrency == 'NGN') {
            $currency = 'NGN';
            $channel = 'paystack';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->balance += $amountCredited;
            $wallet->save();
        } elseif ($camp->currency == 'USD') {
            $currency = 'USD';
            $channel = 'paypal';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->usd_balance += $amountCredited;
            $wallet->save();
        } else {
            $currency = baseCurrency($user);
            $channel = 'flutterwave';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->base_currency_balance += $amountCredited;
            $wallet->save();
        }

        PaymentTransaction::create([
            'user_id' => $ca->user_id,
            'campaign_id' => '1',
            'reference' => time(),
            'amount' => $amountCredited,
            'balance' => walletBalance($ca->user_id),
            'status' => 'successful',
            'currency' => $currency,
            'channel' => $channel,
            'type' => 'campaign_payment',
            'description' => 'Campaign Payment for ' . $ca->campaign->post_title,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
