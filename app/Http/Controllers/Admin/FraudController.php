<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FraudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function removeDuplicateAccount(){
        $duplicateAccountNumbers = DB::table('bank_information as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->select('a.account_number', 'u.name', DB::raw('COUNT(*) as total'))
            ->groupBy('a.account_number', 'u.name')
            ->havingRaw('COUNT(*) > 1')
            ->get();
        return view('admin.fraud.duplicate', ['duplicates' => $duplicateAccountNumbers]);
    }
}
