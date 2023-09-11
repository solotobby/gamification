<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $table = 'referral';

    protected $fillable = ['user_id', 'referee_id', 'is_paid'];

    public function referrer(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
