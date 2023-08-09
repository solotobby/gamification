<?php

namespace App\Http\Controllers;

use App\Models\CampaignWorker;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function jobRating(Request $request){

        $request->validate([
            'rating' => 'required',
            'comment' => 'required',
        ]);

        $workDone =  CampaignWorker::where('campaign_id', $request->campaign_id)->where('user_id', auth()->user()->id)->firstOrFail();
        
        Rating::create([
            'user_id'=>auth()->user()->id, 
            'campaign_id' =>$request->campaign_id, 
            'campaign_worker_id'=>$workDone->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'type' => 'job'
        ]);
        
        return back()->with('success', 'Thank you for taking the time to rate this job, we really appreciate it');
    }
}
