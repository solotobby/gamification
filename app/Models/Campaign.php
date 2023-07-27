<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = "campaigns";

    protected $fillable = ['user_id', 'post_title', 'post_link', 
        'campaign_type', 'campaign_subcategory', 'number_of_staff', 
        'campaign_amount', 'description', 'proof', 'total_amount', 'is_paid', 'approved', 'status', 'job_id', 'extension_references', 'is_completed', 'currency'];

    
        public function user(){
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

        // public function completedAll()
        // {
        //     return $this->hasMany(CampaignWorker::class, 'campaign_id');
        // }

        public function myCompleted()
        {
            return $this->hasOne(CampaignWorker::class, 'campaign_id');
        }
}
