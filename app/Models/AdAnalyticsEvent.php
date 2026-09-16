<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAnalyticsEvent extends Model
{
    use HasFactory;

    protected $table = 'ad_analytics_events';

    public $timestamps = false;

    protected $fillable = [
        'event_name',
        'user_id',
        'anonymous_session_id',
        'page_type',
        'page_url',
        'ad_provider',
        'ad_format',
        'placement_key',
        'device_type',
        'platform',
        'country',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
