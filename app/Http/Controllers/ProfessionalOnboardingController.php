<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalDomain;
use App\Models\ProfessionalSubCategories;
use App\Models\ProfessionalTool;
use App\Models\Tool;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProfessionalOnboardingController extends Controller
{
    public function index(){
        $tools = Tool::all();
        return view('professional_onboarding.applicant', ['tools' => $tools]);
    }

    public function store(Request $request){
        // return $request;
       
        $this->validate($request, [
            'full_name' => 'required|string',
            'work_experience' => 'required|string',
            'title' => 'required|string',
           
        ]);

        
        $validateEmail = User::where('email', $request->email)->first();

        if($validateEmail){

            $checkProfessional = Professional::where('user_id', $validateEmail->id)->first();
            if($checkProfessional){
                return back()->with('error', 'Your information already exist as a Professional');
            }

            $validateEmail->username = $request->username;
            $validateEmail->save();


            $Professional = $this->proccessApplication($validateEmail, $request, geo());
            if($Professional){
                return redirect('professional/completed/'.$Professional->_link);
            }
                       //if email exist, store the person information and ask them to login to view the progress

        }else{
                //register them based on the information they provided
                $user = User::create([
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'country' => $request->country,
                    'phone' => $request->phone,
                    'source' => 'direct',
                    'password' => Hash::make(Str::random(10)),
                    'referral_code' => Str::random(7),
                ]);
                
                $location = 'Nigeria';//getLocation(); //get user location dynamically
                $baseCurrency = $location == "Nigeria" ? 'Naira' : 'Dollar';
                 Wallet::create(['user_id' => $user->id, 'balance' => '0.00', 'base_currency' => $baseCurrency]);
        
                 $Professional = $this->proccessApplication($validateEmail, $request,  geo());
                 if($Professional){
                     return redirect('professional/completed/'.$Professional->_link);
                 }
                     


        }



      

        
    }

    private function proccessApplication($user,  $request, $geo){

        $data['user_id'] = $user->id;
        $data['professional_category_id'] = $request->professional_category_id;
        $data['professional_sub_category_id'] = $request->professional_sub_category_id;
        $data['professional_domain_id'] = $request->main_production_domain;
        $data['full_name'] = $request->full_name;
        $data['employment_status'] = $request->employment_status;
        $data['title'] = $request->title;
        $data['work_experience'] = $request->work_experience;
        $data['communication_mode'] = 'online';
       
        $data['avg_rating'] = 0;
        $data['website_link'] = $request->website_link;
        $data['fb_link'] = $request->fb_link;
        $data['tiktok_link'] = $request->tiktok_link;

        $data['x_link'] = $request->x_link;
        $data['linkedin_link'] =$request->linkedin_link;
        $data['instagram_link'] = $request->instagram_link;
        $data['_link'] = Str::random(82);
        $data['geo'] = $geo;
        

        $Professional = Professional::create($data);

        if($Professional){
            foreach($request->tools as $tool){
                ProfessionalTool::create(['professional_id' => $Professional->id, 'tool_id' => $tool]);
            }
        }

        return $Professional;
    }

    public function completed($link){
        $Professional = Professional::where('_link', $link)->first();
        if($Professional){
            return view('professional_onboarding.completed');
        }else{
            abort(403);
        }
        
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
