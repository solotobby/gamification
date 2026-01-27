<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets';

    protected $fillable = [
        'user_id',
        'balance',
        'bonus',
        'user_type',
        'base_currency',
        'usd_balance',
        'base_currency_balance',
        'base_currency_set'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
