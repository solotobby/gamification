<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        '_user_id',
        'wallet_id',
        'account_name',
        'account_number',
        'bank_name',
        'bank_code',
        'currency',
        'provider',
        'status'
    ];

    public function accountInformation()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
