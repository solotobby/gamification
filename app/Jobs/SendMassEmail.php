<?php

namespace App\Jobs;

use App\Models\MassEmailCampaign;
use App\Models\User;
use App\Models\MassEmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMassEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 180;
    public $maxExceptions = 2;

    protected $userIds;
    protected $message;
    protected $subject;
    protected $campaignId;
    protected $imagePath;

    public function __construct(array $userIds, string $message, string $subject, int $campaignId, ?string $imagePath = null)
    {
        $this->userIds = $userIds;
        $this->message = $message;
        $this->subject = $subject;
        $this->campaignId = $campaignId;
        $this->imagePath = $imagePath;
    }

    public function handle(): void
    {
        $logs = MassEmailLog::where('campaign_id', $this->campaignId)
            ->whereIn('user_id', $this->userIds)
            ->where('status', 'pending')
            ->get()
            ->keyBy('user_id');

        if ($logs->isEmpty()) {
            return;
        }

        $users = User::whereIn('id', $logs->keys())
            ->select('id', 'email', 'name')
            ->get();

        $deliveredCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            $log = $logs[$user->id];

            try {
                // Extract first name
                $firstName = explode(' ', trim($user->name))[0];

                $htmlBody = view('emails.mass_mail.content_new', [
                    'name' => $firstName,
                    'message' => nl2br($this->message),
                    'imagePath' => $this->imagePath,
                ])->render();

                $response = sendZeptoMail(
                    $user->email,
                    $user->name,
                    $this->subject.', '.$firstName,
                    $htmlBody
                );

                $status = $response['status'] === 'accepted' ? 'sent' : 'failed';

                $log->update([
                    'status' => $status,
                    'message_id' => $response['message_id'] ?? null,
                    'sent_at' => $status === 'sent' ? now() : null,
                    'error_message' => $response['error'] ?? null,
                ]);

                $status === 'sent' ? $deliveredCount++ : $failedCount++;
            } catch (\Throwable $e) {
                Log::error('Mass email failed', [
                    'campaign_id' => $this->campaignId,
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);

                $log->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                $failedCount++;
            }
        }

        MassEmailCampaign::where('id', $this->campaignId)
            ->increment('delivered', $deliveredCount);

        MassEmailCampaign::where('id', $this->campaignId)
            ->increment('failed', $failedCount);
    }
}
