<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', '_link', 'professional_category_id', 'professional_sub_category_id', 'professional_domain_id', 'title', 'full_name', 'employment_status', 'main_production_domain', 
                            'work_experience', 'communication_mode', 'avg_rating', 'website_link', 'fb_link', 'x_link', 'linkedin_link', 'instagram_link', 'tiktok_link', 'geo'];

   

}
