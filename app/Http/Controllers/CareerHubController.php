<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;

class CareerHubController extends Controller
{

    public function jobs(Request $request){
        return redirect(route('career-hub.index'));
    }
    public function index(Request $request)
    {
        $jobs = JobListing::active()
            ->filter($request->only([
                'type',
                'tier',
                'location',
                'salary_min',
                'salary_max',
                'remote',
                'search'
            ]))
            ->with('postedBy:id,name')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            // 'total_jobs' => JobListing::active()->count(),
            // 'premium_jobs' => JobListing::active()->where('tier', 'premium')->count(),
            // 'companies' => JobListing::active()->distinct('company_name')->count('company_name'),
        ];

        return view('career-hub.index', compact('jobs', 'stats'));
    }

    public function show(JobListing $job)
    {
        // Check premium access
        if (!$job->canView(auth()->user())) {
            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('warning', 'Please login to view premium opportunities');
            }

            if (!auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')
                    ->with('warning', 'Please verify your email to access premium opportunities');
            }
        }

        $job->incrementViews();
        $job->load('postedBy:id,name');

        $relatedJobs = JobListing::active()
            ->where('id', '!=', $job->id)
            ->where('tier', '=', 'free')
            ->where(function ($q) use ($job) {
                $q->where('type', $job->type)
                    ->orWhere('location', 'like', "%{$job->location}%");
            })
            ->limit(3)
            ->get();

        return view('career-hub.show', compact('job', 'relatedJobs'));
    }

    public function apply(Request $request, JobListing $job)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('warning', 'Please login to apply');
        }

        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Please verify your email to apply');
        }

        if ($job->hasApplied(auth()->user())) {
            return back()->with('info', 'You have already applied to this position');
        }

        $validated = $request->validate([
            'cover_letter' => 'nullable|string|max:5000',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {

            $resumePath = uploadFileToCloudinary(
                $request->file('resume'),
                'files',
            );
;
        }


        $job->applications()->create([
            'user_id' => auth()->id(),
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_path' => $resumePath
        ]);

        $job->increment('applications_count');

        return back()->with('success', 'Application submitted successfully!');
    }
}
