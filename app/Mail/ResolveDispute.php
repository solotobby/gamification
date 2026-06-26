<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResolveDispute extends Mailable
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
    public $reason;

    public function __construct($approve, $subject, $status, $reason = null)
    {
        $this->approve = $approve;
        $this->subject = $subject;
        $this->status = $status;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->markdown('emails.campaigns.dispute_decision')->subject($this->subject)->with([
            'name' => $this->approve->user->name,
            'subject' => $this->subject,
            'amount' => $this->approve->amount,
            'campaign' => $this->approve->campaign->post_title,
            'status' => $this->status,
            'reason' => $this->reason
        ]);
    }
}
