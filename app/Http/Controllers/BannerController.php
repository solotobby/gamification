<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
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
        return view('user.banner.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countryList = countryList();
        $preferences = listPreferences();
        return view('user.banner.create', ['preferences' => $preferences, 'countryLists' => $countryList]);
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
            'count' => 'required|array|min:5',
            'country' => 'required|string',
            'age_bracket' => 'required|string',
            'ad_placement' => 'required|string',
            'adplacement' => 'required|string',
            'external_link' => 'required|string',
            'duration' => 'required|string',
        ]);

        //return explode("|",$request->count);

        $lissy = [];
        foreach($request->count as $res){
            $lissy[] = $res;
        }

        return $lissy;

        if($request->hasFile('banner_url')){

            $fileBanner = $request->file('banner_url');
            $Bannername = time() . $fileBanner->getClientOriginalName();
            $filePathBanner = 'adBanners/' . $Bannername;
    
            Storage::disk('s3')->put($filePathBanner, file_get_contents($fileBanner), 'public');
            $bannerUrl = Storage::disk('s3')->url($filePathBanner);
            
            $parameters =  array_sum($request->count) + $request->ad_placement + $request->age_bracket + $request->duration + $request->country;
            $banner['user_id'] = auth()->user()->id; 
            $banner['banner_id'] = Str::random(16);
            $banner['external_link'] = $request->external_link; 
            $banner['ad_placement_point'] = $request->ad_placement;
            $banner['adplacement_position'] = $request->adplacement;
            $banner['age_bracket'] = $request->age_bracket;
            $banner['duration'] = $request->duration; 
            $banner['country'] = $request->country;
            $banner['status'] = false;
            $banner['amount'] = $parameters * 500;
            $banner['banner_url'] = $bannerUrl;
            $banner['impression'] = 0;

            $createdBanner = Banner::create($banner);

            $interests = $request->count;

            foreach($interests as $interest){
                \DB::table('banner_interests')->insert(['banner_id' => $createdBanner->id, 'interest_id' => $interest]);
            }
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
}
