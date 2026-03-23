<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    use HasFactory;
    protected $table = 'app_notifications';

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'type',
        'data',
        'is_read',
        'is_broadcast',
    ];

    protected $casts = [
        'data'         => 'array',
        'is_read'      => 'boolean',
        'is_broadcast' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
