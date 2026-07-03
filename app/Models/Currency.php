<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'country',
        'is_active',
        'min_upgrade_amount',
        'upgrade_fee',
        'allow_upload',
        'priotize',
        'referral_commission',
        'base_rate',
        'min_withdrawal_amount',
        'withdrawal_percent',
        'freebyz_withdrawal_percent',
        'referral_withdrawal_percent',
        'banner_clicks_amount',
        'hire_worker_points_amount',
        'job_points_amount',
        'job_listing_amount'
    ];
}
