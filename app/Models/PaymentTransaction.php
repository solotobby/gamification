<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallet;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $table = 'payment_transactions';

    /**
     * Canonical list of transaction types that debit (deduct from) the user's wallet.
     */
    public const DEBIT_TYPES = [
        'ad_banner',
        'added_more_worker',
        'airtime_purchase',
        'campaign_posted',
        'cash_withdrawal',
        'databundle',
        'edit_campaign_payment',
        'job_listing',
        'job_point_purchase',
        'point_purchase',
        'safelock_created',
        'upgrade_payment',
        'upgrade_payment_naira_dollar',
        'upgrade_payment_usd',
        'upgrade_payment_wallet',
        'wallet_debit',
    ];

    /**
     * Canonical list of transaction types that credit (add to) the user's wallet.
     */
    public const CREDIT_TYPES = [
        'ad_banner_reversal',
        'auto_credit_dispute',
        'campaign_job_reversal',
        'campaign_job_reversal_credit',
        'campaign_payment',
        'campaign_payment_dispute_resolved',
        'campaign_payment_refund',
        'campaign_revenue',
        'campaign_revenue_add',
        'campaign_reversal',
        'career_hub_job_refund',
        'cash_transfer_top',
        'databundle_reversal',
        'direct_foreign_referer_bonus',
        'direct_referer_bonus',
        'direct_referer_bonus_naira_usd',
        'foreign_referer_bonus',
        'login_point_redemption',
        'market_place_commission',
        'market_place_payment',
        'membership_badge_bonus',
        'referer_bonus',
        'referral_withdrawal_commission',
        'safelock_redeemed',
        'spin_wheel_prize',
        'transfer_topup',
        'upgrade_bonus',
        'usd_referer_bonus',
        'wallet_topup',
        'wellahealth_affiliate_commission',
        'wellahealth_payment',
        'wellahealth_subscription',
        'withdrawal_commission',
        'withdrawal_reversal',
    ];

    /**
     * Determine whether a transaction is a debit, credit, or non-wallet transaction.
     *
     * @param string|null $type
     * @param string|null $description
     * @param string|null $fallbackTxType
     * @return string|null 'debit', 'credit', or null
     */
    public static function determineTxType(?string $type, ?string $description = null, ?string $fallbackTxType = null): ?string
    {
        $normalizedType = strtolower(trim((string) $type));

        if (in_array($normalizedType, self::DEBIT_TYPES, true)) {
            return 'debit';
        }

        if (in_array($normalizedType, self::CREDIT_TYPES, true)) {
            return 'credit';
        }

        // Bi-directional types (currency conversion, balance reconciliation)
        if (in_array($normalizedType, ['naira_dollar_exchange', 'currency_conversion', 'balance_reconciliation'], true)) {
            $desc = strtolower((string) $description);
            if (strpos($desc, 'debit') !== false) {
                return 'debit';
            }
            if (strpos($desc, 'credit') !== false) {
                return 'credit';
            }
            if ($fallbackTxType && strtolower(trim($fallbackTxType)) === 'debit') {
                return 'debit';
            }
            if ($fallbackTxType && strtolower(trim($fallbackTxType)) === 'credit') {
                return 'credit';
            }
        }

        if ($fallbackTxType) {
            $tx = strtolower(trim($fallbackTxType));
            if ($tx === 'debit' || $tx === 'credit') {
                return $tx;
            }
        }

        return null;
    }

    public static function isDebitType(?string $type, ?string $description = null, ?string $fallbackTxType = null): bool
    {
        return self::determineTxType($type, $description, $fallbackTxType) === 'debit';
    }

    public static function isCreditType(?string $type, ?string $description = null, ?string $fallbackTxType = null): bool
    {
        return self::determineTxType($type, $description, $fallbackTxType) === 'credit';
    }

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

        $this->balance = $walletBalance;
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
