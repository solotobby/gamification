@extends('layouts.main.master')
@section('style')
<style>
    .cp-avatar { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 1px solid #E5E9F0; }
    .cp-avatar-placeholder {
        width: 72px; height: 72px; border-radius: 50%; background: #E8F0FE; color: #1565D8;
        display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.4rem; font-family: 'Sora', sans-serif;
    }

    .cp-stat-card { background: #fff; border: 1px solid #E5E9F0; border-radius: 12px; padding: 1.1rem; text-align: center; }
    .cp-stat-num { font-size: 1.5rem; font-weight: 800; color: #1565D8; }
    .cp-stat-label { font-size: .76rem; color: #64748B; margin-top: .2rem; }

    .cp-day-bar-row { display: flex; align-items: center; gap: .75rem; padding: .3rem 0; border-bottom: 1px solid #F1F5F9; }
    .cp-day-bar-label { width: 90px; font-size: .78rem; color: #64748B; flex-shrink: 0; }
    .cp-day-bar-track { flex: 1; background: #F1F5F9; border-radius: 4px; height: 9px; overflow: hidden; }
    .cp-day-bar-fill { background: #1565D8; height: 100%; }
    .cp-day-bar-count { width: 34px; text-align: right; font-size: .78rem; font-weight: 700; color: #0D1B2A; }

    .cp-chip { display: inline-block; background: #EFF6FF; color: #1565D8; font-size: .78rem; font-weight: 600; padding: .3rem .8rem; border-radius: 20px; margin: 0 .35rem .35rem 0; }
    .cp-list-item { border: 1px solid #E5E9F0; border-radius: 10px; padding: .85rem 1rem; margin-bottom: .6rem; }
</style>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Manage Career Profile</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.career-profiles.index') }}">Career Profiles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.career-profiles.index') }}" class="btn btn-alt-secondary btn-sm mb-3">
        <i class="fa fa-arrow-left opacity-50 me-1"></i> Back to list
    </a>

    {{-- Header card --}}
    <div class="block block-rounded">
        <div class="block-content d-flex align-items-center gap-3 flex-wrap py-3">
            @if($profile->photo_path)
                <img src="{{ $profile->photo_path }}" class="cp-avatar" alt="{{ $profile->user->name }}">
            @else
                <div class="cp-avatar-placeholder">{{ strtoupper(substr($profile->user->name ?? 'U', 0, 2)) }}</div>
            @endif
            <div class="flex-grow-1">
                <div class="fs-5 fw-bold">
                    <a href="{{ url('user/'.$profile->user->id.'/info') }}" target="_blank">{{ $profile->user->name ?? 'Unknown' }}</a>
                </div>
                <div class="text-muted">{{ $profile->professional_title ?? 'No title set' }}</div>
                <div class="mt-1">
                    <span class="badge {{ $profile->is_public ? 'bg-success' : 'bg-secondary' }}">{{ $profile->is_public ? 'Public' : 'Private' }}</span>
                    <span class="badge bg-info">{{ $profile->profile_completeness }}% complete</span>
                    <span class="badge bg-dark">Talent score: {{ $profile->talent_score }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                @if($profile->cv_file_path)
                    <a href="{{ $profile->cv_file_path }}" target="_blank" class="btn btn-alt-secondary btn-sm">
                        <i class="fa fa-file-pdf opacity-50 me-1"></i> View CV
                    </a>
                @endif
                @if($profile->slug)
                    <a href="{{ config('services.freebyz.cv_url', 'https://cv.freebyz.com') . '/' . $profile->slug }}" target="_blank" class="btn btn-alt-secondary btn-sm">
                        <i class="fa fa-external-link-alt opacity-50 me-1"></i> View Public Page
                    </a>
                @endif
                <form action="{{ route('admin.career-profiles.toggle-publish', $profile->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $profile->is_public ? 'btn-warning' : 'btn-success' }}">
                        {{ $profile->is_public ? 'Unpublish' : 'Publish' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Individual analytics --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-2">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($analytics['total_views']) }}</div><div class="cp-stat-label">Total Views</div></div>
        </div>
        <div class="col-6 col-md-2">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($analytics['unique_viewers']) }}</div><div class="cp-stat-label">Unique Viewers</div></div>
        </div>
        <div class="col-6 col-md-2">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($analytics['cv_downloads']) }}</div><div class="cp-stat-label">CV Downloads</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($analytics['views_last_7_days']) }}</div><div class="cp-stat-label">Views (7 days)</div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="cp-stat-card"><div class="cp-stat-num">{{ number_format($analytics['views_last_30_days']) }}</div><div class="cp-stat-label">Views (30 days)</div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            {{-- Edit form --}}
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Edit Profile Details</h3>
                </div>
                <div class="block-content">
                    <form action="{{ route('admin.career-profiles.update', $profile->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Professional Title</label>
                            <input type="text" name="professional_title" class="form-control" value="{{ old('professional_title', $profile->professional_title) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Headline</label>
                            <input type="text" name="headline" class="form-control" value="{{ old('headline', $profile->headline) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Summary</label>
                            <textarea name="summary" class="form-control" rows="4">{{ old('summary', $profile->summary) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Professional Level</label>
                                <select name="professional_level" class="form-select">
                                    <option value="">— None —</option>
                                    @foreach(['student_talent' => 'Student Talent', 'junior_professional' => 'Junior Professional', 'mid_level_professional' => 'Mid-Level Professional', 'senior_professional' => 'Senior Professional', 'expert' => 'Expert'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('professional_level', $profile->professional_level) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $profile->city) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state', $profile->state) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country', $profile->country) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Min Rate</label>
                                <input type="number" step="0.01" name="price_min" class="form-control" value="{{ old('price_min', $profile->price_min) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max Rate</label>
                                <input type="number" step="0.01" name="price_max" class="form-control" value="{{ old('price_max', $profile->price_max) }}">
                            </div>
                        </div>
                        <div class="fs-xs text-muted mb-3">Currency ({{ $profile->price_currency ?? 'not set' }}) is derived from the user's wallet and can't be changed here.</div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            {{-- Views over time --}}
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
                        <p class="text-muted fs-sm mb-0">No views recorded yet.</p>
                    @else
                        @foreach($days as $day)
                            <div class="cp-day-bar-row">
                                <div class="cp-day-bar-label">{{ \Carbon\Carbon::parse($day->date)->format('d M') }}</div>
                                <div class="cp-day-bar-track"><div class="cp-day-bar-fill" style="width: {{ ($day->count / $maxCount) * 100 }}%"></div></div>
                                <div class="cp-day-bar-count">{{ $day->count }}</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Skills --}}
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Skills</h3>
                </div>
                <div class="block-content">
                    @forelse($profile->skills as $skill)
                        <span class="cp-chip">{{ $skill->name }}</span>
                    @empty
                        <p class="text-muted fs-sm mb-0">No skills added.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

   {{-- Experience --}}
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">Experience</h3>
    </div>
    <div class="block-content">
        @forelse($profile->experiences as $exp)
            <div class="cp-list-item">
                <strong>{{ $exp->position }}</strong> — {{ $exp->employer }}
                <div class="fs-xs text-muted mb-2">
                    {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} –
                    {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                    @if($exp->employment_type) · {{ ucfirst(str_replace('-', ' ', $exp->employment_type)) }} @endif
                    @if($exp->location) · {{ $exp->location }} @endif
                </div>
                @if($exp->responsibilities)
                    <div class="fs-sm mb-1">
                        <strong class="fs-xs text-muted d-block mb-1">Responsibilities</strong>
                        {{ $exp->responsibilities }}
                    </div>
                @endif
                @if($exp->achievements)
                    <div class="fs-sm">
                        <strong class="fs-xs text-muted d-block mb-1">Achievements</strong>
                        {{ $exp->achievements }}
                    </div>
                @endif
            </div>
        @empty
            <p class="text-muted fs-sm mb-0">No experience added.</p>
        @endforelse
    </div>
</div>

    {{-- Education --}}
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Education</h3>
        </div>
        <div class="block-content">
            @forelse($profile->educations as $edu)
                <div class="cp-list-item">
                    <strong>{{ $edu->qualification }}</strong>{{ $edu->course ? ' — ' . $edu->course : '' }}
                    <div class="fs-xs text-muted">{{ $edu->institution }} · {{ $edu->start_year }}–{{ $edu->is_current ? 'Present' : $edu->end_year }}</div>
                </div>
            @empty
                <p class="text-muted fs-sm mb-0">No education added.</p>
            @endforelse
        </div>
    </div>

    {{-- Certifications --}}
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Certifications</h3>
        </div>
        <div class="block-content">
            @forelse($profile->certifications as $cert)
                <div class="cp-list-item">
                    <strong>{{ $cert->name }}</strong> — {{ $cert->issuer }}
                </div>
            @empty
                <p class="text-muted fs-sm mb-0">No certifications added.</p>
            @endforelse
        </div>
    </div>

    {{-- Social Profiles --}}
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">Social Profiles</h3>
    </div>
    <div class="block-content">
        @forelse($profile->socialProfiles as $social)
            <div class="cp-list-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ ucfirst($social->platform) }}</strong>
                    <div class="fs-xs text-muted">{{ $social->url }}</div>
                </div>
                <a href="{{ $social->url }}" target="_blank" rel="noopener" class="btn btn-sm btn-alt-secondary">
                    <i class="fa fa-external-link-alt opacity-50"></i>
                </a>
            </div>
        @empty
            <p class="text-muted fs-sm mb-0">No social profiles added.</p>
        @endforelse
    </div>
</div>
</div>
@endsection
