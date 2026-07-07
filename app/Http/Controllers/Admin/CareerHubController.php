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

    // public function index()
    // {
    //     $jobs = JobListing::withTrashed()
    //         ->withCount('applications')
    //         ->latest()
    //         ->paginate(20);

    //     // return 'hello';
    //     return view('admin.career_hub.index', compact('jobs'));
    // }

    public function index()
    {
        $jobs = JobListing::withCount('applications')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->paginate(20);

        return view('admin.career_hub.index', compact('jobs'));
    }

    public function pause(JobListing $job)
    {
        $job->update(['is_active' => false, 'paused_at' => now()]);

        return back()->with('success', 'Job paused.');
    }

    public function resume(JobListing $job)
    {
        $job->update(['is_active' => true, 'paused_at' => null]);

        return back()->with('success', 'Job resumed.');
    }

    public function showPending()
    {
        $jobs = JobListing::withCount('applications')
            ->where('user_posted', true)
            ->where('is_active', false)
            ->whereNull('decision_reason')
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(20);

        return view('admin.career_hub.pending', compact('jobs'));
    }

    public function showDeclined()
    {
        $jobs = JobListing::withCount('applications')
            ->where('is_active', false)
            ->whereNotNull('decision_reason')
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(20);

        return view('admin.career_hub.declined', compact('jobs'));
    }

    public function showExpired()
    {
        $jobs = JobListing::withCount('applications')
            ->where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(20);

        return view('admin.career_hub.expired', compact('jobs'));
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
            'tier' => 'required|in:free,premium,sponsored',
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
            'tier' => 'required|in:free,premium,sponsored',
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

    // public function showPending()
    // {
    //     $jobs = JobListing::where('is_active', false)->latest()->paginate(20);
    //     return view('admin.career_hub.pending', compact('jobs'));
    // }


    public function approve(JobListing $job)
    {
        // $job->update(['is_active' => true]);
            $job->update(['is_active' => true, 'decision_reason' => null, 'paused_at' => null]);


        $user = User::find($job->posted_by);

        Mail::to($user->email)->send(new GeneralMail(
            $user,
            'Your job Vacancy listing <strong>' . $job->title . '</strong> has been approved and is now live on the platform.',
            'Job Listing Approved 🎉',
            ''
        ));

        app(NotificationHelpers::class)->createNotification(
            $user,
            'Job Listing Approved',
            'Your job Vacancy listing "' . $job->title . '" has been approved and is now live!',
            'job_listing'
        );



        return back()->with('success', 'Job approved and user notified.');
    }

    public function decline(JobListing $job, Request $request)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $reason = $request->reason ?? 'No reason provided.';
        $alreadyProcessed = !is_null($job->decision_reason); // guards against double refund
        $isPaidTier = in_array($job->tier, ['premium', 'sponsored']);

        $job->update([
            'is_active'       => false,
            'decision_reason' => $reason,
            'paused_at'       => null,
        ]);

        $user = User::find($job->posted_by);
        $refundNote = '';

        if ($isPaidTier && !$alreadyProcessed) {
            $currency = baseCurrency($user);
            dd($currency);
            $amount   = $currency->job_listing_amount;

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
                'description' => 'Refund for declined ' . ucfirst($job->tier) . ' job: ' . $job->title,
                'tx_type'     => 'Credit',
                'user_type'   => 'regular',
            ]);

            $refundNote = 'A refund has been credited to your wallet.';
        }

        Mail::to($user->email)->send(new GeneralMail(
            $user,
            'Your job Vacancy listing <strong>' . $job->title . '</strong> has been declined.<br><br>'
                . '<strong>Reason:</strong> ' . $reason . $refundNote,
            'Job Listing Declined',
            ''
        ));

        app(NotificationHelpers::class)->createNotification(
            $user,
            'Job Listing Declined',
            'Your job Vacancy listing "' . $job->title . '" was declined. Reason: ' . $reason . $refundNote,
            'job_listing'
        );

        return back()->with('success', 'Job declined' . ($refundNote ? ' and user refunded.' : '.'));
    }

    // public function decline(JobListing $job, Request $request)
    // {
    //     $request->validate(['reason' => 'nullable|string|max:500']);

    //     $job->update(['is_active' => false]);

    //     $user   = User::find($job->posted_by);
    //     $reason = $request->reason ?? 'No reason provided.';

    //     if ($job->tier === 'premium') {
    //         $currency = baseCurrency($user);
    //         $amount   = $currency->job_listing_amount ?? 5000;

    //         creditWallet($user, $currency, $amount);

    //         PaymentTransaction::create([
    //             'user_id'     => $job->posted_by,
    //             'campaign_id' => 1,
    //             'reference'   => time(),
    //             'amount'      => $amount,
    //             'balance'     => walletBalance($job->posted_by),
    //             'status'      => 'successful',
    //             'currency'    => $currency,
    //             'channel'     => 'wallet',
    //             'type'        => 'career_hub_job_refund',
    //             'description' => 'Refund for declined premium job: ' . $job->title,
    //             'tx_type'     => 'Credit',
    //             'user_type'   => 'regular',
    //         ]);
    //     }

    //     $refundNote = $job->tier === 'premium' ? 'A refund has been credited to your wallet.' : '';

    //     Mail::to($user->email)->send(new GeneralMail(
    //         $user,
    //         'Your job listing <strong>' . $job->title . '</strong> has been declined.<br><br>'
    //             . '<strong>Reason:</strong> ' . $reason . $refundNote,
    //         'Job Listing Declined',
    //         ''
    //     ));

    //     app(NotificationHelpers::class)->createNotification(
    //         $user,
    //         'Job Listing Declined',
    //         'Your job listing "' . $job->title . '" was declined. Reason: ' . $reason . $refundNote,
    //         'job_listing'
    //     );



    //     return back()->with('success', 'Job declined' . ($job->tier === 'premium' ? ' and user refunded.' : '.'));
    // }
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
