<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInformation extends Model
{
    use HasFactory;

    protected $table = "bank_information";

    protected $fillable = ['user_id', 'name', 'bank_name', 'account_number', 'bank_code', 'status', 'recipient_code', 'currency'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
