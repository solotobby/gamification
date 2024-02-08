<?php

namespace App\Http\Controllers;

use App\Models\BloqAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BloqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function setupAccount(){
        $info = BloqAccount::where('user_id', auth()->user()->id)->first();
        return view('user.account.setup', ['info' => $info]);
    }

    public function setupAccountProcess(Request $request){
        //create customer
        //upgrate customer
        //create account
        //issue card

        $request->validate([
            'bvn' => ['required', 'numeric', 'digits:11'],
            'phone' => ['required', 'numeric', 'digits:11'],
            'picture' => ['required','image', 'mimes:png,jpeg,jpg'],
        ]);
       

        if($request->hasFile('picture')){
            $fileBanner = $request->file('picture');
            $Bannername = time() . $fileBanner->getClientOriginalName();
            $filePathBanner = 'proofs/' . $Bannername;
    
            Storage::disk('s3')->put($filePathBanner, file_get_contents($fileBanner), 'public');
            $proofUrl = Storage::disk('s3')->url($filePathBanner);

            $payload1 = [
                'email' => auth()->user()->email,
                'phone_number' => $request->phone,
                'first_name' => auth()->user()->name,
                'last_name' => 'Freebyz',
                'customer_type' => 'Personal',
                'bvn' => $request->bvn
             ];
    
             $customer = bloqCreateCustomer($payload1)['data'];

            $payload2 = [
                'place_of_birth' => 'lagos', 
                'dob' => '1992-03-30',
                'gender' => auth()->user()->gender,
                'address' => [
                    'street' => '10 Lagos Way',
                    'city' => 'Lekki',
                    'state' => 'Lagos',
                    'country' => 'Nigeria', 
                    "postal_code"=>"1000101"
                ],
                'image' => $proofUrl,
             ];
    
             $kyc1 = bloqUpgradeCustomerKYC1($payload2, $customer['id']);

             $payload3 = [
                "customer_id"=> $customer['id'],
                "preferred_bank"=> "Banc Corp",
                "alias"=> "Freebyz"
                // "collection_rules"=> [
                //     "amount"=> 30000,
                //     "frequency"=> 2
                // ]
            ];

             $bloqAccount = bloqCreateWallet($payload3)['data'];

          

            $bloq = BloqAccount::create([
                'user_id' => auth()->user()->id,
                'customer_id' => $customer['id'],
                'customer_name' => $bloqAccount['name'], 
                'account_id' => $bloqAccount['id'], 
                'balance' => $bloqAccount['balance'], 
                'account_number' => $bloqAccount['account_number'], 
                'bank_name' => $bloqAccount['bank_name'], 
                'currency' => 'NGN', 
                'provider' => 'bloq'
             ]);

             // $payload = [
                //     "customer_id"=> "65c2adf0cd5ee96c010dbec0",
                //     'brand' => 'MasterCard'
                // ];

                // return bloqIssueCard($payload);

             if($bloq){
                return back()->with('success', 'Account Setup Completed');
             }
        }
        
        // $bvnPayload = [
        //     "bvn"=> $request->bvn,
        //     "firstname"=> auth()->user()->name,
        //     "lastname"=> "freebyz",
        //     "redirect_url"=> "https://example-url.company.com"
        // ];
        // return bvnVerification($bvnPayload);
    }
}
