@extends('layouts.main.master')
@section('style')
<style>
    .stat-card {
        background: #fff;
        border: 1px solid #e5e9f0;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
    }
    .stat-num {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1565D8;
    }
    .stat-label {
        font-size: .8rem;
        color: #64748B;
        margin-top: .25rem;
    }
    .day-bar-row {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .35rem 0;
        border-bottom: 1px solid #F1F5F9;
    }
    .day-bar-label { width: 100px; font-size: .8rem; color: #64748B; flex-shrink: 0; }
    .day-bar-track { flex: 1; background: #F1F5F9; border-radius: 4px; height: 10px; overflow: hidden; }
    .day-bar-fill { background: #1565D8; height: 100%; }
    .day-bar-count { width: 40px; text-align: right; font-size: .8rem; font-weight: 700; color: #0D1B2A; }
</style>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Analytics — {{ $blog->title }}</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-alt-secondary btn-sm mb-3">
        <i class="fa fa-arrow-left opacity-50 me-1"></i> Back to posts
    </a>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-num">{{ number_format($analytics['total_views']) }}</div>
                <div class="stat-label">Total Views</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-num">{{ number_format($analytics['unique_views']) }}</div>
                <div class="stat-label">Unique Views</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-num">{{ number_format($analytics['views_last_7_days']) }}</div>
                <div class="stat-label">Last 7 Days</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-num">{{ number_format($analytics['views_last_30_days']) }}</div>
                <div class="stat-label">Last 30 Days</div>
            </div>
        </div>
    </div>

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Views — Last 30 Days</h3>
        </div>
        <div class="block-content">
            @php
                $days = collect($analytics['views_by_day'])->keyBy('date');
                $maxCount = $days->max('count') ?: 1;
            @endphp

            @if($days->isEmpty())
                <p class="text-muted fs-sm mb-0">No views recorded yet in this period.</p>
            @else
                @foreach($days as $day)
                    <div class="day-bar-row">
                        <div class="day-bar-label">{{ \Carbon\Carbon::parse($day->date)->format('d M') }}</div>
                        <div class="day-bar-track">
                            <div class="day-bar-fill" style="width: {{ ($day->count / $maxCount) * 100 }}%"></div>
                        </div>
                        <div class="day-bar-count">{{ $day->count }}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
