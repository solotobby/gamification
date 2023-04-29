<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPaymentLog extends Model
{
    use HasFactory;

    protected $table = "staff_payment_log";

    protected $fillable = ['staff_id','date', 'amount', 'payment_type'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
