<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaystackHelpers;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $staff = User::where('role', 'staff')->get();
        return view('admin.staff.index', ['staffs' => $staff]);
    }

    public function create(){
        $bankList = PaystackHelpers::bankList();
        return view('admin.staff.create', ['banklist' => $bankList]);
    }

    public function store(Request $request){
        $request->validate([
            'email' => 'required|unique:users',
            'phone' => 'required|numeric',
        ]);
        $bankCode = explode(":", $request->bank_code);
        $bankInfor = PaystackHelpers::resolveBankName($request->account_number, $bankName = $bankCode['0']);
        $bankInfor['data']['account_name'];

        $user = User::create([
            'name' => $bankInfor['data']['account_name'], 
            'phone' => $request->phone, 
            'email' => $request->email,
            'password' => bcrypt('staffing001'),
            'role' => 'staff'
        ]);

        $staff = Staff::create(['user_id' => $user->id, 'staff_id'=>'1', 'role' => $request->role,  'account_number' => $request->account_number, 'account_name' => $bankInfor['data']['account_name'], 'bank_name'=>$bankCode['1'], 'basic_salary' => $request->basic_salary, 'bonus' => $request->bonus, 'gross' => $request->basic_salary + $request->bonus]);
        $staff->staff_id = 'FBY0'.$staff->id; 
        $staff->save();
        return  back()->with('success', 'Staff Created Successfully');
    }

    public function salary(){
        $staff = User::where('role', 'staff')->get();
        return view('admin.staff.process_salary', ['staffs' => $staff]);
    }
}
