<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class StaffManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function payslip(){
        $user = Staff::where('user_id', auth()->user()->id)->first();
        //get all months paid
        $months_paid = $user->salaryPaid()->orderBy('created_at', 'DESC')->get();
        return view('staff.payslip', ['months_paid' => $months_paid]);
    }
}
