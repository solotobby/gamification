<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalProficiencyLevel extends Model
{
    use HasFactory;

    protected $table = 'professionals_proficiency_levels';
    protected $fillable = ['name', 'status'];
}
