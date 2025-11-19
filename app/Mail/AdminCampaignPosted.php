<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminCampaignPosted extends Mailable
{
    use Queueable, SerializesModels;

    protected $campaign;

    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    public function build()
    {
        return $this->markdown('emails.campaigns.admin_posted')
            ->subject('New Campaign Posted')
            ->with([
                'campaign_name'   => $this->campaign->post_title,
                'amount'          => $this->campaign->campaign_amount,
                'number_of_staff' => $this->campaign->number_of_staff,
                'total_amount'    => $this->campaign->total_amount,
                'job_id'          => $this->campaign->job_id,
                'id'              => $this->campaign->id,
                'type'            => $this->campaign->campaignType->name,
                'category'        => $this->campaign->campaignCategory->name,
                'poster'          => $this->campaign->user->name,
                'poster_email'    => $this->campaign->user->email,
            ]);
    }
}
