<?php

namespace App\Http\Controllers;

use App\Mail\GeneralMail;
use App\Models\Banner;
use App\Models\BannerClick;
use App\Models\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        return view('user.banner.index', ['banners' => $banners]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $countryList = countryList();
        // $preferences = listPreferences();
        return view('user.banner.create');
        // ['preferences' => $preferences, 'countryLists' => $countryList]
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'banner_url' => 'required|image|mimes:png,jpeg,gif,jpg',
            // 'count' => 'required|array|min:5',
            'external_link' => 'required|string',
            'budget' => 'required|numeric',
            // 'country' => 'required|string',
            // 'age_bracket' => 'required|string',
            // 'ad_placement' => 'required|string',
            // 'adplacement' => 'required|string',
            // 'duration' => 'required|string',
        ]);

        // $lissy = [];
        // foreach($request->count as $res){
        //     $lissy[] = explode("|",$res);
        // }

        // // $newlissy = [];
        // foreach($lissy as $lis)
        // {
        //     $counts[] = ['unit'=>$lis[0], 'id' => $lis[1]];
        // }

        // foreach($counts as $id)
        // {
        //     $unit[] = $id['unit'];
        // }

        // $parameters =  array_sum($unit) + $request->ad_placement + $request->age_bracket + $request->duration + $request->country;
        // $finalTotal = $parameters * 25;

        if($request->budget > auth()->user()->wallet->balance){
            return back()->with('error', 'Insurficient Balance');
        }

        if($request->hasFile('banner_url')){

            //s3 bucket processing
            $fileBanner = $request->file('banner_url');
            $Bannername = time();// . $fileBanner->getClientOriginalName();
            $filePathBanner = 'ad/' . $Bannername.'.'.$fileBanner->extension();
            
            Storage::disk('s3')->put($filePathBanner, file_get_contents($fileBanner), 'public');
            $bannerUrl = Storage::disk('s3')->url($filePathBanner);
            

            // $fileBanner = $request->file('banner_url');
            // //process local storage
            // $imgName = time();
            // $Bannername =  $imgName.'.'.$request->banner_url->extension();//time();// . $fileBanner->getClientOriginalName();
            // $request->banner_url->move(public_path('banners'), $Bannername); //store in local storage

        
            $banner['user_id'] = auth()->user()->id; 
            $banner['banner_id'] = Str::random(7);
            $banner['external_link'] = $request->external_link; 
            $banner['ad_placement_point'] = 0; //$request->ad_placement;
            $banner['adplacement_position'] = 'top'; //$request->adplacement;
            $banner['age_bracket'] = '18'; //$request->age_bracket;
            $banner['duration'] = '1';//$request->duration; 
            $banner['country'] = 'all';//$request->country;
            $banner['status'] = false;
            $banner['amount'] = $request->budget; //$finalTotal;
            $banner['banner_url'] = $bannerUrl;
            $banner['impression'] = 0;
            $banner['impression_count'] = 0;
            $banner['clicks'] = $request->budget / 11.5;
            $banner['click_count'] = 0;
            


            $createdBanner = Banner::create($banner);

            if($createdBanner){
                debitWallet(auth()->user(),'Naira', $request->budget);
                //transaction log 
                PaymentTransaction::create([
                    'user_id' => auth()->user()->id,
                    'campaign_id' => $createdBanner->id,
                    'reference' => time(),
                    'amount' => $request->budget,
                    'status' => 'successful',
                    'currency' => 'NGN',
                    'channel' => 'internal',
                    'type' => 'ad_banner',
                    'description' => 'Ad Banner Placement by '.auth()->user()->name,
                    'tx_type' => 'Debit',
                    'user_type' => 'regular'
                ]);

                // foreach($counts as $id)
                // {
                //     \DB::table('banner_interests')->insert(['banner_id' => $createdBanner->id, 'interest_id' => $id['id'], 'unit' => $id['unit'], 'created_at' => now(), 'updated_at' => now()]);
                // }
            }
          
            // $content = 'Your ad banner placement is successfully created. It is currenctly under review, you will get a notification when it goes live!';
            // $subject = 'Ad Banner Placement - Under Review';

            // Mail::to(auth()->user()->email)->send(new GeneralMail(auth()->user(), $content, $subject, ''));  
            return back()->with('success', 'Banner ad Created Successfully');

        }else{
            return back()->with('error', 'Please upload a banner');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function bannerResources(){
        return auth()->user()->wallet->balance;
    }

    public function adView($bannerId){
        $ban = Banner::where('banner_id', $bannerId)->first();
        // $ban->impression += 1;
        // $ban->clicks -= 1;
        $ban->click_count += 1;
        $ban->save();

        if($ban->click_count >= $ban->clicks){
            $ban->live_state = 'Ended';
            $ban->banner_end_date = Carbon::now();
            $ban->save();
        }

        BannerClick::create(['user_id' => auth()->user()->id, 'banner_id' => $ban->id]);
        return redirect($ban->external_link);
    }
}
