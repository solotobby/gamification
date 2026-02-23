<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'twitter_id',
        'avatar',
        'role',
        'referral_code',
        'source',
        'phone',
        'country',
        'age_range',
        'gender',
        'base_currency',
        'is_verified',
        'is_blacklisted',
        'is_business',
        'verified_at',
        'is_wallet_transfered',
        'email_verified_at',
        'email_verification_attempted_at',
        'username',
        'email_preference'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_business' => 'boolean',
        'is_blacklisted' => 'boolean',
        'email_preference' => 'boolean',
        'is_wallet_transfered' => 'boolean',

    ];

    public function hasRole($role = []): bool
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role == $role;
    }
    public function getWalletCurrencyAttribute()
    {
        if ($this->hasRole('super_admin')) {
            return 'NGN';
        }

        return $this->wallet->base_currency ?? 'NGN';
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function referees()
    {
        return $this->belongsToMany(User::class, 'referral', 'referee_id');
    }
    public function usd_referees()
    {
        return $this->belongsToMany(User::class, 'usdverifieds', 'referral_id');
    }

    public function attemptedCampaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_workers', 'user_id', 'campaign_id');
    }

    public function myWorks()
    {
        return $this->hasMany(CampaignWorker::class, 'campaign_id');
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function myJobs()
    {
        return $this->hasMany(CampaignWorker::class, 'user_id');
    }


    public function myCampaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function accountDetails()
    {
        return $this->hasOne(BankInformation::class,  'user_id');
    }

    public function myFeedBackList()
    {
        return $this->hasMany(Feedback::class,  'user_id');
    }

    public function myFeedBackReplies()
    {
        return $this->hasMany(FeedbackReplies::class,  'user_id');
    }

    public function interests()
    {
        return $this->belongsToMany(Preference::class, 'user_interest', 'user_id');
    }

    public function accountInfo()
    {
        return $this->hasOne(AccountInformation::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function USD_verified()
    {
        return $this->hasOne(Usdverified::class, 'user_id');
    }

    public function USD_verifiedList()
    {
        return $this->hasOne(Usdverified::class, 'user_id');
    }
    public function virtualAccount()
    {
        return $this->hasOne(VirtualAccount::class, 'user_id');
    }

    public function myAttemptedJobs()
    {
        return $this->hasMany(CampaignWorker::class, 'user_id');
    }

    public function myRating()
    {
        return $this->hasMany(Rating::class, 'campaign_id');
    }

    public function skill()
    {
        return $this->hasOne(SkillAsset::class, 'user_id');
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getIncomeAttribute()
    {
        return $this->attributes['income'] ?? 0;
    }
}
