<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnershipSubscriber extends Model
{
    use HasFactory;

    protected $fillable = ['partnership_subscription_id', 'subscriptionCode', 'firstName',
     'lastName', 'email', 'phone', 'status', 'amount', 'product'];

}
