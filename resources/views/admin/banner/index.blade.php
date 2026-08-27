@extends('layouts.main.master')
@section('style')
<style>
    .bn-status-pill {
        font-size: .72rem;
        font-weight: 700;
        padding: .2rem .7rem;
        border-radius: 20px;
        display: inline-block;
    }
    .bn-status-pending  { background: #FEF3C7; color: #92400E; }
    .bn-status-live     { background: #D1FAE5; color: #065F46; }
    .bn-status-paused   { background: #DBEAFE; color: #1E40AF; }
    .bn-status-rejected { background: #FEE2E2; color: #991B1B; }
    .bn-status-ended    { background: #E5E7EB; color: #4B5563; }

    .bn-filters {
        display: flex;
        gap: .4rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    .bn-filter-btn {
        padding: .4rem 1rem;
        border-radius: 20px;
        font-size: .82rem;
        font-weight: 600;
        border: 1.5px solid #E5E9F0;
        background: #fff;
        color: #64748B;
        text-decoration: none;
        transition: all .15s;
    }
    .bn-filter-btn:hover { border-color: #1565D8; color: #1565D8; }
    .bn-filter-btn.active { background: #1565D8; color: #fff; border-color: #1565D8; }
</style>
@endsection

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Banners</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Ad Banner</li>
                    <li class="breadcrumb-item active" aria-current="page">List</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Banners</h3>
        </div>
        <div class="block-content">
            @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif

            <div class="bn-filters">
                <a href="{{ route('admin.banner.index') }}" class="bn-filter-btn {{ !request('status') ? 'active' : '' }}">All</a>
                <a href="{{ route('admin.banner.index', ['status' => 'pending']) }}" class="bn-filter-btn {{ request('status') === 'pending' ? 'active' : '' }}">Pending Review</a>
                <a href="{{ route('admin.banner.index', ['status' => 'live']) }}" class="bn-filter-btn {{ request('status') === 'live' ? 'active' : '' }}">Live</a>
                <a href="{{ route('admin.banner.index', ['status' => 'paused']) }}" class="bn-filter-btn {{ request('status') === 'paused' ? 'active' : '' }}">Paused</a>
                <a href="{{ route('admin.banner.index', ['status' => 'rejected']) }}" class="bn-filter-btn {{ request('status') === 'rejected' ? 'active' : '' }}">Rejected</a>
                <a href="{{ route('admin.banner.index', ['status' => 'ended']) }}" class="bn-filter-btn {{ request('status') === 'ended' ? 'active' : '' }}">Ended</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Budget</th>
                            <th>Clicks</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bannerList as $banner)
                            <tr>
                                <td>{{ $banner->banner_id }}</td>
                                <td>
                                    <a href="{{ url('user/'.$banner->user->id.'/info') }}" target="_blank">{{ $banner->user->name ?? 'Unknown' }}</a>
                                </td>
                                <td>
                                    @if($banner->currency == 'NGN')
                                        &#8358;{{ number_format($banner->amount, 2) }}
                                    @else
                                        {{ number_format($banner->amount, 2) }}
                                    @endif
                                </td>
                                <td>{{ $banner->click_count ?? '0' }}/{{ $banner->clicks }}</td>
                                <td>
                                    {{-- FIX: live_state is the string "Under Review" at creation, never null —
                                         the old `== null` check meant pending banners never matched here and
                                         silently fell through to "Ended". Checking the real value now. --}}
                                    @if($banner->live_state === 'Under Review' || $banner->live_state === null)
                                        <span class="bn-status-pill bn-status-pending">Under Review</span>
                                    @elseif($banner->live_state === 'Started')
                                        <span class="bn-status-pill bn-status-live">Live</span>
                                    @elseif($banner->live_state === 'Paused')
                                        <span class="bn-status-pill bn-status-paused">Paused</span>
                                    @elseif($banner->live_state === 'Rejected')
                                        <span class="bn-status-pill bn-status-rejected">Rejected</span>
                                    @else
                                        <span class="bn-status-pill bn-status-ended">
                                            Ended {{ $banner->banner_end_date ? 'on ' . \Carbon\Carbon::parse($banner->banner_end_date)->format('d M, Y') : '' }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($banner->created_at)->format('d F, Y') }}</td>
                                <td>
                                    <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $banner->id }}">View</button>
                                </td>
                            </tr>

                            <div class="modal fade" id="modal-default-popout-{{ $banner->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body pb-1">
                                            <div class="col-xl-12">
                                                <div class="block block-rounded">
                                                    <div class="block-header block-header-default">
                                                        <h3 class="block-title">{{ $banner->banner_id }}</h3>
                                                    </div>
                                                    <div class="block-content">
                                                        <img src="{{ displayImage($banner->banner_url) }}" width="100%" height="300" class="img-responsive mb-4">

                                                        <ul class="list-group push">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                @if($banner->currency == 'NGN')
                                                                    Amount - &#8358;{{ number_format($banner->amount, 2) }}
                                                                @else
                                                                    Amount - {{ number_format($banner->amount, 2) }}
                                                                @endif
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Creator - {{ $banner->user->name ?? 'Unknown' }}
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                External Link - <a href="{{ url($banner->external_link) }}" target="_blank">{{ $banner->external_link }}</a>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Clicks - {{ $banner->click_count ?? '0' }}/{{ $banner->clicks }}
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Impression - {{ $banner->impression }}
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                @if($banner->live_state === 'Under Review' || $banner->live_state === null)
                                                                    Status - Under Review
                                                                @elseif($banner->live_state === 'Started')
                                                                    Status - Started
                                                                @elseif($banner->live_state === 'Paused')
                                                                    Status - Paused
                                                                @elseif($banner->live_state === 'Rejected')
                                                                    Status - Rejected
                                                                @else
                                                                    Status - Ended on {{ \Carbon\Carbon::parse($banner->banner_end_date)->format('d F, Y') }}
                                                                @endif
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Date Created - {{ \Carbon\Carbon::parse($banner->created_at)->format('d F, Y') }}
                                                            </li>
                                                        </ul>

                                                        @if($banner->live_state === 'Rejected')
                                                            <button class="btn btn-secondary btn-sm disabled">Rejected — refunded</button>
                                                        @elseif($banner->live_state === 'Ended')
                                                            <button class="btn btn-secondary btn-sm disabled">Ended</button>
                                                        @elseif($banner->live_state === 'Under Review' || $banner->live_state === null)
                                                            <form action="{{ route('admin.banner.activate', $banner->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-secondary btn-sm">Take Live</button>
                                                            </form>
                                                            <form action="{{ route('admin.banner.reject', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this banner and refund the user? This cannot be undone.')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm">Reject Banner Ad</button>
                                                            </form>
                                                        @elseif($banner->live_state === 'Started')
                                                            <form action="{{ route('admin.banner.toggle', $banner->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="action" value="pause">
                                                                <button type="submit" class="btn btn-warning btn-sm">
                                                                    <i class="fa fa-pause opacity-75 me-1"></i> Pause Banner
                                                                </button>
                                                            </form>
                                                        @elseif($banner->live_state === 'Paused')
                                                            <form action="{{ route('admin.banner.toggle', $banner->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="action" value="activate">
                                                                <button type="submit" class="btn btn-success btn-sm">
                                                                    <i class="fa fa-play opacity-75 me-1"></i> Resume Banner
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No banners found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex">
                    {!! $bannerList->appends(request()->query())->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
