<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = "staff";

    protected $fillable = [
        'user_id',
        'staff_id',
        'role',
        'account_number',
        'account_name',
        'bank_name',
        'basic_salary',
        'bonus',
        'gross',
        'status',
        'recipient_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function salaryPaid()
    {
        return $this->belongsToMany(StaffPayment::class, 'staff_paid', 'user_id');
    }

    public function payslips()
    {
        return $this->hasMany(StaffPaymentLog::class, 'staff_id');
    }
}
