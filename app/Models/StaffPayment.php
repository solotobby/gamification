<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPayment extends Model
{
    use HasFactory;

    protected $table = "staff_payments";

    protected $fillable = ['user_id','date', 'number_paid', 'total_salary_paid'];
}
