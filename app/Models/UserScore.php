<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserScore extends Model
{
    use HasFactory;

    protected $table = "user_scores";

    protected $fillable = [
        'game_id',
        'user_id',
        'score',
        'reward_type',
        'is_redeem'
    ];

    public function game()
    {
        return $this->belongsTo(Games::class, 'game_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
