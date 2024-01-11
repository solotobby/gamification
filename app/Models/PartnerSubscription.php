<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'plan_code', 'subscription_code', 'amount','commission', 'affiliate_commission', 'affiliate_referral_id', 'payment_plan', 'numberOfSubscribers', 'nextPayment', 'product', 'partner', 'settlement_status'];
}
