<?php

namespace App\Http\Controllers;

use App\Models\MarketPlaceProduct;
use App\Models\User;
use Illuminate\Http\Request;


class MarketplaceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $list = MarketPlaceProduct::orderBy('created_at', 'ASC')->get();
        return view('user.marketplace.index', ['marketPlaceLists' => $list]);
    }
}
