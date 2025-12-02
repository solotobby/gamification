<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MassEmailCampaign extends Model
{
    protected $fillable = [
        'channel',
        'subject',
        'message',
        'sms_message',
        'audience_type',
        'days_filter',
        'country_filter',
        'total_recipients',
        'delivered',
        'opened',
        'bounced',
        'failed',
        'sms_recipients',
        'sms_delivered',
        'sms_failed',
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

    public function emailLogs()
    {
        return $this->hasMany(MassEmailLog::class, 'campaign_id')->where('channel', 'email');
    }

    public function smsLogs()
    {
        return $this->hasMany(MassEmailLog::class, 'campaign_id')->where('channel', 'sms');
    }
}
