<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalCategory;
use App\Models\ProfessionalDomain;
use App\Models\ProfessionalSubCategories;
use App\Models\Tool;
use Illuminate\Http\Request;

class ProfessionalOnboardingController extends Controller
{
    public function index(){
        $tools = Tool::all();
        return view('professional_onboarding.applicant', ['tools' => $tools]);
    }

    public function store(Request $request){
        return $request;
    }

    public function categories(){
        return ProfessionalCategory::orderBy('name', 'DESC')->get();;
    }

    public function subCategories($id){
        return ProfessionalSubCategories::where('professional_category_id', $id)->orderBy('name', 'DESC')->get();
    }

    public function domains($id){
        return ProfessionalDomain::where('professional_sub_categories_id', $id)->orderBy('name', 'DESC')->get();
    }
}
