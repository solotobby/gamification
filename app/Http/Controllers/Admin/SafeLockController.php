<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaystackHelpers;
use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Models\BankInformation;
use App\Models\PaymentTransaction;
use App\Models\SafeLock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SafeLockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $safeLock = SafeLock::where('is_paid', false)->orderBy('created_at', 'DESC')->get();//paginate(20);
        // $safeLockAll = SafeLock::where('is_paid', false)->get();
        return view('admin.safelock_mgt.index', ['safelocks' => $safeLock]);
    }

    // public function redeemSafeLock($id){

    //     return $id;

    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $getSafeLock = SafeLock::where('id', $id)->first();
         if($getSafeLock->status == 'Redeemed'){
            return back()->with('error', 'Safelock redeemed');
         }

         $user = User::where('id', $getSafeLock->user_id)->first();
         $getSafeLock->status = 'Redeemed';
         $getSafeLock->is_paid = true;
         $getSafeLock->save();

         PaymentTransaction::create([
             'user_id' => auth()->user()->id,
             'campaign_id' => 1,
             'reference' => time(),
             'amount' =>$getSafeLock->total_payment,
             'balance' => walletBalance($user->id),
             'status' => 'successful',
             'currency' => 'NGN',
             'channel' => 'paystack',
             'type' => 'safelock_redeemed',
             'tx_type' => 'Debit',
             'description' => 'Redeemed SafeLock'
         ]);

         $subject = 'Freebyz SafeLock Redeemed';
         $content = 'Your SafeLock has been redeemed successfully. A total amount of NGN '.$getSafeLock->total_payment.' has been sent to your account.';
        //  Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

         return back()->with('success', 'Safelock redeemed, and your account has been funded.');

        // $bankInfo = BankInformation::where('user_id', $getSafeLock->user_id)->first();
        // if($bankInfo){
        //      $transfter = transferFund((int)$getSafeLock->total_payment*100, $bankInfo->recipient_code, 'Freebyz SafeLock Redeemption');
        //     if($transfter['status'] == true){

        //         $getSafeLock->status = 'Redeemed';
        //         $getSafeLock->is_paid = true;
        //         $getSafeLock->save();

        //         PaymentTransaction::create([
        //             'user_id' => auth()->user()->id,
        //             'campaign_id' => 1,
        //             'reference' => time(),
        //             'amount' =>$getSafeLock->total_payment,
        //             'status' => 'successful',
        //             'currency' => 'NGN',
        //             'channel' => 'paystack',
        //             'type' => 'safelock_redeemed',
        //             'tx_type' => 'Debit',
        //             'description' => 'Redeemed SafeLock'
        //         ]);

        //         $subject = 'Freebyz SafeLock Redeemed';
        //         $content = 'Your SafeLock has been redeemed successfully. A total amount of NGN '.$getSafeLock->total_payment.' has been sent to your account.';
        //         Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

        //         return back()->with('success', 'Safelock redeemed, and your account has been funded.');

        //     }else{
        //         return back()->with('error', 'An error occoured, please try again');
        //     }
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
