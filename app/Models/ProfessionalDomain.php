<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalDomain extends Model
{
    use HasFactory;

    protected $fillable = ['professional_sub_categories_id', 'name'];
}
