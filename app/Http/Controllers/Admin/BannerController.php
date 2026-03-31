<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Models\Banner;
use App\Models\PaymentTransaction;
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
        if(auth()->user()->hasRole('admin')){
            $banner = Banner::where('id', $id)->first();
            if($banner->status == false){

                 $banner->status = true;
                 $banner->live_state = 'Started';
                 $banner->banner_end_date = Carbon::now()->addDays($banner->duration);
                 $banner->save();

                 $user = User::where('id', $banner->user_id)->first();
                 $content = 'Congratulations, your ad is Live on Freebyz.';
                 $subject = 'Ad Banner Placement - Live!';
                //  Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

                 return back()->with('success', 'Ad Banner is Live!');
            }else{
                 return back()->with('success', 'Ad Banner is Live!');
            }

        }else{
            return 'unauthorized';
        }

    }

    public function rejectBanner($id){
        if(auth()->user()->hasRole('admin')){

            $banner = Banner::where('id', $id)->first();
            $banner->live_state = 'Rejected';
            $banner->save();



            $user = User::where('id', $banner->user_id)->first();
            creditWallet($user, 'NGN', $banner->amount);

            PaymentTransaction::create([
                'user_id' => $user->id,
                'campaign_id' => '1',
                'reference' => time(),
                'amount' => $banner->amount,
                'balance' => walletBalance($user->id),
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'internal',
                'type' => 'ad_banner_reversal',
                'description' => 'Reversal: Ad Banner Placement by '.$user->name,
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]);


            $content = 'Ad Banner Placement - Rejected!d';
            $subject = 'Sorry, your banner ads was rejected, this is because it is against our advertising regulatory policies. Your ad is in one or all of these categories, Betting Apps, spamming websites, investment apps and links we think are unsafe for our users. ';
            // Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

            return back()->with('success', 'Ad Banner Rejected!');

        }else{
            return 'unauthorized';
        }
    }
}
