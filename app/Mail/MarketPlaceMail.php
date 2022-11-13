<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketPlaceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    public $content;
    public $subject;

    public function __construct($user, $content, $subject)
    {
        $this->user = $user;
        $this->content = $content;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.marketplace')->subject($this->subject)->with([
            'name' => $this->user->name,
            'url' => $this->user->url,
            'content' => $this->content
        ]);
    }
}
