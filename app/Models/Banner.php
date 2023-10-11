<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'banner_id', 'banner_url', 'external_link', 'age_bracket', 'ad_placement_point', 'adplacement_position', 'duration', 'country', 'amount', 'status', 'impression'];
}
