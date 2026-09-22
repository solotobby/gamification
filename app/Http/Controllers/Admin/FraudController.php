<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class FraudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function removeDuplicateAccount(Request $request)
    {
        $type = $request->input('type', 'bank_name'); // 'bank_name', 'account_number', 'phone', 'name', 'ip_address'
        $search = $request->input('search');
        $statusFilter = $request->input('status', 'all'); // 'all', 'active_only', 'blacklisted_only'
        $perPage = 50;

        $ignoreRoles = ['admin', 'super_admin', 'staff'];

        $liveBalanceExpr = "CASE 
            WHEN LOWER(COALESCE(w.base_currency, 'NGN')) IN ('naira', 'ngn') THEN w.balance
            WHEN LOWER(COALESCE(w.base_currency, 'NGN')) IN ('dollar', 'usd') THEN w.usd_balance
            ELSE w.base_currency_balance
        END";

        $currencyExpr = "CASE 
            WHEN LOWER(COALESCE(w.base_currency, 'NGN')) IN ('naira', 'ngn') THEN 'NGN'
            WHEN LOWER(COALESCE(w.base_currency, 'NGN')) IN ('dollar', 'usd') THEN 'USD'
            ELSE UPPER(COALESCE(w.base_currency, 'NGN'))
        END";

        $lastIpSubquery = "(SELECT ip_address FROM activity_logs WHERE user_id = u.id AND ip_address IS NOT NULL AND ip_address NOT IN ('127.0.0.1', '::1', 'localhost', 'Unknown IP') ORDER BY id DESC LIMIT 1) as last_ip";

        // Count totals for each vector for tab badges
        $vectorCounts = [
            'bank_name' => DB::table('bank_information as b')
                ->join('users as u', 'b.user_id', '=', 'u.id')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('b.name')->where('b.name', '!=', '')
                ->groupBy('b.name')
                ->havingRaw('COUNT(DISTINCT b.user_id) > 1')
                ->select('b.name')
                ->get()->count(),

            'account_number' => DB::table('bank_information as b')
                ->join('users as u', 'b.user_id', '=', 'u.id')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('b.account_number')->where('b.account_number', '!=', '')
                ->groupBy('b.account_number')
                ->havingRaw('COUNT(DISTINCT b.user_id) > 1')
                ->select('b.account_number')
                ->get()->count(),

            'phone' => DB::table('users as u')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('u.phone')->where('u.phone', '!=', '')
                ->groupBy('u.phone')
                ->havingRaw('COUNT(u.id) > 1')
                ->select('u.phone')
                ->get()->count(),

            'name' => DB::table('users as u')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('u.name')->where('u.name', '!=', '')
                ->groupBy('u.name')
                ->havingRaw('COUNT(u.id) > 1')
                ->select('u.name')
                ->get()->count(),

            'ip_address' => DB::table('activity_logs as a')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('a.ip_address')
                ->whereNotIn('a.ip_address', ['127.0.0.1', '::1', 'localhost', 'Unknown IP'])
                ->groupBy('a.ip_address')
                ->havingRaw('COUNT(DISTINCT a.user_id) > 1')
                ->select('a.ip_address')
                ->get()->count(),
        ];

        // Execute query based on selected vector
        if ($type === 'account_number') {
            $keysQuery = DB::table('bank_information as b')
                ->join('users as u', 'b.user_id', '=', 'u.id')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('b.account_number')->where('b.account_number', '!=', '')
                ->select('b.account_number as match_key')
                ->groupBy('b.account_number')
                ->havingRaw('COUNT(DISTINCT b.user_id) > 1');

            if (!empty($search)) {
                $keysQuery->where(function ($q) use ($search) {
                    $q->where('b.account_number', 'LIKE', "%{$search}%")
                      ->orWhere('b.name', 'LIKE', "%{$search}%")
                      ->orWhere('u.name', 'LIKE', "%{$search}%")
                      ->orWhere('u.email', 'LIKE', "%{$search}%");
                });
            }
            $matchKeys = $keysQuery->pluck('match_key');

            $rowsQuery = DB::table('bank_information as b')
                ->join('users as u', 'b.user_id', '=', 'u.id')
                ->leftJoin('wallets as w', 'w.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as job_count FROM campaign_workers GROUP BY user_id) as cw'), 'cw.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as referral_count FROM referral GROUP BY user_id) as r'), 'r.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, SUM(amount) as total_withdrawn FROM payment_transactions WHERE type = "withdrawal" AND status = "successful" GROUP BY user_id) as pt'), 'pt.user_id', '=', 'u.id')
                ->whereIn('b.account_number', $matchKeys)
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->select(
                    'b.account_number as match_value',
                    DB::raw("'Bank Account Number' as match_type_label"),
                    'b.name as bank_account_name',
                    'b.account_number',
                    'b.bank_name',
                    'b.created_at as bank_created',
                    'u.id as user_id',
                    'u.name as user_name',
                    'u.email',
                    'u.phone',
                    'u.country',
                    'u.created_at as user_created',
                    'u.auth_device',
                    'u.source',
                    'u.is_blacklisted',
                    'u.is_verified',
                    DB::raw("({$currencyExpr}) as currency"),
                    DB::raw("COALESCE(({$liveBalanceExpr}), 0) as balance"),
                    DB::raw('COALESCE(cw.job_count, 0) as job_count'),
                    DB::raw('COALESCE(r.referral_count, 0) as referral_count'),
                    DB::raw('COALESCE(pt.total_withdrawn, 0) as total_withdrawn'),
                    DB::raw($lastIpSubquery)
                )
                ->orderBy('b.account_number');

        } elseif ($type === 'phone') {
            $keysQuery = DB::table('users as u')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('u.phone')->where('u.phone', '!=', '')
                ->select('u.phone as match_key')
                ->groupBy('u.phone')
                ->havingRaw('COUNT(u.id) > 1');

            if (!empty($search)) {
                $keysQuery->where(function ($q) use ($search) {
                    $q->where('u.phone', 'LIKE', "%{$search}%")
                      ->orWhere('u.name', 'LIKE', "%{$search}%")
                      ->orWhere('u.email', 'LIKE', "%{$search}%");
                });
            }
            $matchKeys = $keysQuery->pluck('match_key');

            $rowsQuery = DB::table('users as u')
                ->leftJoin('bank_information as b', 'b.user_id', '=', 'u.id')
                ->leftJoin('wallets as w', 'w.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as job_count FROM campaign_workers GROUP BY user_id) as cw'), 'cw.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as referral_count FROM referral GROUP BY user_id) as r'), 'r.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, SUM(amount) as total_withdrawn FROM payment_transactions WHERE type = "withdrawal" AND status = "successful" GROUP BY user_id) as pt'), 'pt.user_id', '=', 'u.id')
                ->whereIn('u.phone', $matchKeys)
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->select(
                    'u.phone as match_value',
                    DB::raw("'Phone Number' as match_type_label"),
                    'b.name as bank_account_name',
                    'b.account_number',
                    'b.bank_name',
                    'b.created_at as bank_created',
                    'u.id as user_id',
                    'u.name as user_name',
                    'u.email',
                    'u.phone',
                    'u.country',
                    'u.created_at as user_created',
                    'u.auth_device',
                    'u.source',
                    'u.is_blacklisted',
                    'u.is_verified',
                    DB::raw("({$currencyExpr}) as currency"),
                    DB::raw("COALESCE(({$liveBalanceExpr}), 0) as balance"),
                    DB::raw('COALESCE(cw.job_count, 0) as job_count'),
                    DB::raw('COALESCE(r.referral_count, 0) as referral_count'),
                    DB::raw('COALESCE(pt.total_withdrawn, 0) as total_withdrawn'),
                    DB::raw($lastIpSubquery)
                )
                ->orderBy('u.phone');

        } elseif ($type === 'ip_address') {
            $keysQuery = DB::table('activity_logs as a')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('a.ip_address')
                ->whereNotIn('a.ip_address', ['127.0.0.1', '::1', 'localhost', 'Unknown IP'])
                ->select('a.ip_address as match_key')
                ->groupBy('a.ip_address')
                ->havingRaw('COUNT(DISTINCT a.user_id) > 1');

            if (!empty($search)) {
                $keysQuery->where(function ($q) use ($search) {
                    $q->where('a.ip_address', 'LIKE', "%{$search}%")
                      ->orWhere('u.name', 'LIKE', "%{$search}%")
                      ->orWhere('u.email', 'LIKE', "%{$search}%");
                });
            }
            $matchKeys = $keysQuery->pluck('match_key');

            $rowsQuery = DB::table('activity_logs as a')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->leftJoin('bank_information as b', 'b.user_id', '=', 'u.id')
                ->leftJoin('wallets as w', 'w.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as job_count FROM campaign_workers GROUP BY user_id) as cw'), 'cw.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as referral_count FROM referral GROUP BY user_id) as r'), 'r.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, SUM(amount) as total_withdrawn FROM payment_transactions WHERE type = "withdrawal" AND status = "successful" GROUP BY user_id) as pt'), 'pt.user_id', '=', 'u.id')
                ->whereIn('a.ip_address', $matchKeys)
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->select(
                    'a.ip_address as match_value',
                    DB::raw("'IP Address' as match_type_label"),
                    'b.name as bank_account_name',
                    'b.account_number',
                    'b.bank_name',
                    'b.created_at as bank_created',
                    'u.id as user_id',
                    'u.name as user_name',
                    'u.email',
                    'u.phone',
                    'u.country',
                    'u.created_at as user_created',
                    'u.auth_device',
                    'u.source',
                    'u.is_blacklisted',
                    'u.is_verified',
                    DB::raw("({$currencyExpr}) as currency"),
                    DB::raw("COALESCE(({$liveBalanceExpr}), 0) as balance"),
                    DB::raw('COALESCE(cw.job_count, 0) as job_count'),
                    DB::raw('COALESCE(r.referral_count, 0) as referral_count'),
                    DB::raw('COALESCE(pt.total_withdrawn, 0) as total_withdrawn'),
                    'a.ip_address as last_ip'
                )
                ->distinct()
                ->orderBy('a.ip_address');

        } elseif ($type === 'name') {
            $keysQuery = DB::table('users as u')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('u.name')->where('u.name', '!=', '')
                ->select('u.name as match_key')
                ->groupBy('u.name')
                ->havingRaw('COUNT(u.id) > 1');

            if (!empty($search)) {
                $keysQuery->where(function ($q) use ($search) {
                    $q->where('u.name', 'LIKE', "%{$search}%")
                      ->orWhere('u.email', 'LIKE', "%{$search}%")
                      ->orWhere('u.phone', 'LIKE', "%{$search}%");
                });
            }
            $matchKeys = $keysQuery->pluck('match_key');

            $rowsQuery = DB::table('users as u')
                ->leftJoin('bank_information as b', 'b.user_id', '=', 'u.id')
                ->leftJoin('wallets as w', 'w.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as job_count FROM campaign_workers GROUP BY user_id) as cw'), 'cw.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as referral_count FROM referral GROUP BY user_id) as r'), 'r.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, SUM(amount) as total_withdrawn FROM payment_transactions WHERE type = "withdrawal" AND status = "successful" GROUP BY user_id) as pt'), 'pt.user_id', '=', 'u.id')
                ->whereIn('u.name', $matchKeys)
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->select(
                    'u.name as match_value',
                    DB::raw("'User Full Name' as match_type_label"),
                    'b.name as bank_account_name',
                    'b.account_number',
                    'b.bank_name',
                    'b.created_at as bank_created',
                    'u.id as user_id',
                    'u.name as user_name',
                    'u.email',
                    'u.phone',
                    'u.country',
                    'u.created_at as user_created',
                    'u.auth_device',
                    'u.source',
                    'u.is_blacklisted',
                    'u.is_verified',
                    DB::raw("({$currencyExpr}) as currency"),
                    DB::raw("COALESCE(({$liveBalanceExpr}), 0) as balance"),
                    DB::raw('COALESCE(cw.job_count, 0) as job_count'),
                    DB::raw('COALESCE(r.referral_count, 0) as referral_count'),
                    DB::raw('COALESCE(pt.total_withdrawn, 0) as total_withdrawn'),
                    DB::raw($lastIpSubquery)
                )
                ->orderBy('u.name');

        } else { // default: 'bank_name'
            $type = 'bank_name';
            $keysQuery = DB::table('bank_information as b')
                ->join('users as u', 'b.user_id', '=', 'u.id')
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->whereNotNull('b.name')->where('b.name', '!=', '')
                ->select('b.name as match_key')
                ->groupBy('b.name')
                ->havingRaw('COUNT(DISTINCT b.user_id) > 1');

            if (!empty($search)) {
                $keysQuery->where(function ($q) use ($search) {
                    $q->where('b.name', 'LIKE', "%{$search}%")
                      ->orWhere('b.account_number', 'LIKE', "%{$search}%")
                      ->orWhere('u.name', 'LIKE', "%{$search}%")
                      ->orWhere('u.email', 'LIKE', "%{$search}%");
                });
            }
            $matchKeys = $keysQuery->pluck('match_key');

            $rowsQuery = DB::table('bank_information as b')
                ->join('users as u', 'b.user_id', '=', 'u.id')
                ->leftJoin('wallets as w', 'w.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as job_count FROM campaign_workers GROUP BY user_id) as cw'), 'cw.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, COUNT(*) as referral_count FROM referral GROUP BY user_id) as r'), 'r.user_id', '=', 'u.id')
                ->leftJoin(DB::raw('(SELECT user_id, SUM(amount) as total_withdrawn FROM payment_transactions WHERE type = "withdrawal" AND status = "successful" GROUP BY user_id) as pt'), 'pt.user_id', '=', 'u.id')
                ->whereIn('b.name', $matchKeys)
                ->where(function ($q) use ($ignoreRoles) {
                    $q->whereNull('u.role')->orWhereNotIn('u.role', $ignoreRoles);
                })
                ->select(
                    'b.name as match_value',
                    DB::raw("'Bank Beneficiary Name' as match_type_label"),
                    'b.name as bank_account_name',
                    'b.account_number',
                    'b.bank_name',
                    'b.created_at as bank_created',
                    'u.id as user_id',
                    'u.name as user_name',
                    'u.email',
                    'u.phone',
                    'u.country',
                    'u.created_at as user_created',
                    'u.auth_device',
                    'u.source',
                    'u.is_blacklisted',
                    'u.is_verified',
                    DB::raw("({$currencyExpr}) as currency"),
                    DB::raw("COALESCE(({$liveBalanceExpr}), 0) as balance"),
                    DB::raw('COALESCE(cw.job_count, 0) as job_count'),
                    DB::raw('COALESCE(r.referral_count, 0) as referral_count'),
                    DB::raw('COALESCE(pt.total_withdrawn, 0) as total_withdrawn'),
                    DB::raw($lastIpSubquery)
                )
                ->orderBy('b.name');
        }

        $allRows = $rowsQuery->get();

        // Status filtering if requested
        if ($statusFilter === 'active_only') {
            $allRows = $allRows->where('is_blacklisted', false);
        } elseif ($statusFilter === 'blacklisted_only') {
            $allRows = $allRows->where('is_blacklisted', true);
        }

        // Group rows by match value
        $grouped = $allRows->groupBy('match_value');

        // Total stats across the current vector dataset
        $stats = [
            'total_groups' => $grouped->count(),
            'total_accounts' => $allRows->unique('user_id')->count(),
            'blacklisted_accounts' => $allRows->where('is_blacklisted', true)->unique('user_id')->count(),
            'active_accounts' => $allRows->where('is_blacklisted', false)->unique('user_id')->count(),
        ];

        // Paginate groups (50 groups per page)
        $currentPage = LengthAwarePaginator::resolveCurrentPage('page');
        $currentPageGroups = $grouped->slice(($currentPage - 1) * $perPage, $perPage);
        $paginatedDuplicates = new LengthAwarePaginator(
            $currentPageGroups,
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('admin.fraud.duplicate', compact('paginatedDuplicates', 'stats', 'vectorCounts', 'type', 'search', 'statusFilter'));
    }

    public function blacklist(User $user)
    {
        $user->update(['is_blacklisted' => true]);
        return back()->with('success', "{$user->name} has been blacklisted and suspended.");
    }

    public function unblacklist(User $user)
    {
        $user->update(['is_blacklisted' => false]);
        return back()->with('success', "{$user->name} has been unblocked.");
    }
}
