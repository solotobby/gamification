<?php

namespace App\Http\Controllers;

use App\Mail\ApproveCampaign;
use App\Mail\CreateCampaign;
use App\Mail\GeneralMail;
use App\Mail\SubmitJob;
use App\Models\Campaign;
use App\Models\CampaignWorker;
use App\Models\Category;
use App\Models\DisputedJobs;
use App\Models\PaymentTransaction;
use App\Models\Rating;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Wallet;
use App\Rules\ProhibitedWords;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'email']);
        // $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $campaignList = Campaign::where(
            'user_id',
            $user->id
        )->orderBy(
            'created_at',
            'DESC'
        )->paginate(10);
        return view('user.campaign.index', [
            'lists' => $campaignList,
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.campaign.create');
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
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = Campaign::where('job_id', $id)->first();
        return view('user.campaign.edit', ['campaign' => $campaign]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        $est_amount = $request->number_of_staff * $request->campaign_amount;
        $percent = (60 / 100) * $est_amount;
        $total = $est_amount + $percent;
        //$total = $request->total_amount_pay;

        $wallet = Wallet::where('user_id', auth()->user()->id)->first();

        if ($wallet->balance >= $total) {
            $wallet->balance -= $total;
            $wallet->save();
            $camp = $camp = Campaign::where('id', $request->post_id)->first();

            $camp->extension_references = null;
            $camp->number_of_staff += $request->number_of_staff;
            $camp->total_amount += $total;
            $camp->save();

            $ref = time();

            PaymentTransaction::create([
                'user_id' => auth()->user()->id,
                'campaign_id' => $request->post_id,
                'reference' => $ref,
                'amount' => $total,
                'balance' => walletBalance(auth()->user()->id),
                'status' => 'successful',
                'currency' => 'NGN',
                'channel' => 'paystack',
                'type' => 'edit_campaign_payment',
                'description' => 'Extend Campaign Payment'
            ]);

            Mail::to(auth()->user()->email)->send(new CreateCampaign($camp));



            return back()->with('success', 'Campaign Updated Successfully');
        } else {
            return back()->with('error', 'You do not have suficient funds in your wallet');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
        //
    }

    public function getCategories()
    {
        return Category::orderBy('name', 'ASC')->get();
    }

    public function getSubCategories($id)
    {

        $baseCurrency = auth()->user()->wallet->base_currency;

        if ($baseCurrency == 'NGN') {

            return SubCategory::where('category_id', $id)->orderBy('name', 'DESC')->get();
        } elseif ($baseCurrency == 'USD') {

            // $rate = nairaConversion($baseCurrency);
            $subCates = SubCategory::where('category_id', $id)->orderBy('name', 'DESC')->get();
            $list = [];
            foreach ($subCates as $sub) {
                $list[] = [
                    'id' => $sub->id,
                    'amount' => $sub->usd,
                    'category_id' => $sub->category_id,
                    'name' => $sub->name,
                    // 'amt_usd' => $sub->amount,
                    '_channel' => 'usd'
                ];
            }
            return $list;
        } else {

            $subCates = SubCategory::where('category_id', $id)->orderBy('name', 'DESC')->get();
            $list = [];
            foreach ($subCates as $sub) {
                // $convertedAmount = $sub->amount * $rate;
                $list[] = [
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'amount' => CurrencyConverter('NGN', baseCurrency(), $sub->amount), //number_format($convertedAmount,2),
                    'category_id' => $sub->category_id,
                    '_currency' => baseCurrency(),
                    '_channel' => 'other'
                ];
            }
            return $list;
        }
    }
    public function getSubcategoriesInfo($id)
    {
        $baseCurrency = auth()->user()->wallet->base_currency;

        if ($baseCurrency == 'NGN') {
            return SubCategory::where('id', $id)->first();
        } elseif ($baseCurrency == 'USD') {

            $subCate = SubCategory::where('id', $id)->first();
            // $rate = nairaConversion($baseCurrency);
            // $convertedAmount = $subCate->amount * $rate;

            return  $list = [
                'id' => $subCate->id,
                'name' => $subCate->name,
                'amount' =>  $subCate->usd,
                'category_id' => $subCate->category_id,
                '_currency' => $baseCurrency,
                '_channel' => 'other'
            ];
        } else {
            $subCate = SubCategory::where('id', $id)->first();

            //  $rate = nairaConversion($baseCurrency);
            //  $convertedAmount = $subCate->amount * $rate;
            $list = [
                'id' => $subCate->id,
                'name' => $subCate->name,
                'amount' => CurrencyConverter('NGN', baseCurrency(), $subCate->amount),
                'category_id' => $subCate->category_id,
                '_currency' => $baseCurrency,
                '_channel' => 'other'
            ];

            return $list;
        }
    }

    public function postCampaign(Request $request)
    {

        if ($request->allow_upload == true) {
            $request->validate([
                'description' => 'required|string',
                'proof' => 'required|string',
                'post_title' => 'required|string',
                'post_link' => 'required|string',
                'number_of_staff' => 'required|numeric',
                'campaign_amount' => 'required|numeric',
                'validate' => 'required'
            ]);
        } else {
            $request->validate([
                'description' => 'required|string',
                'proof' => ['required', new ProhibitedWords()],
                'post_title' => 'required|string',
                'post_link' => 'required|string',
                'number_of_staff' => 'required|numeric',
                'campaign_amount' => 'required|numeric',
                'validate' => 'required'
            ]);
        }


        $prAmount = '';
        $priotize = '';
        $baseCurrency = auth()->user()->wallet->base_currency;

        if ($request->priotize == true) {

            $amountPriotize = currencyParameter(baseCurrency())->priotize;
            $prAmount = $amountPriotize;
            $priotize = 'Priotize';
        } else {
            $prAmount =  0;
            $priotize = 'Pending';
        }

        $iniAmount = '';
        if ($request->allow_upload == true) {

            $iniAmount = $request->number_of_staff * currencyParameter(baseCurrency())->allow_upload;
            $allowUpload = true;
        } elseif ($request->allow_upload == '') {
            $iniAmount = 0;
            $allowUpload = false;
        }

        $est_amount = $request->number_of_staff * $request->campaign_amount;
        $percent = (60 / 100) * $est_amount;
        $total = $est_amount + $percent;


        [$est_amount, $percent, $total];
        $job_id = Str::random(7); //rand(10000,10000000);

        $walletValidity = checkWalletBalance(auth()->user(), baseCurrency(), $total + $iniAmount + $prAmount);

        if ($walletValidity) {

            $debitWallet = debitWallet(auth()->user(), baseCurrency(), $total + $iniAmount + $prAmount);
            if ($debitWallet) {
                $processedCampaign = $this->processCampaign($total + $iniAmount + $prAmount, $request, $job_id, $percent, $allowUpload, $priotize);
                Mail::to(auth()->user()->email)->send(new CreateCampaign($processedCampaign));
                return back()->with('success', 'Campaign Posted Successfully. A member of our team will activate your campaign in less than 24 hours.');
            }
        } else {
            return back()->with('error', 'You do not have suficient funds in your wallet');
        }
    }



    public function processCampaign($total, $request, $job_id, $percent, $allowUpload, $priotize)
    {

        $currency = '';
        $channel = '';

        $baseCurrency = auth()->user()->wallet->base_currency;
        if ($baseCurrency == "NGN") {
            $currency = 'NGN';
            $channel = 'paystack';
        } elseif ($baseCurrency == "USD") {
            $currency = 'USD';
            $channel = 'paypal';
        } else {
            $currency = $baseCurrency;
            $channel = 'flutterwave';
        }

        $request->request->add(['user_id' => auth()->user()->id, 'total_amount' => $total, 'job_id' => $job_id, 'currency' => $currency, 'impressions' => 0, 'pending_count' => 0, 'completed_count' => 0, 'allow_upload' => $allowUpload, 'approved' => $priotize]);
        $campaign = Campaign::create($request->all());

        $ref = time();
        PaymentTransaction::create([
            'user_id' => auth()->user()->id,
            'campaign_id' => $campaign->id,
            'reference' => $ref,
            'amount' => $total,
            'balance' => walletBalance(auth()->user()->id),
            'status' => 'successful',
            'currency' => $currency,
            'channel' => $channel,
            'type' => 'campaign_posted',
            'description' => $campaign->post_title . ' Campaign',
            'tx_type' => 'Debit',
            'user_type' => 'regular'
        ]);

        //CREDIT ADMIN
        $adminUser = User::where('id', 1)->first();
        $creditAdminWallet = creditWallet($adminUser, $baseCurrency, $percent);

        if ($creditAdminWallet) {
            //Admin Transaction Tablw
            PaymentTransaction::create([
                'user_id' => 1,
                'campaign_id' => '1',
                'reference' => $ref,
                'amount' => $percent,
                'balance' => walletBalance('1'),
                'status' => 'successful',
                'currency' => $currency,
                'channel' => $channel,
                'type' => 'campaign_revenue',
                'description' => 'Campaign revenue from ' . auth()->user()->name,
                'tx_type' => 'Credit',
                'user_type' => 'admin'
            ]);
        }

        return $campaign;
    }

    private function checkUserVerified($baseCurrency)
    {

        if ($baseCurrency == 'NGN') {
            return auth()->user()->is_verified == true ? 'Verified' : 'Not_Verified';
        } else {
            return auth()->user()->USD_verified == true ? 'Verified' : 'Not_Verified';
        }
    }

    public function viewCampaign($job_id)
    {

        if (!auth()->user()->skill()->exists()) {
            return redirect()->route('create.skill');
        }
        if ($job_id == null) {
            abort(400);
        }

        if ($job_id == 'Pt4bZz0') {
            return $this->premiumCampaign($job_id);
        }

        $userBaseCurrency = baseCurrency();

        $checkIsVerified = $this->checkUserVerified($userBaseCurrency);

        $getCampaign = viewCampaign($job_id);




        //CHECK IF USER IS VERIFIED IN NGN
        if ($checkIsVerified == 'Verified') {

            if ($getCampaign['is_completed'] == true) {
                return redirect('home');
            } else {
                $completed = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
                $rating = Rating::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
                $checkRating = isset($rating) ? true : false;
                return view('user.campaign.view', ['campaign' => $getCampaign, 'completed' => $completed, 'is_rated' => $checkRating]);
            }
        } else {


            $currencyParams = currencyParameter($userBaseCurrency);
            $minUpgradeAmount = $currencyParams->min_upgrade_amount;

            $campaignLocalAmount = $getCampaign['local_converted_amount'];

            if ($campaignLocalAmount >= $minUpgradeAmount) {
                return redirect('info');
            } else {
                if ($getCampaign['is_completed'] == true) {
                    return redirect('home');
                } else {

                    $completed = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
                    $rating = Rating::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
                    $checkRating = isset($rating) ? true : false;
                    return view('user.campaign.view', ['campaign' => $getCampaign, 'completed' => $completed, 'is_rated' => $checkRating]);
                }
            }
        }
    }

    public function viewPublicCampaign($job_id)
    {
        if ($job_id == null) {
            return view('campaign-not-found');
        }

        try {
            // Get campaign details without authentication
            $getCampaign = viewCampaign($job_id);

            if (!$getCampaign) {
                return view('campaign-not-found');
            }

            // Check if campaign is still active
            if ($getCampaign['is_completed'] == true) {
                return view('public.campaign-completed', ['campaign' => $getCampaign]);
            }

            return view('public.campaign-view', ['campaign' => $getCampaign]);
        } catch (\Exception $e) {
            return view('campaign-not-found');
        }
    }

    public function handleCampaignRedirect(Request $request)
    {
        $redirectUrl = $request->query('redirect');

        if ($redirectUrl && auth()->check()) {
            // Extract job_id from the redirect URL
            $urlParts = explode('/', $redirectUrl);
            $job_id = end($urlParts);

            // Redirect to authenticated campaign view
            return redirect()->route('view.campaign', ['job_id' => $job_id]);
        }

        return redirect()->route('home');
    }

    private function premiumCampaign($job_id)
    {

        $getCampaign = viewCampaign($job_id);
        $completed = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
        $rating = Rating::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
        $checkRating = isset($rating) ? true : false;
        return view('user.campaign.view', ['campaign' => $getCampaign, 'completed' => $completed, 'is_rated' => $checkRating]);
    }

    public function submitWork(Request $request)
    {
        $this->validate($request, [
            'proof' => 'image|mimes:png,jpeg,gif,jpg',
            'comment' => 'required|string',
        ]);

        if (auth()->user()->is_business) {
            return back()->with('error', 'Business accounts cannot perform tasks.');
        }

        $check = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $request->campaign_id)->first();
        if ($check) {
            return back()->with('error', 'You have comppleted this campaign before');
        }

        $campaign = Campaign::where('id', $request->campaign_id)->first();

        // $campCount = $campaign->completed()->where('status', '!=', 'Denied')->count();

        // if($campCount >= $campaign->number_of_staff){
        //     return back()->with('error', 'This campaign has reach its maximum of workers');
        // }

        $data['campaign'] = $campaign;

        $imageName = '';
        if ($request->hasFile('proof')) {
            $proofUrl = '';
            // if($request->hasFile('proof')){
            //   $image = $request->file('proof');
            //   $imageName = time().'.'.$request->proof->extension();
            //   $destinationPath = $request->proof->move(public_path('images'), $imageName);

            //    // Load image using GD
            //      $file = $request->file('proof');

            //         $extension = strtolower($file->getClientOriginalExtension());
            //         $imageName = time() . '.jpg'; // Save as JPEG
            //         $destination = public_path('images/' . $imageName);

            //         // Get temporary file path BEFORE moving or doing anything else
            //         $tempPath = $file->getRealPath();

            //         // Load image from temp file using GD
            //         switch ($extension) {
            //             case 'jpg':
            //             case 'jpeg':
            //                 $source = imagecreatefromjpeg($tempPath);
            //                 break;
            //             case 'png':
            //                 $source = imagecreatefrompng($tempPath);
            //                 break;
            //             case 'gif':
            //                 $source = imagecreatefromgif($tempPath);
            //                 break;
            //             default:
            //                 return back()->with('error', 'Unsupported image type.');
            //         }

            // Resize if width > 800px
            // $width = imagesx($source);
            // $height = imagesy($source);
            // $maxWidth = 800;

            // if ($width > $maxWidth) {
            //     $newWidth = $maxWidth;
            //     $newHeight = intval($height * ($maxWidth / $width));
            //     $resized = imagecreatetruecolor($newWidth, $newHeight);
            //     imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            // } else {
            //     $resized = $source;
            // }

            // Compress and save as JPEG (quality 70%)
            // imagejpeg($source, $destination, 70);


            // $fileBanner = $request->file('proof');
            // $Bannername = time() . $fileBanner->getClientOriginalName();
            // $filePathBanner = 'proofs/' . $Bannername;
            // Storage::disk('s3')->put($filePathBanner, file_get_contents($fileBanner), 'public');
            // $proofUrl = Storage::disk('s3')->url($filePathBanner);
            $imageName = time() . '.' . $request->proof->extension();
            $request->proof->move(public_path('images'), $imageName);
        }



        $campaignWorker['user_id'] = auth()->user()->id;
        $campaignWorker['campaign_id'] = $request->campaign_id;
        $campaignWorker['comment'] = $request->comment;
        $campaignWorker['amount'] = $request->amount;
        $campaignWorker['proof_url'] = $imageName == '' ? 'no image' : 'images/' . $imageName; //'no image'; //$imageName == '' ? 'no image' : 'images/'.$imageName;
        $campaignWorker['currency'] = baseCurrency(); //$campaign->currency;

        $campaignWork = CampaignWorker::create($campaignWorker);

        //activity log
        // $campaign->pending_count += 1;
        // $campaign->save();

        // setPendingCount($campaign->id);

        $campaignStatus = checkCampaignCompletedStatus($campaign->id);

        // $campaign->pending_count = $campaignStatus['Pending'] ?? 0;
        // $campaign->completed_count = $campaignStatus['Approved'] ?? 0;
        // $campaign->save();

        // Mail::to(auth()->user()->email)->send(new SubmitJob($campaignWork)); //send email to the member

        //$campaign = Campaign::where('id', $request->campaign_id)->first();

        $user = User::where('id', $campaign->user->id)->first();
        $subject = 'Job Submission';
        $content = auth()->user()->name . ' submitted a response to the your campaign - ' . $campaign->post_title . '. Please login to review.';
        Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

        return back()->with('success', 'Job Submitted Successfully');

        // }else{
        //     return back()->with('error', 'Upload an image');
        // }
    }

    public function mySubmittedCampaign($id)
    {
        $work = CampaignWorker::where('id', $id)->first();
        if ($work) {

            $campaignStat = checkCampaignCompletedStatus($work->campaign->id);
            // $c = @$campaignStat['Pending'] + @$campaignStat['Approved'];
            // $c >= $work->campaign->number_of_staff ? true : false;

            return view('user.campaign.my_submitted_campaign', ['work' => $work, 'check' => $campaignStat]);
        } else {
            return redirect('home');
        }
    }

    public function activities($id)
    {
        $cam = Campaign::where('job_id', $id)->where('user_id', auth()->user()->id)->first();
        if (!$cam) {
            return redirect('home');
        }

        $convertedAmount = currencyConverter($cam->currency, baseCurrency(), $cam->campaign_amount);

        $campaignStat = checkCampaignCompletedStatus($cam->id);


        $responses = CampaignWorker::where('campaign_id', $cam->id)->orderBy('created_at', 'DESC')->paginate(10);

        return view('user.campaign.activities', ['lists' => $cam, 'responses' => $responses, 'amount' => $convertedAmount, 'campaignStat' => $campaignStat]);
    }

    public function activitiesResponse($id)
    {
        $campaignResponse = CampaignWorker::with(['campaign', 'user'])->where('id', $id)->first();
        return view('user.campaign.activity_response', ['campaign' => $campaignResponse]);
    }

    public function pauseCampaign($id)
    {
        $campaign = Campaign::where('job_id', $id)->where('user_id', auth()->user()->id)->first();
        if ($campaign->status == 'Live') {
            $campaign->status = 'Paused';
            $campaign->save();
        } elseif ($campaign->status == 'Decline') {
        } else {
            $campaign->status = 'Live';
            $campaign->save();
        }
        return back()->with('success', 'Campaign status updated!');
    }

    public function campaignDecision(Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $workSubmitted = CampaignWorker::where('id', $request->id)->first();

        if ($workSubmitted->reason != null) {
            return back()->with('error', 'Campaign has been attended to');
        }

        $campaign = Campaign::where('id', $workSubmitted->campaign_id)->first();

        if ($request->action == 'approve') {

            $completed_campaign = $campaign->completed()->where('status', 'Approved')->count();
            if ($completed_campaign >= $campaign->number_of_staff) {
                return back()->with('error', 'Campaign has reached its maximum capacity');
            }

            $user = User::where('id', $workSubmitted->user_id)->first();

            $workSubmitted->status = 'Approved';
            $workSubmitted->reason = $request->reason;
            $workSubmitted->save();

            //    setIsComplete($workSubmitted->campaign_id);

            if (baseCurrency($user) == 'NGN') {
                $currency = 'NGN';
                $channel = 'paystack';
                creditWallet($user, 'NGN', $workSubmitted->amount);
            } elseif (baseCurrency($user) == 'USD') {
                $currency = 'USD';
                $channel = 'paypal';
                creditWallet($user, 'USD', $workSubmitted->amount);
            } else {

                $currency = baseCurrency($user);
                $channel = 'flutterwave';
                creditWallet($user, baseCurrency($user), $workSubmitted->amount);
            }


            $ref = time();

            PaymentTransaction::create([
                'user_id' =>  $workSubmitted->user_id,
                'campaign_id' =>  $workSubmitted->campaign->id,
                'reference' => $ref,
                'amount' =>  $workSubmitted->amount,
                'balance' => walletBalance($workSubmitted->user_id),
                'status' => 'successful',
                'currency' => $currency,
                'channel' => $channel,
                'type' => 'campaign_payment',
                'description' => 'Campaign Payment for ' . $workSubmitted->campaign->post_title,
                'tx_type' => 'Credit',
                'user_type' => 'regular'
            ]);

            activityLog($user, 'campaign_payment', $user->name . ' earned a campaign payment of NGN' . number_format($workSubmitted->amount), 'regular');

            $subject = 'Job Approved';
            $status = 'Approved';

            Mail::to($workSubmitted->user->email)->send(new ApproveCampaign($workSubmitted, $subject, $status));


            return redirect('campaign/activities/' . $request->campaign_job_id)->with('success', 'Campaign Approve Successfully');
        } else {

            //check if the
            // $chckCount = PaymentTransaction::where('user_id', $workSubmitted->campaign->user_id)->where('type', 'campaign_payment_refund')->whereDate('created_at', Carbon::today())->count();
            // if($chckCount >= 3){
            //     return back()->with('error', 'You cannot deny more than 3 jobs in a day');
            // }
            $workSubmitted->status = 'Denied';
            $workSubmitted->reason = $request->reason;
            $workSubmitted->save();

            $this->removePendingCountAfterDenial($workSubmitted->campaign_id);

            // $campaign = Campaign::where('id', $deny->campaign_id)->first();
            // $campaingOwner = User::where('id', $campaign->user_id)->first();

            if ($campaign->currency == 'NGN') {
                $currency = 'Naira';
                $channel = 'paystack';
            } elseif ($campaign->currency == 'USD') {
                $currency = 'Dollar';
                $channel = 'paypal';
            } elseif ($campaign->currency == null) {
                $currency = 'Naira';
                $channel = 'paystack';
            }

            // creditWallet($campaingOwner, $currency, $workSubmitted->amount);

            // $ref = time();

            // PaymentTransaction::create([
            //     'user_id' => $workSubmitted->campaign->user_id,
            //     'campaign_id' => $workSubmitted->campaign->id,
            //     'reference' => $ref,
            //     'amount' => $workSubmitted->amount,
            //     'status' => 'successful',
            //     'currency' => $currency,
            //     'channel' => $channel,
            //     'type' => 'campaign_payment_refund',
            //     'description' => 'Campaign Payment Refund for '.$workSubmitted->campaign->post_title,
            //     'tx_type' => 'Credit',
            //     'user_type' => 'regular'
            // ]);



            $subject = 'Job Denied';
            $status = 'Denied';

            Mail::to($workSubmitted->user->email)->send(new ApproveCampaign($workSubmitted, $subject, $status));



            return redirect('campaign/activities/' . $request->campaign_job_id)->with('success', 'Campaign has been denied');
        }
    }


    public function removePendingCountAfterDenial($id)
    {
        $campaign = Campaign::where('id', $id)->first();
        $campaign->pending_count -= 1;
        $campaign->save();
    }


    public function approvedCampaigns()
    {
        $mycampaigns = Campaign::where('user_id', auth()->user()->id)->pluck('id')->toArray();
        $approved = CampaignWorker::whereIn('campaign_id', $mycampaigns)->where('status', 'Approved')->orderby('created_at', 'ASC')->paginate(10);
        return view('user.campaign.approved', ['lists' => $approved]);
    }
    public function deniedCampaigns()
    {
        $mycampaigns = Campaign::where('user_id', auth()->user()->id)->pluck('id')->toArray();
        $denied = CampaignWorker::whereIn('campaign_id', $mycampaigns)->where('status', 'Denied')->orderby('created_at', 'ASC')->paginate(10);
        return view('user.campaign.denied', ['lists' => $denied]);
    }

    public function completedJobs()
    {
        $completedJobs = CampaignWorker::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('user.campaign.completed_jobs', ['lists' => $completedJobs]);
    }

    public function disputedJobs()
    {
        $disputedJobs = CampaignWorker::where('user_id', auth()->user()->id)->where('is_dispute', true)->orderBy('created_at', 'ASC')->paginate(10);
        return view('user.campaign.disputed_jobs', ['lists' => $disputedJobs]);
    }

    public function processDisputedJobs(Request $request)
    {
        $workDone = CampaignWorker::where('id', $request->id)->first();
        $workDone->is_dispute = true;
        $workDone->save();

        $disputedJob = DisputedJobs::create([
            'campaign_worker_id' => $workDone->id,
            'campaign_id' => $workDone->campaign_id,
            'user_id' => auth()->user()->id,
            'reason' => $request->reason
        ]);


        if ($disputedJob) {
            $subject = 'New Dispute Raised';
            $content = 'A despute has been raised by ' . auth()->user()->name . ' on a Job. Please follow the link below to attend to it.';
            $url = 'admin/campaign/disputes/' . $workDone->id;
            Mail::to('freebyzcom@gmail.com')->send(new GeneralMail(auth()->user(), $content, $subject, $url));
            return back()->with('success', 'Dispute Submitted Successfully');
        }
    }

    public function addMoreWorkers(Request $request)
    {
        return back()->with('error', 'Contact support to add more workers to your campaign.');

        // $request->validate([
        //     'new_number' => 'required|numeric|min:1',
        //     'amount' => 'required|numeric',
        //     'revenue' => 'required|numeric',
        //     'total' => 'required|numeric',
        //     'id' => 'required'
        // ]);

        // $campaign = Campaign::where('job_id', $request->id)->first();
        // $walletValidity = checkWalletBalance(auth()->user(), baseCurrency(), $request->total);

        // if ($walletValidity) {
        //     $debitWallet = debitWallet(auth()->user(), baseCurrency(), $request->total);
        //     if ($debitWallet) {
        //         $basicAmount = $request->new_number * $request->campaign_amount;
        //         $campaign->number_of_staff += $request->new_number;
        //         $campaign->total_amount += $basicAmount;
        //         $campaign->is_completed = false;
        //         $campaign->save();

        //         $currency = baseCurrency();
        //         if (baseCurrency() == 'NGN') {
        //             $channel = 'paystack';
        //         } elseif (baseCurrency() == 'USD') {
        //             $channel = 'paypal';
        //         } else {
        //             $channel = 'flutterwave';
        //         }
        //         $ref = time();

        //         PaymentTransaction::create([
        //             'user_id' => auth()->user()->id,
        //             'campaign_id' => $campaign->id,
        //             'reference' => $ref,
        //             'amount' => $basicAmount,
        //             'balance' => walletBalance(auth()->user()->id),
        //             'status' => 'successful',
        //             'currency' => $currency,
        //             'channel' => $channel,
        //             'type' => 'added_more_worker',
        //             'description' => 'Added worker for ' . $campaign->post_title . ' campaign',
        //             'tx_type' => 'Debit',
        //             'user_type' => 'regular'
        //         ]);

        //         //credit admin
        //         $adminUser = User::where('id', 1)->first();
        //         $creditAdmin = creditWallet($adminUser, $adminUser->wallet->base_currency, $request->revenue);
        //         if ($creditAdmin) {
        //             PaymentTransaction::create([
        //                 'user_id' => '1',
        //                 'campaign_id' => $campaign->id,
        //                 'reference' => $ref,
        //                 'amount' => $request->revenue,
        //                 'balance' => walletBalance('1'),
        //                 'status' => 'successful',
        //                 'currency' => $currency,
        //                 'channel' => $channel,
        //                 'type' => 'campaign_revenue_add',
        //                 'description' => 'Revenue for worker added on ' . $campaign->post_title . ' campaign',
        //                 'tx_type' => 'Credit',
        //                 'user_type' => 'admin'
        //             ]);
        //         }


        //         $content = "You have successfully increased the number of your workers.";
        //         $subject = "Add More Worker";
        //         $user = User::where('id', auth()->user()->id)->first();
        //         Mail::to(auth()->user()->email)->send(new GeneralMail($user, $content, $subject, ''));

        //         return back()->with('success', $request->new_number . ' Worker(s) Added Successfully');
        //     }
        // } else {
        //     return back()->with('error', 'You do not have suficient funds in your wallet');
        // }
    }

    public function addMoreWorkersDpreciated(Request $request)
    {

        $est_amount = $request->new_number * $request->amount;
        $percent = (60 / 100) * $est_amount;
        // $total = $est_amount + $percent;
        $total = $request->total;
        //[$est_amount, $percent, $total];


        $wallet = Wallet::where('user_id', auth()->user()->id)->first();

        if (baseCurrency() == 'NGN') {

            $campaign = Campaign::where('job_id', $request->id)->first();
            // $uploadFee = '';
            // if ($campaign->allow_upload == 1) {
            //     $uploadFee = $request->new_number * 5;
            // } else {
            //     $uploadFee = 0;
            // }

            $walletValidity = checkWalletBalance(auth()->user(), baseCurrency(), $total);


            if ($walletValidity) {

                $debitWallet = debitWallet(auth()->user(), baseCurrency(), $total);
                if ($debitWallet) {
                    $campaign->number_of_staff += $request->new_number;
                    $campaign->total_amount += $est_amount;
                    $campaign->is_completed = false;
                    $campaign->save();

                    $currency = 'NGN';
                    $channel = 'paystack';

                    $ref = time();
                    PaymentTransaction::create([
                        'user_id' => auth()->user()->id,
                        'campaign_id' => $campaign->id,
                        'reference' => $ref,
                        'amount' => $total,
                        'balance' => walletBalance(auth()->user()->id),
                        'status' => 'successful',
                        'currency' => $currency,
                        'channel' => $channel,
                        'type' => 'added_more_worker',
                        'description' => 'Added worker for ' . $campaign->post_title . ' campaign',
                        'tx_type' => 'Debit',
                        'user_type' => 'regular'
                    ]);

                    //credit admin
                    $adminWallet = Wallet::where('user_id', '1')->first();
                    $adminWallet->balance += $percent;
                    $adminWallet->save();
                    PaymentTransaction::create([
                        'user_id' => '1',
                        'campaign_id' => $campaign->id,
                        'reference' => $ref,
                        'amount' => $percent,
                        'balance' => walletBalance('1'),
                        'status' => 'successful',
                        'currency' => $currency,
                        'channel' => $channel,
                        'type' => 'campaign_revenue_add',
                        'description' => 'Revenue for worker added on ' . $campaign->post_title . ' campaign',
                        'tx_type' => 'Credit',
                        'user_type' => 'admin'
                    ]);

                    $content = "You have successfully increased the number of your workers.";
                    $subject = "Add More Worker";
                    $user = User::where('id', auth()->user()->id)->first();
                    Mail::to(auth()->user()->email)->send(new GeneralMail($user, $content, $subject, ''));
                    return back()->with('success', 'Worker Updated Successfully');
                }
            } else {
                return back()->with('error', 'You do not have suficient funds in your wallet');
            }
        } elseif (baseCurrency() == 'USD') {
            $campaign = Campaign::where('job_id', $request->id)->first();
            // $uploadFee = '';
            // if ($campaign->allow_upload == 1) {
            //     $uploadFee = $request->new_number * 0.01;
            // } else {
            //     $uploadFee = 0;
            // }

            $walletValidity = checkWalletBalance(auth()->user(), baseCurrency(), $total);
            if ($walletValidity) {

                $debitWallet = debitWallet(auth()->user(), baseCurrency(), $total);
                if ($debitWallet) {

                    $campaign->number_of_staff += $request->new_number;
                    $campaign->total_amount += $est_amount;
                    $campaign->is_completed = false;
                    $campaign->save();


                    $currency = 'USD';
                    $channel = 'paypal';

                    $ref = time();
                    PaymentTransaction::create([
                        'user_id' => auth()->user()->id,
                        'campaign_id' => $campaign->id,
                        'reference' => $ref,
                        'amount' => $total,
                        'balance' => walletBalance(auth()->user()->id),
                        'status' => 'successful',
                        'currency' => $currency,
                        'channel' => $channel,
                        'type' => 'added_more_worker',
                        'description' => 'Added worker for ' . $campaign->post_title . ' campaign',
                        'tx_type' => 'Debit',
                        'user_type' => 'regular'
                    ]);

                    //credit admin
                    $adminWallet = Wallet::where('user_id', '1')->first();
                    $adminWallet->usd_balance += $percent;
                    $adminWallet->save();

                    PaymentTransaction::create([
                        'user_id' => '1',
                        'campaign_id' => $campaign->id,
                        'reference' => $ref,
                        'amount' => $percent,
                        'balance' => walletBalance('1'),
                        'status' => 'successful',
                        'currency' => $currency,
                        'channel' => $channel,
                        'type' => 'campaign_revenue_add',
                        'description' => 'Revenue for worker added on ' . $campaign->post_title . ' campaign',
                        'tx_type' => 'Credit',
                        'user_type' => 'admin'
                    ]);


                    $content = "You have successfully increased the number of your workers.";
                    $subject = "Add More Worker";
                    $user = User::where('id', auth()->user()->id)->first();
                    Mail::to(auth()->user()->email)->send(new GeneralMail($user, $content, $subject, ''));
                    return back()->with('success', 'Worker Updated Successfully');
                }
            } else {

                return back()->with('error', 'You do not have suficient funds in your wallet');
            }
        } else {

            if ($wallet->base_currency_balance >= $total) {
                $campaign = Campaign::where('job_id', $request->id)->first();
                $uploadFee = '';
                $allowUpload = currencyParameter(baseCurrency())->allow_upload;
                if ($campaign->allow_upload == 1) {
                    $uploadFee = $request->new_number * $allowUpload;
                } else {
                    $uploadFee = 0;
                }

                $wallet->base_currency_balance -= $total + $uploadFee;
                $wallet->save();

                $campaign->number_of_staff += $request->new_number;
                $campaign->total_amount += $est_amount;
                $campaign->is_completed = false;
                $campaign->save();


                $currency = baseCurrency();
                $channel = 'flutterwave';

                $ref = time();
                PaymentTransaction::create([
                    'user_id' => auth()->user()->id,
                    'campaign_id' => $campaign->id,
                    'reference' => $ref,
                    'amount' => $total,
                    'balance' => walletBalance(auth()->user()->id),
                    'status' => 'successful',
                    'currency' => $currency,
                    'channel' => $channel,
                    'type' => 'added_more_worker',
                    'description' => 'Added worker for ' . $campaign->post_title . ' campaign',
                    'tx_type' => 'Debit',
                    'user_type' => 'regular'
                ]);

                //credit admin
                $adminWallet = Wallet::where('user_id', '1')->first();
                $adminInfo = User::find('1');
                if ($adminWallet->base_currency == 'NGN') {
                    $baseCurr = 'NGN';
                } elseif ($adminWallet->base_currency == 'USD') {
                    $baseCurr = 'USD';
                } else {
                    $baseCurr = $adminWallet->base_currency;
                }
                creditWallet($adminInfo, $baseCurr, $percent);

                PaymentTransaction::create([
                    'user_id' => '1',
                    'campaign_id' => $campaign->id,
                    'reference' => $ref,
                    'amount' => $percent,
                    'balance' => walletBalance('1'),
                    'status' => 'successful',
                    'currency' => $baseCurr,
                    'channel' => $channel,
                    'type' => 'campaign_revenue_add',
                    'description' => 'Revenue for worker added on ' . $campaign->post_title . ' campaign',
                    'tx_type' => 'Credit',
                    'user_type' => 'admin'
                ]);


                $content = "You have successfully increased the number of your workers.";
                $subject = "Add More Worker";
                $user = User::where('id', auth()->user()->id)->first();
                Mail::to(auth()->user()->email)->send(new GeneralMail($user, $content, $subject, ''));
                return back()->with('success', 'Worker Updated Successfully');
            } else {
                return back()->with('error', 'You do not have suficient funds in your wallet');
            }
        }
    }

    public function adminActivities($id)
    {

        $cam = Campaign::where('job_id', $id)->first();

        $approved = $cam->completed()->where('status', 'Approved')->count();

        $remainingNumber = $cam->number_of_staff - $approved;

        $count =  $remainingNumber;

        return view('admin.campaign_mgt.admin_activities', ['lists' => $cam, 'count' => $count]);
    }
}
