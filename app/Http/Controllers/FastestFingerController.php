<?php

namespace App\Http\Controllers;

use App\Models\FastestFinger;
use App\Models\FastestFingerPool;
use App\Models\Referral;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FastestFingerController extends Controller
{
    public function __construct()
    {
         $this->middleware(['auth', 'email']);
        // $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $refCount =  Referral::where('referee_id', $user->id)->where('updated_at', '>=', Carbon::now()->subDays(10))->count();
        $Info=FastestFinger::where('user_id', $user->id)->first();
        $checkTodayPool = FastestFingerPool::where(['user_id'=> $user->id, 'date' => Carbon::today()->format('Y-m-d')])->first();
        // return $user->referees;

        return view('user.finger.index', ['refCount' => $refCount, 'Info' => $Info, 'checkTodayPool' =>$checkTodayPool]);
    }

    public function declareInterest(Request $request){
        $request->validate([
            'phone' => 'required|numeric|digits:11',
            'tiktok' => 'required|string',
            'network' => 'required|string'
        ]);

        $user = Auth::user();
        // return $user->referees;
        FastestFinger::create(['user_id' => $user->id, 'tiktok' => $request->tiktok, 'phone' => $request->phone, 'network' => $request->network]);
        return back()->with('success', 'Interest registered successfully!');
    }

    public function enterPool(Request $request){

        $user = Auth::user();
        $refCount =  Referral::where('referee_id', $user->id)->where('updated_at', '>=', Carbon::now()->subDays(10))->count();

        if($refCount >= 10){
            FastestFingerPool::create(['user_id' => $user->id, 'date' => Carbon::today()->format('Y-m-d')]);
            return back()->with('success', 'Pool Submitted successfully!');
        }else{
            return back()->with('error', 'Not valid!');
        }
    }
}
