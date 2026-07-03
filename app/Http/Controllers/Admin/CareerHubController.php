<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NotificationHelpers;
use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Models\{JobListing, JobApplication, PaymentTransaction, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CareerHubController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $jobs = JobListing::withTrashed()
            ->withCount('applications')
            ->latest()
            ->paginate(20);

        // return 'hello';
        return view('admin.career_hub.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.career_hub.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'type' => 'required|in:fulltime,parttime,contract,internship,gig,nysc',
            'tier' => 'required|in:free,premium',
            'location' => 'required|string|max:255',
            'remote_allowed' => 'boolean',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'currency' => 'nullable|string|max:3',
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|max:2048',
            'company_description' => 'nullable|string',
            'company_website' => 'nullable|url',
            'application_link' => 'nullable|url',
            'expires_at' => 'nullable|date|after:today'
        ]);

        $validated['posted_by'] = auth()->id();
        $validated['remote_allowed'] = $request->boolean('remote_allowed');

        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = uploadImageToCloudinary(
                $request->file('company_logo'),
                'company-logos'
            );
        }

        JobListing::create($validated);

        return redirect()->route('admin.career-hub.index')
            ->with('success', 'Job opportunity posted successfully!');
    }

    public function edit(JobListing $job)
    {
        return view('admin.career_hub.edit', compact('job'));
    }

    public function update(Request $request, JobListing $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'type' => 'required|in:fulltime,parttime,contract,internship,gig,nysc',
            'tier' => 'required|in:free,premium',
            'location' => 'required|string|max:255',
            'remote_allowed' => 'boolean',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'currency' => 'nullable|string|max:3',
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|max:2048',
            'company_description' => 'nullable|string',
            'company_website' => 'nullable|url',
            'application_link' => 'nullable|url',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        $validated['remote_allowed'] = $request->boolean('remote_allowed');
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = uploadImageToCloudinary(
                $request->file('company_logo'),
                'company-logos'
            );
        }

        $job->update($validated);

        return redirect()->route('admin.career-hub.index')
            ->with('success', 'Job opportunity updated successfully!');
    }

    public function destroy(JobListing $job)
    {
        $job->delete();

        return back()->with('success', 'Job opportunity deleted successfully');
    }

    public function show(JobListing $job)
    {
        $job->loadCount('applications');
        return view('admin.career_hub.show', compact('job'));
    }

    public function showPending()
    {
        $jobs = JobListing::where('is_active', false)->latest()->paginate(20);
        return view('admin.career_hub.pending', compact('jobs'));
    }


    public function approve(JobListing $job)
    {
        $job->update(['is_active' => true]);
        return back()->with('success', 'Job approved and is now live.');
    }

    // public function decline(JobListing $job)
    // {
    //     $job->update(['is_active' => false]);

    //     if($job->tier = 'premium') {
    //         $user = User::where('id', $job->posted_by)->first();
    //         $currency = baseCurrency($user);
    //         creditWallet($user, $currency, 1000);

    //         PaymentTransaction::create([
    //             'user_id' =>  $job->posted_by,
    //             'reference' => time(),
    //             'amount' =>  1000,
    //             'balance' => walletBalance($job->posted_by),
    //             'status' => 'successful',
    //             'currency' => $currency,
    //             'channel' => 'flutterwave',
    //             'type' => 'career_hub_job_declined',
    //             'description' => 'Refund for declined premium job post: ' . $job->title,
    //             'tx_type' => 'Credit',
    //             'user_type' => 'regular'
    //         ]);
    //      $user = User::where('id', $job->posted_by)->first();
    //         if ($job->currency == 'NGN') {
    //             $currency = 'NGN';
    //             $channel = 'paystack';
    //             creditWallet($user, $currency, $workDone->amount);
    //         } elseif ($campaign->currency == 'USD') {
    //             $currency = 'USD';
    //             $channel = 'paypal';
    //             creditWallet($user, $currency, $workDone->amount);
    //         } else {
    //             $currency = baseCurrency($user);
    //             $channel = 'flutterwave';
    //             creditWallet($user, $currency, $workDone->amount);
    //         }


    //         $ref = time();

    //         PaymentTransaction::create([
    //             'user_id' =>  $workDone->user_id,
    //             'campaign_id' =>  $workDone->campaign->id,
    //             'reference' => $ref,
    //             'amount' =>  $workDone->amount,
    //             'balance' => walletBalance($workDone->user_id),
    //             'status' => 'successful',
    //             'currency' => $currency,
    //             'channel' => $channel,
    //             'type' => 'campaign_payment_dispute_resolved',
    //             'description' => 'Campaign Dispute Resolution for ' . $workDone->campaign->post_title,
    //             'tx_type' => 'Credit',
    //             'user_type' => 'regular'
    //         ]);

    //         $subject = 'Disputed Task - ' . $request->status;
    //         $status = $request->status;
    //         Mail::to($workDone->user->email)->send(new ResolveDispute($workDone, $subject, $status, $request->reason));
    //     }

    //     return back()->with('success', 'Job declined.');
    // }


    public function decline(JobListing $job)
    {
        $job->update(['is_active' => false]);

        $user = User::find($job->posted_by);

        if ($job->tier === 'premium') {
            // $currency = $job->currency ?? 'NGN';
            $currency = baseCurrency($user);
            // $amount   = 5000;
            $amount       = $currency->job_listing_amount ?? 5000;


            creditWallet($user, $currency, $amount);

            PaymentTransaction::create([
                'user_id'     => $job->posted_by,
                'campaign_id' => 1,
                'reference'   => time(),
                'amount'      => $amount,
                'balance'     => walletBalance($job->posted_by),
                'status'      => 'successful',
                'currency'    => $currency,
                'channel'     => 'wallet',
                'type'        => 'career_hub_job_refund',
                'description' => 'Refund for declined premium job: ' . $job->title,
                'tx_type'     => 'Credit',
                'user_type'   => 'regular',
            ]);
        }

        // Notification
        app(NotificationHelpers::class)->createNotification(
            $user,
            'Job Listing Declined',
            'Your job listing "' . $job->title . '" has been declined.' . ($job->tier === 'premium' ? ' A refund of ' . ($job->currency ?? 'NGN') . ' 5,000 has been credited to your wallet.' : ''),
            'job_listing'
        );

        // Mail
        $content = 'Your job listing <strong>' . $job->title . '</strong> has been reviewed and declined.'
            . ($job->tier === 'premium' ? ' A refund of ' . ($job->currency ?? 'NGN') . ' 5,000 has been credited to your wallet.' : '')
            . ' You may post a new listing or contact support if you have questions.';

        Mail::to($user->email)->send(new GeneralMail($user, $content, 'Job Listing Declined', ''));

        return back()->with('success', 'Job declined' . ($job->tier === 'premium' ? ' and user refunded.' : '.'));
    }
    public function applications(JobListing $job)
    {
        $applications = $job->applications()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.career_hub.applications', compact('job', 'applications'));
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,rejected,accepted'
        ]);

        $application->update($validated);

        return back()->with('success', 'Application status updated');
    }
}
