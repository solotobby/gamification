@extends('layouts.main.master')

@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Career Hub Management</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Career Hub</li>
                    <li class="breadcrumb-item active" aria-current="page">Jobs List</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="block block-rounded text-center">
                <div class="block-content block-content-full">
                    <div class="fs-2 fw-bold text-dark">{{ $jobs->total() }}</div>
                    <div class="text-muted">Total Jobs</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="block block-rounded text-center">
                <div class="block-content block-content-full">
                    <div class="fs-2 fw-bold text-success">{{ $jobs->where('is_active', true)->count() }}</div>
                    <div class="text-muted">Active Jobs</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="block block-rounded text-center">
                <div class="block-content block-content-full">
                    <div class="fs-2 fw-bold text-warning">{{ $jobs->where('tier', 'premium')->count() }}</div>
                    <div class="text-muted">Premium Jobs</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="block block-rounded text-center">
                <div class="block-content block-content-full">
                    <div class="fs-2 fw-bold text-primary">{{ $jobs->sum('applications_count') }}</div>
                    <div class="text-muted">Total Applications</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Job Opportunities - {{ $jobs->total() }}</h3>
            <div class="block-options">
                <a href="{{ route('admin.career-hub.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus me-1"></i> Post New Job
                </a>
                <button type="button" class="btn-block-option">
                    <i class="si si-settings"></i>
                </button>
            </div>
        </div>

        <div class="block-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Tier</th>
                            <th>Status</th>
                            <th style="width: 100px;">Views</th>
                            <th style="width: 100px;">Applications</th>
                            <th>Posted</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = ($jobs->currentPage() - 1) * $jobs->perPage() + 1; @endphp
                        @forelse($jobs as $job)
                        <tr>
                            <th scope="row">{{ $i++ }}</th>
                            <td class="fw-semibold">
                                <a href="{{ route('career-hub.show', $job->slug) }}" target="_blank">
                                    {{ $job->title }}
                                </a>
                            </td>
                            <td>{{ $job->company_name }}</td>
                            <td>
                                <span class="badge bg-primary">{{ ucfirst($job->type) }}</span>
                            </td>
                            <td>
                                @if($job->tier === 'premium')
                                    <span class="badge bg-warning">Premium</span>
                                @else
                                    <span class="badge bg-secondary">Free</span>
                                @endif
                            </td>
                            <td>
                                @if($job->trashed())
                                    <span class="badge bg-danger">Deleted</span>
                                @elseif(!$job->is_active)
                                    <span class="badge bg-secondary">Inactive</span>
                                @elseif($job->expires_at && $job->expires_at < now())
                                    <span class="badge bg-warning">Expired</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>{{ number_format($job->views_count) }}</td>
                            <td>
                                <a href="{{ route('admin.career-hub.applications', $job) }}" class="fw-semibold">
                                    {{ number_format($job->applications_count) }}
                                </a>
                            </td>
                            <td>{{ $job->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    @if(!$job->trashed())
                                        <a href="{{ route('admin.career-hub.edit', $job) }}"
                                           class="btn btn-sm btn-alt-info"
                                           title="Edit">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.career-hub.applications', $job) }}"
                                           class="btn btn-sm btn-alt-success"
                                           title="Applications">
                                            <i class="fa fa-users"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.career-hub.destroy', $job) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete this job?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-danger" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <p class="text-muted mb-3">No jobs posted yet</p>
                                <a href="{{ route('admin.career-hub.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus me-1"></i> Post First Job
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($jobs->hasPages())
                    <div class="d-flex">
                        {!! $jobs->links('pagination::bootstrap-4') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>
@endsection
