<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\SpinScore;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class SpinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() : View{
        return view('user.spin.index');
    }

    public function spinWheel(Request $request){
        // Define outcomes
        $prizes = [
            ['degreeOffset' => 0, 'score' => 20, 'prize' => 'You win ₦20'],
            ['degreeOffset' => 45, 'score' => 30, 'prize' => 'You win ₦30'],
            ['degreeOffset' => 90, 'score' => 40, 'prize' => 'You win ₦40'],
            ['degreeOffset' => 135, 'score' => 50, 'prize' => 'You win ₦50'],
            ['degreeOffset' => 180, 'score' => 60, 'prize' => 'You win ₦60'],
            ['degreeOffset' => 225, 'score' => 70, 'prize' => 'You win ₦70'],
            ['degreeOffset' => 270, 'score' => 10000, 'prize' => 'You win ₦10k'],
            ['degreeOffset' => 315, 'score' => 50000, 'prize' => 'You win ₦50k'],
        ];

        // Pre-determine the result (logic can be enhanced as per requirements)
        $resultIndex = array_rand($prizes);
        $result = $prizes[$resultIndex];

        $spin = SpinScore::create(['user_id' => auth()->user()->id, 'score' => $result['score'], 'prize' => $result['prize'], 'is_paid' => true]);
        if($spin){
            creditWallet(auth()->user(), baseCurrency(),$result['score']);
            
            PaymentTransaction::create([
                'user_id' => auth()->user()->id,
                'campaign_id' => '1',
                'reference' => time(),
                'amount' => $result['score'],
                'status' => 'successful',
                'currency' => baseCurrency(),
                'channel' => 'paystack',
                'type' => 'spin_wheel_prize',
                'description' => 'Spin the Wheel Prize',
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]);
        }

        return response()->json($result);
    }

    public function attempt(){
        // whereDate('created_at', $today)
        $check = SpinScore::where('user_id', auth()->user()->id)->whereDate('created_at', Carbon::today())->first();
        if($check){
            $count = 0;
        }else{
            $count = 1;
        }
        $balanceCheck = checkWalletBalance(auth()->user(), baseCurrency(), 10000);
        $data['count'] = $count;
        $data['balance'] = $balanceCheck;
        return response()->json($data);

    }
}
