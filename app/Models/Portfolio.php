<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'skill_id', 'title', 'description'];

    public function skills(){
        return $this->belongsToMany(Tool::class, 'portfolio_tools', 'portfolio_id');
    }
}
