<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Welcome extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    // public $content;
    public $subject;
    public $url;

    public function __construct($user, $subject, $url)
    {
        $this->user = $user;
        // $this->content = $content;
        $this->subject = $subject;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcome')->subject($this->subject)->with([
            'name' => $this->user->name,
            // 'content' => $this->content,
            'url' => isset($this->url) && $this->url ? $this->url : 'home'
        ]);
    }
}
