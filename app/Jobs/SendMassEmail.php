<?php

namespace App\Jobs;

use App\Mail\MassMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMassEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userIds;
    public $mailMessage;
    public $subject;

    public function __construct(array $userIds, $mailMessage, $subject)
    {
        $this->userIds = $userIds;
        $this->mailMessage = $mailMessage;
        $this->subject = $subject;
    }

    public function handle()
    {
        $users = User::whereIn('id', $this->userIds)->get(['id', 'email', 'name']);

        Log::info($users);
        foreach ($users as $user) {
            // Mail::to($user->email)->queue(
            //     new MassMail($user->name, $this->mailMessage, $this->subject)
            // );
        }
    }
}
