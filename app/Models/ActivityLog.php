<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = "activity_logs";

    protected $fillable = [
        'user_id',
        'activity_type',
        'description',
        'user_type',
        'client_type',
        'device',
        'platform',
        'browser',
        'device_model',
        'ip_address',
        'user_agent',
        'action',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by User ID.
     */
    public function scopeForUser(Builder $query, $userId): Builder
    {
        if (empty($userId)) {
            return $query;
        }
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by User Type (admin, regular, etc).
     */
    public function scopeUserType(Builder $query, ?string $userType): Builder
    {
        if (empty($userType) || $userType === 'all') {
            return $query;
        }
        return $query->where('user_type', $userType);
    }

    /**
     * Scope to filter by Activity Type.
     */
    public function scopeActivityType(Builder $query, ?string $activityType): Builder
    {
        if (empty($activityType) || $activityType === 'all') {
            return $query;
        }
        return $query->where('activity_type', $activityType);
    }

    /**
     * Scope to filter by Client Type (web, app, api).
     */
    public function scopeClientType(Builder $query, ?string $clientType): Builder
    {
        if (empty($clientType) || $clientType === 'all') {
            return $query;
        }
        if ($clientType === 'app') {
            return $query->whereIn('client_type', ['app', 'mobile_app', 'ios', 'android']);
        }
        return $query->where('client_type', $clientType);
    }

    /**
     * Scope to filter by Device (mobile, desktop, tablet).
     */
    public function scopeDevice(Builder $query, ?string $device): Builder
    {
        if (empty($device) || $device === 'all') {
            return $query;
        }
        return $query->where('device', $device);
    }

    /**
     * Scope to filter by Date Range.
     */
    public function scopeDateRange(Builder $query, $startDate = null, $endDate = null): Builder
    {
        if (!empty($startDate)) {
            $query->where('created_at', '>=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $query->where('created_at', '<=', $endDate . ' 23:59:59');
        }
        return $query;
    }

    /**
     * Scope to search by keyword across description, action, ip, or user name/email.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('description', 'LIKE', "%{$term}%")
              ->orWhere('action', 'LIKE', "%{$term}%")
              ->orWhere('ip_address', 'LIKE', "%{$term}%")
              ->orWhere('platform', 'LIKE', "%{$term}%")
              ->orWhere('browser', 'LIKE', "%{$term}%")
              ->orWhere('device_model', 'LIKE', "%{$term}%")
              ->orWhereHas('user', function (Builder $uq) use ($term) {
                  $uq->where('name', 'LIKE', "%{$term}%")
                     ->orWhere('email', 'LIKE', "%{$term}%")
                     ->orWhere('phone', 'LIKE', "%{$term}%");
              });
        });
    }

    /**
     * Determine if action came from Mobile App.
     */
    public function getIsAppAttribute(): bool
    {
        return in_array(strtolower($this->client_type ?? ''), ['app', 'mobile_app', 'ios', 'android'])
            || in_array(strtolower($this->platform ?? ''), ['android', 'ios']);
    }

    /**
     * Determine if action came from Web.
     */
    public function getIsWebAttribute(): bool
    {
        return strtolower($this->client_type ?? '') === 'web';
    }
}
