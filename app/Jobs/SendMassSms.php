<?php

namespace App\Jobs;

use App\Models\MassEmailCampaign;
use App\Models\MassEmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMassSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userIds;
    public $phones;
    public $message;
    public $campaignId;

    public function __construct($userIds, $phones, $message, $campaignId)
    {
        $this->userIds = $userIds;
        $this->phones = $phones;
        $this->message = $message;
        $this->campaignId = $campaignId;
    }

    public function handle()
    {
        $phones = formatAndArrange($this->phones);

        Log::info('Sending SMS', [
            'campaign_id' => $this->campaignId,
            'phones_count' => count($phones)
        ]);

        $result = sendBulkSMS($phones, $this->message);

        $code = is_array($result) ? $result['code'] : $result->code;
        $status = $code === 'ok' ? 'sent' : 'failed';

        Log::info('SMS Result', [
            'campaign_id' => $this->campaignId,
            'status' => $status,
            'code' => $code
        ]);

        // Update logs based on user_ids (more reliable than phone matching)
        // MassEmailLog::where('campaign_id', $this->campaignId)
        //     ->where('channel', 'sms')
        //     ->whereIn('user_id', $this->userIds)
        //     ->update([
        //         'status' => $status,
        //         'sent_at' => $status === 'sent' ? now() : null,
        //         'error_message' => $code !== 'ok' ? ($result['message'] ?? 'Unknown error') : null,
        //         'updated_at' => now()
        //     ]);

        // Update campaign stats
        if ($status === 'sent') {
            MassEmailCampaign::where('id', $this->campaignId)
                ->increment('sms_delivered', count($this->userIds));
        } else {
            MassEmailCampaign::where('id', $this->campaignId)
                ->increment('sms_failed', count($this->userIds));
        }
    }
}
