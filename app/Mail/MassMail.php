<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SplSubject;

class MassMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $mailMessage;
    public $subject;

    public function __construct($name, $mailMessage, $subject)
    {
        $this->name = $name;
        $this->mailMessage = $mailMessage;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->markdown('emails.mass_mail.content')
            ->subject($this->subject)
            ->with([
                'name' => $this->name,
                'message' => $this->mailMessage
            ]);
    }
}
