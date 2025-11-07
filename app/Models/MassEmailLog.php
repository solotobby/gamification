<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MassEmailLog extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'email',
        'status',
        'message_id',
        'sent_at',
        'delivered_at',
        'opened_at',
        'bounced_at',
        'error_message'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'bounced_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(MassEmailCampaign::class, 'campaign_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
