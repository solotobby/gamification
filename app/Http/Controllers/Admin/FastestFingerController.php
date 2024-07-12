<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FastestFinger;
use App\Models\FastestFingerPool;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FastestFingerController extends Controller
{
    public function showPool(){
        $date = Carbon::today()->format('Y-m-d');
        $show = FastestFingerPool::where(['date'=>$date ])->get();
        return view('admin.fastest_finger.show', ['show' => $show, 'date' =>$date]);
    }

    public function randomSelection(){
        $date = Carbon::today()->format('Y-m-d');
        $checkSelection = FastestFingerPool::where(['date'=>$date ])->where('is_selected', true)->first();
        if(!$checkSelection){
            $show = FastestFingerPool::where(['date'=>$date ])->inRandomOrder()->first();
            $show->is_selected = true;
            $show->save();
            return back()->with('success', 'Pool Selection successfully!');
        }else{
            return back()->with('error', 'Pool Selelction is made for ' .$date.' already!');
        }
        
    }
}
