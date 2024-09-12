<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioTool;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\SkillProficiencyLevel;
use App\Models\Tool;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        return view('user.skills.index');
    }

    public function create(){
        $skillCategory = SkillCategory::all();
        $skill = Skill::where('user_id', auth()->user()->id)->first();
        $tools = Tool::all();
        $portfolio = Portfolio::where('user_id', auth()->user()->id)->get();
        $profeciencies = SkillProficiencyLevel::all();
        return view('user.skills.create', ['skillCategory' => $skillCategory, 'skill' => $skill, 'tools' => $tools, 'portfolio' => $portfolio, 'profeciencies' => $profeciencies]);
    }

    public function storeSkill(Request $request){
        
        $this->validate($request, [
            'title' => 'required|string',
            'skill_category' => 'required|numeric',
            'description' => 'required|string',
            'payment_mode' => 'required|string',
            'profeciency_level' => 'required|string',
            'availability' => 'required|string',
            // 'max_price' => 'required|numeric',
            // 'min_price' => 'required|numeric'
        ]);

        $request->request->add(['user_id' => auth()->user()->id, 'max_price' => 0, 'min_price' => 0]);
        Skill::create($request->all());

        return back();
 
    }

    public function addPortfolio(Request $request){
       $validated =  $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'tools' => 'required|array',
            
        ]);
        //return $validated;

        if($validated){
            $request->request->add(['user_id' => auth()->user()->id]); 
            $portfolio = Portfolio::create($request->all());
            if($portfolio){
                foreach($request->tools as $tool){
                    PortfolioTool::create(['portfolio_id' => $portfolio->id, 'tool_id' => $tool]);
                }
            }

            return back()->with('success', 'Portfolio Created Successfully');

        }else{
            return back()->with('error', 'An Erro occoured, please try again');
        }
    }

    public function mySkill(){
        $skill = Skill::where('user_id', auth()->user()->id)->first();
       
        return view('user.skills.myskill', ['skill' => $skill]);
    }
}
