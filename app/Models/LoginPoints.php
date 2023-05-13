<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginPoints extends Model
{
    use HasFactory;

    protected $table = "login_points";

    protected $fillable = ['user_id', 'date', 'point', 'is_redeemed'];
}
