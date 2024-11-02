<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConversionRateRequest;
use App\Http\Requests\UpdateConversionRateRequest;
use App\Models\ConversionRate;

class ConversionRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversions = ConversionRate::all();
        return view('admin.conversion.index', ['rates' => $conversions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreConversionRateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConversionRateRequest $request)
    {
        if($request->from == $request->to){
            return back()->with('error', 'You cannot create the same currency variation');
        }

        $created = ConversionRate::create(['from' => $request->from, 'to' => $request->to, 'rate' => $request->rate, 'amount' => 0]);
        return back()->with('success', 'Currency variation created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConversionRate  $conversionRate
     * @return \Illuminate\Http\Response
     */
    public function show(ConversionRate $conversionRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConversionRate  $conversionRate
     * @return \Illuminate\Http\Response
     */
    public function edit(ConversionRate $conversionRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateConversionRateRequest  $request
     * @param  \App\Models\ConversionRate  $conversionRate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConversionRateRequest $request, ConversionRate $conversionRate)
    {
        $conversionRate = ConversionRate::find($request->id);
        $conversionRate->update([
            'rate' => $request->rate,
            // 'referral_commission' => $request->referral_commission,
            // 'upgrade_fee' => $request->upgrade_fee,
            // 'priotize' => $request->priotize,
            // 'allow_upload' => $request->allow_upload,

        ]);
        return back()->with('success', 'Rate updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConversionRate  $conversionRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConversionRate $conversionRate)
    {
        //
    }
}
