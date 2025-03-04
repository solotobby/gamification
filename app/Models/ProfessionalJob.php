<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalJob extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'professional_category_id', 'professional_sub_category_id', 'title', 'slug', 'description', 'views'];

    public function category(){
        return $this->belongsTo(ProfessionalCategory::class, 'professional_category_id');
    }
}
