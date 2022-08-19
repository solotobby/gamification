<?php

namespace App\Mail;

use App\Models\CampaignWorker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmitJob extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $campaignWorker;
    public function __construct($campaignWorker)
    {
        $this->campaignWorker = $campaignWorker;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.jobs.submit')->subject('Job Submission')->with([
                'campaign_name' => $this->campaignWorker->campaign->post_title,
                'amount' => $this->campaignWorker->amount,
                'name' => $this->campaignWorker->user->name
        ]);
    }
}
