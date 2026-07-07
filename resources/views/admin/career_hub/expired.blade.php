@extends('layouts.main.master')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Expired Job Approvals</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.career-hub.index') }}">Job Vacancies</a></li>
                    <li class="breadcrumb-item active">Expired</li>
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

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Expired Jobs — {{ $jobs->total() }}</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Tier</th>
                            <th>Posted By</th>
                            <th>Applications</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = ($jobs->currentPage() - 1) * $jobs->perPage() + 1; @endphp
                        @forelse($jobs as $job)
                            <tr>
                                <th>{{ $i++ }}</th>
                                <td class="fw-semibold">{{ $job->title }}</td>
                                <td>{{ $job->company_name }}</td>
                                <td><span class="badge bg-primary">{{ ucfirst($job->type) }}</span></td>
                                <td>
                                    {{-- @if($job->tier === 'premium')
                                        <span class="badge bg-warning">Premium</span>
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

                                </td>
                                <td>{{ $job->postedBy->name ?? '—' }}</td>
                                <td>{{ $job->applications_count }}</td>
                                <td>{{ $job->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.career-hub.show', $job) }}" class="btn btn-sm btn-alt-primary" title="Review">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        {{-- <form method="POST" action="{{ route('admin.career-hub.approve', $job) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success" title="Approve">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.career-hub.decline', $job) }}" class="d-inline"
                                              onsubmit="return confirm('Decline and refund if premium?')">
                                            @csrf
                                            <button class="btn btn-sm btn-danger" title="Decline">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-4 text-muted">No pending jobs.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($jobs->hasPages())
                    <div class="d-flex">{!! $jobs->links('pagination::bootstrap-4') !!}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
