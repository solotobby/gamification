<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMassSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phones;
    public $message;

    public function __construct(array $phones, string $message)
    {
        $this->phones = $phones;
        $this->message = $message;
    }

    public function handle()
    {
        $formattedPhones = formatAndArrange($this->phones);

        if (empty($formattedPhones)) {
            return;
        }

        $process = sendBulkSMS($formattedPhones, $this->message);
        $code = is_array($process) ? $process['code'] : $process->code;

        if ($code !== 'ok') {
            Log::error('SMS sending failed', [
                'error' => $process['message'] ?? $process->message ?? 'Unknown error'
            ]);
        }else{
            Log::info($process);
        }
    }
}
