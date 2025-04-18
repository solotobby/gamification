<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalJob;
use App\Models\ProfessionalSubCategories;
use App\Models\Skill;
use App\Models\SkillAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfessionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        $jobs = ProfessionalJob::orderBy('created_at', 'DESC')->get();
        return view('admin.professional.index', ['jobs' => $jobs]);
    }

    public function store(Request $request){
   

        ProfessionalJob::create([
            'user_id' => auth()->user()->id, 
            'professional_category_id' => $request->professional_category_id, 
            'professional_sub_category_id' => $request->professional_sub_category_id, 
            'title' => $request->title, 'slug' => Str::slug($request->title), 
            'description' => $request->description
        ]);

        return back()->with('success', 'Job Created');
    }

    public function loadProfessionalCategories(){
       return ProfessionalCategory::all();
    }

    public function loadProfessionalSubCategories($id){
        return ProfessionalSubCategories::where('professional_category_id', $id)->get();
    }

    public function storeProfessionalCategory(Request $request){

        ProfessionalCategory::create(['name' => $request->name]);
        return back()->with('success', 'Category Created');
    }

    public function storeProfessionalSubCategory(Request $request){
        ProfessionalSubCategories::create(['professional_category_id' => $request->category_id, 'name' => $request->name, 'unique_id' => rand(999, 99999)]);
        return back()->with('success', 'Sub Category Created');
    }

    public function professionalCategory(){
        $category = ProfessionalCategory::all();
        return view('admin.professional.category', ['category' => $category]);
    }

    public function storeProfessionalSkillset(Request $request){
        Skill::create(['name' => $request->name]);
        return back()->with('success', 'Skillset Created');
    }

    public function listProfessional($status){
        $skillAsset = SkillAsset::query();
        $list = $skillAsset->where('status', $status)->orderBy('created_at', 'DESC')->paginate(20);
        $total = $skillAsset->count();
        return view('admin.professional.list', ['skillAsset' => $list, 'total' => $total, 'status' => $status]);   
    }


    public function updateStatus($status, $id){
        $skilAsset = SkillAsset::where('id', $id)->first();
        $skilAsset->status = $status;
        $skilAsset->save();
        return back()->with('success', 'User is '.$status.'');
    }
}
