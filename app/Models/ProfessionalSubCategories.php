<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalSubCategories extends Model
{
    use HasFactory;
    protected $table = 'professionals_sub_categories';
    protected $fillable = ['professional_category_id', 'name', 'unique_id', 'status'];
}
