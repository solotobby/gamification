<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        if(auth()->user()->hasRole('admin')){
            $currencies = Currency::orderBy('created_at', 'DESC')->get();
            return view('admin.currency.index', ['currencies' => $currencies]);
        }else{
            abort(403);
        }
       
    }
    public function updateCurrency(Request $request){
        $currency = Currency::where('id', $request->id)->first();
        $currency->base_rate = $request->base_rate;
        $currency->referral_commission = $request->referral_commission;
        $currency->upgrade_fee = $request->upgrade_fee;
        $currency->priotize = $request->priotize;
        $currency->allow_upload = $request->allow_upload;
        $currency->min_upgrade_amount = $request->min_upgrade_amount;
        $currency->save();

        return back()->with('success', 'Currency updated!');
    }

    public function baseRates(){

        return currencyConverter('XOF', 'GHS', 103);

        // $currencies = Currency::query()->get(['id', 'code', 'base_rate']);

        // // Initialize results array
        // $pairsWithRates = [];

        // foreach($currencies as $base){

        //     foreach($currencies as $target){
        //         // if($base !== $target){
        //             $rate = $target['base_rate'] / $base['base_rate'];
        //             $pairsWithRates[] = [
        //                 'from' => $base['code'],
        //                 'to' => $target['code'],
        //                 'rate' => $rate,
        //             ];
        //         // }
                
        //     }

        // }

        // return $pairsWithRates;

    }

    public function currencyPairRate($from, $to){

    }
    
}
