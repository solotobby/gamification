<?php

namespace App\Http\Controllers;

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

    public function setupSkillSet(Request $request){
        return $request;
    }
}
