@extends('layouts.main.master')
@section('style')
<style>
    .cp-stat-card {
        background: #fff;
        border: 1px solid #E5E9F0;
        border-radius: 12px;
        padding: 1.1rem;
        text-align: center;
    }
    .cp-stat-num { font-size: 1.6rem; font-weight: 800; color: #1565D8; }
    .cp-stat-label { font-size: .78rem; color: #64748B; margin-top: .2rem; }

    .cp-filters { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .cp-filter-btn {
        padding: .4rem 1rem; border-radius: 20px; font-size: .82rem; font-weight: 600;
        border: 1.5px solid #E5E9F0; background: #fff; color: #64748B; text-decoration: none; transition: all .15s;
    }
    .cp-filter-btn:hover { border-color: #1565D8; color: #1565D8; }
    .cp-filter-btn.active { background: #1565D8; color: #fff; border-color: #1565D8; }

    .cp-status-pill { font-size: .7rem; font-weight: 700; padding: .18rem .65rem; border-radius: 20px; }
    .cp-status-public { background: #D1FAE5; color: #065F46; }
    .cp-status-private { background: #E5E7EB; color: #4B5563; }
    .cp-completeness-bar { width: 80px; height: 6px; border-radius: 4px; background: #F1F5F9; overflow: hidden; display: inline-block; vertical-align: middle; margin-right: .4rem; }
    .cp-completeness-fill { height: 100%; background: #1565D8; }
</style>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Career Profiles</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Career Profiles</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- General analytics --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-2">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($stats['total']) }}</div><div class="cp-stat-label">Total Profiles</div></div>
        </div>
        <div class="col-6 col-md-2">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($stats['public_count']) }}</div><div class="cp-stat-label">Public</div></div>
        </div>
        <div class="col-6 col-md-2">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($stats['private_count']) }}</div><div class="cp-stat-label">Private</div></div>
        </div>
        <div class="col-6 col-md-2">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ $stats['avg_completeness'] }}%</div><div class="cp-stat-label">Avg. Completeness</div></div>
        </div>
        <div class="col-6 col-md-2">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($stats['with_cv']) }}</div><div class="cp-stat-label">With CV Uploaded</div></div>
        </div>
        <div class="col-6 col-md-2">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($stats['total_profile_views']) }}</div><div class="cp-stat-label">Total Profile Views</div></div>
        </div>
    </div>

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Profiles</h3>
        </div>
        <div class="block-content">
            <div class="cp-filters">
                <a href="{{ route('admin.career-profiles.index') }}" class="cp-filter-btn {{ !request('status') ? 'active' : '' }}">All</a>
                <a href="{{ route('admin.career-profiles.index', ['status' => 'public']) }}" class="cp-filter-btn {{ request('status') === 'public' ? 'active' : '' }}">Public</a>
                <a href="{{ route('admin.career-profiles.index', ['status' => 'private']) }}" class="cp-filter-btn {{ request('status') === 'private' ? 'active' : '' }}">Private</a>
            </div>

            <form method="GET" action="{{ route('admin.career-profiles.index') }}" class="mb-3">
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                <div class="row g-2">
                    <div class="col-md-9">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or professional title…" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-alt-secondary w-100"><i class="fa fa-search opacity-50 me-1"></i> Search</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Title</th>
                            <th>Completeness</th>
                            <th>CV</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th style="width:160px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profiles as $profile)
                            <tr>
                                <td>
                                    <a href="{{ url('user/'.$profile->user->id.'/info') }}" target="_blank">{{ $profile->user->name ?? 'Unknown' }}</a>
                                </td>
                                <td>{{ $profile->professional_title ?? '—' }}</td>
                                <td>
                                    <span class="cp-completeness-bar"><span class="cp-completeness-fill" style="width:{{ $profile->profile_completeness }}%"></span></span>
                                    {{ $profile->profile_completeness }}%
                                </td>
                                <td>
                                    @if($profile->cv_file_path)
                                        <a href="{{ $profile->cv_file_path }}" target="_blank"><i class="fa fa-file-pdf"></i> View</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="cp-status-pill {{ $profile->is_public ? 'cp-status-public' : 'cp-status-private' }}">
                                        {{ $profile->is_public ? 'Public' : 'Private' }}
                                    </span>
                                </td>
                                <td>{{ $profile->created_at->format('d M, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.career-profiles.show', $profile->id) }}" class="btn btn-sm btn-alt-primary">Manage</a>
                                        <form action="{{ route('admin.career-profiles.toggle-publish', $profile->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $profile->is_public ? 'btn-alt-warning' : 'btn-alt-success' }}">
                                                {{ $profile->is_public ? 'Unpublish' : 'Publish' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No career profiles found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex">
                    {!! $profiles->appends(request()->query())->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
