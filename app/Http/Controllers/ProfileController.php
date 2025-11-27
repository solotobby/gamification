<?php

namespace App\Http\Controllers;

use App\Helpers\PaystackHelpers;
use App\Helpers\SystemActivities;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\BankInformation;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'email']);
        // $this->middleware('auth');
    }

    // public function index()
    // {
    //     // return currentLocation();
    //     $bankList = bankList();

    //     //  return ['countries'=>$countries, 'banks'=>$bankList];
    //     @$bankInfo = BankInformation::where(
    //         'user_id',
    //         auth()->user()->id
    //     )->first();

    //     $badge = badge();
    //     return view('user.profile.profile', ['bankList' => $bankList, 'bankInfo' => $bankInfo, 'badge' => $badge]);
    // }

    public function index()
    {
        $bankList = bankList();
        $bankInfo = BankInformation::where('user_id', auth()->user()->id)->first();

        // Mask account number if exists
        if ($bankInfo) {
            $accountNumber = $bankInfo->account_number;
            $bankInfo->masked_account = substr($accountNumber, 0, 3) . '****' . substr($accountNumber, -2);
        }

        $badge = badge();
        return view('user.profile.profile', compact('bankList', 'bankInfo', 'badge'));
    }

   
    public function create()
    {
        //
    }


    public function store(StoreProfileRequest $request)
    {
        //
    }


    public function show(Profile $profile)
    {
        //
    }


    public function edit(Profile $profile)
    {
        //
    }


    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        //
    }


    public function destroy(Profile $profile)
    {
        //
    }

    public function welcomeUser()
    {
        auth()->user()->profile()->update([
            'is_welcome' => true
        ]);
        return redirect('home');
    }
}
