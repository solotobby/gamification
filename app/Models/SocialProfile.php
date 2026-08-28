<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    protected $fillable = [
        'user_id',
        'platform',
        'url'
    ];
}
