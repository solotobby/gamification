<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogView extends Model
{
    protected $fillable = ['blog_id', 'viewer_user_id', 'ip_address', 'referrer'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
