<?php

namespace App\Http\Controllers;

use App\Helpers\SystemActivities;
use App\Models\ActivityLog;
use App\Models\LoginPoints;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function survey(){
        $interests = Preference::orderBy('name', 'ASC')->get();
        return view('user.survey.index', ['interests' => $interests]);
    }


    public function storeSurvey(Request $request){
        
        $request->validate([
            'interest' => 'required|array|min:15',
            'age_range' => 'required|string',
            'gender' => 'required|string'
        ]);
        $user = User::where('id', auth()->user()->id)->first();

        $user->age_range = $request->age_range;
        $user->gender = $request->gender;
        $user->save();
        foreach($request->interest as $int){
            \DB::table('user_interest')->insert(['user_id'=>$user->id, 'preference_id' => $int, 'created_at' => now(), 'updated_at' => now()]);
        }
        $date = \Carbon\Carbon::today()->toDateString();
        
        ActivityLog::create(['user_id' => $user->id, 'activity_type' => 'survey_points', 'description' =>  SystemActivities::getInitials($user->name) .' earned 100 points for taking freebyz survey', 'user_type' => 'regular']);
        LoginPoints::create(['user_id' => $user->id, 'date' => $date, 'point' => '100']);

        return view('user.survey.completed');

    }
}
