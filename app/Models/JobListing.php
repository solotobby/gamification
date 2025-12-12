<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, SoftDeletes, Factories\HasFactory};
use Illuminate\Support\Str;

class JobListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'type',
        'tier',
        'location',
        'remote_allowed',
        'salary_min',
        'salary_max',
        'currency',
        'company_name',
        'company_logo',
        'company_description',
        'company_website',
        'expires_at',
        'is_active',
        'posted_by'
    ];

    protected $casts = [
        'remote_allowed' => 'boolean',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . Str::random(6);
            }
        });
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['type'] ?? null, fn($q, $type) =>
            $q->where('type', $type))
            ->when($filters['tier'] ?? null, fn($q, $tier) =>
            $q->where('tier', $tier))
            ->when($filters['location'] ?? null, fn($q, $location) =>
            $q->where('location', 'like', "%{$location}%"))
            ->when($filters['salary_min'] ?? null, fn($q, $min) =>
            $q->where('salary_max', '>=', $min))
            ->when($filters['salary_max'] ?? null, fn($q, $max) =>
            $q->where('salary_min', '<=', $max))
            ->when($filters['remote'] ?? false, fn($q) =>
            $q->where('remote_allowed', true))
            ->when($filters['search'] ?? null, fn($q, $search) =>
            $q->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            }));
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function canView(?User $user): bool
    {
        if ($this->tier === 'free') {
            return true;
        }

        return $user && $user->hasVerifiedEmail();
    }

    public function hasApplied(?User $user): bool
    {
        if (!$user) return false;

        return $this->applications()->where('user_id', $user->id)->exists();
    }

    public function getSalaryRangeAttribute(): ?string
    {
        if (!$this->salary_min) return null;

        $min = number_format($this->salary_min);
        $max = $this->salary_max ? number_format($this->salary_max) : null;

        return $max ? "{$this->currency} {$min} - {$max}" : "{$this->currency} {$min}";
    }
}
