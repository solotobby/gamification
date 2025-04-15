<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioTool;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalProficiencyLevel;
use App\Models\Skill;
use App\Models\SkillAsset;
use App\Models\SkillCategory;
use App\Models\SkillProficiencyLevel;
use App\Models\Tool;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SkillsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request){
        $searchResult = collect([]); // Always initialize as a collection

    if ($request->isMethod('get') && $request->filled('skill_id') || $request->filled('proficiency_level') || $request->filled('year_experience')) {
        $query = SkillAsset::query();

        // Filter by Skillset
        if ($request->filled('skill_id')) {
            $query->where('skill_id', $request->skill_id);
        }

        // Filter by Experience Level
        if ($request->filled('proficiency_level')) { // Fixed typo
            $query->where('proficiency_level', $request->proficiency_level);
        }

        // Filter by Years of Experience
        if ($request->filled('year_experience')) { // Fixed key
            $years = $request->year_experience;
            if ($years == '0-2') {
                $query->whereBetween('year_experience', [0, 2]);
            } elseif ($years == '3-5') {
                $query->whereBetween('year_experience', [3, 5]);
            } elseif ($years == '6-10') {
                $query->whereBetween('year_experience', [6, 10]);
            } elseif ($years == '10+') {
                $query->where('year_experience', '>=', 10);
            }
        }

        $searchResult = $query->paginate(50); // Get filtered data
    }

        $skills = Skill::all();
        $experience = ProfessionalProficiencyLevel::all();
    
        return view('user.skills.index', [
            'skills' => $skills,
            'experience' => $experience,
            'searchResult' => $searchResult // Fixed typo
        ]);
    
    }

    public function viewSkill($id){
       
        $query = SkillAsset::where('id', $id)->first();
        $portfolio = Portfolio::where('user_id', $query->user_id)->get();
        return view('user.skills.view_skill', ['skill' => $query, 'portfolio' => $portfolio]);
    }



    public function create(){

        $profeciencies = ProfessionalProficiencyLevel::all();
        $skills = Skill::all();
        $skillAsset = SkillAsset::where('user_id', auth()->user()->id)->first();
        // $portfolio = Portfolio::where('skill_id', $skillAsset->id)->get();

        return view('user.skills.create', 
                    ['skills' => $skills, 
                    'profeciencies' => $profeciencies, 
                    'skillAsset' => $skillAsset
                    // 'portfolio' => $portfolio
                ]);
    }

    public function storeSkill(Request $request){

        $validated = $this->validate($request, [
            'title' => 'required|string',
            'skill_id' => 'required|numeric',
            'description' => 'required|string',
            'year_experience' => 'required|string',
            'profeciency_level' => 'required|string',
            'availability' => 'required|string',
            'location' => 'required|string',
            'min_price' => ['required', 'numeric', 'min:0'],
            'max_price' => [
                'required', 'numeric', 'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->min_price > $request->max_price) {
                        session()->flash('error', "Min price cannot be greater than Max price.");
                        $fail('Min price cannot be greater than Max price.');
                    }
                }
            ],
        ]);

        // $request->request->add(['user_id' => auth()->user()->id, 'max_price' => 0, 'min_price' => 0]);
        if($validated){

            SkillAsset::updateOrCreate(
                ['user_id' => auth()->user()->id],
                [
                'title' => $request->title,
                'description' => $request->description , 
                'skill_id' => $request->skill_id, 
                'profeciency_level' => $request->profeciency_level, 
                'year_experience' => $request->year_experience, 
                'location' => $request->location, 
                'availability' => $request->availability,
                'max_price' => $request->max_price, 
                'min_price' => $request->min_price
                ]
            );

            Alert::success('Success', 'Skill Updated Successfully');
            return back()->with('success', 'Skill Updated Successfully');
        
        
        }else{
            Alert::error('Error', 'Something happened, please try again later');
            return back()->with('error', 'Something happened, please try again later');
        }
        
    }

    public function addPortfolio(Request $request){
       $validated =  $this->validate($request, [
            'title' => 'required|string',
            
            'description' => [
            'required',
            function ($attribute, $value, $fail) {
                // List of social media domains to check
                $socialMediaDomains = [
                    'facebook.com', 'fb.com',
                    'twitter.com', 'x.com',
                    'instagram.com',
                    'youtube.com', 'youtu.be',
                    'tiktok.com',
                    'linkedin.com',
                    'snapchat.com',
                    'reddit.com',
                ];

                // Create a regex pattern to detect URLs with these domains
                $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:' . implode('|', array_map('preg_quote', $socialMediaDomains)) . ')/i';

                if (preg_match($pattern, $value)) {

                    session()->flash('error', "The {$attribute} field contains prohibited social media links.");
                    $fail("The {$attribute} field contains prohibited social media links.");
             
                    // Alert::error('Error', "The {$attribute} field contains prohibited social media links");
                    // $fail("The {$attribute} field contains prohibited social media links.");

                }
            },],
            
        ]);
       

        if($validated){
            $request->request->add(['user_id' => auth()->user()->id]); 
            $portfolio = Portfolio::create($request->all());
            // if($portfolio){
            //     foreach($request->tools as $tool){
            //         PortfolioTool::create(['portfolio_id' => $portfolio->id, 'tool_id' => $tool]);
            //     }
            // }
            if($portfolio){
                Alert::success('Success', 'Portfolio added Successfully');
                //return redirect()->url('home');
                return back()->with('success', 'Portfolio added Successfully');    
            }
            
        }else{
            Alert::error('Error', 'An Error occoured, please try again');
            return back()->with('error', 'An Error occoured, please try again');

            // return back()->with('errors', 'An Error occoured, please try again');
        }
    }

    public function mySkill(){
        $skill = SkillAsset::where('user_id', auth()->user()->id)->first();
       
        return view('user.skills.myskill', ['skill' => $skill]);
    }
}
