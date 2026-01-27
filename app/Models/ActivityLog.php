<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = "activity_logs";

    protected $fillable = [
        'user_id',
        'activity_type',
        'description',
        'user_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
