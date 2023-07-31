<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $errorMessage;
    public $errorTrace;
    public function __construct($errorMessage, $errorTrace)
    {
        $this->errorMessage = $errorMessage;
        $this->errorTrace = $errorTrace;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Freebyz Error Notification')
                ->view('emails.error')->with([
                    'errorMessage' => $this->errorMessage,
                    'errorTrace' => $this->errorTrace
                ]);
        //return $this->view('view.name');
    }
}
