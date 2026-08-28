<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CareerProfile extends Model
{
    protected $appends = ['price_range'];

    protected $fillable = [
        'user_id',
        'slug',
        'professional_title',
        'headline',
        'summary',
        'professional_level',
        'city',
        'state',
        'country',
        'profile_completeness',
        'talent_score',
        'photo_path',
        'cv_file_path',
        'is_public',
        'onboarding_completed',
        'price_min',
        'price_max',
        'price_currency',
        'views_count'
    ];

    protected $casts = [
        'price_min' => 'decimal:2',
        'price_max' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (CareerProfile $p) {
            if (empty($p->slug)) {
                $p->slug = static::generateUniqueSlug($p->resolveUserName());
            }

            if (empty($p->price_currency)) {
                $p->price_currency = optional(User::find($p->user_id))->wallet_currency ?? 'NGN';
            }
        });
    }

    public function getPriceRangeAttribute(): ?string
    {
        if (!$this->price_min)
            return null;
        $min = number_format((float) $this->price_min);
        $max = $this->price_max ? number_format((float) $this->price_max) : null;
        return $max ? "{$this->price_currency} {$min} - {$max}" : "{$this->price_currency} {$min}";
    }

    protected function resolveUserName(): string
    {
        return optional(User::find($this->user_id))->name ?? 'professional';
    }

    public static function generateUniqueSlug(string $userName, $ignoreId = null): string
    {
        $base = Str::slug($userName) ?: 'professional';
        $slug = $base;
        $i = 2;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return $query->where('slug', $value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(CareerAvailability::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'career_skills', 'user_id', 'skill_id', 'user_id', 'id');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'user_id', 'user_id');
    }

    public function educations()
    {
        return $this->hasMany(Education::class, 'user_id', 'user_id');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class, 'user_id', 'user_id');
    }

    public function socialProfiles()
    {
        return $this->hasMany(SocialProfile::class, 'user_id', 'user_id');
    }

    public function badges()
    {
        return $this->belongsToMany(VerificationBadge::class, 'user_verification_badges', 'user_id', 'verification_badge_id', 'user_id', 'id');
    }
}
