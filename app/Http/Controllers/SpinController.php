<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\SpinParams;
use App\Models\SpinScore;
use App\Models\SpinTracker;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        try{

            $user = Auth::user();
            $params = SpinParams::where('total_spins_allowed', '!=', '')->first();
            $userBalance = $user->wallet->balance;
            $dailyLimitSpins = $params->total_spins_allowed; // Max spins per day
            $dailyLimitPayout =$params->total_payouts_allowed; // Max total payout in Naira
            $highValuePrizes = [1000, 20000, 50000]; // High-value prizes
            $monthlyLimitPrizes = [50000, 200000, 1000]; // Prizes limited to once per month

            // Ensure the user has at least ₦10000
            if ($userBalance < 10000) {
                return response()->json(['error' => 'Insufficient balance to spin'], 403);
            }
        
            // Track daily spins and payouts
            $today = now()->toDateString();
            
            $spinTracker = SpinTracker::firstOrCreate(['date' => $today]);
        
            if ($spinTracker->total_spins >= $dailyLimitSpins) {
                return response()->json(['error' => true, 'message' =>'Daily spin limit reached'], 403);
            }
        
            if ($spinTracker->total_payout >= $dailyLimitPayout) {
                return response()->json(['error' => true, 'message' =>'Daily payout limit reached'], 403);
            }
    
            // Track already won high-value prizes this month
            $alreadyWonHighValue = SpinScore::where('user_id', $user->id)
                                            ->whereIn('score', $monthlyLimitPrizes)
                                            ->whereMonth('created_at', now()->month)
                                            ->exists();

        
            // Define outcomes
            $prizes = [
                ['degreeOffset' => 0, 'score' => 10, 'prize' => 'You win ₦10'],
                ['degreeOffset' => 45, 'score' => 15, 'prize' => 'You win ₦15'],
                ['degreeOffset' => 90, 'score' => 20, 'prize' => 'You win ₦20'],
                ['degreeOffset' => 135, 'score' => 25, 'prize' => 'You win ₦25'],
                ['degreeOffset' => 180, 'score' => 50, 'prize' => 'You win ₦50'],
                ['degreeOffset' => 225, 'score' => 1000, 'prize' => 'You win ₦1000'],
                ['degreeOffset' => 270, 'score' => 20000, 'prize' => 'You win ₦20k'],
                ['degreeOffset' => 315, 'score' => 50000, 'prize' => 'You win ₦50k'],
            ];


            // Filter prizes
            if ($alreadyWonHighValue) {
                $prizes = array_filter($prizes, function ($prize) use ($monthlyLimitPrizes) {
                    return !in_array($prize['score'], $monthlyLimitPrizes);
                });
            }

                // Select a prize
            $selectedPrize = $prizes[array_rand($prizes)];

            // Check daily payout limit
            if ($spinTracker->total_payout + $selectedPrize['score'] > $dailyLimitPayout) {
                return response()->json(['error' => 'Prize exceeds daily payout limit'], 403);
            }

            // Log the prize
            if (in_array($selectedPrize['score'], $monthlyLimitPrizes)) {
                SpinScore::create([
                    'user_id' => $user->id,
                    'score' => $selectedPrize['score'],
                    'prize' => $selectedPrize['prize'],
                    'is_paid' => true,
                    'is_high_prize' => true
                ]);
            }else{
                SpinScore::create([
                    'user_id' => $user->id,
                    'score' => $selectedPrize['score'],
                    'prize' => $selectedPrize['prize'],
                    'is_paid' => true
                ]);
            }

           //$check = in_array($selectedPrize['score'], $monthlyLimitPrizes);

            // Update spin tracker
            $spinTracker->increment('total_spins');
            $spinTracker->increment('total_payout', $selectedPrize['score']);

        }catch(Exception $exception){
            return response()->json(['status' => false,  'error'=> $exception->getMessage(), 'message' => 'Error processing request'], 500);
  
        }

        // Return prize
        return response()->json([
            'degreeOffset' => $selectedPrize['degreeOffset'],
            'prize' => $selectedPrize['prize'],
            // 'prize_check' => $check,
        ]);
        
     

    



        // // Define outcomes
        // $prizes = [
        //     ['degreeOffset' => 0, 'score' => 10, 'prize' => 'You win ₦10'],
        //     ['degreeOffset' => 45, 'score' => 15, 'prize' => 'You win ₦15'],
        //     ['degreeOffset' => 90, 'score' => 20, 'prize' => 'You win ₦20'],
        //     ['degreeOffset' => 135, 'score' => 25, 'prize' => 'You win ₦25'],
        //     ['degreeOffset' => 180, 'score' => 50, 'prize' => 'You win ₦50'],
        //     ['degreeOffset' => 225, 'score' => 1000, 'prize' => 'You win ₦1000'],
        //     ['degreeOffset' => 270, 'score' => 20000, 'prize' => 'You win ₦20k'],
        //     ['degreeOffset' => 315, 'score' => 50000, 'prize' => 'You win ₦50k'],
        // ];

        // Pre-determine the result (logic can be enhanced as per requirements)
        // $resultIndex = array_rand($prizes);
        
        // $result = $prizes[$resultIndex];

        // $spin = SpinScore::create(['user_id' => auth()->user()->id, 'score' => $result['score'], 'prize' => $result['prize'], 'is_paid' => true]);
        // if($spin){
        //     creditWallet(auth()->user(), baseCurrency(),$result['score']);
            
        //     PaymentTransaction::create([
        //         'user_id' => auth()->user()->id,
        //         'campaign_id' => '1',
        //         'reference' => time(),
        //         'amount' => $result['score'],
        //         'status' => 'successful',
        //         'currency' => baseCurrency(),
        //         'channel' => 'paystack',
        //         'type' => 'spin_wheel_prize',
        //         'description' => 'Spin the Wheel Prize',
        //         'tx_type' => 'Credit',
        //         'user_type' => 'regular'
        //     ]);
        // }

        // return response()->json($result);
    }

    public function attempt(){
        
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

    public function adminSpinner(){
       $spinTracker = SpinTracker::all();//pluck('id', 'date', 'total_spins', 'total_payout');
       $param = SpinParams::where('total_spins_allowed', '!=', '')->first();
        return view('admin.spin.create', ['spinTracker' => $spinTracker, 'param' => $param]);
    }

    public function store(Request $request){
        $params = SpinParams::where('total_spins_allowed', '!=', '')->delete();
        SpinParams::create(['total_spins_allowed' => $request->total_spins_allowed, 'total_payouts_allowed' => $request->total_payouts_allowed]);
        return back()->with('success', 'Paramed Updated successfully');
    }
}
