<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionRate extends Model
{
    use HasFactory;

    protected $fillable = ['from', 'to', 'rate', 'amount', 'status', 'referral_commission', 'upgrade_fee', 'allow_upload', 'priotize'];
}
