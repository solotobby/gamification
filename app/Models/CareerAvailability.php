<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerAvailability extends Model
{
    protected $fillable = [
        'career_profile_id',
        'type'
    ];
}
