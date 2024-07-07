<?php

namespace App\Http\Controllers;


use App\Forms\GenerateForm;
use App\Models\Survey;
use App\Models\SurveyForm;
use App\Models\SurveyInterest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\FormBuilder;

class FormBuilderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(){
        $preferences = listPreferences();
        return view('user.form_builder.create', ['preferences' => $preferences]);
    }

    public function survey($survey_code){
        $surevey = Survey::where('survey_code', $survey_code)->first();// $survey_code;
        return view('user.form_builder.builder', ['survey' => $surevey]);
        
    }

    public function buildForm(Request $request){

        $type = $request->type;
        $name = $request->name;
        $choices = $request->choices;
        $required = $request->required;
       
        $data = [];
        foreach($type as $key => $value){

            $data[] = [
                'survey_id' =>$request->survey_id,
                'name' => $name[$key],
                'type' => $value,
                'required' => $required[$key] ?? 0, // Default to 0 if not set
                'choices' => $choices[$key] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

        }

         SurveyForm::insert($data);

         $updatSurvey = Survey::find($request->survey_id);
         $updatSurvey->status = 'Pending';
         $updatSurvey->save();

         return redirect('preview/form/'.$request->survey_code);



    }

    public function storeForm(Request $request){
    
        $lissy = [];
        foreach($request->count as $res){
            $lissy[] = explode("|",$res);
        }

        // $newlissy = [];
        foreach($lissy as $lis)
        {
            $counts[] = ['unit'=>$lis[0], 'id' => $lis[1]];
        }

        foreach($counts as $id)
        {
            $unit[] = $id['unit'];
        }
        $survey = Survey::create([
            'user_id' => auth()->user()->id,
            'survey_code' => Str::random(16),
            'category_id' => '1', 
            'sub_category_id' => '1', 
            'title' => $request->title, 
            'description' => $request->description, 
            'amount' => '3', 
            'total_amount' => '600', 
            'currency' => 'USD', 
            'number_of_response' => $request->number_of_response, 
            'number_of_response_submitted' => '0', 
            'status' => 'in_progress'
        ]);

        foreach($counts as $id)
        {
            SurveyInterest::create(['survey_id' => $survey->id, 'interest_id' => $id['id'], 'unit' => $id['unit']]);
            // \DB::table('survey_interests')->insert(['survey_id' => $survey->id, 'interest_id' => $id['id'], 'unit' => $id['unit'], 'created_at' => now(), 'updated_at' => now()]);
        }

       
          return redirect('survey/'.$survey->survey_code);
           
      
    }

    public function previewForm(FormBuilder $formBuilder, $survey_code){

        // return $survey_code;

        //  // session(['form_builder_app_id' => $survey->id]);
         $form = $formBuilder->create(GenerateForm::class, [
            'method' => 'POST',
            'url' => route('redeem.badge'),
            'files'=>true,
            'class'=>'col-md-12 row'
        ], ['surveyId' => $survey_code]);

        return view('user.form_builder.index',['form' => $form]);

    }

    public function listSurvey(){
        $surveyList = Survey::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('user.form_builder.list', ['lists' => $surveyList]);
    }
}
