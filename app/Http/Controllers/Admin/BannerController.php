<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Models\Banner;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $bannerList = Banner::orderBy('created_at', 'DESC')->get();
        return view('admin.banner.index', ['bannerList' => $bannerList]);
    }

    public function activateBanner($id){
       $banner = Banner::where('id', $id)->first();
       if($banner->status == false){
           
            $banner->status = true;
            $banner->live_state = 'Started';
            $banner->banner_end_date = Carbon::now()->addDays($banner->duration);
            $banner->save();

            $user = User::where('id', $banner->user_id)->first();
            $content = 'Congratulations, your ad is Live on Freebyz.';
            $subject = 'Ad Banner Placement - Live!';
            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));  

            return back()->with('success', 'Ad Banner is Live!');
       }else{
            return back()->with('success', 'Ad Banner is Live!');
       }

    }
}
