<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallet;

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

    protected static function boot()
    {
        parent::boot();

        // static::saved(function ($transaction) {
        //     if ($transaction->status === 'successful') {
        //         $transaction->updateTransactionBalance();
        //     }
        // });
    }


    public function updateTransactionBalance()
    {
        if (!$this->user_id) return;

        $wallet = Wallet::where('user_id', $this->user_id)->first();

        if (!$wallet) return;

        $walletBalance = match ($wallet->base_currency) {
            'NGN' => (float) $wallet->balance,
            'USD' => (float) $wallet->usd_balance,
            default => (float) $wallet->base_currency_balance,
        };

        $newBalance = $walletBalance + (float) $this->amount;

        $this->balance = $newBalance;
        $this->saveQuietly();
    }

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
