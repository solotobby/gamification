<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'country', 'min_upgrade_amount', 'upgrade_fee', 'allow_upload', 'priotize', 'referral_commission'];
}
