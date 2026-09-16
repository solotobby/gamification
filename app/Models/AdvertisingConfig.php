<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingConfig extends Model
{
    use HasFactory;

    protected $table = 'advertising_configs';

    protected $fillable = [
        'name',
        'status',
        'web_enabled',
        'mobile_app_enabled',
        'native_enabled',
        'banner_enabled',
        'social_bar_enabled',
        'interstitial_enabled',
        'popunder_enabled',
        'smartlink_enabled',
        'max_ads_per_session',
    ];

    protected $casts = [
        'web_enabled' => 'boolean',
        'mobile_app_enabled' => 'boolean',
        'native_enabled' => 'boolean',
        'banner_enabled' => 'boolean',
        'social_bar_enabled' => 'boolean',
        'interstitial_enabled' => 'boolean',
        'popunder_enabled' => 'boolean',
        'smartlink_enabled' => 'boolean',
        'max_ads_per_session' => 'integer',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(
            ['name' => 'default'],
            [
                'status' => 'ACTIVE',
                'web_enabled' => true,
                'mobile_app_enabled' => true,
                'native_enabled' => true,
                'banner_enabled' => true,
                'social_bar_enabled' => false,
                'interstitial_enabled' => false,
                'popunder_enabled' => false,
                'smartlink_enabled' => false,
                'max_ads_per_session' => 10,
            ]
        );
    }
}
