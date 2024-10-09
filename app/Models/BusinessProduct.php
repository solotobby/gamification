<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProduct extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'name', 'description', 'price', 'img', 'visits', 'is_live'];
}
