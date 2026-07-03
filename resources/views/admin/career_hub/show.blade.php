@extends('layouts.main.master')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $job->title }}</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.career-hub.index') }}">Job Vacancies</a></li>
                    <li class="breadcrumb-item active">View Job</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Job Details --}}
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Job Details</h3>
                    <div class="block-options">
                        @if($job->user_posted)
                            <span class="badge bg-info fs-sm">User Submitted</span>
                        @endif
                        @if($job->is_active)
                            <span class="badge bg-success fs-sm">Active</span>
                        @else
                            <span class="badge bg-warning fs-sm">Inactive / Pending</span>
                        @endif
                    </div>
                </div>
                <div class="block-content">
                    <table class="table table-bordered">
                        <tr><th style="width:200px">Title</th><td>{{ $job->title }}</td></tr>
                        <tr><th>Company</th><td>{{ $job->company_name }}</td></tr>
                        <tr><th>Type</th><td>{{ ucfirst($job->type) }}</td></tr>
                        <tr><th>Tier</th><td>
                            @if($job->tier === 'premium')
                                <span class="badge bg-warning">Premium</span>
                            @else
                                <span class="badge bg-secondary">Free</span>
                            @endif
                        </td></tr>
                        <tr><th>Location</th><td>{{ $job->location }} {{ $job->remote_allowed ? '(Remote OK)' : '' }}</td></tr>
                        <tr><th>Salary</th><td>{{ $job->salary_range ?? 'Not specified' }}</td></tr>
                        <tr><th>Application Link</th><td>
                            @if($job->application_link)
                                <a href="{{ $job->application_link }}" target="_blank">{{ $job->application_link }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </td></tr>
                        <tr><th>Company Website</th><td>
                            @if($job->company_website)
                                <a href="{{ $job->company_website }}" target="_blank">{{ $job->company_website }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </td></tr>
                        <tr><th>Deadline</th><td>{{ $job->expires_at ? $job->expires_at->format('M d, Y') : 'No deadline' }}</td></tr>
                        <tr><th>Posted By</th><td>{{ $job->postedBy->name ?? 'Admin' }} {{ $job->user_posted ? '(User)' : '(Admin)' }}</td></tr>
                        <tr><th>Applications</th><td>{{ $job->applications_count }}</td></tr>
                        <tr><th>Posted</th><td>{{ $job->created_at->format('M d, Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>

            @foreach([
                'description'      => 'Description',
                'responsibilities' => 'Responsibilities',
                'requirements'     => 'Requirements',
                'benefits'         => 'Benefits',
                'company_description' => 'About the Company',
            ] as $field => $label)
                @if(!empty($job->$field))
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ $label }}</h3>
                        </div>
                        <div class="block-content">
                            <p style="white-space:pre-line;font-size:.9rem">{{ $job->$field }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="col-lg-4">
            {{-- Company Logo --}}
            @if($job->company_logo)
                <div class="block block-rounded">
                    <div class="block-header block-header-default"><h3 class="block-title">Company Logo</h3></div>
                    <div class="block-content text-center py-3">
                        <img src="{{ displayImage($job->company_logo) }}" alt="Logo" style="max-height:100px">
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="block block-rounded">
                <div class="block-header block-header-default"><h3 class="block-title">Actions</h3></div>
                <div class="block-content py-3 d-flex flex-column gap-2">

                    @if($job->user_posted)
                        {{-- @if(!$job->is_active) --}}
                            <form method="POST" action="{{ route('admin.career-hub.approve', $job) }}">
                                {{-- @csrf --}}
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fa fa-check me-1"></i> Approve & Publish
                                </button>
                            </form>
                        {{-- @else --}}
                            <form method="POST" action="{{ route('admin.career-hub.decline', $job) }}">
                                {{-- @csrf --}}
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fa fa-times me-1"></i> Deactivate
                                </button>
                            </form>
                        {{-- @endif --}}
                    @endif

                    <a href="{{ route('admin.career-hub.edit', $job) }}" class="btn btn-alt-info w-100">
                        <i class="fa fa-pencil-alt me-1"></i> Edit Job
                    </a>
                    <a href="{{ route('admin.career-hub.applications', $job) }}" class="btn btn-alt-primary w-100">
                        <i class="fa fa-users me-1"></i> View Applications ({{ $job->applications_count }})
                    </a>
                    <form method="POST" action="{{ route('admin.career-hub.destroy', $job) }}"
                          onsubmit="return confirm('Delete this job?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-alt-danger w-100">
                            <i class="fa fa-trash me-1"></i> Delete Job
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
