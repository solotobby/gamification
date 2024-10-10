<?php

namespace App\Http\Controllers;

use App\Mail\GeneralMail;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PromoteBusinessController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function create(){
       // ProfessionalCategory::orderBy('name', 'DESC')->get();;
       $business = Business::where('user_id', auth()->user()->id)->first();
       $categories = BusinessCategory::all();
        return view('user.business.create', ['business' => $business, 'categories' => $categories]);

    }

    public function store(Request $request){
        $this->validate($request, [
            'business_name' => 'required|string|unique:businesses',
            'business_phone' => 'required|string|unique:businesses',
            'description' => 'required|string',
            // 'x_link' => 'string',
            // 'facebook_link' => 'string',
            // 'instagram_link' => 'string',
            // 'tiktok_link' => 'string',
            // 'pinterest_link' => 'string',
           
        ]);
        $request->request->add(['user_id' => auth()->user()->id, 'status' => 'PENDING', 'business_link' => Str::random(7)]);
        
        $business = Business::create($request->all());

        if($business){
            $subject = 'Freebyz Business Promotion - Business Created Successfully';
            $content = 'Your business will soon be activated. Once activated, you can add products and share your links freely on your social media.';
            
            Mail::to(auth()->user()->email)->send(new GeneralMail(auth()->user(), $content, $subject, ''));

            return back()->with('success', 'Business Created Successfully');
        }

       
    }

    public function createProduct(Request $request){
        $this->validate($request, [
            'img' => 'image|mimes:png,jpeg,gif,jpg',
            'name' => 'required|string',
            'price' => 'required|string',
            'description' => 'required|string',
        ]);
        

        $proofUrl = '';
            if($request->hasFile('img')){
                $fileBanner = $request->file('img');
                $Bannername = time() . $fileBanner->getClientOriginalName();
                $filePathBanner = 'products/' . $Bannername;
        
                Storage::disk('s3')->put($filePathBanner, file_get_contents($fileBanner), 'public');
                $productfUrl = Storage::disk('s3')->url($filePathBanner);
                $request->request->add(['img' =>$productfUrl]);
        
                BusinessProduct::create($request->all());

                return back()->with('success', 'Product added Successfully');
            }
           
    }

}
