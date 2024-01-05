<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AfricaTalkingHandlers;
use App\Helpers\Analytics;
use App\Helpers\CapitalSage;
use App\Helpers\FacebookHelper;
use App\Helpers\PaystackHelpers;
use App\Helpers\Sendmonny;
use App\Helpers\SystemActivities;
use App\Http\Controllers\Controller;
use App\Jobs\SendMassEmail;
use App\Mail\ApproveCampaign;
use App\Mail\GeneralMail;
use App\Mail\MassMail;
use App\Mail\UpgradeUser;
use App\Models\BankInformation;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\DataBundle;
use App\Models\Games;
use App\Models\MarketPlaceProduct;
use App\Models\PaymentTransaction;
use App\Models\ProductType;
use App\Models\Profile;
use App\Models\Question;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\Usdverified;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\UserScore;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use App\Models\Withrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createGame()
    {
        $user = auth()->user();

        if($user->hasRole('admin'))
        {
            //$game = Games::find($id);
            return view('admin.create_game');
        }
    }

    public function createQuestion()
    {
        $questions = Question::all();
        return view('admin.add_question', ['question' => $questions]);
    }

    publiC function storeQuestion(Request $request)
    {   
        $question = Question::create($request->all());
        $question->save();

        $get_answer = DB::table('questions')->select($request->correct_answer)->latest()->first();
        $collect = collect($get_answer);
        $value = $collect->shift();
        $question->update(['correct_answer' => $value]);

        return back()->with('status', 'Question Created Successfully');
    }

    public function updateQuestion(Request $request)
    {
        $question = Question::where('id', $request->id)->first();
        $question->content = $request->content;
        $question->option_A = $request->option_A;
        $question->option_B = $request->option_B;
        $question->option_C = $request->option_C;
        $question->option_D = $request->option_D;
        $question->correct_answer = $request->correct_answer;
        $collect = collect($question);
        $value = $collect->shift();
        $question->correct_answer = $value;
        $question->save();

        return back()->with('status', 'Question Updated Successfully');
        
    }

    public function gameStatus($id)
    {

        $game = Games::where('id', $id)->first();
        if($game->status == '1'){
            $game->status = '0';
            $game->save();
        }else{
            $game->status = '1';
            $game->save();
        }

         return back()->with('status', 'Status Changed Successfully');

    }

    public function gameCreate()
    {
        $user = auth()->user();

        if($user->hasRole('admin'))
        {
            //$game = Games::find($id);
            return view('admin.create_game');
        }
    }

    public function gameStore(Request $request)
    {
        $slug = Str::slug($request->name);
        $game = Games::create([
            'name' => $request->name, 
            'type' => $request->type, 
            'number_of_winners' => $request->number_of_winners, 
            'slug' => $slug, 
            'time_allowed' => 0.25,//$request->time_allowed, 
            'number_of_questions'=>$request->number_of_questions,
            'status' => 1
        ]);
        return back()->with('status', 'Game Created Successfully');

    }

    public function updateAmount(Request $request)
    {
        $reward = Reward::where('id', $request->id)->first();
        $reward->name = $request->name;
        $reward->amount = $request->amount;
        $reward->save();
        return back()->with('status', 'Amount updated Successfully');
    }

    public function viewAmount()
    {
        $reward = Reward::all();
        return view('admin.update_amount', ['rewards' => $reward]);
    }

    public function listQuestion()
    {
        $questions = Question::orderBy('created_at', 'desc')->paginate('200');
        $question_count = Question::all()->count();
        return view('admin.question_list', ['questions' => $questions, 'question_count' => $question_count]);
    }

    public function viewActivities($id)
    {
        $game = Games::where('id', $id)->first();
        $activities = UserScore::where('game_id', $id)->orderBy('score', 'desc')->get();

        return view('admin.game_activities', ['activities' => $activities, 'game' => $game]);
    }

    public function assignReward(Request $request)
    {
        if(empty($request->id))
        {
             return back()->with('error', 'Please Select A Score');
        }
        $reward = Reward::where('name', $request->name)->first()->amount;
        $formattedReward = number_format($reward,2);
        foreach($request->id as $id)
        {
            $score = UserScore::where('id', $id)->first();
            $score->reward_type = $request->name;
            $score->save();

            $phone = '234'.substr($score->user->phone, 1);
            $message = "Hello ".$score->user->name. " you have a ".$request->name." reward of ".$formattedReward." from Freebyz.com. Please login to cliam it. Thanks";
            PaystackHelpers::sendNotificaion($phone, $message);
        }
        return back()->with('status', 'Reward Assigned Successfully');
    }

    public function campaignMetrics(){
        //  $metrics = Analytics::campaignMetrics();
        $dashbordMetrics = Analytics::dashboardAnalytics();
        return view('admin.campaign_metric.index', ['analytics' => $dashbordMetrics]);
    }

    public function campaignDisputes(){
         $disputes = CampaignWorker::where('status', 'In-dispute')->orderBy('created_at', 'DESC')->paginate(100);
        return view('admin.campaign_mgt.disputes', ['disputes' => $disputes]);
    }

    public function campaignDisputesView($id){
        $workdisputed = CampaignWorker::where('id', $id)->first();
        return view('admin.campaign_mgt.view_dispute', ['campaign' => $workdisputed]);
    }

    public function campaignDisputesDecision(Request $request){
        $workDone = CampaignWorker::where('id', $request->id)->first();

        if($request->status == 'Approved'){
            $campaignInfo = Campaign::where('id', $workDone->campaign_id)->first();
            $approvedJob = $campaignInfo->completed()->where('status', 'Approved')->count();

            if($approvedJob >= $campaignInfo->number_of_staff){
                return back()->with('error', 'Job has reached its maximum number of staff');
            }

            $wallet = Wallet::where('user_id', $workDone->user_id)->first();
            $wallet->balance += $workDone->amount;
            $wallet->save();

            $workDone->status = $request->status;
            $workDone->reason = $request->reason;
            $workDone->save();

        }else{
            $workDone->status = $request->status;
            $workDone->reason = $request->reason;
            $workDone->save();
        }

        $subject = 'Job '.$request->status;
        $status = $request->status;
        Mail::to($workDone->user->email)->send(new ApproveCampaign($workDone, $subject, $status));
        return back()->with('success', 'Campaign Dispute Successful');
    }

    public function userList(){
        $users = User::where('role', 'regular')->orderBy('id', 'DESC')->paginate(500);
        return view('admin.users.list', ['users' => $users]);
    }

    public function userSearch(Request $request){
        if(isset($request)){
            $users = User::where([
                [function ($query) use ($request) {
                    if (($search = $request->q)) {
                        $query->orWhere('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%')
                            ->orWhere('phone', 'LIKE', '%' . $search . '%')
                            ->orWhere('referral_code', 'LIKE', '%' . $search . '%')
                            ->get();
                    }
                }]
            ])->get();
        }
        return view('admin.users.search_result', ['users' => $users]);
    }


    public function verifiedUserList(){
        $verifiedUsers = User::where('role', 'regular')->where('is_verified', '1')->orderBy('created_at', 'desc')->get();
        return view('admin.verified_user', ['verifiedUsers' => $verifiedUsers]);
    }
    public function usdVerifiedUserList(){
        $verifiedUsers = Usdverified::all(); //User::with(['USD_verifiedList'])->get(); //User::where('role', 'regular')->where('is_verified', '1')->orderBy('created_at', 'desc')->get();
        return view('admin.users.usd_verified', ['verifiedUsers' => $verifiedUsers]);
    }

    public function adminTransaction(){
        $list = PaymentTransaction::where('user_type', 'admin')->where('status', 'successful')->orderBy('created_at', 'DESC')->paginate(50);
        return view('admin.admin_transactions', ['lists' => $list]);
    }
    public function userTransaction(){
        $list = PaymentTransaction::where('user_type', 'regular')->where('status', 'successful')->orderBy('created_at', 'DESC')->paginate(50);
        return view('admin.users.user_transactions', ['lists' => $list]);
    }

    public function userInfo($id){
        $info = User::where('id', $id)->first();
        @$user = Referral::where('user_id', $id)->first()->referee_id;
        @$referredBy = User::where('id', $user)->first();
        $bankList = PaystackHelpers::bankList();
        return view('admin.users.user_info', ['info' => $info, 'referredBy' => $referredBy, 'bankList' => $bankList]);
    }

    public function adminUserReferrals($id){
        $user = User::where('id', $id)->first();
        $ref = $user->referees()->paginate(50);
        return view('admin.users.referrals', ['ref' => $ref, 'user' => $user]);
    }

    public function withdrawalRequest(){
        $withdrawal = Withrawal::where('status', '1')->orderBy('created_at', 'DESC')->paginate(50);
        return view('admin.withdrawals.sent', ['withdrawals' => $withdrawal]);
    }
    public function withdrawalRequestQueued(){
        $withdrawal = Withrawal::where('status', '0')->orderBy('created_at', 'DESC')->paginate(50);
        return view('admin.withdrawals.queued', ['withdrawals' => $withdrawal]);
    }

    public function upgradeUser($id){
        if(auth()->user()->hasRole('admin')){

                $getUser = User::where('id', $id)->first();
                if($getUser->wallet->base_currency == 'Naira'){

                    $getUser->is_verified = 1;
                    $getUser->save();

                    $ref = time();
                    PaymentTransaction::create([
                        'user_id' => $getUser->id,
                        'campaign_id' => '1',
                        'reference' => $ref,
                        'amount' => 1000,
                        'status' => 'successful',
                        'currency' => 'NGN',
                        'channel' => 'paystack',
                        'type' => 'upgrade_payment',
                        'description' => 'Manual Ugrade Payment'
                    ]);

                    $referee = Referral::where('user_id',  $getUser->id)->first();
                    
                    if($referee){

                        $refereeInfo = Profile::where('user_id', $referee->referee_id)->first()->is_celebrity;

                        if(!$refereeInfo){
                        
                            $wallet = Wallet::where('user_id', $referee->referee_id)->first();
                            $wallet->balance += 500;
                            $wallet->save();

                            $refereeUpdate = Referral::where('user_id', $getUser->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
                            $refereeUpdate->is_paid = 1;
                            $refereeUpdate->save();

                            $referee_user = User::where('id', $referee->referee_id)->first();
                            ///Transactions
                            PaymentTransaction::create([
                                'user_id' => $referee_user->id,///auth()->user()->id,
                                'campaign_id' => '1',
                                'reference' => $ref,
                                'amount' => 500,
                                'status' => 'successful',
                                'currency' => 'NGN',
                                'channel' => 'paystack',
                                'type' => 'referer_bonus',
                                'description' => 'Referer Bonus from '.auth()->user()->name
                            ]);

                            $adminWallet = Wallet::where('user_id', '1')->first();
                            $adminWallet->balance += 500;
                            $adminWallet->save();
                            //Admin Transaction Table
                            PaymentTransaction::create([
                                'user_id' => 1,
                                'campaign_id' => '1',
                                'reference' => $ref,
                                'amount' => 500,
                                'status' => 'successful',
                                'currency' => 'NGN',
                                'channel' => 'paystack',
                                'type' => 'referer_bonus',
                                'description' => 'Referer Bonus from '.$getUser->name,
                                'tx_type' => 'Credit',
                                'user_type' => 'admin'
                            ]);

                        }else{
                            $refereeUpdate = Referral::where('user_id', auth()->user()->id)->first(); //\DB::table('referral')->where('user_id',  auth()->user()->id)->update(['is_paid', '1']);
                            $refereeUpdate->is_paid = true;
                            $refereeUpdate->save();
                        }

                    }else{

                        $adminWallet = Wallet::where('user_id', '1')->first();
                        $adminWallet->balance += 1000;
                        $adminWallet->save();
                        //Admin Transaction Tablw
                        PaymentTransaction::create([
                            'user_id' => 1,
                            'campaign_id' => '1',
                            'reference' => $ref,
                            'amount' => 1000,
                            'status' => 'successful',
                            'currency' => 'NGN',
                            'channel' => 'paystack',
                            'type' => 'direct_referer_bonus',
                            'description' => 'Direct Referer Bonus from '.$getUser->name,
                            'tx_type' => 'Credit',
                            'user_type' => 'admin'
                        ]);
                    
                    }


                }else{
                   
                    Usdverified::create(['user_id'=> $getUser->id]);

                    $ref = time();
                    PaymentTransaction::create([
                        'user_id' => $getUser->id,
                        'campaign_id' => '1',
                        'reference' => $ref,
                        'amount' => 5,
                        'status' => 'successful',
                        'currency' => 'USD',
                        'channel' => 'paypal',
                        'type' => 'upgrade_payment',
                        'description' => 'Manual Ugrade Payment - USD'
                    ]);
    
                }
            systemNotification(Auth::user(), 'success', 'User Verification',  $getUser->name.' was manually verified');
            Mail::to($getUser->email)->send(new UpgradeUser($getUser));
            return back()->with('success', 'Upgrade Successful');
        }else{
            return back()->with('error', 'Unathorised user, only admin can upgrade people');
        }
    }

    public function campaignList(){
        $campaigns = Campaign::where('status', 'Live')->orderBy('created_at', 'DESC')->paginate(30);
        return view('admin.campaign_list', ['campaigns' => $campaigns]);
    }

    public function unapprovedJobs(){
        
        $currentTime = Carbon::now();
        $twentyFourHoursAgo = $currentTime->subHours(24);

        $list = CampaignWorker::where('status', 'Pending')
        ->whereDate('created_at', '<=', $twentyFourHoursAgo)->paginate(200);
        // ->orderBy('created_at', 'DESC')->paginate(200);

        //  $list = SystemActivities::availableJobs();

        return view('admin.unapproved_list', ['campaigns' => $list]); 
    }

    public function campaignInfo($id){
        $campaign = Campaign::where('id', $id)->first();
        $activities = $campaign->attempts;
        return view('admin.campaign_mgt.info', ['campaign' => $campaign, 'activities' => $activities]);
    }

    public function campaignDelete($id){
        Campaign::where('id', $id)->delete();
        return redirect('campaigns/pending'); //redirect()->with('success', 'Campaign Deleted Successfully');
        
       // return view('admin.campaign_mgt.info', ['campaign' => $campaign, 'activities' => $activities]);
    }


    public function approvedJobs(){
        $list = CampaignWorker::where('status', 'Approved')->orderBy('created_at', 'DESC')->paginate(200);
        return view('admin.approved_list', ['campaigns' => $list]); 
    }
    public function deniedCampaigns(){
        $list = Campaign::where('status', 'Decline')->orderBy('created_at', 'DESC')->get();
        return view('admin.denied_list', ['campaigns' => $list]); 
    }

    public function jobReversal($id){
        $list = CampaignWorker::where('id', $id)->first();
        $list->status = 'Pending';
        $list->save();

        $campaignAmount = Campaign::where('id', $list->campaign_id)->first(); //get campaign information
        //debit worker
        $wallet = Wallet::where('user_id', $list->user_id)->first();
        $wallet->balance -= $campaignAmount->campaign_amount;
        $wallet->save();

        //credit campaigner
        $wallet = Wallet::where('user_id', $campaignAmount->user_id)->first();
        $wallet->balance += $campaignAmount->campaign_amount;
        $wallet->save();

        $ref = time();

        PaymentTransaction::create([
            'user_id' => $list->user_id,
            'campaign_id' => $campaignAmount->id,
            'reference' => $ref,
            'amount' => $campaignAmount->campaign_amount,
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'campaign_job_reversal',
            'description' => 'Reversal of job revenue on '.$campaignAmount->post_title,
            'tx_type' => 'Debit',
            'user_type' => 'regular'
        ]);

        PaymentTransaction::create([
            'user_id' =>  $campaignAmount->user_id,
            'campaign_id' => $campaignAmount->id,
            'reference' => $ref,
            'amount' => $campaignAmount->campaign_amount,
            'status' => 'successful',
            'currency' => 'NGN',
            'channel' => 'paystack',
            'type' => 'campaign_job_reversal_credit',
            'description' => 'Reversal of amount spent on '.$campaignAmount->post_title,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);

        $user = User::where('id', $campaignAmount->user_id)->first();
        $subject = 'Job Reversal';
        $content = 'Your request to for job reversal is successful. A total of NGN' .$campaignAmount->campaign_amount . ' has been credited to your wallet from '.$campaignAmount->post_title.' job';
        Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
        return back()->with('success', 'Reversal Successful');
    }

    public function changeCompleted($id){
        $camp = Campaign::where('id', $id)->first();
        if($camp->is_completed == true){
            $camp->is_completed = false;
            $camp->save();
        }else{
            $camp->is_completed = true;
            $camp->save();
        }
        return back()->with('success', 'Completed status changed!');
    }


    public function massApproval(Request $request){

       $ids = $request->id;
       if(empty($ids)){
        return back()->with('error', 'Please select at least one item');
       }

       foreach($ids as $id){
        $ca = CampaignWorker::where('id', $id)->first();
        $ca->status = 'Approved';
        $ca->save();

        $camp = Campaign::where('id', $ca->campaign_id)->first();
        $camp->completed_count += 1;
        $camp->pending_count -= 1;
        $camp->save();
        
        if($camp->currency == 'NGN'){
            $currency = 'NGN';
            $channel = 'paystack';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->balance += $ca->amount;
            $wallet->save();
        }else{
            $currency = 'USD';
            $channel = 'paypal';
            $wallet = Wallet::where('user_id', $ca->user_id)->first();
            $wallet->usd_balance += $ca->amount;
            $wallet->save();
        }

       
        $ref = time();

        setIsComplete($ca->campaign_id);

        PaymentTransaction::create([
            'user_id' => $ca->user_id,
            'campaign_id' => '1',
            'reference' => $ref,
            'amount' => $ca->amount,
            'status' => 'successful',
            'currency' => $currency,
            'channel' => $channel,
            'type' => 'campaign_payment',
            'description' => 'Campaign Payment for '.$ca->campaign->post_title,
            'tx_type' => 'Credit',
            'user_type' => 'regular'
        ]);

    //    $subject = 'Job Approved';
    //    $status = 'Approved';
    //    Mail::to($ca->user->email)->send(new ApproveCampaign($ca, $subject, $status));

       }
       return back()->with('success', 'Mass Approval Successful');

    }

    public function massMail(){
        return view('admin.mass_mail');
    }

    public function sendMassMail(Request $request){
        if($request->type == 'all'){
            $users = User::where('is_verified', 0)->where('role', 'regular')->pluck('phone')->toArray();
        }else{
            $users = User::where('is_verified', 1)->where('role', 'regular')->pluck('phone')->toArray();
        }
        // return $users;

        $message = $request->message;
        $subject = $request->subject;
        $number = $users;

        // foreach($users as $user){
        //     dispatch(new SendMassEmail($user, $message, $subject));
        // }

        // $list = [];
        // foreach($users as $key => $value){
        //     $value['phone'];
        //     //$list[++$key] = ['234'.substr($value->phone, 1)];//$value->phone];
        // }
        // return $list;

        $process = PaystackHelpers::sendBulkSMS($number, $message);
        if($process['code'] == 'ok'){
            return back()->with('success', 'SMS Sent Successful');
        }else{
            return back()->with('error', 'There was an error in transit');
        } 
    }

    public function campaignPending(){
        $pendingCampaign = Campaign::orderBy('created_at', 'DESC')->where('status', 'Offline')->orderBy('created_at', 'DESC')->get();
        return view('admin.pending_campaigns', ['campaigns' => $pendingCampaign]);
    }

    public function campaignStatus(Request $request){
        
         $camp = Campaign::where('id', $request->id)->first();//find($request->id);

        if($request->status == 'Decline'){
            // return $status;
            $amount = $camp->total_amount;
            $camp->status = $request->status;
            $camp->save();

            //reverse the money
            $userWallet = Wallet::where('user_id', $camp->user_id)->first();
            $userWallet->balance += $amount;
            $userWallet->save(); 

            $est_amount = $camp->number_of_staff * $camp->campaign_amount;
            $percent = (50 / 100) * $est_amount;
            $adminCom = $est_amount - $percent;

            $adminWallet = Wallet::where('user_id', '1')->first();
            $adminWallet->balance -= $adminCom;
            $adminWallet->save(); 
            
            PaymentTransaction::create([
                'user_id' => $camp->user_id,
                'campaign_id' => $camp->id,
                'reference' => time(),
                'amount' => $amount,
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'paystack',
                'type' => 'campaign_reversal',
                'description' => 'Campaign Reversal for '.$camp->post_title,
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]); 
            $user = User::where('id', $camp->user_id)->first();
            $content = 'Reason: '.$request->reason.'.';
            $subject = 'Campaign Declined';
            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));     
        }else{
            $camp->status = $request->status;
            $camp->post_title = $request->post_title;
            $camp->post_link = $request->post_link;
            $camp->description = $request->description;
            $camp->proof = $request->proof;
            $camp->save();
            $user = User::where('id', $camp->user_id)->first();
            $content = 'Your campaign has been approved and it is now Live. Thank you for choosing Freebyz.com';
            $subject = 'Campaign Live!!!';
            //Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
        }
        return back()->with('success', 'Campaign Successfully '.$request->status);
    }

    public function marketplaceCreateProduct(){
        $product_type = ProductType::all();
        return view('admin.market_place.create', ['product_type' => $product_type]);
    }

    public function storeMarketplace(Request $request){
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

    public function viewMarketplace(){
        $list = MarketPlaceProduct::orderBy('created_at', 'ASC')->get();
        return view('admin.market_place.view', ['marketPlaceLists' => $list]);
    }

    public function updateWithdrawalRequest($id){
       $withdrawals = Withrawal::where('id', $id)->first();
       
       if($withdrawals->status == '0'){
            $user = User::where('id', $withdrawals->user->id)->first();
            $bankInformation = BankInformation::where('user_id', $withdrawals->user->id)->first();
            $transfer = $this->transferFund($withdrawals->amount*100, $bankInformation->recipient_code, 'Freebyz Withdrawal');
            if($transfer['data']['status'] == 'success' || $transfer['data']['status'] == 'pending'){
                $withdrawals->status = true;
                $withdrawals->save();
                //set activity log
                $am = number_format($withdrawals->amount*100);
                
                $name = SystemActivities::getInitials($user->name);
                SystemActivities::activityLog($user, 'withdrawal_sent', 'NGN'.$am.' cash withdrawal by '.$name, 'regular');
                //send mail
                $content = 'Your withdrawal request has been granted and your acount credited successfully. Thank you for choosing Freebyz.com';
                $subject = 'Withdrawal Request Granted';
                Mail::to($withdrawals->user->email)->send(new GeneralMail($user, $content, $subject, ''));
                return back()->with('success', 'Withdrawals Updated');
            }else{
                return back()->with('error', 'Withdrawals Error');
            }
       }else{
            return back()->with('error', 'Payment has already been processed');
       }
       
    }

    public function updateWithdrawalRequestManual($id){
        $withdrawals = Withrawal::where('id', $id)->first();
        $withdrawals->status = true;
        $withdrawals->save();
        $user = User::where('id', $withdrawals->user->id)->first();

        //set activity log
        $am = number_format($withdrawals->amount);
        $name = SystemActivities::getInitials($user->name);
        SystemActivities::activityLog($user, 'withdrawal_sent', 'NGN'.$am.' cash withdrawal by '.$name, 'regular');

        $content = 'Your withdrawal request has been granted and your acount credited successfully. Thank you for choosing Freebyz.com';
        $subject = 'Withdrawal Request Granted';
        Mail::to($withdrawals->user->email)->send(new GeneralMail($user, $content, $subject, ''));
        return back()->with('success', 'Withdrawals Updated');
    }


    public function transferFund($amount, $recipient, $reason)
    {
           return PaystackHelpers::transferFund($amount, $recipient, $reason);
    }

    public function removeMarketplaceProduct($product_id){
        $productInfo = MarketPlaceProduct::where('product_id', $product_id)->first();
        $excludedUrl = explode('https://freebyz.s3.us-east-1.amazonaws.com/banners/', $productInfo->banner);
        $bannerName = $excludedUrl[1];
        Storage::disk('s3')->delete('banners/'.$bannerName);
        $productInfo->delete();
        // return Storage::disk('s3')->download('banners/'.$bannerName);
        return back()->with('success', 'Product removed Successfully');
    }

    public function createDatabundles(){
        $databundles = DataBundle::orderby('name', 'ASC')->get();
        return view('admin.databundles.index', ['databundles' => $databundles]);
    }

    public function storeDatabundles(Request $request){
        $created = DataBundle::create($request->all());
        $created->save();
        return back()->with('success', 'Databundle Created Successfully');
    }

    public function adminWalletTopUp(Request $request){
        $user = auth()->user();
        if($user->hasRole('admin')){
            if($request->type == 'credit'){
                    $currency = '';
                    $channel = '';
                    if($request->currency == 'NGN'){
                        $currency = 'NGN';
                        $channel = 'paystack';
                        $wallet = Wallet::where('user_id', $request->user_id)->first(); 
                        $wallet->balance += $request->amount;
                        $wallet->save();
                    }else{
                        $currency = 'USD';
                        $channel = 'paypal';
                        $wallet = Wallet::where('user_id', $request->user_id)->first(); 
                        $wallet->usd_balance += $request->amount;
                        $wallet->save();
                    }
                
                    PaymentTransaction::create([
                        'user_id' => $request->user_id,
                        'campaign_id' => '1',
                        'reference' => time(),
                        'amount' => $request->amount,
                        'status' => 'successful',
                        'currency' => $currency,
                        'channel' => $channel,
                        'type' => 'wallet_topup',
                        'description' => 'Manual Wallet Topup',
                        'tx_type' => 'Credit',
                        'user_type' => 'regular'
                    ]);

                    // PaystackHelpers::paymentTrasanction($request->user_id, '1', time(), $request->amount, 'successful', 'wallet_topup', 'Manual Wallet Topup', 'Credit', 'regular');
                    $content = 'Your walet has been succesfully credited with NGN'.$request->amount.'. Thank you for choosing Freebyz.com';
                    $subject = 'Wallet Topup';
                    $user = User::where('id', $request->user_id)->first();
                    Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));
                    return back()->with('success', 'Wallet Successfully Funded');
            }else{
                    $currency = '';
                    $channel = '';
                    if($request->currency == 'NGN'){
                        $currency = 'NGN';
                        $channel = 'paystack';
                        $wallet = Wallet::where('user_id', $request->user_id)->first(); 
                        $wallet->balance -= $request->amount;
                        $wallet->save();
                    }else{
                        $currency = 'USD';
                        $channel = 'paypal';
                        $wallet = Wallet::where('user_id', $request->user_id)->first(); 
                        $wallet->usd_balance -= $request->amount;
                        $wallet->save();
                    }
                
                    PaymentTransaction::create([
                        'user_id' => $request->user_id,
                        'campaign_id' => '1',
                        'reference' => time(),
                        'amount' => $request->amount,
                        'status' => 'successful',
                        'currency' => $currency,
                        'channel' => $channel,
                        'type' => 'wallet_debit',
                        'description' => 'Admin manual Wallet Debit',
                        'tx_type' => 'Debit',
                        'user_type' => 'regular'
                    ]);
                    return back()->with('success', 'Wallet Successfully Debitted');
            } 
        }else{
            echo "You are not suppose to be doing this!";
        }
        
    }

    public function adminCelebrity(Request $request){
        
        $userInfor = User::where('id', $request->user_id)->first();
        $userInfor->referral_code = $request->referral_code;
        $userInfor->save();

        $userInfor->profile()->update(['is_celebrity' => true]);
        return back()->with('success', 'User Updated to Celebrity Successfully');
    }

    public function campaignCompleted(){
        $campaigns = Campaign::where('status', 'Live')->orderBy('created_at', 'DESC')->get();
        return view('admin.campaign_completed', ['campaigns' => $campaigns]);
    }

    public function userlocation(){
        $userTracker = UserLocation::orderBy('created_at', 'DESC')->paginate(100);
        return view('admin.users.user_location', ['userTracker' => $userTracker]);
    }

    public function blacklist($id){
        User::where('id', $id)->update(['is_blacklisted' => 1]);
        return back()->with('success', 'User Blacklisted');
    }

    public function updateUserAccountDetails(Request $request){

            $accountInformation = PaystackHelpers::resolveBankName($request->account_number, $request->bank_code);

            if($accountInformation['status'] == 'true')
            {
                $recipientCode = PaystackHelpers::recipientCode($accountInformation['data']['account_name'], $request->account_number, $request->bank_code);
                $bankInfor = BankInformation::where('user_id', $request->user_id)->first();
                $bankInfor->name = $accountInformation['data']['account_name'];
                $bankInfor->bank_name = $recipientCode['data']['details']['bank_name'];
                $bankInfor->account_number = $request->account_number;
                $bankInfor->bank_code = $request->bank_code;
                $bankInfor->recipient_code = $recipientCode['data']['recipient_code'];
                $bankInfor->save();
            }

            $user = User::where('id', $request->user_id)->first();
            $subject = 'Account Details Updated';
            $content = 'Congratulations, your account details has been updated on Freebyz.';
            Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

            return back()->with('success', 'Account Details Upated');
    }

    public function virtualAccountList(){
        $virtual = VirtualAccount::where('account_number', null)->orderBy('created_at', 'DESC')->get();
        return view('admin.users.virtual_account', ['virtual' => $virtual]);
    }

    public function reactivateVA($id){
        // return $request;
        // $bankInfor = BankInformation::where('user_id', $id)->first()->name;
       $userPhone = User::where('id', $id)->first();
        reGenerateVirtualAccount($userPhone);
        return back()->with('success', 'VA regenerated Successfully');
    }

    public function removeVirtualAccount($id){
        VirtualAccount::where('id', $id)->delete();
        return back()->with('success', 'VA removed - ');
    }

    public function listFlutterwaveTrf(){
        return PaystackHelpers::listFlutterwaveTransaction();
    }

    public function test(){
        $html =  Sendmonny::index();

        return view('welcome', ['html' => $html]);
    }
}
