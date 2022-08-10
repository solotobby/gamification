<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignWorker extends Model
{
    use HasFactory;

    protected $table = "campaign_workers";
    
    protected $fillable = ['user_id', 'campaign_id', 'comment', 'amount', 'status', 'reason'];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
   

}
