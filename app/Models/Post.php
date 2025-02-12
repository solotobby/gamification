<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
    protected $fillable = ['user_id', 'username', 'category_id', 'category_name', 'title', 'slug', 'body'];

    public function pageViews(){
        return $this->hasMany(PostViews::class, 'post_id');
    }

    public function totalPageViews(){
        return $this->pageViews()->count();
    }
}
