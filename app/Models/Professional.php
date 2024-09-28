<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'professional_category_id', 'description', 'max_price', 'min_price', 'profeciency_level', 'payment_mode', 'availability'];

    public function portfolios(){
        return $this->hasMany(Portfolio::class, 'skill_id');
    }

}
