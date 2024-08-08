<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastestFinger extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'phone', 'tiktok', 'network'];

    public function user(){
        return  $this->belongsTo(User::class, 'user_id');
    }

    public function pool(){
        return $this->belongsTo(FastestFingerPool::class, 'user_id');
    }
    
}
