<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $table = 'payment_transactions';

    protected $fillable = ['reference', 'user_id', 'campaign_id', 'amount', 'currency', 'status', 'channel', 'type', 'description', 'tx_type', 'user_type'];
}
