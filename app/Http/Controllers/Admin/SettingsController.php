<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function index()
    {
        $settings = Settings::all();
        return view('admin.settings', ['settings' => $settings]);
    }

    public function store(Request $request){
        return $request;
        $setting = Settings::where('id', $request->id)->first();
        if($setting == null){
            //create
            Settings::create(['name' => $request->name, 'value' => $request->value]);
        }else{
            //update
            $setting->value = $request->value;
            $setting->save();
        }
        return back()->with('success', 'Settings were saved');

        // return $request;
    }

    public function createOrUpdate($key, $value = null)
    {
        $setting = Settings::where('name', $key)->first();

        if ($setting) {  //found
            $setting->value = $value;
            $setting->update();
            return $setting;
        }
        $setting = Settings::create(['name' => $key, 'value' => $value]);
        return $setting;
    }

    public function activate($id){
        Settings::query()->update(['status' => false]);
        $set = Settings::where('id', $id)->first();
        $set->status = true;
        $set->save();
        return back()->with('success', 'Settings updated');
    }

}


    
