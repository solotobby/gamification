<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withrawal extends Model
{
    use HasFactory;

    protected $table = "withrawals";

    protected $fillable = [
        'user_id',
        'amount',
        'next_payment_date',
        'status',
        'base_currency',
        'channel',
        'paypal_email',
        'is_usd',
        'content'
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

    public function accountDetails()
    {
        return $this->belongsTo(BankInformation::class);
    }
}
