<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafeLock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount_locked',
        'interest_rate',
        'interest_accrued',
        'total_payment',
        'duration',
        'start_date',
        'maturity_date',
        'is_paid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
