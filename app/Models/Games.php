<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    use HasFactory;

    protected $table = "games";

    protected $fillable = [
        'name',
        'type',
        'slug',
        'time_allowed',
        'number_of_winners',
        'number_of_questions',
        'status'
    ];

    public function games()
    {
        return $this->hasMany(UserScore::class, 'game_id');
    }
}
