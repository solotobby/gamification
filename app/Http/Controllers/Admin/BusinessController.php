<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Models\Accounts;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BusinessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $business = Business::query()->get();
        return view('admin.business.index', ['business' => $business]);
    }

    public function randomBusinessSelection(){
       // First, set all businesses 'is_live' to false
        Business::query()->where('status', 'ACTIVE')->update(['is_live' => false]);

        // Then, select a random business and set its 'is_live' to true
        $randomBusiness = Business::inRandomOrder()->first();
        if ($randomBusiness) {
            $randomBusiness->update(['is_live' => true]);
        }

        $user = User::where('id', $randomBusiness->user_id)->first();
        $subject = 'Freebyz Business Promotion - Business Selected';
        $content = 'Your business has been selected for Freebyz Business Promotion. This will last for 24hours';
            

        Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

        return back()->with('success', 'Business Selection successful!');

    }

    public function category(){
        $category = BusinessCategory::all();
        return view('admin.business.category', ['category' => $category]);
    }

    public function storeCategory(Request $request){
        BusinessCategory::create($request->all());
        return back()->with('success', 'Business Category created successfully!');
    }

    public function status($id){
        $business = Business::where('business_link', $id)->first();
        if($business->status == 'ACTIVE'){
            $business->status = 'PENDING';
            $business->save();
        }else{
            $business->status = 'ACTIVE';
            $business->save();
        }
        $user = User::where('id', $business->user_id)->first();
        $subject = 'Freebyz Business Promotion - Business '.$business->status;
        $content = 'Your business is now '.$business->status.' on Freebyz Business Promotion.';
            
        Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

        return back()->with('success', 'Business Status Updated successfully!');
    }

}