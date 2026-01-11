<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisputedJobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_worker_id',
        'campaign_id',
        'user_id',
        'reason',
        'is_resolved'
    ];
}
