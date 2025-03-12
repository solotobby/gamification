<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillAsset extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'skill_id', 'profeciency_level', 'year_experience', 'location', 'availability', 'max_price', 'min_price'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function skill(){
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function profeciencyLevel(){
        return $this->belongsTo(ProfessionalProficiencyLevel::class, 'profeciency_level');
    }

}
