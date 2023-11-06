<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'channel', 'customer_id', 
    'customer_intgration', 'bank_name', 'account_name', 'account_number', 'status'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bankInformation()
    {
        return $this->belongsTo(BankInformation::class,  'user_id');
    }

}
