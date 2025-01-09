<?php

namespace App\Http\Controllers;

use App\Helpers\SystemActivities;
use App\Models\MembershipBadge;
use App\Models\PaymentTransaction;
use App\Models\Referral;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $badge = badge();
        return view('user.badge.index', ['badge' => $badge]); 
    }

    public function redeemBadge(){
        $badge = badge();
        $date = Carbon::now()->subMonth()->format('M, Y');
        if($badge['count'] < 10){
            return back()->with('error', 'You do not have up to 10 paid referral in '.$date);
        }

        $check = MembershipBadge::where('user_id', auth()->user()->id)->where('duration', $badge['duration'])->first();
        
        if($check){
            return back()->with('error', 'You already redeem membership badge bonus for '.$date);
        }
        creditWallet(auth()->user(), 'Naira', $badge['amount']);
        
        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => '1',
            'reference' => time(),
            'amount' => $badge['amount'],
            'balance' => walletBalance(auth()->user()->id),
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'membership_badge_bonus',
            'description' => $badge['badge'].' membership badge bonus',
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);

        MembershipBadge::create([
            'user_id' => auth()->user()->id,
            'amount' => $badge['amount'],
            'badge' => $badge['badge'],
            'duration' => $badge['duration']
        ]);
        return back()->with('success', $badge['badge'].' membership badge redeemed successfully');
    }
}
