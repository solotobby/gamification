<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = "feedback";

    protected $fillable = ['user_id', 'category', 'message', 'status', 'proof_url'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function reply(){
        return $this->hasMany(FeedbackReplies::class);
    }
}
