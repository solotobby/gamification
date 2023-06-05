<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveCampaign extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $approve;
    public $subject;
    public $status;

    public function __construct($approve, $subject, $status)
    {
        $this->approve = $approve;
        $this->subject = $subject;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->markdown('emails.campaigns.approve')->subject($this->subject)->with([
            'name' => $this->approve->user->name,
            'subject' => $this->subject,
            'amount' => $this->approve->amount,
            'campaign' => $this->approve->campaign->post_title,
            'status' => $this->status,
            'reason' => $this->approve->reason
        ]);
    }
}
