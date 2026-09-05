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

    public function getCurrencyCodeAttribute(): string
    {
        $currency = $this->base_currency ?: 'NGN';
        return match (strtolower($currency)) {
            'naira', 'ngn' => 'NGN',
            'dollar', 'usd' => 'USD',
            default => strtoupper($currency),
        };
    }

    public function getActiveBalanceAttribute(): float
    {
        $currency = $this->currency_code;
        if (in_array($currency, ['NGN', 'NAIRA'])) {
            return (float) $this->balance;
        } elseif (in_array($currency, ['USD', 'DOLLAR'])) {
            return (float) $this->usd_balance;
        } else {
            return (float) $this->base_currency_balance;
        }
    }

    public function getFormattedBalanceAttribute(): string
    {
        return formatCurrency($this->active_balance, $this->currency_code);
    }
}
