<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalCategory;
use App\Models\ProfessionalSubCategories;
use App\Models\SkillCategory;
use App\Models\SkillsetSubCategories;
use Illuminate\Http\Request;

class SkillOnboardingController extends Controller
{
    public function applicant(){
        return view('skill_onboarding.applicant');
    }

   
}
