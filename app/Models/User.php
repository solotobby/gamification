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
        'referral_code'
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
    ];

    public function hasRole($role = []): bool
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role == $role;
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function referees()
    {
        return $this->belongsToMany(User::class, 'referral', 'referee_id');
    }

    public function myWorks()
    {
        return $this->hasMany(CampaignWorker::class, 'campaign_id');
    }
}
