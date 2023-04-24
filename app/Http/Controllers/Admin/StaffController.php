<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaystackHelpers;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffPayment;
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

        $recipientCode = PaystackHelpers::recipientCode($bankInfor['data']['account_name'], $request->account_number, $bankCode['0']);
        $r_code = $recipientCode['data']['recipient_code'];
        $user = User::create([
            'name' => $bankInfor['data']['account_name'], 
            'phone' => $request->phone, 
            'email' => $request->email,
            'password' => bcrypt('staffing001'),
            'role' => 'staff'
        ]);

        $staff = Staff::create(['user_id' => $user->id, 'staff_id'=>'1', 'role' => $request->role,  'account_number' => $request->account_number, 'account_name' => $bankInfor['data']['account_name'], 'bank_name'=>$bankCode['1'], 'basic_salary' => $request->basic_salary, 'bonus' => $request->bonus, 'gross' => $request->basic_salary + $request->bonus, 'recipient_code' => $r_code]);
        $staff->staff_id = 'FBY0'.$staff->id; 
        $staff->save();
        return  back()->with('success', 'Staff Created Successfully');
    }

    public function salary(){
        $staff = User::where('role', 'staff')->get();
        $check = StaffPayment::where('date', now()->format('F, Y'))->first();
        return view('admin.staff.process_salary', ['staffs' => $staff, 'today' => now()->format('d'), 'check'=>$check]);
    }

    public function processSalary(Request $request){
      
        if(!empty($request->id)){
            $todays_date =  now()->format('d');
            if($todays_date >= '26'){
                $message = 'Freebyz Salary Payment for '.now()->format('F, Y');
                $staffList = Staff::whereIn('id', $request->id)->select(['user_id', 'basic_salary', 'recipient_code'])->get();
                $list = [];
               foreach($staffList as $key=>$value){
                $list = ['amount' => $value->basic_salary*100, 'reason' => $message, 'recipient' => $value->recipient_code];
                }
            $bulkTransfer =  PaystackHelpers::bulkFundTransfer($list);
            if($bulkTransfer['status'] == true){
                $staff_payment = StaffPayment::create(['user_id'=>auth()->user()->id, 'date' => now()->format('F, Y'), 'number_paid' => $staffList->count(), 'total_salary_paid' => $staffList->sum('basic_salary')]);
                foreach($staffList as $list){
                    \DB::table('staff_paid')->insert(['staff_payment_id' => $staff_payment->id, 'user_id' => $list->user->id, 'created_at' => now(), 'updated_at' => now()]);
                }
                return  back()->with('success', 'Transfers Successfully');
            }else{
                return back()->with('error', 'An error occoured while processing payment');
            }
           
            }else{
                return back()->with('error', 'You cannot process staffs record until after 25th of each month');
            }
        }else{
            return back()->with('error', 'Please select at least one staff');
        }
    }
}
