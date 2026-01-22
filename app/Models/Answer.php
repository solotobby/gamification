<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = "answers";

    protected $fillable = [
        'game_id',
        'question_id',
        'user_id',
        'selected_option',
        'correct_option',
        'is_correct'
    ];
}
