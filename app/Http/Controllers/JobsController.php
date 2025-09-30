<?php

namespace App\Http\Controllers;

use App\Models\CampaignWorker;
use Illuminate\Http\Request;

class JobsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'email']);
    }


    public function myJobs()
    {
        $joblist = CampaignWorker::where('user_id', auth()->user()->id)->where('status', 'Pending')->orderBy('created_at', 'ASC')->get();
        return view('user.jobs.my_jobs', ['lists' => $joblist]);
    }
}
