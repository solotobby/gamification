<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = "questions";

    protected $fillable = ['content', 'asset_url', 'option_A','option_B', 'option_C', 'option_D', 'correct_answer'];


}
