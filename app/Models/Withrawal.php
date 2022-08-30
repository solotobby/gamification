<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withrawal extends Model
{
    use HasFactory;

    protected $table = "withrawals"; 
    protected $fillable = ['user_id', 'amount', 'next_payment_date', 'status'];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

}
