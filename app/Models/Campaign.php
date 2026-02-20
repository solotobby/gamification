<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = "campaigns";

    protected $fillable = [
        'user_id',
        'post_title',
        'post_link',
        'campaign_type',
        'campaign_subcategory',
        'number_of_staff',
        'campaign_amount',
        'description',
        'proof',
        'total_amount',
        'is_paid',
        'approved',
        'status',
        'job_id',
        'extension_references',
        'is_completed',
        'currency',
        'pending_count',
        'impressions',
        'completed_count',
        'allow_upload',
        'approval_time',
        'flagged_at',
        'flagged_reason',
        'flagging_resolved',
        'expected_result_image',
    ];


    protected $casts = [
        'flagged_at' => 'datetime',
    ];

    public function getPercentageProgressAttribute()
    {
        if ($this->number_of_staff > 0) {
            return round(($this->completed / $this->number_of_staff) * 100, 2);
        }
        return 0;
    }

    public function userAttempts()
    {
        return $this->belongsToMany(User::class, 'campaign_workers', 'campaign_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaignType()
    {
        return $this->belongsTo(Category::class, 'campaign_type');
    }

    public function campaignCategory()
    {
        return $this->belongsTo(SubCategory::class, 'campaign_subcategory');
    }

    public function completed()
    {
        return $this->hasMany(CampaignWorker::class, 'campaign_id');
    }

    public function attempts()
    {
        return $this->hasMany(CampaignWorker::class, 'campaign_id');
    }

    public function approvedAttempts()
    {
        $this->attempts()->where('status', 'Approved')->get();
    }



    // public function completedAll()
    // {
    //     return $this->hasMany(CampaignWorker::class, 'campaign_id');
    // }

    public function myCompleted()
    {
        return $this->hasOne(CampaignWorker::class, 'campaign_id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'campaign_id');
    }

    // Check if campaign is active and accepting workers
    public function isActive(): bool
    {
        return  !$this->is_completed
            && $this->status === 'Live'
            && $this->completed_count < $this->number_of_staff;
    }

    // Get remaining spots
    public function getRemainingSpots(): int
    {
        return max(0, $this->number_of_staff - $this->completed_count);
    }

     // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_completed', false)
            ->where('status', 'Live');
    }

    public function scopePublic($query)
    {
        return $query->active()
            ->where(function($q) {
                $q->whereColumn('completed_count', '<', 'number_of_staff');
            });
    }
}
