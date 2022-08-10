<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CampaignController extends Controller
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
        $campaignList = Campaign::where('user_id', auth()->user()->id)->get();
        return view('user.campaign.index', ['lists' => $campaignList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.campaign.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
        //
    }

    public function getCategories()
    {
        return $categories = Category::orderBy('name', 'ASC')->get();
    }
    public function getSubCategories($id)
    {
        return $subCategories = SubCategory::where('category_id', $id)->orderBy('name', 'DESC')->get();
    }
    public function getSubcategoriesInfo($id)
    {
        return $subCategoriesInfo = SubCategory::where('id', $id)->first();
    }

    public function postCampaign(Request $request)
    {
        $est_amount = $request->number_of_staff * $request->campaign_amount;
        $percent = (50 / 100) * $est_amount;
        $total = $est_amount + $percent;
        [$est_amount, $percent, $total];
        $job_id = rand(10000,10000000);
        $request->request->add(['user_id' => auth()->user()->id,'total_amount' => $total, 'job_id' => $job_id]);
        $campaign = Campaign::create($request->all());
        $campaign->save();

        return back()->with('success', 'Campaign Posted Successfully');
    }

    public function viewCampaign($job_id)
    {
        $getCampaign = Campaign::where('job_id', $job_id)->first();
        return view('user.campaign.view', ['campaign' => $getCampaign]);
    }

    public function postCampaignWork(Request $request)
    {
        $campaignWorker = CampaignWorker::create($request->all());
        $campaignWorker->save();
        return back()->with('success', 'Campaign Submitted Successfully');
    }

    public function mySubmittedCampaign($id)
    {
        $work = CampaignWorker::where('id', $id)->first();
        return view('user.campaign.my_submitted_campaign', ['work' => $work]);
    }

    
}
