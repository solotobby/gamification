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
        'campaign_amount', 'description', 'proof', 'total_amount', 'is_paid', 'approved', 'status'];
}
