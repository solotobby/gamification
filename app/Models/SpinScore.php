<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinScore extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'score', 'prize', 'is_paid'];
}