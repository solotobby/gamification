<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnershipBeneficiary extends Model
{
    use HasFactory;

    protected $fillable = ['partnership_subscriptions_id', 'firstName', 'lastName', 'email', 'phone', 'dateOfBirth', 'gender'];
    
}
