<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MassEmailCampaign extends Model
{
    protected $fillable = [
        'subject',
        'message',
        'audience_type',
        'days_filter',
        'country_filter',
        'total_recipients',
        'delivered',
        'opened',
        'bounced',
        'failed',
        'sent_by'
    ];

    public function logs()
    {
        return $this->hasMany(MassEmailLog::class, 'campaign_id');
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
