<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobListingBroadcast extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $emailSubject;
    public $jobs;

    public function __construct($user, $subject, $jobs)
    {
        $this->user = $user;
        $this->emailSubject = $subject;
        $this->jobs = $jobs;
    }

    public function build()
    {
        return $this->subject($this->emailSubject)
            ->view('emails.career-hub-broadcast')
            ->with([
                'userName' => $this->user->name,
                'jobs' => $this->jobs,
            ]);
    }
}
