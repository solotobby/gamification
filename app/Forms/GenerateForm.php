<?php

namespace App\Forms;

use App\Models\Survey;
use App\Models\SurveyForm;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class GenerateForm extends Form
{
   

    public function buildForm()
    {
        $survey = $this->getData('surveyId');
        $surv = Survey::where('survey_code', $survey)->first();
       $forms = SurveyForm::where('survey_id', $surv->id)->where('active', true)->get();

            foreach($forms as $form){

                switch ($form->type) {
                    case "TEXT":
                        $this->add($form->name, 'text', [
                                    'rules' => $form->required ? 'required' : null . '|min:3',
                                    'error_messages' => [ucfirst($form->name) . '.required' => 'The ' . ucfirst($form->name) . ' field is mandatory.'],
                                    'wrapper' => ['class' => 'form-group col-md-12 mb-2'],
                                    'label' => ucfirst($form->name),  // Field name used
                                    'attr' => ['placeholder' => 'Enter ' . ucfirst($form->name)],
                                    'label_attr' => ['class' => 'font-weight-bold'],
                                ]);
                        break;
                    case "EMAIL": 
                        $this->add($form->name, 'email', [
                            'rules' => $form->required ? 'required|email' : null . '|max:100000',
                            'error_messages' => [ucfirst($form->name) . '.required' => 'The ' .  ucfirst($form->name) . ' field is mandatory.'],
                                    
                            'wrapper' => ['class' => 'form-group col-md-12 mb-4'],
                            'label' => ucfirst($form->name),  // Field name used
                            'attr' => ['placeholder' => 'Enter ' . ucfirst($form->name)],
                            'label_attr' => ['class' => 'font-weight-bold'],
                        ]);
                        break;
                    case "TEXTAREA":
                        $this->add($form->name, 'textarea', [
                            'rules' => $form->required ? 'required' : null . '|max:100000',
                            'error_messages' => [
                                $form->name . '.required' => 'The ' . ucfirst($form->name) . ' field is mandatory.'
                            ],
                            'wrapper' => ['class' => 'form-group col-md-12 mb-2'],
                            'label' => ucfirst($form->name),  // Field name used
                            'attr' => ['placeholder' => 'Input ' . ucfirst($form->name)],
                            'label_attr' => ['class' => 'font-weight-bold'],
                            // 'help_block' => [
                            //     'text' => "help",
                            //     'tag' => 'p',
                            //     'attr' => ['class' => 'text-danger']
                            // ],
                        ]);
                        break;
                        case 'SELECT':
                            $options = [];
                            $frdfsv = explode(',', $form->choices);
                            
                                foreach ($frdfsv as $k => $v) {
                                    $options[$v] = $v;
                                }
                                $this->add($form->name, 'select', [
                                    'choices' => $options,
                                    'selected' => null,
                                    'empty_value' => ' Select ' . ucfirst($form->name). '',
                                    'wrapper' => ['class' => 'form-group col-md-12 mb-2'],
                                    'label' => ucfirst($form->name),  // Field name used
                                    'label_attr' => ['class' => 'font-weight-bold'],
                                    'rules' => $form->required ? 'required' : null
                                ]);
                            break;
                            case 'CHECKBOX':
                                $options = [];
                                $frdfsv = explode(',', $form->choices);
                                
                                    foreach ($frdfsv as $k => $v) {
                                        $options[$v] = $v;
                                    }
                                  
                                $this->add($form->name, 'choice', [
                                    'choices' => $options,
                                    'choice_options' => [
                                        'wrapper' => ['class' => ''],
                                        'label_attr' => ['class' => 'font-weight-bold'],
                                    ],
                                    'wrapper' => ['class' => 'form-group col-md-12'],
                                    'label' => ucfirst($form->name),
                                    'selected' => [],
                                    'expanded' => true,
                                    'multiple' => true,
                                    'rules' => $form->require ? 'required' : null
                                ]);
                                break;

                                case 'RADIO':
                                    $options = [];
                                    $frdfsv = explode(',', $form->choices);
                                    
                                        foreach ($frdfsv as $k => $v) {
                                            $options[$v] = $v;
                                        }
                                      
                                    $this->add($form->name, 'choice', [
                                        'choices' => $options,
                                        'choice_options' => [
                                            'wrapper' => ['class' => ''],
                                            'label_attr' => ['class' => 'font-weight-bold'],
                                        ],
                                        'wrapper' => ['class' => 'form-group col-md-12'],
                                        'label' => ucfirst($form->name),
                                        'expanded' => true,
                                        'multiple' => false,
                                        'rules' => $form->require ? 'required' : null
                                    ]);
                                    break;
                    default:
                        echo "Your favorite color is neither red, blue, nor green!";
                    }

            }
    
        


           
            if(!$surv->user_id == auth()->user()->id){
                $this->add('submit', 'submit', [
                    'label' =>  'Submit',
                    'wrapper' => ['class' => 'form-group col-md-12'],
                    'attr' => ['class' => 'btn btn-primary pull-right'],
                ]);
            }
        


        
    }

    public function buildEachForm(){

        

    }
}
