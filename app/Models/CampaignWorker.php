<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CampaignWorker extends Model
{
    use HasFactory;

    protected $table = "campaign_workers";

    protected $fillable = [
        'user_id',
        'campaign_id',
        'comment',
        'amount',
        'status',
        'reason',
        'proof_url',
        'currency',
        'denied_at',
        'slot_released',
        'is_dispute'
    ];

    protected $casts = [
        'denied_at' => 'datetime',
        'slot_released' => 'boolean',
        'is_dispute' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function dispute()
    {
        return $this->hasOne(DisputedJobs::class, 'campaign_worker_id');
    }

    /**
     * Check if the 12-hour dispute window has expired
     */
    public function isDisputeWindowExpired()
    {
        if (!$this->denied_at) {
            return false;
        }

        return Carbon::now()->diffInHours($this->denied_at) >= 12;
    }

    /**
     * Check if worker can still dispute
     */
    public function canDispute()
    {
        return $this->status === 'Denied'
            && !$this->slot_released
            && !$this->is_dispute
            && !$this->isDisputeWindowExpired();
    }

    /**
     * Get remaining time to dispute in hours
     */
    public function remainingDisputeTime()
    {
        if (!$this->denied_at || $this->isDisputeWindowExpired()) {
            return 0;
        }

        $hoursPassed = Carbon::now()->diffInHours($this->denied_at);
        return 12 - $hoursPassed;
    }
}
