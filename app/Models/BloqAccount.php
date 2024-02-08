<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqAccount extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'customer_id','customer_name', 'account_id', 'balance', 'account_number', 'bank_name', 'currency', 'provider'];

    public function bloqAccount(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
