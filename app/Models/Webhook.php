<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = [
        'provider',
        'event',
        'payload',
        'status',
        'message',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
