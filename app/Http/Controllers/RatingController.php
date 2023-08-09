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
        
        $workDone =  CampaignWorker::where('campaign_id', $request->campaign_id)->where('user_id', auth()->user()->id)->firstOrFail();

    //    if($workDone){
    //         return back()->with('error', 'You haven not completed this job, you can only rate the job done');
    //    }
        $rating = '';
        if($request->rating == null){
            $rating = 0;
        }else{
            $rating = $request->rating;
        }
        $rating = new Rating();
        $rating->user_id = auth()->user()->id;
        $rating->campaign_id = $request->campaign_id;
        $rating->campaign_worker_id = $workDone->id;
        $rating->rating = $rating;
        $rating->comment = $request->comment;
        $rating->type = 'job';
        $rating->save();

        return back()->with('success', 'Thank you for taking the time to rate this job, we really appreciate it');


    }
}
