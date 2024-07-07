<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyInterest extends Model
{
    use HasFactory;

    protected $fillable = ['survey_id', 'interest_id', 'unit'];
}
