<?php

namespace App\Http\Controllers;

use App\Mail\ApproveCampaign;
use App\Mail\CreateCampaign;
use App\Mail\AdminCampaignPosted;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'email']);
        // $this->middleware('auth');
    }


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


    public function create()
    {
        return view('user.campaign.create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Campaign $campaign)
    {
        //
    }


    // public function edit($id)
    // {
    //     $campaign = Campaign::where('job_id', $id)->first();
    //     return view('user.campaign.edit', ['campaign' => $campaign]);
    // }

    public function edit($id)
    {
        $campaign = Campaign::where('job_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $countries = User::distinct()->pluck('country')->filter();

        return view('user.campaign.add_worker', compact('campaign', 'countries'));
    }

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

    // public function postCampaign(Request $request)
    // {

    //     if ($request->allow_upload == true) {
    //         $request->validate([
    //             'description' => 'required|string',
    //             'proof' => 'required|string',
    //             'post_title' => 'required|string',
    //             'post_link' => 'required|string',
    //             'number_of_staff' => 'required|numeric',
    //             'campaign_amount' => 'required|numeric',
    //             'validate' => 'required'
    //         ]);
    //     } else {
    //         $request->validate([
    //             'description' => 'required|string',
    //             'proof' => ['required', new ProhibitedWords()],
    //             'post_title' => 'required|string',
    //             'post_link' => 'required|string',
    //             'number_of_staff' => 'required|numeric',
    //             'campaign_amount' => 'required|numeric',
    //             'validate' => 'required'
    //         ]);
    //     }


    //     $prAmount = '';
    //     $priotize = '';
    //     $baseCurrency = auth()->user()->wallet->base_currency;

    //     if ($request->priotize == true) {

    //         $amountPriotize = currencyParameter(baseCurrency())->priotize;
    //         $prAmount = $amountPriotize;
    //         $priotize = 'Priotize';
    //     } else {
    //         $prAmount =  0;
    //         $priotize = 'Pending';
    //     }

    //     $iniAmount = '';
    //     if ($request->allow_upload == true) {

    //         $iniAmount = $request->number_of_staff * currencyParameter(baseCurrency())->allow_upload;
    //         $allowUpload = true;
    //     } elseif ($request->allow_upload == '') {
    //         $iniAmount = 0;
    //         $allowUpload = false;
    //     }

    //     $est_amount = $request->number_of_staff * $request->campaign_amount;
    //     if (auth()->user()->is_business) {
    //         $percent = (100 / 100) * $est_amount;
    //     } else {
    //         $percent = (60 / 100) * $est_amount;
    //     }
    //     //  $percent = (60 / 100) * $est_amount;
    //     $total = $est_amount + $percent;


    //     [$est_amount, $percent, $total];
    //     $job_id = Str::random(9); //rand(10000,10000000);

    //     $walletValidity = checkWalletBalance(auth()->user(), baseCurrency(), $total + $iniAmount + $prAmount);

    //     if ($walletValidity) {

    //         $debitWallet = debitWallet(auth()->user(), baseCurrency(), $total + $iniAmount + $prAmount);
    //         if ($debitWallet) {
    //             $processedCampaign = $this->processCampaign($total + $iniAmount + $prAmount, $request, $job_id, $percent, $allowUpload, $priotize);
    //             Mail::to(auth()->user()->email)->send(new CreateCampaign($processedCampaign));
    //             return back()->with('success', 'Campaign Posted Successfully. A member of our team will activate your campaign in less than 24 hours.');
    //         }
    //     } else {
    //         return back()->with('error', 'You do not have sufficient funds in your wallet');
    //     }
    // }

    // public function processCampaign($total, $request, $job_id, $percent, $allowUpload, $priotize)
    // {

    //     $currency = '';
    //     $channel = '';

    //     $baseCurrency = auth()->user()->wallet->base_currency;
    //     if ($baseCurrency == "NGN") {
    //         $currency = 'NGN';
    //         $channel = 'paystack';
    //     } elseif ($baseCurrency == "USD") {
    //         $currency = 'USD';
    //         $channel = 'paypal';
    //     } else {
    //         $currency = $baseCurrency;
    //         $channel = 'flutterwave';
    //     }

    //     $request->request->add(['user_id' => auth()->user()->id, 'total_amount' => $total, 'job_id' => $job_id, 'currency' => $currency, 'impressions' => 0, 'pending_count' => 0, 'completed_count' => 0, 'allow_upload' => $allowUpload, 'approved' => $priotize]);
    //     $campaign = Campaign::create($request->all());

    //     $ref = time();
    //     PaymentTransaction::create([
    //         'user_id' => auth()->user()->id,
    //         'campaign_id' => $campaign->id,
    //         'reference' => $ref,
    //         'amount' => $total,
    //         'balance' => walletBalance(auth()->user()->id),
    //         'status' => 'successful',
    //         'currency' => $currency,
    //         'channel' => $channel,
    //         'type' => 'campaign_posted',
    //         'description' => $campaign->post_title . ' Campaign',
    //         'tx_type' => 'Debit',
    //         'user_type' => 'regular'
    //     ]);

    //     //CREDIT ADMIN
    //     $adminUser = User::where('id', 1)->first();
    //     $creditAdminWallet = creditWallet($adminUser, $baseCurrency, $percent);

    //     if ($creditAdminWallet) {
    //         //Admin Transaction Tablw
    //         PaymentTransaction::create([
    //             'user_id' => 1,
    //             'campaign_id' => '1',
    //             'reference' => $ref,
    //             'amount' => $percent,
    //             'balance' => walletBalance('1'),
    //             'status' => 'successful',
    //             'currency' => $currency,
    //             'channel' => $channel,
    //             'type' => 'campaign_revenue',
    //             'description' => 'Campaign revenue from ' . auth()->user()->name,
    //             'tx_type' => 'Credit',
    //             'user_type' => 'admin'
    //         ]);
    //     }

    //     return $campaign;
    // }


    public function postCampaign(Request $request)
    {
        $rules = [
            'description'        => ['required', 'string'],
            'post_title'         => ['required', 'string'],
            'post_link'          => ['required', 'url'],
            'number_of_staff'    => ['required', 'integer', 'min:10'],
            'campaign_amount'    => ['required', 'numeric', 'min:0'],
            'expected_result_image' => ['nullable', 'image', 'mimes:png,jpeg,jpg,gif', 'max:2048'],
        ];

        // Add proof validation
        if ($request->allow_upload == true) {
            $rules['proof'] = 'required|string';
        } else {
            $rules['proof'] = ['required', new ProhibitedWords()];
        }

        // Add validation based on account type
        if (auth()->user()->is_business) {
            $rules['approval_time'] = 'required|numeric|in:24,36,48,56,72';
        } else {
            $rules['validate'] = 'required';
        }

        $request->validate($rules);

        $prAmount = '';
        $priotize = '';
        $baseCurrency = auth()->user()->wallet->base_currency;

        if ($request->priotize == true) {
            $amountPriotize = currencyParameter(baseCurrency())->priotize;
            $prAmount = $amountPriotize;
            $priotize = 'Priotize';
        } else {
            $prAmount = 0;
            $priotize = 'Pending';
        }

        $iniAmount = '';
        if ($request->allow_upload == true) {
            $iniAmount = $request->number_of_staff * currencyParameter(baseCurrency())->allow_upload;
            $allowUpload = true;
        } else {
            $iniAmount = 0;
            $allowUpload = false;
        }

        $est_amount = $request->number_of_staff * $request->campaign_amount;
        if (auth()->user()->is_business) {
            $percent = (100 / 100) * $est_amount;
        } else {
            $percent = (60 / 100) * $est_amount;
        }
        $total = $est_amount + $percent;

        $job_id = Str::random(9);

        // Set approval time based on account type
        $approvalTime = auth()->user()->is_business
            ? $request->approval_time
            : 24;

        // Handle Expected Result Image Upload
        $expectedResultImageUrl = null;
        if ($request->hasFile('expected_result_image')) {
            $file = $request->file('expected_result_image');

            if ($file->isValid()) {
                try {
                    Log::info('Uploading expected result image', [
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType()
                    ]);

                    $expectedResultImageUrl = uploadImageToCloudinary($file, 'freebyz/expected-results');

                    if (!$expectedResultImageUrl) {
                        return back()
                            ->withInput()
                            ->with('error', 'Failed to upload expected result image. Please try again.');
                    }

                    Log::info('Expected result image uploaded successfully', [
                        'url' => $expectedResultImageUrl
                    ]);
                } catch (\Exception $e) {
                    Log::error('Expected result image upload failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    return back()
                        ->withInput()
                        ->with('error', 'Error uploading image: ' . $e->getMessage());
                }
            } else {
                return back()
                    ->withInput()
                    ->with('error', 'Invalid image file. Please try again.');
            }
        }

        $walletValidity = checkWalletBalance(auth()->user(), baseCurrency(), $total + $iniAmount + $prAmount);

        if ($walletValidity) {
            $debitWallet = debitWallet(auth()->user(), baseCurrency(), $total + $iniAmount + $prAmount);

            if ($debitWallet) {
                $processedCampaign = $this->processCampaign(
                    $total + $iniAmount + $prAmount,
                    $request,
                    $job_id,
                    $percent,
                    $allowUpload,
                    $priotize,
                    $approvalTime,
                    $expectedResultImageUrl
                );

                Mail::to(auth()->user()->email)->send(new CreateCampaign($processedCampaign));

                if (config('app.env') === 'Production') {
                    Mail::to('hello@freebyztechnologies.com')
                        ->cc('blessing@freebyztechnologies.com')
                        ->send(new AdminCampaignPosted($processedCampaign));
                }

                return back()->with('success', 'Campaign Posted Successfully. A member of our team will activate your campaign in less than 24 hours.');
            }
        } else {
            return back()
                ->withInput()
                ->with('error', 'You do not have sufficient funds in your wallet');
        }
    }

    public function processCampaign($total, $request, $job_id, $percent, $allowUpload, $priotize, $approvalTime, $expectedResultImageUrl = null)
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

        // Build the campaign data array
        $campaignData = [
            'user_id' => auth()->user()->id,
            'post_title' => $request->post_title,
            'post_link' => $request->post_link,
            'campaign_type' => $request->campaign_type,
            'campaign_subcategory' => $request->campaign_subcategory,
            'number_of_staff' => $request->number_of_staff,
            'campaign_amount' => $request->campaign_amount,
            'description' => $request->description,
            'proof' => $request->proof,
            'total_amount' => $total,
            'job_id' => $job_id,
            'currency' => $currency,
            'impressions' => 0,
            'pending_count' => 0,
            'completed_count' => 0,
            'allow_upload' => $allowUpload,
            'approved' => $priotize,
            'approval_time' => $approvalTime,
            'expected_result_image' => $expectedResultImageUrl, // This is the Cloudinary URL
        ];

        // Create campaign with explicit data array instead of using $request->all()
        $campaign = Campaign::create($campaignData);

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
            //Admin Transaction Table
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

    // public function processCampaign($total, $request, $job_id, $percent, $allowUpload, $priotize, $approvalTime)
    // {
    //     $currency = '';
    //     $channel = '';

    //     $baseCurrency = auth()->user()->wallet->base_currency;
    //     if ($baseCurrency == "NGN") {
    //         $currency = 'NGN';
    //         $channel = 'paystack';
    //     } elseif ($baseCurrency == "USD") {
    //         $currency = 'USD';
    //         $channel = 'paypal';
    //     } else {
    //         $currency = $baseCurrency;
    //         $channel = 'flutterwave';
    //     }

    //     $request->request->add([
    //         'user_id' => auth()->user()->id,
    //         'total_amount' => $total,
    //         'job_id' => $job_id,
    //         'currency' => $currency,
    //         'impressions' => 0,
    //         'pending_count' => 0,
    //         'completed_count' => 0,
    //         'allow_upload' => $allowUpload,
    //         'approved' => $priotize,
    //         'approval_time' => $approvalTime
    //     ]);

    //     $campaign = Campaign::create($request->all());

    //     $ref = time();
    //     PaymentTransaction::create([
    //         'user_id' => auth()->user()->id,
    //         'campaign_id' => $campaign->id,
    //         'reference' => $ref,
    //         'amount' => $total,
    //         'balance' => walletBalance(auth()->user()->id),
    //         'status' => 'successful',
    //         'currency' => $currency,
    //         'channel' => $channel,
    //         'type' => 'campaign_posted',
    //         'description' => $campaign->post_title . ' Campaign',
    //         'tx_type' => 'Debit',
    //         'user_type' => 'regular'
    //     ]);

    //     //CREDIT ADMIN
    //     $adminUser = User::where('id', 1)->first();
    //     $creditAdminWallet = creditWallet($adminUser, $baseCurrency, $percent);

    //     if ($creditAdminWallet) {
    //         //Admin Transaction Table
    //         PaymentTransaction::create([
    //             'user_id' => 1,
    //             'campaign_id' => '1',
    //             'reference' => $ref,
    //             'amount' => $percent,
    //             'balance' => walletBalance('1'),
    //             'status' => 'successful',
    //             'currency' => $currency,
    //             'channel' => $channel,
    //             'type' => 'campaign_revenue',
    //             'description' => 'Campaign revenue from ' . auth()->user()->name,
    //             'tx_type' => 'Credit',
    //             'user_type' => 'admin'
    //         ]);
    //     }

    //     return $campaign;
    // }
    private function checkUserVerified($baseCurrency)
    {

        if ($baseCurrency == 'NGN') {
            return auth()->user()->is_verified == true ? 'Verified' : 'Not_Verified';
        } else {
            return auth()->user()->USD_verified == true ? 'Verified' : 'Not_Verified';
        }
    }

    // public function viewCampaign($job_id)
    // {

    //     // if (!auth()->user()->skill()->exists()) {
    //     //     return redirect()->route('create.skill');
    //     // }
    //     if ($job_id == null) {
    //         abort(400);
    //     }

    //     $userBaseCurrency = baseCurrency();

    //     $checkIsVerified = $this->checkUserVerified($userBaseCurrency);

    //     $getCampaign = viewCampaign($job_id);

    //     //CHECK IF USER IS VERIFIED IN NGN
    //     if ($checkIsVerified == 'Verified') {

    //         if ($getCampaign['is_completed'] == true) {
    //             return redirect('home');
    //         } else {
    //             $completed = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
    //             $rating = Rating::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
    //             $checkRating = isset($rating) ? true : false;
    //             return view('user.campaign.view', ['campaign' => $getCampaign, 'completed' => $completed, 'is_rated' => $checkRating]);
    //         }
    //     } else {

    //         $currencyParams = currencyParameter($userBaseCurrency);
    //         $minUpgradeAmount = $currencyParams->min_upgrade_amount;

    //         $campaignLocalAmount = $getCampaign['local_converted_amount'];

    //         $fetchUser = User::where('id', $getCampaign['user_id'])->first();
    //         if ($fetchUser->is_business) {

    //             $completed = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
    //             $rating = Rating::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
    //             $checkRating = isset($rating) ? true : false;
    //             return view('user.campaign.view', ['campaign' => $getCampaign, 'completed' => $completed, 'is_rated' => $checkRating]);
    //         } else {

    //             if ($campaignLocalAmount >= $minUpgradeAmount) {

    //                 return redirect('info');
    //             } else {

    //                 if ($getCampaign['is_completed'] == true) {

    //                     return redirect('home');
    //                 } else {

    //                     $completed = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
    //                     $rating = Rating::where('user_id', auth()->user()->id)->where('campaign_id', $getCampaign->id)->first();
    //                     $checkRating = isset($rating) ? true : false;
    //                     return view('user.campaign.view', ['campaign' => $getCampaign, 'completed' => $completed, 'is_rated' => $checkRating]);
    //                 }
    //             }
    //         }
    //     }
    // }

    // public function viewCampaign($job_id)
    // {
    //     if ($job_id == null) {
    //         abort(400);
    //     }

    //     $userBaseCurrency = baseCurrency();
    //     $checkIsVerified = $this->checkUserVerified($userBaseCurrency);
    //     $getCampaign = viewCampaign($job_id);

    //     // Check user's campaign work status
    //     $campaignWorker = CampaignWorker::where('user_id', auth()->user()->id)
    //         ->where('campaign_id', $getCampaign->id)
    //         ->first();

    //     // Initialize variables
    //     $deniedCount = 0;
    //     $canResubmit = false;
    //     $hasPending = false;

    //     $userId = auth()->id();

    //     if ($checkIsVerified == 'Verified') {
    //         if ($getCampaign['is_completed'] == true && !$canResubmit) {
    //             return redirect('home');
    //         }

    //         if ($getCampaign->id == 56 && $campaignWorker) {
    //             $campaignId = $getCampaign->id;

    //             // Fetch all statuses in one query
    //             $hasPending = CampaignWorker::where('user_id', auth()->user()->id)
    //                 ->where('campaign_id', $getCampaign->id)
    //                 ->where('status', 'Pending')
    //                 ->exists();

    //             $approved = CampaignWorker::where('user_id', auth()->user()->id)
    //                 ->where('campaign_id', $getCampaign->id)
    //                 ->where('status', 'Approved')
    //                 ->exists();

    //             $deniedCount = CampaignWorker::where('user_id', auth()->user()->id)
    //                 ->where('campaign_id', $getCampaign->id)
    //                 ->where('status', 'Denied')
    //                 ->count();

    //             $canResubmit = !$hasPending &&
    //                 // $campaignWorker->status === 'Denied' &&
    //                 $deniedCount < 3;

    //             // $completed = ($approved || (!$canResubmit && $hasPending && $deniedCount >= 3))
    //             //     ? $campaignWorker
    //             //     : null;
    //             $completed = $campaignWorker && !$canResubmit && $approved || $hasPending ? $campaignWorker : null;
    //         }

    //         $completed = CampaignWorker::where('user_id', $userId)
    //             ->where('campaign_id', $getCampaign->id)
    //             ->first();

    //         $rating = Rating::where('user_id', auth()->user()->id)
    //             ->where('campaign_id', $getCampaign->id)
    //             ->first();
    //         $checkRating = isset($rating) ? true : false;

    //         return view('user.campaign.view', [
    //             'campaign' => $getCampaign,
    //             'completed' => $completed,
    //             'is_rated' => $checkRating,
    //             'can_resubmit' => $canResubmit,
    //             'denied_count' => $deniedCount,
    //             'has_pending' => $hasPending
    //         ]);
    //     } else {
    //         $currencyParams = currencyParameter($userBaseCurrency);
    //         $minUpgradeAmount = $currencyParams->min_upgrade_amount;
    //         $campaignLocalAmount = $getCampaign['local_converted_amount'];
    //         $fetchUser = User::where('id', $getCampaign['user_id'])->first();

    //         $completed = CampaignWorker::where('user_id', $userId)
    //             ->where('campaign_id', $getCampaign->id)
    //             ->first();
    //         if ($fetchUser->is_business) {
    //             $rating = Rating::where('user_id', auth()->user()->id)
    //                 ->where('campaign_id', $getCampaign->id)
    //                 ->first();
    //             $checkRating = isset($rating) ? true : false;

    //             return view('user.campaign.view', [
    //                 'campaign' => $getCampaign,
    //                 'completed' => $completed,
    //                 'is_rated' => $checkRating,
    //                 'can_resubmit' => $canResubmit,
    //                 'denied_count' => $deniedCount,
    //                 'has_pending' => $hasPending
    //             ]);
    //         } else {
    //             if ($campaignLocalAmount >= $minUpgradeAmount) {
    //                 return redirect('info');
    //             }

    //             if ($getCampaign['is_completed'] == true && !$canResubmit) {
    //                 return redirect('home');
    //             }

    //             $rating = Rating::where('user_id', auth()->user()->id)
    //                 ->where('campaign_id', $getCampaign->id)
    //                 ->first();
    //             $checkRating = isset($rating) ? true : false;

    //             return view('user.campaign.view', [
    //                 'campaign' => $getCampaign,
    //                 'completed' => $completed,
    //                 'is_rated' => $checkRating,
    //                 'can_resubmit' => $canResubmit,
    //                 'denied_count' => $deniedCount,
    //                 'has_pending' => $hasPending
    //             ]);
    //         }
    //     }
    // }

    public function viewCampaign($job_id)
    {
        if (!$job_id) {
            abort(400);
        }

        if (!auth()->user()->accountDetails) {
            return redirect('profile')->with('info', 'You are about to take on a high-paying job. Please scroll down to Bank Account Details to update your information.');
        }
        $userId = auth()->id();
        $userBaseCurrency = baseCurrency();
        // $checkIsVerified = $this->checkUserVerified($userBaseCurrency);
        $checkIsVerified = 'Verified';
        $campaign = viewCampaign($job_id);

        // Fetch user's campaign worker record once
        $campaignWorker = CampaignWorker::where('user_id', $userId)
            ->where('campaign_id', $campaign->id)
            ->first();

        // Fetch rating once
        $rating = Rating::where('user_id', $userId)
            ->where('campaign_id', $campaign->id)
            ->first();
        $isRated = isset($rating);

        $deniedCount = 0;
        $canResubmit = false;
        $hasPending = false;
        $completed = $campaignWorker;


        if ($checkIsVerified === 'Verified') {

            if ($campaign->is_completed && !$canResubmit) {
                return redirect('home');
            }


            if (($campaign->id == 8099 ||  $campaign->id == 8401 ) && $campaignWorker) {
                // Get counts in a single query
                $statusCounts = CampaignWorker::where('user_id', $userId)
                    ->where('campaign_id', $campaign->id)
                    ->selectRaw("
                    SUM(CASE WHEN status = 'Denied' THEN 1 ELSE 0 END) as denied_count,
                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved_count
                ")
                    ->first();

                $deniedCount = $statusCounts->denied_count;
                $hasPending = $statusCounts->pending_count > 0;
                $approved = $statusCounts->approved_count > 0;

                $canResubmit = !$hasPending && $deniedCount < 3;

                $completed = $campaignWorker && (!$canResubmit || $approved || $hasPending) ? $campaignWorker : null;
            }
        } else {
            // Unverified user
            $currencyParams = currencyParameter($userBaseCurrency);
            $minUpgradeAmount = $currencyParams->min_upgrade_amount;
            $campaignLocalAmount = $campaign->local_converted_amount;
            $campaignOwner = User::find($campaign->user_id);

            if (!$campaignOwner->is_business && $campaignLocalAmount >= $minUpgradeAmount) {
                return redirect('info');
            }

            if ($campaign->is_completed && !$canResubmit) {
                return redirect('home');
            }
        }

        return view('user.campaign.view', [
            'campaign' => $campaign,
            'completed' => $completed,
            'is_rated' => $isRated,
            'can_resubmit' => $canResubmit,
            'denied_count' => $deniedCount,
            'has_pending' => $hasPending
        ]);
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

    // public function submitWork(Request $request)
    // {
    //     $this->validate($request, [
    //         'proof' => 'image|mimes:png,jpeg,gif,jpg',
    //         'comment' => 'required|string',
    //     ]);

    //     if (auth()->user()->is_business) {
    //         return back()->with('error', 'Business accounts cannot perform tasks.');
    //     }

    //     $check = CampaignWorker::where('user_id', auth()->user()->id)->where('campaign_id', $request->campaign_id)->first();
    //     if ($check) {
    //         return back()->with('error', 'You have comppleted this campaign before');
    //     }

    //     $campaign = Campaign::where('id', $request->campaign_id)->first();

    //     // $campCount = $campaign->completed()->where('status', '!=', 'Denied')->count();

    //     // if($campCount >= $campaign->number_of_staff){
    //     //     return back()->with('error', 'This campaign has reach its maximum of workers');
    //     // }

    //     $data['campaign'] = $campaign;

    //     $imageName = '';
    //     if ($request->hasFile('proof')) {
    //         $proofUrl = '';
    //         // if($request->hasFile('proof')){
    //         //   $image = $request->file('proof');
    //         //   $imageName = time().'.'.$request->proof->extension();
    //         //   $destinationPath = $request->proof->move(public_path('images'), $imageName);

    //         //    // Load image using GD
    //         //      $file = $request->file('proof');

    //         //         $extension = strtolower($file->getClientOriginalExtension());
    //         //         $imageName = time() . '.jpg'; // Save as JPEG
    //         //         $destination = public_path('images/' . $imageName);

    //         //         // Get temporary file path BEFORE moving or doing anything else
    //         //         $tempPath = $file->getRealPath();

    //         //         // Load image from temp file using GD
    //         //         switch ($extension) {
    //         //             case 'jpg':
    //         //             case 'jpeg':
    //         //                 $source = imagecreatefromjpeg($tempPath);
    //         //                 break;
    //         //             case 'png':
    //         //                 $source = imagecreatefrompng($tempPath);
    //         //                 break;
    //         //             case 'gif':
    //         //                 $source = imagecreatefromgif($tempPath);
    //         //                 break;
    //         //             default:
    //         //                 return back()->with('error', 'Unsupported image type.');
    //         //         }

    //         // Resize if width > 800px
    //         // $width = imagesx($source);
    //         // $height = imagesy($source);
    //         // $maxWidth = 800;

    //         // if ($width > $maxWidth) {
    //         //     $newWidth = $maxWidth;
    //         //     $newHeight = intval($height * ($maxWidth / $width));
    //         //     $resized = imagecreatetruecolor($newWidth, $newHeight);
    //         //     imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    //         // } else {
    //         //     $resized = $source;
    //         // }

    //         // Compress and save as JPEG (quality 70%)
    //         // imagejpeg($source, $destination, 70);


    //         // $fileBanner = $request->file('proof');
    //         // $Bannername = time() . $fileBanner->getClientOriginalName();
    //         // $filePathBanner = 'proofs/' . $Bannername;
    //         // Storage::disk('s3')->put($filePathBanner, file_get_contents($fileBanner), 'public');
    //         // $proofUrl = Storage::disk('s3')->url($filePathBanner);
    //         $imageName = time() . '.' . $request->proof->extension();
    //         $request->proof->move(public_path('images'), $imageName);
    //     }



    //     $campaignWorker['user_id'] = auth()->user()->id;
    //     $campaignWorker['campaign_id'] = $request->campaign_id;
    //     $campaignWorker['comment'] = $request->comment;
    //     $campaignWorker['amount'] = $request->amount;
    //     $campaignWorker['proof_url'] = $imageName == '' ? 'no image' : 'images/' . $imageName; //'no image'; //$imageName == '' ? 'no image' : 'images/'.$imageName;
    //     $campaignWorker['currency'] = baseCurrency(); //$campaign->currency;

    //     $campaignWork = CampaignWorker::create($campaignWorker);

    //     //activity log
    //     // $campaign->pending_count += 1;
    //     // $campaign->save();

    //     // setPendingCount($campaign->id);

    //     $campaignStatus = checkCampaignCompletedStatus($campaign->id);

    //     // $campaign->pending_count = $campaignStatus['Pending'] ?? 0;
    //     // $campaign->completed_count = $campaignStatus['Approved'] ?? 0;
    //     // $campaign->save();

    //     // Mail::to(auth()->user()->email)->send(new SubmitJob($campaignWork)); //send email to the member

    //     //$campaign = Campaign::where('id', $request->campaign_id)->first();

    //     $user = User::where('id', $campaign->user->id)->first();
    //     $subject = 'Job Submission';
    //     $content = auth()->user()->name . ' submitted a response to the your campaign - ' . $campaign->post_title . '. Please login to review.';
    //     Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

    //     return back()->with('success', 'Job Submitted Successfully');

    //     // }else{
    //     //     return back()->with('error', 'Upload an image');
    //     // }
    // }


    public function submitWork(Request $request)
    {
        $this->validate($request, [
            'proof' => 'image|mimes:png,jpeg,gif,jpg',
            'comment' => 'required|string',
        ]);

        if (auth()->user()->is_business) {
            return back()->with('error', 'Business accounts cannot perform tasks.');
        }

        $check = CampaignWorker::where('user_id', auth()->user()->id)
            ->where('campaign_id', $request->campaign_id)
            ->first();

        if ($check) {
            if (in_array($request->campaign_id, ['8401', '8099'])) {
                // Check if has pending submission
                $hasPending = CampaignWorker::where('user_id', auth()->user()->id)
                    ->where('campaign_id', $request->campaign_id)
                    ->where('status', 'Pending')
                    ->exists();

                if ($hasPending) {
                    return back()->with('error', 'You have a pending submission for this campaign. Please wait for review.');
                }

                // Count denied attempts
                $deniedCount = CampaignWorker::where('user_id', auth()->user()->id)
                    ->where('campaign_id', $request->campaign_id)
                    ->where('status', 'Denied')
                    ->count();

                // Check if last submission was denied and attempts < 3
                if ($check->status === 'Denied' && $deniedCount < 3) {
                    // Allow resubmission - continue with the code below
                } elseif ($check->status === 'Approved') {
                    return back()->with('error', 'You have already completed this campaign successfully.');
                } elseif ($deniedCount >= 3) {
                    return back()->with('error', 'You have exceeded the maximum number of attempts (3) for this campaign.');
                } else {
                    return back()->with('error', 'You have already submitted this campaign.');
                }
            } else {
                return back()->with('error', 'You have completed this campaign before');
            }
        }

        $campaign = Campaign::where('id', $request->campaign_id)->first();

        $data['campaign'] = $campaign;

        $proofUrl = 'no image';
        if ($request->hasFile('proof')) {
            $proofUrl = uploadImageToCloudinary($request->proof);
            // $imageName = time() . '.' . $request->proof->extension();
            // $request->proof->move(public_path('images'), $imageName);
        }

        $campaignWorker['user_id'] = auth()->user()->id;
        $campaignWorker['campaign_id'] = $request->campaign_id;
        $campaignWorker['comment'] = $request->comment;
        $campaignWorker['amount'] = $request->amount;
        // $campaignWorker['proof_url'] = $imageName == '' ? 'no image' : 'images/' . $imageName;
        $campaignWorker['proof_url'] = $proofUrl;
        $campaignWorker['currency'] = baseCurrency();

        $campaignWork = CampaignWorker::create($campaignWorker);

        $campaignStatus = checkCampaignCompletedStatus($campaign->id);

        $user = User::where('id', $campaign->user->id)->first();
        $subject = 'Job Submission';
        $content = auth()->user()->name . ' submitted a response to your campaign - ' . $campaign->post_title . '. Please login to review.';
        Mail::to($user->email)->send(new GeneralMail($user, $content, $subject, ''));

        return back()->with('success', 'Job Submitted Successfully');
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


        $responses = CampaignWorker::where('campaign_id', $cam->id)->orderBy('created_at', 'DESC')->paginate(20);

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

    // public function campaignDecision(Request $request)
    // {
    //     $request->validate([
    //         'reason' => 'required|string',
    //     ]);

    //     $workSubmitted = CampaignWorker::where('id', $request->id)->first();

    //     if ($workSubmitted->reason != null) {
    //         return back()->with('error', 'Campaign has been attended to');
    //     }

    //     $campaign = Campaign::where('id', $workSubmitted->campaign_id)->first();

    //     if ($request->action == 'approve') {

    //         $completed_campaign = $campaign->completed()->where('status', 'Approved')->count();
    //         if ($completed_campaign >= $campaign->number_of_staff) {
    //             return back()->with('error', 'Campaign has reached its maximum capacity');
    //         }

    //         $user = User::where('id', $workSubmitted->user_id)->first();

    //         $workSubmitted->status = 'Approved';
    //         $workSubmitted->reason = $request->reason;
    //         $workSubmitted->save();

    //         //    setIsComplete($workSubmitted->campaign_id);

    //         if (baseCurrency($user) == 'NGN') {
    //             $currency = 'NGN';
    //             $channel = 'paystack';
    //             creditWallet($user, 'NGN', $workSubmitted->amount);
    //         } elseif (baseCurrency($user) == 'USD') {
    //             $currency = 'USD';
    //             $channel = 'paypal';
    //             creditWallet($user, 'USD', $workSubmitted->amount);
    //         } else {

    //             $currency = baseCurrency($user);
    //             $channel = 'flutterwave';
    //             creditWallet($user, baseCurrency($user), $workSubmitted->amount);
    //         }


    //         $ref = time();

    //         PaymentTransaction::create([
    //             'user_id' =>  $workSubmitted->user_id,
    //             'campaign_id' =>  $workSubmitted->campaign->id,
    //             'reference' => $ref,
    //             'amount' =>  $workSubmitted->amount,
    //             'balance' => walletBalance($workSubmitted->user_id),
    //             'status' => 'successful',
    //             'currency' => $currency,
    //             'channel' => $channel,
    //             'type' => 'campaign_payment',
    //             'description' => 'Campaign Payment for ' . $workSubmitted->campaign->post_title,
    //             'tx_type' => 'Credit',
    //             'user_type' => 'regular'
    //         ]);

    //         activityLog($user, 'campaign_payment', $user->name . ' earned a campaign payment of NGN' . number_format($workSubmitted->amount), 'regular');

    //         $subject = 'Job Approved';
    //         $status = 'Approved';

    //         Mail::to($workSubmitted->user->email)->send(new ApproveCampaign($workSubmitted, $subject, $status));


    //         return redirect('campaign/activities/' . $request->campaign_job_id)->with('success', 'Campaign Approve Successfully');
    //     } else {

    //         //check if the
    //         // $chckCount = PaymentTransaction::where('user_id', $workSubmitted->campaign->user_id)->where('type', 'campaign_payment_refund')->whereDate('created_at', Carbon::today())->count();
    //         // if($chckCount >= 3){
    //         //     return back()->with('error', 'You cannot deny more than 3 jobs in a day');
    //         // }
    //         $workSubmitted->status = 'Denied';
    //         $workSubmitted->reason = $request->reason;
    //         $workSubmitted->save();

    //         // will return this if doesn't work
    //         // $this->removePendingCountAfterDenial($workSubmitted->campaign_id);

    //         // $campaign = Campaign::where('id', $deny->campaign_id)->first();
    //         // $campaingOwner = User::where('id', $campaign->user_id)->first();

    //         if ($campaign->currency == 'NGN') {
    //             $currency = 'Naira';
    //             $channel = 'paystack';
    //         } elseif ($campaign->currency == 'USD') {
    //             $currency = 'Dollar';
    //             $channel = 'paypal';
    //         } elseif ($campaign->currency == null) {
    //             $currency = 'Naira';
    //             $channel = 'paystack';
    //         }

    //         // creditWallet($campaingOwner, $currency, $workSubmitted->amount);

    //         // $ref = time();

    //         // PaymentTransaction::create([
    //         //     'user_id' => $workSubmitted->campaign->user_id,
    //         //     'campaign_id' => $workSubmitted->campaign->id,
    //         //     'reference' => $ref,
    //         //     'amount' => $workSubmitted->amount,
    //         //     'status' => 'successful',
    //         //     'currency' => $currency,
    //         //     'channel' => $channel,
    //         //     'type' => 'campaign_payment_refund',
    //         //     'description' => 'Campaign Payment Refund for '.$workSubmitted->campaign->post_title,
    //         //     'tx_type' => 'Credit',
    //         //     'user_type' => 'regular'
    //         // ]);



    //         $subject = 'Job Denied';
    //         $status = 'Denied';

    //         Mail::to($workSubmitted->user->email)->send(new ApproveCampaign($workSubmitted, $subject, $status));



    //         return redirect('campaign/activities/' . $request->campaign_job_id)->with('success', 'Campaign has been denied');
    //     }
    // }


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
            // DENIAL LOGIC - Set denied_at timestamp
            $workSubmitted->status = 'Denied';
            $workSubmitted->reason = $request->reason;
            $workSubmitted->denied_at = Carbon::now();
            $workSubmitted->slot_released = false;
            $workSubmitted->save();

            // DO NOT reduce pending_count yet - slot is reserved for 12 hours
            // $this->removePendingCountAfterDenial($workSubmitted->campaign_id);

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

            $subject = 'Job Denied - You have 12 hours to dispute';
            $status = 'Denied';

            Mail::to($workSubmitted->user->email)->send(new ApproveCampaign($workSubmitted, $subject, $status));

            return redirect('campaign/activities/' . $request->campaign_job_id)
                ->with('success', 'Campaign has been denied. Worker has 12 hours to dispute.');
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
        $workDone = CampaignWorker::find($request->id);

        if (! $workDone) {
            return back()->with('error', 'Work submission not found');
        }

        // Centralized dispute eligibility check
        if (! $workDone->canCreateDispute()) {
            return back()->with(
                'error',
                'This job can no longer be disputed. The dispute window has expired.'
            );
        }

        // Mark as disputed
        $workDone->update([
            'is_dispute' => true,
        ]);

        $disputedJob = DisputedJobs::create([
            'campaign_worker_id' => $workDone->id,
            'campaign_id'        => $workDone->campaign_id,
            'user_id'            => auth()->id(),
            'reason'             => $request->reason,
        ]);

        // Send mail only in production
        if (config('app.env') === 'Production') {
            $subject = 'New Dispute Raised';
            $content = 'A dispute has been raised by ' . auth()->user()->name . ' on a job. Please attend to it.';
            $url = 'admin/campaign/disputes/' . $workDone->id;

            Mail::to('freebyzcom@gmail.com')
                ->bcc('favour@freebyztechnologies.com')
                ->send(new GeneralMail(auth()->user(), $content, $subject, $url));
        }

        return back()->with(
            'success',
            'Dispute submitted successfully. Your slot is now reserved pending resolution.'
        );
    }


    // public function processDisputedJobsLatestDepreciate(Request $request)
    // {
    //     $workDone = CampaignWorker::where('id', $request->id)->first();

    //     if (!$workDone) {
    //         return back()->with('error', 'Work submission not found');
    //     }

    //     // Check if dispute window has expired
    //     if ($workDone->isDisputeWindowExpired()) {
    //         return back()->with('error', 'Dispute window has expired');
    //     }

    //     // Check if already disputed
    //     if ($workDone->is_dispute) {
    //         return back()->with('error', 'This job has already been disputed');
    //     }

    //     // Check if slot was already released
    //     if ($workDone->slot_released) {
    //         return back()->with('error', 'This slot has already been released');
    //     }

    //     // Mark as disputed
    //     $workDone->is_dispute = true;
    //     $workDone->save();

    //     $disputedJob = DisputedJobs::create([
    //         'campaign_worker_id' => $workDone->id,
    //         'campaign_id' => $workDone->campaign_id,
    //         'user_id' => auth()->user()->id,
    //         'reason' => $request->reason
    //     ]);

    //     if (config('app.env') == 'Production' && $disputedJob) {
    //         $subject = 'New Dispute Raised';
    //         $content = 'A dispute has been raised by ' . auth()->user()->name . ' on a Job. Please follow the link below to attend to it.';
    //         $url = 'admin/campaign/disputes/' . $workDone->id;
    //         Mail::to('freebyzcom@gmail.com')->bcc('favour@freebyztechnologies.com')->send(new GeneralMail(auth()->user(), $content, $subject, $url));

    //         return back()->with('success', 'Dispute submitted successfully. Your slot is now reserved pending resolution.');
    //     }
    //     return back()->with('error', 'Failed to submit dispute. Please try again.');
    // }

    // public function processDisputedJobs(Request $request)
    // {
    //     $workDone = CampaignWorker::where('id', $request->id)->first();
    //     $workDone->is_dispute = true;
    //     $workDone->save();

    //     $disputedJob = DisputedJobs::create([
    //         'campaign_worker_id' => $workDone->id,
    //         'campaign_id' => $workDone->campaign_id,
    //         'user_id' => auth()->user()->id,
    //         'reason' => $request->reason
    //     ]);


    //     if ($disputedJob) {
    //         $subject = 'New Dispute Raised';
    //         $content = 'A despute has been raised by ' . auth()->user()->name . ' on a Job. Please follow the link below to attend to it.';
    //         $url = 'admin/campaign/disputes/' . $workDone->id;
    //         // Mail::to('freebyzcom@gmail.com')->send(new GeneralMail(auth()->user(), $content, $subject, $url));
    //         return back()->with('success', 'Dispute Submitted Successfully');
    //     }
    // }

    public function previewAddWorker(Request $request)
    {
        $campaign = Campaign::where('job_id', $request->campaign_id)->firstOrFail();
        $newWorkers = (int) $request->new_workers;

        if ($newWorkers < 5) {
            return response()->json(['error' => 'Invalid worker count, you cannot add less than 5 new workers'], 400);
        }

        $baseCurrency = auth()->user()->wallet->base_currency;
        $campaignAmount = currencyConverter($campaign->currency, $baseCurrency, $campaign->campaign_amount);

        // Calculate costs following postCampaign logic
        $baseAmount = $newWorkers * $campaignAmount;

        $isBusiness = auth()->user()->is_business;
        $percentRate = $isBusiness ? 100 : 60;
        $platformFee = ($percentRate / 100) * $baseAmount;

        $uploadFee = 0;
        if ($campaign->allow_upload) {
            $uploadFee = $newWorkers * currencyParameter($baseCurrency)->allow_upload;
        }

        $totalCost = $baseAmount + $platformFee + $uploadFee;
        $walletBalance = walletBalance(auth()->id());

        return response()->json([
            'new_workers' => $newWorkers,
            'campaign_amount' => $campaignAmount,
            'base_amount' => $baseAmount,
            'platform_fee' => $platformFee,
            'upload_fee' => $uploadFee,
            'total_cost' => $totalCost,
            'wallet_balance' => $walletBalance,
            'has_sufficient_balance' => $totalCost <= $walletBalance,
            'currency' => $baseCurrency
        ]);
    }

    // Process add workers
    public function addMoreWorkers(Request $request)
    {
        $request->validate([
            'new_number' => 'required|integer|min:5',
            'id' => 'required|string'
        ]);

        $campaign = Campaign::where('job_id', $request->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $baseCurrency = auth()->user()->wallet->base_currency;
        $campaignAmount = currencyConverter($campaign->currency, $baseCurrency, $campaign->campaign_amount);
        $newWorkers = $request->new_number;

        // Calculate following postCampaign logic
        $baseAmount = $newWorkers * $campaignAmount;

        $isBusiness = auth()->user()->is_business;
        $percentRate = $isBusiness ? 100 : 60;
        $platformFee = ($percentRate / 100) * $baseAmount;

        $uploadFee = 0;
        if ($campaign->allow_upload) {
            $uploadFee = $newWorkers * currencyParameter($baseCurrency)->allow_upload;
        }

        $totalCost = $baseAmount + $platformFee + $uploadFee;
        $platformRevenue = $platformFee + $uploadFee;

        // Check wallet balance
        $walletValidity = checkWalletBalance(auth()->user(), $baseCurrency, $totalCost);

        if (!$walletValidity) {
            return back()->with('error', 'Insufficient funds in your wallet');
        }

        // Debit user wallet
        $debitWallet = debitWallet(auth()->user(), $baseCurrency, $totalCost);

        if (!$debitWallet) {
            return back()->with('error', 'Failed to process payment');
        }

        // Update campaign
        $campaign->number_of_staff += $newWorkers;
        $campaign->total_amount += $totalCost;
        $campaign->is_completed = false;
        $campaign->save();

        // Determine channel
        $channel = match ($baseCurrency) {
            'NGN' => 'paystack',
            'USD' => 'paypal',
            default => 'flutterwave'
        };

        $ref = time();

        // User transaction
        PaymentTransaction::create([
            'user_id' => auth()->id(),
            'campaign_id' => $campaign->id,
            'reference' => $ref,
            'amount' => $totalCost,
            'balance' => walletBalance(auth()->id()),
            'status' => 'successful',
            'currency' => $baseCurrency,
            'channel' => $channel,
            'type' => 'added_more_worker',
            'description' => "Added {$newWorkers} worker(s) for {$campaign->post_title} campaign",
            'tx_type' => 'Debit',
            'user_type' => 'regular'
        ]);

        // Credit admin
        $adminUser = User::find(1);
        $creditAdmin = creditWallet($adminUser, $baseCurrency, $platformRevenue);

        if ($creditAdmin) {
            PaymentTransaction::create([
                'user_id' => 1,
                'campaign_id' => $campaign->id,
                'reference' => $ref,
                'amount' => $platformRevenue,
                'balance' => walletBalance(1),
                'status' => 'successful',
                'currency' => $baseCurrency,
                'channel' => $channel,
                'type' => 'campaign_revenue_add',
                'description' => "Revenue for {$newWorkers} worker(s) added on {$campaign->post_title}",
                'tx_type' => 'Credit',
                'user_type' => 'admin'
            ]);
        }

        // Send email
        $content = "You have successfully added {$newWorkers} worker(s) to your campaign.";
        $subject = "Workers Added Successfully";
        Mail::to(auth()->user()->email)->send(new GeneralMail(auth()->user(), $content, $subject, ''));

        return redirect()->route('my.campaigns')->with('success', "{$newWorkers} worker(s) added successfully");
    }

    public function addMoreWorkersOld(Request $request)
    {

        $request->validate([
            'new_number' => 'required|numeric|min:1',
            'amount' => 'required|numeric',
            'revenue' => 'required|numeric',
            'total' => 'required|numeric',
            'id' => 'required'
        ]);

        $campaign = Campaign::where('job_id', $request->id)->first();
        $walletValidity = checkWalletBalance(auth()->user(), baseCurrency(), $request->total);

        if ($walletValidity) {
            $debitWallet = debitWallet(auth()->user(), baseCurrency(), $request->total);
            if ($debitWallet) {
                $basicAmount = $request->new_number * $request->campaign_amount;
                $campaign->number_of_staff += $request->new_number;
                $campaign->total_amount += $basicAmount;
                $campaign->is_completed = false;
                $campaign->save();

                $currency = baseCurrency();
                if (baseCurrency() == 'NGN') {
                    $channel = 'paystack';
                } elseif (baseCurrency() == 'USD') {
                    $channel = 'paypal';
                } else {
                    $channel = 'flutterwave';
                }
                $ref = time();

                PaymentTransaction::create([
                    'user_id' => auth()->user()->id,
                    'campaign_id' => $campaign->id,
                    'reference' => $ref,
                    'amount' => $basicAmount,
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
                $adminUser = User::where('id', 1)->first();
                $creditAdmin = creditWallet($adminUser, $adminUser->wallet->base_currency, $request->revenue);
                if ($creditAdmin) {
                    PaymentTransaction::create([
                        'user_id' => '1',
                        'campaign_id' => $campaign->id,
                        'reference' => $ref,
                        'amount' => $request->revenue,
                        'balance' => walletBalance('1'),
                        'status' => 'successful',
                        'currency' => $currency,
                        'channel' => $channel,
                        'type' => 'campaign_revenue_add',
                        'description' => 'Revenue for worker added on ' . $campaign->post_title . ' campaign',
                        'tx_type' => 'Credit',
                        'user_type' => 'admin'
                    ]);
                }


                $content = "You have successfully increased the number of your workers.";
                $subject = "Add More Worker";
                $user = User::where('id', auth()->user()->id)->first();
                Mail::to(auth()->user()->email)->send(new GeneralMail($user, $content, $subject, ''));

                return back()->with('success', $request->new_number . ' Worker(s) Added Successfully');
            }
        } else {
            return back()->with('error', 'You do not have suficient funds in your wallet');
        }
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
