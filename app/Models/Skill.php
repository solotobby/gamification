<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'skill_category', 'description', 'max_price', 'min_price', 'profeciency_level', 'payment_mode', 'availability'];


    public function portfolios(){
        return $this->hasMany(Portfolio::class, 'skill_id');
    }

}
