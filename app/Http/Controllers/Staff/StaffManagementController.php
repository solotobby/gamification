<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function payslip(){
        return view('staff.payslip');
    }
}
