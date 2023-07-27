<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateCampaign extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $campaign;
    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.campaigns.post')->subject('Campaign Posted')->with([
            'campaign_name' => $this->campaign->post_title,
            'amount' => $this->campaign->campaign_amount,
            'number_of_staff' => $this->campaign->number_of_staff,
            'total_amount' => $this->campaign->total_amount,
            'job_id' => $this->campaign->job_id,
            'type' => $this->campaign->campaignType->name,
            'category' => $this->campaign->campaignCategory->name,
            'poster' => $this->campaign->user->name
        ]);
    }
}
