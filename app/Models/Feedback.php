<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = "feedback";

    protected $fillable = ['user_id', 'category', 'message', 'status', 'proof_url', 'respondent_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function replies(){
        return $this->hasMany(FeedbackReplies::class, 'feedback_id');
    }
}
