<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function survey(){
        $interests = Preference::orderBy('name', 'ASC')->get();
        return view('user.survey.index', ['interests' => $interests]);
    }


    public function storeSurvey(Request $request){
        return $request;
    }
}
