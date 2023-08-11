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

    public function index(){
        $audit = ActivityLog::orderBy('created_at', 'DESC')->paginate(100);
        return view('admin.audit.index', ['audits' => $audit]);
    }
}
