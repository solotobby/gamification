<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AchieverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function topEarners(){
        $topEarners = topEarners();
        return view('user.achievers.top_earners', ['topEarners' => $topEarners]);
    }
}
