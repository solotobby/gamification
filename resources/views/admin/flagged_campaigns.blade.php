@extends('layouts.main.master')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Flagged Campaigns</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Campaigns</li>
                        <li class="breadcrumb-item active" aria-current="page">Flagged</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Flagged Campaigns - {{ $campaigns->total() }}</h3>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="block block-rounded mb-3">
                    <div class="block-content">
                        <form method="GET" action="">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                    placeholder="Search by job ID, campaign name, or creator..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Search
                                </button>
                                @if(request('search'))
                                    <a href="{{ url()->current() }}" class="btn btn-secondary">Clear</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>Job ID</th>
                                <th>Title</th>
                                <th>Owner</th>
                                <th>Total Workers</th>
                                <th>Denied</th>
                                <th>Denial Rate</th>
                                <th>Flagged Date</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($campaigns as $campaign)
                                @php
                                    $totalWorkers = $campaign->attempts()->count();
                                    $deniedWorkers = $campaign->attempts()->where('status', 'Denied')->count();
                                    $denialRate = $totalWorkers > 0 ? round(($deniedWorkers / $totalWorkers) * 100, 2) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $campaign->job_id }}</td>
                                    <td>{{ $campaign->post_title }}</td>
                                    <td>
                                        <a href="{{ url('user/' . $campaign->user_id . '/info') }}" target="_blank">
                                            {{ $campaign->user->name }}
                                        </a>
                                    </td>
                                    <td>{{ $totalWorkers }}</td>
                                    <td class="text-danger fw-bold">{{ $deniedWorkers }}</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $denialRate }}%</span>
                                    </td>
                                    <td>{{ $campaign->flagged_at->format('d/m/Y h:i A') }}</td>
                                    <td>
                                        <small>{{ $campaign->flagged_reason }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ url('campaign/info/' . $campaign->id) }}" class="btn btn-sm btn-info"
                                            target="_blank">
                                            View
                                        </a>
                                        <a href="{{ url('admin/campaign/' . $campaign->id . '/disputes') }}"
                                            class="btn btn-sm btn-danger" target="_blank">
                                            Disputes
                                        </a>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#unflag-{{ $campaign->id }}">
                                            Unflag
                                        </button>
                                    </td>
                                </tr>

                                <!-- Unflag Modal -->
                                <div class="modal fade" id="unflag-{{ $campaign->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Unflag Campaign</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ url('campaigns/' . $campaign->id . '/unflag') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Are you sure you want to unflag this campaign?</p>
                                                    <p><strong>Campaign:</strong> {{ $campaign->post_title }}</p>
                                                    <p><strong>Denial Rate:</strong> {{ $denialRate }}%</p>

                                                    <div class="mb-3">
                                                        <label class="form-label">New Status</label>
                                                        <select class="form-select" name="new_status" required>
                                                            <option value="Pending">Pending Review</option>
                                                            <option value="Live">Live</option>
                                                            <option value="Paused">Paused</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Unflagging</label>
                                                        <textarea class="form-control" name="unflag_reason" rows="3"
                                                            required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning">Unflag Campaign</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No flagged campaigns found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex">
                        {!! $campaigns->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
