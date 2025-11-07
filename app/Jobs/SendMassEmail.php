<?php
namespace App\Jobs;

use App\Mail\MassMail;
use App\Models\User;
use App\Models\MassEmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMassEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userIds;
    public $message;
    public $subject;
    public $campaignId;

    public function __construct($userIds, $message, $subject, $campaignId)
    {
        $this->userIds = $userIds;
        $this->message = $message;
        $this->subject = $subject;
        $this->campaignId = $campaignId;
    }

    public function handle()
{
    $users = User::whereIn('id', $this->userIds)->get(['id', 'email', 'name']);

    foreach ($users as $user) {
        try {
            Mail::to($user->email)->send(
                new MassMail($user->name, $this->message, $this->subject)
            );

            // Get message ID (you'll need to capture this from ZeptoMail response)
            $messageId = uniqid('msg_', true);

            MassEmailLog::where('campaign_id', $this->campaignId)
                ->where('user_id', $user->id)
                ->update([
                    'status' => 'sent',
                    'message_id' => $messageId,
                    'sent_at' => now(),
                ]);

        } catch (\Exception $e) {
            MassEmailLog::where('campaign_id', $this->campaignId)
                ->where('user_id', $user->id)
                ->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
        }
    }
}

    // public function handle()
    // {
    //     $users = User::whereIn('id', $this->userIds)->get();

    //     foreach ($users as $user) {
    //         try {
    //             Mail::html($this->message, function ($message) use ($user) {
    //                 $message->to($user->email)
    //                         ->subject($this->subject);
    //             });

    //             // Get message ID from mail (depends on your mail driver)
    //             $messageId = $this->getMessageId();

    //             MassEmailLog::where('campaign_id', $this->campaignId)
    //                 ->where('user_id', $user->id)
    //                 ->update([
    //                     'status' => 'sent',
    //                     'message_id' => $messageId,
    //                     'sent_at' => now(),
    //                 ]);

    //         } catch (\Exception $e) {
    //             MassEmailLog::where('campaign_id', $this->campaignId)
    //                 ->where('user_id', $user->id)
    //                 ->update([
    //                     'status' => 'failed',
    //                     'error_message' => $e->getMessage(),
    //                 ]);
    //         }
    //     }
    // }

    private function getMessageId()
    {
        // Implement based on your mail driver
        // For ZeptoMail, check their documentation on how to retrieve message ID
        return uniqid('msg_', true);
    }
}
