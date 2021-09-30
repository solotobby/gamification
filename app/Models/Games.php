<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    use HasFactory;

    protected $table = "games";

    protected $fillable = ['name', 'category_id', 'number_of_winners'];
}
