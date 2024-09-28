<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalCategory extends Model
{
    use HasFactory;

    protected $table = 'professionals_categories';
    protected $fillable = ['name', 'status'];
}
