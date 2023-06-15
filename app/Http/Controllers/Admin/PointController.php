<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginPointsRedeemed;
use App\Models\Point;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $points = Point::all();
        return view('admin.points.index', ['points' => $points]);
    }

    public function redeemed(){
        $redeemed = LoginPointsRedeemed::orderBy('created_at', 'DESC')->get();
        return view('admin.points.redeemed', ['redeemed' => $redeemed]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:points|max:255',
            'point' => 'required|numeric',
        ]);
        Point::create([
            'name' => $request->name, 'point' => $request->point
        ]);
        return back()->with('success', 'Point Successfully created');
    }
}
