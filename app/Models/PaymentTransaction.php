<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $table = 'payment_transactions';

    protected $fillable = [
        'reference',
        'user_id',
        'campaign_id',
        'amount',
        'balance',
        'currency',
        'status',
        'channel',
        'type',
        'description',
        'tx_type',
        'user_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userName()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['name']);
    }

    public function referee()
    {
        return $this->hasMany(User::class, 'referral', 'user_id');
    }
}
