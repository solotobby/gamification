<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'avarta', 'is_welcome', 'phone_verified', 'email_verified'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
