<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'business_name',
        'business_phone',
        'description',
        'facebook_link',
        'x_link',
        'tiktok_link',
        'instagram_link',
        'pinterest_link',
        'business_link',
        'status',
        'visits',
        'is_live'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(BusinessProduct::class, 'business_id');
    }
}
