<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{JobListing, JobApplication};
use Illuminate\Http\Request;
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
            'type' => 'required|in:fulltime,parttime,contract,internship,gig',
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
            'type' => 'required|in:fulltime,parttime,contract,internship,gig',
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
