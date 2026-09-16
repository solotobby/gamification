@extends('layouts.main.master')

@section('style')
<style>
    .ad-stat-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        padding: 1.25rem;
        transition: transform .15s;
    }
    .ad-stat-card:hover {
        transform: translateY(-2px);
    }
    .ad-stat-num {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.2;
    }
    .ad-stat-label {
        font-size: .8rem;
        font-weight: 600;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-top: .3rem;
    }
    .ad-nav-tabs {
        display: flex;
        gap: .5rem;
        border-bottom: 2px solid #E2E8F0;
        margin-bottom: 1.5rem;
    }
    .ad-nav-tab {
        padding: .65rem 1.25rem;
        font-weight: 600;
        font-size: .9rem;
        color: #64748B;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        text-decoration: none;
        transition: all .15s;
    }
    .ad-nav-tab:hover {
        color: #1565D8;
    }
    .ad-nav-tab.active {
        color: #1565D8;
        border-bottom-color: #1565D8;
    }
    .switch-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: .75rem;
    }
    .switch-card-info h5 {
        font-size: .92rem;
        font-weight: 700;
        margin: 0 0 .2rem 0;
        color: #0F172A;
    }
    .switch-card-info p {
        font-size: .78rem;
        color: #64748B;
        margin: 0;
    }
    .form-check-input {
        width: 2.75rem;
        height: 1.5rem;
        cursor: pointer;
    }
</style>
@endsection

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Advertising Management</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Operations</li>
                    <li class="breadcrumb-item active" aria-current="page">Advertising</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Sub Navigation Tabs & Period Filter --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="ad-nav-tabs mb-0">
            <a href="{{ route('admin.advertising.index', ['period' => $period]) }}" class="ad-nav-tab active">Overview & Controls</a>
            <a href="{{ route('admin.advertising.placements') }}" class="ad-nav-tab">Placement Rules & Intervals</a>
            <a href="{{ route('admin.advertising.codes') }}" class="ad-nav-tab">Adsterra Script Snippets</a>
        </div>

        {{-- Date Range / Period Filter Dropdown --}}
        <form method="GET" action="{{ route('admin.advertising.index') }}" id="periodFilterForm" class="d-flex align-items-center gap-2">
            <label class="form-label mb-0 fs-sm fw-semibold text-muted text-nowrap" for="periodSelect">
                <i class="fa fa-calendar-alt me-1 text-primary"></i> Filter Period:
            </label>
            <select class="form-select form-select-sm fw-semibold shadow-sm" id="periodSelect" name="period" style="min-width: 160px; cursor: pointer;" onchange="this.form.submit()">
                <option value="24h" {{ $period === '24h' ? 'selected' : '' }}>Last 24 Hours</option>
                <option value="7d" {{ $period === '7d' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30d" {{ $period === '30d' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="60d" {{ $period === '60d' ? 'selected' : '' }}>Last 60 Days</option>
                <option value="90d" {{ $period === '90d' ? 'selected' : '' }}>Last 90 Days</option>
                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>All Time</option>
            </select>
        </form>
    </div>

    {{-- Telemetry KPI Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="ad-stat-card">
                <div class="ad-stat-num text-primary">{{ number_format($totalRendered) }}</div>
                <div class="ad-stat-label">Ad Impressions ({{ $periodLabel }})</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="ad-stat-card">
                <div class="ad-stat-num text-success">{{ number_format($totalClicked) }}</div>
                <div class="ad-stat-label">Ad Clicks ({{ $periodLabel }})</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="ad-stat-card">
                <div class="ad-stat-num text-info">{{ $ctr }}%</div>
                <div class="ad-stat-label">Click-Through Rate</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="ad-stat-card">
                <div class="ad-stat-num text-danger">{{ number_format($totalFailed) }}</div>
                <div class="ad-stat-label">Blocked / Failed ({{ $periodLabel }})</div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.advertising.config.update') }}">
        @csrf

        <div class="row">
            {{-- Left Column: Global & Platform Controls --}}
            <div class="col-lg-6">
                {{-- Master Emergency Kill Switch --}}
                <div class="block block-rounded border {{ $config->status === 'ACTIVE' ? 'border-success' : 'border-danger' }} mb-4">
                    <div class="block-header {{ $config->status === 'ACTIVE' ? 'bg-success-light' : 'bg-danger-light' }}">
                        <h3 class="block-title fw-bold {{ $config->status === 'ACTIVE' ? 'text-success' : 'text-danger' }}">
                            🚨 Master Advertising Switch
                        </h3>
                    </div>
                    <div class="block-content py-3">
                        <div class="switch-card" style="background:#fff">
                            <div class="switch-card-info">
                                <h5>Global Advertising Status</h5>
                                <p>When turned OFF, all Adsterra advertising is immediately stopped across all web and mobile platforms.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status_active" id="status_active" value="1" {{ $config->status === 'ACTIVE' ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-2 px-1">
                            <span class="badge {{ $config->status === 'ACTIVE' ? 'bg-success' : 'bg-danger' }}">
                                Current Status: {{ $config->status }}
                            </span>
                            <small class="text-muted">Takes instant effect on all active sessions</small>
                        </div>
                    </div>
                </div>

                {{-- Platform Toggles --}}
                <div class="block block-rounded mb-4">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Platform Controls</h3>
                    </div>
                    <div class="block-content py-3">
                        <div class="switch-card">
                            <div class="switch-card-info">
                                <h5>Website Monetization</h5>
                                <p>Enable or disable Adsterra advertising on Freebyz web.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="web_enabled" id="web_enabled" value="1" {{ $config->web_enabled ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="switch-card">
                            <div class="switch-card-info">
                                <h5>Mobile App Monetization</h5>
                                <p>Enable or disable Adsterra monetization response for Android & iOS apps.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="mobile_app_enabled" id="mobile_app_enabled" value="1" {{ $config->mobile_app_enabled ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold" for="max_ads_per_session">Max Ad Impressions Per Session</label>
                            <input type="number" class="form-control" id="max_ads_per_session" name="max_ads_per_session" value="{{ $config->max_ads_per_session }}" min="1" max="50">
                            <small class="text-muted">Protects user experience by capping total ads displayed in a single user session.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Format Controls --}}
            <div class="col-lg-6">
                <div class="block block-rounded mb-4">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Ad Format Controls</h3>
                    </div>
                    <div class="block-content py-3">
                        {{-- Active Primary Formats --}}
                        <div class="switch-card">
                            <div class="switch-card-info">
                                <h5>Native Banner Ads</h5>
                                <p>Responsive native cards placed within Jobs, Tasks, Blog and Talent feeds.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="native_enabled" id="native_enabled" value="1" {{ $config->native_enabled ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="switch-card">
                            <div class="switch-card-info">
                                <h5>Standard Banner Ads</h5>
                                <p>Display banners (728×90, 300×250, 320×50) in top, bottom, and article content slots.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="banner_enabled" id="banner_enabled" value="1" {{ $config->banner_enabled ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="text-muted text-uppercase fs-xs fw-bold my-3 px-1">Additional Ad Formats</div>

                        <div class="switch-card">
                            <div class="switch-card-info">
                                <h5>Social Bar</h5>
                                <p>Interactive notifications, custom widgets, and in-page push ads.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="social_bar_enabled" id="social_bar_enabled" value="1" {{ $config->social_bar_enabled ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="switch-card">
                            <div class="switch-card-info">
                                <h5>Interstitial</h5>
                                <p>Full-screen overlay ads between major page transitions.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="interstitial_enabled" id="interstitial_enabled" value="1" {{ $config->interstitial_enabled ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="switch-card">
                            <div class="switch-card-info">
                                <h5>Popunder</h5>
                                <p>Opens advertiser landing page behind active browser window.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="popunder_enabled" id="popunder_enabled" value="1" {{ $config->popunder_enabled ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="switch-card">
                            <div class="switch-card-info">
                                <h5>Smart Direct Link</h5>
                                <p>Direct monetized link routing for app and mobile campaigns.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="smartlink_enabled" id="smartlink_enabled" value="1" {{ $config->smartlink_enabled ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                Save Advertising Settings
            </button>
        </div>
    </form>

    {{-- Impression Breakdown by Page Type --}}
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Impressions by Page Type ({{ $periodLabel }})</h3>
            <div class="block-options">
                <a href="{{ route('admin.advertising.placements') }}" class="btn btn-sm btn-alt-primary">Manage Placements</a>
            </div>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>Page Type</th>
                            <th>Target Surfaces</th>
                            <th>Impressions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($byPage as $pageRow)
                            <tr>
                                <td class="fw-bold">{{ $pageRow->page_type }}</td>
                                <td><code>/{{ strtolower(str_replace('_', '-', $pageRow->page_type)) }}</code></td>
                                <td>{{ number_format($pageRow->impressions) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No impression events logged for {{ strtolower($periodLabel) }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
