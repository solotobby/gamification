<?php

namespace App\Jobs;

use App\Mail\JobBroadcast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBroadcastEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $subject;
    public $filteredArray;

    public function __construct($user, $subject, $filteredArray)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->filteredArray = $filteredArray;
    }

    public function handle()
    {
        Mail::to($this->user->email)->send(new JobBroadcast($this->user, $this->subject, $this->filteredArray));
    }
}
