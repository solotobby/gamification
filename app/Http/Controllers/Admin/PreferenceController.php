<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StorePreferenceRequest;
use App\Http\Requests\UpdatePreferenceRequest;
use App\Models\Preference;
use App\Http\Controllers\Controller;

class PreferenceController extends Controller
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
        $preferences = Preference::orderBy('created_at', 'DESC')->get();
        return view('admin.preferences.index', ['preferences' => $preferences]);
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
     * @param  \App\Http\Requests\StorePreferenceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePreferenceRequest $request)
    {
        $validated = $request->validated();
        Preference::create(['name' => $validated['name']]);
        return back()->with('success', 'Preference created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Preference  $preference
     * @return \Illuminate\Http\Response
     */
    public function show(Preference $preference)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Preference  $preference
     * @return \Illuminate\Http\Response
     */
    public function edit(Preference $preference)
    {
        return $preference;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePreferenceRequest  $request
     * @param  \App\Models\Preference  $preference
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePreferenceRequest $request, Preference $preference)
    {
        $validated = $request->validated();
        $preference->update(['name' => $validated['name']]);
        return back()->with('success', 'Preference status updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Preference  $preference
     * @return \Illuminate\Http\Response
     */
    public function destroy(Preference $preference)
    {
        //
    }
}
