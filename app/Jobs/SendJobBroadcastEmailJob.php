<?php

namespace App\Jobs;

use App\Mail\JobListingBroadcast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendJobBroadcastEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $subject;
    public $jobs;

    public function __construct($user, $subject, $jobs)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->jobs = $jobs;
    }

    public function handle()
    {
        Mail::to($this->user->email)->send(
            new JobListingBroadcast($this->user, $this->subject, $this->jobs)
        );
    }
}
