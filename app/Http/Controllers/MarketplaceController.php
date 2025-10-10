<?php

namespace App\Http\Controllers;

use App\Models\MarketPlaceProduct;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MarketplaceController extends Controller
{

    public function __construct()
    {
         $this->middleware(['auth', 'email']);
        // $this->middleware('auth');
    }

    public function index(){
        $list = MarketPlaceProduct::orderBy('created_at', 'DESC')->get();
        return view('user.marketplace.index', ['marketPlaceLists' => $list]);
    }
    public function createProduct(){
        $product_type = ProductType::all();
        return view('user.marketplace.create', ['product_type' => $product_type]);
    }

    public function storeProduct(Request $request){
        //return $request;
        $this->validate($request, [
            'banner' => 'image|mimes:png,jpeg,gif,jpg',
            // 'product' => 'mimes:mp3,mpeg,mp4,3gp,pdf',
            'name' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        //&& $request->hasFile('product')
        if($request->hasFile('banner')){

            $fileBanner = $request->file('banner');
            // $fileProduct = $request->file('product');

            $Bannername = time() . $fileBanner->getClientOriginalName();
            // $Productname = time() . $fileProduct->getClientOriginalName();

            $filenameExtensionBanner = $fileBanner->getClientOriginalExtension();
            // $filenameExtensionProduct = $fileProduct->getClientOriginalExtension();

            $filePathBanner = 'banners/' . $Bannername;
            // $filePathProduct = 'products/' . $Productname;

            $storeBanner = Storage::disk('s3')->put($filePathBanner, file_get_contents($fileBanner), 'public');
            $bannerUrl = Storage::disk('s3')->url($filePathBanner);


            // $storeProduct = Storage::disk('s3')->put($filePathProduct, file_get_contents($fileProduct), 'public');
            // $prodductUrl = Storage::disk('s3')->url($filePathProduct);

            $data['user_id'] = auth()->user()->id;
            $data['name'] = $request->name;
            $data['amount'] = $request->amount;
            $data['commission'] = $request->commission;
            $data['total_payment'] = $request->total_payment;
            $data['type'] = 'electronic';
            $data['commission_payment'] = $request->commission_payment;
            $data['banner'] = $bannerUrl;
            $data['views'] = '0';
            $data['product_id'] = Str::random(7);
            $data['product'] = $request->product;//$prodductUrl;
            $data['description'] = $request->description;
            MarketPlaceProduct::create($data);
            return back()->with('success', 'Product Successfully created');
        }else{
            return back()->with('error', 'Please upload an image');
        }
    }
    public function myProduct(){
        $lists = MarketPlaceProduct::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        return view('user.marketplace.myproduct', ['lists' => $lists]);
    }
}


