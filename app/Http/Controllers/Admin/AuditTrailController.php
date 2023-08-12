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

    public function index(Request $request){
        if($request->account_type != null || $request->user_type != null){
            
            $audit = ActivityLog::where([
                [function ($query) use ($request) {
                   
                    $query->where('activity_type', 'LIKE', '%' . $request->activity_type . '%')
                          ->where('user_type', 'LIKE', '%' . $request->user_type . '%')
                          ->where('created_at', '>=', $request->start)
                          ->where('created_at', '<=', $request->end)
                          ->get();

                }]
            ])->paginate(100);
            
            return view('admin.audit.index', ['audits' => $audit]);

        }else{
            $audit = ActivityLog::orderBy('created_at', 'DESC')->paginate(100);
            return view('admin.audit.index', ['audits' => $audit]);
        }
       
        
    }
}
