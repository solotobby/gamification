<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdPlacement extends Model
{
    use HasFactory;

    protected $table = 'ad_placements';

    protected $fillable = [
        'placement_key',
        'page_type',
        'ad_format',
        'device',
        'platform',
        'position',
        'display_interval',
        'frequency_cap',
        'enabled',
        'priority',
        'code',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'display_interval' => 'integer',
        'frequency_cap' => 'integer',
        'priority' => 'integer',
    ];
}
