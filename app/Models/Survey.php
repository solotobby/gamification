<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable =['user_id', 'survey_code', 'category_id', 'sub_category_id', 'title', 'description', 'banner', 'amount', 'total_amount', 'currency', 'number_of_response', 'number_of_response_submitted', 'status'];

}
