<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view(){
        //INCOME
        $transactions = PaymentTransaction::where('status', 'successful')->select(['amount', 'type', 'tx_type', 'user_type'])->get();//where('tx_type', 'Credit')->where('user_type', 'admin')->select(['amount', 'type'])->get();
        // return $debit = PaymentTransaction::where('tx_type', 'Debit')->select(['amount', 'type'])->get();
        $accounts = Accounts::orderBy('created_at', 'DESC')->get();
        return view('admin.account.index', ['transactions' => $transactions, 'accounts' => $accounts]);
    }

    public function store(Request $request){
       $account = Accounts::create($request->all());
       if($account){
        return back()->with('success', 'Transaction Created');
       }
    }
}
