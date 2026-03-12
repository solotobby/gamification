<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FraudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function removeDuplicateAccountOld()
    {

        $duplicates = DB::table('bank_information as b')
            ->join('users as u', 'b.user_id', '=', 'u.id')
            ->select(
                'b.account_number',
                'b.bank_name',
                'b.created_at',
                'b.bank_name',
                'b.name as account_name',
                'b.user_id',
                'u.name as user_name',
                'u.email',
                'u.phone',
                'u.id',
                DB::raw('COUNT(*) OVER (PARTITION BY b.account_number) as total')
            )
            ->whereIn('b.account_number', function ($query) {
                $query->select('account_number')
                    ->from('bank_information')
                    ->groupBy('account_number')
                    ->havingRaw('COUNT(DISTINCT user_id) > 1');
            })
            ->orderBy('b.account_number')
            ->get();


        return view('admin.fraud.duplicate', ['duplicates' => $duplicates]);
    }

    public function removeDuplicateAccount()
    {
        $duplicateNames = DB::table('bank_information')
            ->select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(DISTINCT user_id) > 1')
            ->pluck('name');

        // Get all accounts with those names, plus stats
        $rows = DB::table('bank_information as b')
            ->join('users as u', 'b.user_id', '=', 'u.id')
            ->leftJoin('wallets as w', 'w.user_id', '=', 'u.id')
            ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as job_count FROM campaign_workers GROUP BY user_id) as cw'), 'cw.user_id', '=', 'u.id')
            ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as referral_count FROM referral GROUP BY user_id) as r'), 'r.user_id', '=', 'u.id')
            ->leftJoin(DB::raw('(SELECT user_id, SUM(amount) as total_withdrawn FROM payment_transactions WHERE type = "withdrawal" AND status = "successful" GROUP BY user_id) as pt'), 'pt.user_id', '=', 'u.id')
            ->whereIn('b.name', $duplicateNames)
            ->select(
                'b.id as bank_id',
                'b.name as account_name',
                'b.account_number',
                'b.bank_name',
                'b.created_at as bank_created',
                'u.id as user_id',
                'u.name as user_name',
                'u.email',
                'u.phone',
                'u.is_blacklisted',
                'w.balance',
                'w.usd_balance',
                'w.base_currency',
                DB::raw('COALESCE(cw.job_count, 0) as job_count'),
                DB::raw('COALESCE(r.referral_count, 0) as referral_count'),
                DB::raw('COALESCE(pt.total_withdrawn, 0) as total_withdrawn')
            )
            ->orderBy('b.name')
            ->get();

        // Group by account_name
        $duplicates = $rows->groupBy('account_name');

        return view('admin.fraud.duplicate', compact('duplicates'));
    }

    // FraudController.php
    public function blacklist(User $user)
    {
        $user->update(['is_blacklisted' => true]);
        return back()->with('success', "{$user->name} has been blacklisted.");
    }

    public function unblacklist(User $user)
    {
        $user->update(['is_blacklisted' => false]);
        return back()->with('success', "{$user->name} has been unblocked.");
    }
}
