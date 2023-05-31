<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    use HasFactory;

    protected $table = "locations";

    protected $fillable = ['user_id', 'activity', 'ip', 'countryName', 'countryCode', 'regionName', 'regionCode', 'cityName', 'zipCode', 'areaCode', 'timezone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
