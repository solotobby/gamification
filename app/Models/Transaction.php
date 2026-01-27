<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'game_id',
        'reward_type',
        'reference',
        'transfer_code',
        'amount',
        'status',
        'recipient',
        'currency'
    ];
}
