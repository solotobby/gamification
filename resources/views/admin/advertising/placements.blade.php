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
    .badge-format {
        font-size: .75rem;
        font-weight: 700;
        padding: .25rem .6rem;
        border-radius: 6px;
    }
    .badge-native { background: #E0E7FF; color: #3730A3; }
    .badge-banner { background: #FEF3C7; color: #92400E; }
    .badge-other  { background: #F1F5F9; color: #475569; }
</style>
@endsection

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Ad Placement Rules</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Advertising</li>
                    <li class="breadcrumb-item active" aria-current="page">Placements</li>
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
        <a href="{{ route('admin.advertising.placements') }}" class="ad-nav-tab active">Placement Rules & Intervals</a>
        <a href="{{ route('admin.advertising.codes') }}" class="ad-nav-tab">Adsterra Script Snippets</a>
    </div>

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Configured Advertising Placements</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>Placement Key</th>
                            <th>Page Section</th>
                            <th>Format</th>
                            <th>Position / Rule</th>
                            <th>Interval / Frequency</th>
                            <th class="text-center" style="width: 120px;">Status</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($placements as $p)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $p->placement_key }}</div>
                                    <small class="text-muted">Platform: {{ $p->platform }} | Device: {{ $p->device }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $p->page_type }}</span>
                                </td>
                                <td>
                                    @if($p->ad_format === 'NATIVE_BANNER')
                                        <span class="badge-format badge-native">Native Banner</span>
                                    @elseif($p->ad_format === 'STANDARD_BANNER')
                                        <span class="badge-format badge-banner">Standard Banner</span>
                                    @else
                                        <span class="badge-format badge-other">{{ $p->ad_format }}</span>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $p->position }}</code>
                                </td>
                                <td>
                                    @if($p->display_interval)
                                        <span class="badge bg-primary">Every {{ $p->display_interval }} items</span>
                                    @else
                                        <span class="text-muted">Single (Cap: {{ $p->frequency_cap ?? 1 }})</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin.advertising.placement.update', $p->id) }}">
                                        @csrf
                                        <input type="hidden" name="toggle_only" value="1">
                                        <button type="submit" class="btn btn-sm {{ $p->enabled ? 'btn-success' : 'btn-danger' }} px-3">
                                            {{ $p->enabled ? 'Active' : 'Disabled' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $p->id }}">
                                        <i class="fa fa-pencil-alt"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            {{-- Edit Placement Modal --}}
                            <div class="modal fade" id="editModal-{{ $p->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $p->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.advertising.placement.update', $p->id) }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel-{{ $p->id }}">Edit Placement: {{ $p->placement_key }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="enabled" id="enabled-{{ $p->id }}" value="1" {{ $p->enabled ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="enabled-{{ $p->id }}">Enable this placement</label>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="interval-{{ $p->id }}">Display Interval (In-feed lists)</label>
                                                    <input type="number" class="form-control" name="display_interval" id="interval-{{ $p->id }}" value="{{ $p->display_interval }}" placeholder="e.g. 5 for every 5th item (optional)">
                                                    <small class="text-muted">If set, ad will repeat after this number of cards.</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="cap-{{ $p->id }}">Frequency Cap (Max per page)</label>
                                                    <input type="number" class="form-control" name="frequency_cap" id="cap-{{ $p->id }}" value="{{ $p->frequency_cap ?? 1 }}" min="1" max="10">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="priority-{{ $p->id }}">Priority Order</label>
                                                    <input type="number" class="form-control" name="priority" id="priority-{{ $p->id }}" value="{{ $p->priority }}" min="0">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="code-{{ $p->id }}">Placement-Specific Code Override (Optional)</label>
                                                    <textarea class="form-control font-monospace fs-xs" name="code" id="code-{{ $p->id }}" rows="3" placeholder="Leave empty to use global format script">{{ $p->code }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection
