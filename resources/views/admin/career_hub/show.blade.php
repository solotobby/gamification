@extends('layouts.main.master')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $job->title }}</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.career-hub.index') }}">Job Vacancies</a></li>
                    @if($job->user_posted && !$job->is_active)
                        <li class="breadcrumb-item"><a href="{{ route('admin.career-hub.pending') }}">Pending</a></li>
                    @endif
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
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Job Details</h3>
                    {{-- <div class="block-options d-flex gap-2">
                        @if($job->user_posted)
                            <span class="badge bg-info">User Submitted</span>
                        @endif
                        @if($job->trashed())
                            <span class="badge bg-danger">Deleted</span>
                        @elseif($job->user_posted && !$job->is_active)
                            <span class="badge bg-warning">Pending Approval</span>
                        @elseif($job->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div> --}}
                    <div class="block-options d-flex gap-2">
                        @if($job->user_posted)
                            <span class="badge bg-info">User Submitted</span>
                        @endif
                        @if($job->trashed())
                            <span class="badge bg-danger">Deleted</span>
                        @elseif($job->paused_at)
                            <span class="badge bg-dark">Paused</span>
                        @elseif($job->user_posted && !$job->is_active && is_null($job->decision_reason))
                            <span class="badge bg-warning">Pending Approval</span>
                        @elseif(!$job->is_active && $job->decision_reason)
                            <span class="badge bg-danger">Declined</span>
                        @elseif($job->is_active && $job->expires_at && $job->expires_at < now())
                            <span class="badge bg-warning">Expired</span>
                        @elseif($job->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="block-content">
                    <table class="table table-bordered">
                        <tr><th style="width:200px">Title</th><td>{{ $job->title }}</td></tr>
                        <tr><th>Company</th><td>{{ $job->company_name }}</td></tr>
                        <tr><th>Type</th><td><span class="badge bg-primary">{{ ucfirst($job->type) }}</span></td></tr>
                        <tr><th>Tier</th><td>
                            {{-- @if($job->tier === 'premium')
                                <span class="badge bg-warning">⭐ Premium</span>
                            @else
                                <span class="badge bg-secondary">Free</span>
                            @endif --}}
                            @if($job->tier === 'premium')
                                <span class="badge bg-warning">Premium</span>
                            @elseif($job->tier === 'sponsored')
                                <span class="badge bg-info">⭐ Sponsored</span>
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
                        <tr><th>Views</th><td>{{ number_format($job->views_count) }}</td></tr>
                        <tr><th>Applications</th><td>{{ $job->applications_count }}</td></tr>
                        <tr><th>Posted</th><td>{{ $job->created_at->format('M d, Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>

            @foreach([
                'description'         => 'Description',
                'responsibilities'    => 'Responsibilities',
                'requirements'        => 'Requirements',
                'benefits'            => 'Benefits',
                'company_description' => 'About the Company',
            ] as $field => $label)
                @if(!empty($job->$field))
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ $label }}</h3>
                        </div>
                        <div class="block-content">
                            <p style="white-space:pre-line;font-size:.9rem;color:#444">{{ $job->$field }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="col-lg-4">
            @if($job->company_logo)
                <div class="block block-rounded">
                    <div class="block-header block-header-default"><h3 class="block-title">Company Logo</h3></div>
                    <div class="block-content text-center py-3">
                        <img src="{{ displayImage($job->company_logo) }}" alt="Logo" style="max-height:100px">
                    </div>
                </div>
            @endif

            <div class="block block-rounded">
                <div class="block-header block-header-default"><h3 class="block-title">Actions</h3></div>
                <div class="block-content py-3 d-flex flex-column gap-2">

                    @if($job->user_posted && !$job->is_active && is_null($job->decision_reason) && !$job->trashed())
                        <button type="button" class="btn btn-success w-100" onclick="document.getElementById('approve-modal').style.display='flex'">
                            <i class="fa fa-check me-1"></i> Approve & Publish
                        </button>
                        <button type="button" class="btn btn-danger w-100" onclick="document.getElementById('decline-modal').style.display='flex'">
                            <i class="fa fa-times me-1"></i> Decline{{ $job->tier !== 'free' ? ' & Refund' : '' }}
                        </button>

                    @elseif($job->is_active && !$job->trashed())
                        <form method="POST" action="{{ route('admin.career-hub.pause', $job) }}">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fa fa-pause me-1"></i> Pause Listing
                            </button>
                        </form>

                    @elseif($job->paused_at && !$job->trashed())
                        <form method="POST" action="{{ route('admin.career-hub.resume', $job) }}">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fa fa-play me-1"></i> Resume Listing
                            </button>
                        </form>

                    @elseif(!$job->is_active && !$job->user_posted && !is_null($job->decision_reason) === false && !$job->trashed())
                        <button type="button" class="btn btn-success w-100" onclick="document.getElementById('approve-modal').style.display='flex'">
                            <i class="fa fa-play me-1"></i> Activate
                        </button>
                    @endif

                    {{-- @if($job->user_posted && !$job->is_active && !$job->trashed())
                        <button type="button" class="btn btn-success w-100"
                                onclick="document.getElementById('approve-modal').style.display='flex'">
                            <i class="fa fa-check me-1"></i> Approve & Publish
                        </button>


                        <button type="button" class="btn btn-danger w-100"
                                onclick="document.getElementById('decline-modal').style.display='flex'">
                            <i class="fa fa-times me-1"></i> Decline{{ $job->tier === 'premium' ? ' & Refund' : '' }}
                        </button>

                    @elseif($job->is_active && !$job->trashed())
                        <button type="button" class="btn btn-warning w-100"
                                onclick="document.getElementById('decline-modal').style.display='flex'">
                            <i class="fa fa-pause me-1"></i> Deactivate
                        </button>

                    @elseif(!$job->is_active && !$job->user_posted && !$job->trashed())
                        <button type="button" class="btn btn-success w-100"
                                onclick="document.getElementById('approve-modal').style.display='flex'">
                            <i class="fa fa-play me-1"></i> Activate
                        </button>
                    @endif --}}

                    {{-- @if(!$job->trashed())
                        <a href="{{ route('admin.career-hub.edit', $job) }}" class="btn btn-alt-info w-100">
                            <i class="fa fa-pencil-alt me-1"></i> Edit Job
                        </a>
                        <a href="{{ route('admin.career-hub.applications', $job) }}" class="btn btn-alt-primary w-100">
                            <i class="fa fa-users me-1"></i> Applications ({{ $job->applications_count }})
                        </a>
                        <form method="POST" action="{{ route('admin.career-hub.destroy', $job) }}"
                              onsubmit="return confirm('Permanently delete this job?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-alt-danger w-100">
                                <i class="fa fa-trash me-1"></i> Delete Job
                            </button>
                        </form>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Approve Modal --}}
<div id="approve-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:1.75rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2)">
        <h5 class="fw-bold mb-2">Approve Job Listing</h5>
        <p class="text-muted" style="font-size:.875rem">
            Are you sure you want to approve <strong>{{ $job->title }}</strong>?
            It will go live immediately and the user will be notified by email and push notification.
        </p>
        <div class="d-flex gap-2 justify-content-end mt-3">
            <button class="btn btn-secondary" onclick="document.getElementById('approve-modal').style.display='none'">
                Cancel
            </button>
            <form method="POST" action="{{ route('admin.career-hub.approve', $job) }}">
                @csrf
                <button type="submit" class="btn btn-success">Yes, Approve</button>
            </form>
        </div>
    </div>
</div>

{{-- Decline Modal --}}
<div id="decline-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:1.75rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2)">
        <h5 class="fw-bold mb-2">
            {{ $job->is_active ? 'Deactivate' : 'Decline' }} Job Listing
        </h5>
        <p class="text-muted" style="font-size:.875rem">
            Are you sure you want to {{ $job->is_active ? 'deactivate' : 'decline' }}
            <strong>{{ $job->title }}</strong>?
           @if(in_array($job->tier, ['premium', 'sponsored']) && !$job->is_active)
                The user will be refunded automatically.
            @endif
            The user will be notified by email and push notification.
        </p>
        <div class="mb-3">
            <label class="fw-semibold" style="font-size:.83rem">
                Reason <span class="text-muted fw-normal">(optional)</span>
            </label>
            <textarea id="modal-reason" class="form-control form-control-sm mt-1" rows="3"
                      placeholder="e.g. Incomplete information, policy violation..."></textarea>
        </div>
        <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-secondary" onclick="document.getElementById('decline-modal').style.display='none'">
                Cancel
            </button>
            <form method="POST" action="{{ route('admin.career-hub.decline', $job) }}" id="decline-form">
                @csrf
                <input type="hidden" name="reason" id="decline-reason-input">
                <button type="submit" class="btn btn-danger"
                        onclick="document.getElementById('decline-reason-input').value=document.getElementById('modal-reason').value">
                    Yes, {{ $job->is_active ? 'Deactivate' : 'Decline' }}
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Close modals on backdrop click --}}
<script>
['approve-modal','decline-modal'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});
</script>
@endsection
