<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobBroadcast extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $subject;
    public $campaigns;

    public function __construct($user, $subject, $campaigns)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->campaigns = $campaigns;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.job_broadcast')->subject($this->subject)->with([
            'name' => $this->user->name,
            'campaigns' => $this->campaigns,
        ]);
    }
}
