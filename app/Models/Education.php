<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';
    protected $fillable = [
        'user_id',
        'university_id',
        'institution',
        'qualification',
        'course',
        'start_year',
        'end_year',
        'is_current',
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }
}
