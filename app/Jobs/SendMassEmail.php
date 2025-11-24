<?php

namespace App\Jobs;

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

    public function __construct(array $userIds, string $message, string $subject, int $campaignId)
    {
        $this->userIds = $userIds;
        $this->message = $message;
        $this->subject = $subject;
        $this->campaignId = $campaignId;
    }

    public function handle(): void
    {
        $pendingLogs = MassEmailLog::where('campaign_id', $this->campaignId)
            ->whereIn('user_id', $this->userIds)
            ->where('status', 'pending')
            ->pluck('user_id')
            ->toArray();

        if (empty($pendingLogs)) {
            return; // All emails already sent
        }

        $users = User::whereIn('id', $pendingLogs)
            ->select('id', 'email', 'name')
            ->get();

        foreach ($users as $user) {
            try {
                $log = MassEmailLog::where('campaign_id', $this->campaignId)
                    ->where('user_id', $user->id)
                    ->first();

                if ($log->status !== 'pending') {
                    continue;
                }

                $formattedMessage = nl2br($this->message);

                $htmlBody = view('emails.mass_mail.content_new', [
                    'name' => $user->name,
                    'message' => $formattedMessage,
                ])->render();

                $response = sendZeptoMail(
                    $user->email,
                    $user->name,
                    $this->subject,
                    $htmlBody
                );

                if ($response['status'] === 'accepted') {
                    $messageId = $response['message_id'];
                    $status = 'sent';
                } else {
                    $messageId = null;
                    $status = 'failed';
                }

                MassEmailLog::where('campaign_id', $this->campaignId)
                    ->where('user_id', $user->id)
                    ->update([
                        'status' => $status,
                        'message_id' => $messageId,
                        'sent_at' => $status === 'sent' ? now() : null,
                        'error_message' => $response['error'] ?? null,
                    ]);

            } catch (\Throwable $e) {
                Log::error('Mass email failed', [
                    'user_id' => $user->id,
                    'campaign_id' => $this->campaignId,
                    'error' => $e->getMessage(),
                ]);

                MassEmailLog::where('campaign_id', $this->campaignId)
                    ->where('user_id', $user->id)
                    ->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
            }
        }
    }
}
