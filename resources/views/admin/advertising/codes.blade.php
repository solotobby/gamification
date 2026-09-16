@extends('layouts.main.master')

@section('style')
<style>
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
    .code-card {
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
    }
    .code-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: .75rem;
    }
    .code-card-title {
        font-size: .95rem;
        font-weight: 700;
        color: #0F172A;
        margin: 0;
    }
</style>
@endsection

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Adsterra Script Snippets</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Advertising</li>
                    <li class="breadcrumb-item active" aria-current="page">Code Snippets</li>
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

    {{-- Sub Navigation Tabs --}}
    <div class="ad-nav-tabs">
        <a href="{{ route('admin.advertising.index') }}" class="ad-nav-tab">Overview & Kill Switches</a>
        <a href="{{ route('admin.advertising.placements') }}" class="ad-nav-tab">Placement Rules & Intervals</a>
        <a href="{{ route('admin.advertising.codes') }}" class="ad-nav-tab active">Adsterra Script Snippets</a>
    </div>

    <form method="POST" action="{{ route('admin.advertising.codes.update') }}">
        @csrf

        {{-- Active Primary Formats --}}
        <div class="block block-rounded mb-4">
            <div class="block-header block-header-default">
                <h3 class="block-title">Active Ad Script Slots</h3>
            </div>
            <div class="block-content py-3">

                {{-- Native Banner (Web) --}}
                <div class="code-card">
                    <div class="code-card-header">
                        <div>
                            <h5 class="code-card-title">Native Banner — Web</h5>
                            <small class="text-muted">Adsterra Native Banner script tag for feed and content areas</small>
                        </div>
                        <span class="badge bg-primary">NATIVE_BANNER_ALL</span>
                    </div>
                    <textarea class="form-control font-monospace fs-xs" name="codes[NATIVE_BANNER_ALL]" rows="4" placeholder="Paste Adsterra Native Banner code here...">{{ $codes['NATIVE_BANNER_ALL']->code ?? '' }}</textarea>
                </div>

                {{-- Desktop Standard Banner --}}
                <div class="code-card">
                    <div class="code-card-header">
                        <div>
                            <h5 class="code-card-title">Standard Banner — Desktop Web (728×90 / 300×250)</h5>
                            <small class="text-muted">Adsterra Desktop banner snippet tag</small>
                        </div>
                        <span class="badge bg-primary">BANNER_DESKTOP_DESKTOP</span>
                    </div>
                    <textarea class="form-control font-monospace fs-xs" name="codes[BANNER_DESKTOP_DESKTOP]" rows="4" placeholder="Paste Adsterra Desktop Banner code here...">{{ $codes['BANNER_DESKTOP_DESKTOP']->code ?? '' }}</textarea>
                </div>

                {{-- Mobile Standard Banner --}}
                <div class="code-card">
                    <div class="code-card-header">
                        <div>
                            <h5 class="code-card-title">Standard Banner — Mobile Web (320×50 / 300×250)</h5>
                            <small class="text-muted">Adsterra Mobile banner snippet tag</small>
                        </div>
                        <span class="badge bg-primary">BANNER_MOBILE_MOBILE</span>
                    </div>
                    <textarea class="form-control font-monospace fs-xs" name="codes[BANNER_MOBILE_MOBILE]" rows="4" placeholder="Paste Adsterra Mobile Banner code here...">{{ $codes['BANNER_MOBILE_MOBILE']->code ?? '' }}</textarea>
                </div>

                {{-- Mobile App Implementation / Configuration --}}
                <div class="code-card">
                    <div class="code-card-header">
                        <div>
                            <h5 class="code-card-title">Mobile App Configuration (Smart Direct Link / JSON payload)</h5>
                            <small class="text-muted">App configuration payload or direct link returned to Mobile clients</small>
                        </div>
                        <span class="badge bg-primary">APP_CONFIG_ALL</span>
                    </div>
                    <textarea class="form-control font-monospace fs-xs" name="codes[APP_CONFIG_ALL]" rows="3" placeholder="Paste Mobile App direct configuration or link...">{{ $codes['APP_CONFIG_ALL']->code ?? '' }}</textarea>
                </div>

            </div>
        </div>

        {{-- Additional Format Scripts --}}
        <div class="block block-rounded mb-4">
            <div class="block-header block-header-default">
                <h3 class="block-title">Additional Ad Format Scripts</h3>
            </div>
            <div class="block-content py-3">

                <div class="row">
                    <div class="col-md-6">
                        <div class="code-card">
                            <h5 class="code-card-title mb-2">Social Bar Code</h5>
                            <textarea class="form-control font-monospace fs-xs" name="codes[SOCIAL_BAR_ALL]" rows="3" placeholder="Paste Adsterra Social Bar code here...">{{ $codes['SOCIAL_BAR_ALL']->code ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="code-card">
                            <h5 class="code-card-title mb-2">Interstitial Code</h5>
                            <textarea class="form-control font-monospace fs-xs" name="codes[INTERSTITIAL_ALL]" rows="3" placeholder="Paste Adsterra Interstitial code here...">{{ $codes['INTERSTITIAL_ALL']->code ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="code-card">
                            <h5 class="code-card-title mb-2">Popunder Code</h5>
                            <textarea class="form-control font-monospace fs-xs" name="codes[POPUNDER_ALL]" rows="3" placeholder="Paste Adsterra Popunder code here...">{{ $codes['POPUNDER_ALL']->code ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="code-card">
                            <h5 class="code-card-title mb-2">Smartlink URL</h5>
                            <textarea class="form-control font-monospace fs-xs" name="codes[SMARTLINK_ALL]" rows="3" placeholder="Paste Adsterra Smartlink URL here...">{{ $codes['SMARTLINK_ALL']->code ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end mb-5">
            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                Save All Adsterra Snippets
            </button>
        </div>
    </form>

</div>

@endsection
