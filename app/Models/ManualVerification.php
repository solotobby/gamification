<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'payment_method',
        'reference',
        'proof_image',
        'amount',
        'currency',
        'status',
        'admin_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
