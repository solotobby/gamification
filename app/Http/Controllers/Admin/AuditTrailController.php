<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->search($request->search)
            ->activityType($request->activity_type)
            ->userType($request->user_type)
            ->clientType($request->client_type)
            ->device($request->device)
            ->dateRange($request->start, $request->end)
            ->orderBy('created_at', 'DESC');

        $audits = $query->paginate(100)->withQueryString();

        // Summary Statistics for the Admin View
        $summary = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', now()->toDateString())->count(),
            'web' => ActivityLog::where('client_type', 'web')->count(),
            'app' => ActivityLog::whereIn('client_type', ['app', 'mobile_app', 'ios', 'android'])->count(),
            'mobile' => ActivityLog::where('device', 'mobile')->count(),
            'desktop' => ActivityLog::where('device', 'desktop')->count(),
        ];

        return view('admin.audit.index', [
            'audits' => $audits,
            'summary' => $summary,
        ]);
    }
}
