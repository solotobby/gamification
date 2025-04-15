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

        // DB::table('bank_information')
        // ->select('account_number', 'bank_name')
        // ->groupBy('account_number')
        // ->havingRaw('COUNT(*) > 1')
        // ->pluck('account_number');


        // $duplicateAccountNumbers = DB::table('bank_information as a')
        //     ->join('users as u', 'a.user_id', '=', 'u.id')
        //     ->select('a.account_number', 'a.bank_name', 'u.name', DB::raw('COUNT(*) as total'))
        //     ->groupBy('a.account_number', 'u.name')
        //     ->havingRaw('COUNT(*) > 1')
        //     ->get();

        //     $duplicates = DB::table('bank_information as b')
        //     ->join('users as u', 'b.user_id', '=', 'u.id')
        //     ->select(
        //         'b.account_number',
        //         'b.bank_name',
        //         'u.name as user_name',
        //         'u.id',
        //         'u.email',
        //         'u.phone',
        //         DB::raw('COUNT(*) as total')
        //     )->groupBy('b.account_number', 'u.name')
        //     //     ->havingRaw('COUNT(*) > 1')
        //     // ->whereIn('b.account_number', function ($query) {
        //     //     $query->select('account_number')
        //     //         ->from('bank_information')
        //     //         ->groupBy('account_number')
        //     //         ->havingRaw('COUNT(*) > 1');
        //     // })
        //     // ->orderBy('b.account_number')
        //     ->get();

        $duplicateAccountNumbers = DB::table('bank_information')
    ->select('account_number')
    ->groupBy('account_number')
    ->havingRaw('COUNT(*) > 1')
    ->pluck('account_number');

// Now use that to get full user + bank details
$duplicates = DB::table('bank_information as b')
    ->join('users as u', 'b.user_id', '=', 'u.id')
    ->whereIn('b.account_number', $duplicateAccountNumbers)
    ->select(
        'b.account_number',
        'b.bank_name',
        'b.name as account_name',
        'u.name as user_name',
        'u.email',
        'u.phone',
        'u.id',
        DB::raw('COUNT(*) OVER (PARTITION BY b.account_number) as total')
    )
    ->orderBy('b.account_number')
    ->get();


        return view('admin.fraud.duplicate', ['duplicates' => $duplicates]);
    }
}
