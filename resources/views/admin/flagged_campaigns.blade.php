@extends('layouts.main.master')

@section('title', 'Flagged Campaigns & Disputes')

@section('content')

  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <div>
          <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-1">Flagged Campaigns</h1>
          <span class="text-muted fs-sm">Campaigns flagged by automated high denial rate triggers or worker reports</span>
        </div>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Campaigns</li>
            <li class="breadcrumb-item active" aria-current="page">Flagged</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <div class="content">

    @if (session('success'))
      <div class="alert alert-success d-flex align-items-center shadow-sm" role="alert">
        <i class="fa fa-check-circle me-2 fs-5"></i>
        <div>{{ session('success') }}</div>
      </div>
    @endif

    @if (session('error'))
      <div class="alert alert-danger d-flex align-items-center shadow-sm" role="alert">
        <i class="fa fa-exclamation-circle me-2 fs-5"></i>
        <div>{{ session('error') }}</div>
      </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="block block-rounded">
      <div class="block-content py-3">
        <form method="GET" action="{{ url()->current() }}">
          <div class="row g-3 align-items-center">
            <div class="col-md-6 col-lg-5">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                <input type="text" class="form-control" name="search"
                       placeholder="Search by Job ID, campaign title, or creator name..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
            </div>

            <div class="col-md-4 col-lg-4">
              <select name="currency" class="form-select" onchange="this.form.submit()">
                <option value="ALL" {{ request('currency', 'ALL') === 'ALL' ? 'selected' : '' }}>All Currencies</option>
                @if(isset($activeCurrencies))
                  @foreach($activeCurrencies as $c)
                    <option value="{{ $c->code }}" {{ request('currency') === $c->code ? 'selected' : '' }}>
                      {{ $c->code }} - {{ $c->country }}
                    </option>
                  @endforeach
                @endif
              </select>
            </div>

            <div class="col-md-2 col-lg-3 text-md-end">
              @if(request('search') || request('currency'))
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                  <i class="fa fa-times me-1"></i> Clear Filters
                </a>
              @endif
            </div>
          </div>

          <!-- Currency Quick Filter Pills -->
          <div class="d-flex flex-wrap align-items-center gap-2 pt-3 mt-2 border-top">
            <span class="fs-xs fw-bold text-uppercase text-muted me-1">Currency:</span>
            <a href="{{ request()->fullUrlWithQuery(['currency' => 'ALL']) }}" 
               class="pill-filter {{ request('currency', 'ALL') === 'ALL' ? 'active' : '' }}">
              All
            </a>
            @if(isset($activeCurrencies))
              @foreach($activeCurrencies as $c)
                <a href="{{ request()->fullUrlWithQuery(['currency' => $c->code]) }}" 
                   class="pill-filter {{ request('currency', 'ALL') === 'ALL' ? 'active' : '' }}">
                  <span class="badge-currency badge-currency-{{ strtolower($c->code) }} py-0 px-1">{{ $c->code }}</span>
                </a>
              @endforeach
            @endif
          </div>
        </form>
      </div>
    </div>

    <!-- Flagged Table Card -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Flagged Records <span class="badge bg-danger rounded-pill ms-2">{{ number_format($campaigns->total()) }}</span>
        </h3>
      </div>

      <div class="block-content p-0">
        <div class="table-responsive">
          <table class="table table-modern table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>Currency</th>
                <th>Job ID & Title</th>
                <th>Owner</th>
                <th class="text-center">Denial Rate</th>
                <th class="text-end">Unit Price</th>
                <th>Flagged Date</th>
                <th>Reason</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($campaigns as $campaign)
                @php
                  $cCurr = $campaign->currency ?: 'NGN';
                  $totalWorkers = $campaign->attempts->count();
                  $deniedWorkers = $campaign->attempts->where('status', 'Denied')->count();
                  $denialRate = $totalWorkers > 0 ? round(($deniedWorkers / $totalWorkers) * 100, 2) : 0;
                @endphp
                <tr>
                  <td>
                    <span class="badge-currency badge-currency-{{ strtolower($cCurr) }}">
                      <i class="fa fa-circle fs-xs"></i> {{ $cCurr }}
                    </span>
                  </td>
                  <td>
                    <div class="fw-semibold text-dark">
                      <a href="{{ url('campaign/info/' . $campaign->id) }}" class="text-dark text-hover-primary" target="_blank">
                        {{ $campaign->post_title }}
                      </a>
                    </div>
                    <div class="fs-xs font-monospace text-muted">ID: #{{ $campaign->job_id }}</div>
                  </td>
                  <td>
                    <div class="fw-medium">
                      <a href="{{ url('user/' . $campaign->user_id . '/info') }}" class="text-muted" target="_blank">
                        {{ @$campaign->user->name ?? 'User #' . $campaign->user_id }}
                      </a>
                    </div>
                    <div class="fs-xs text-muted">{{ @$campaign->user->email }}</div>
                  </td>
                  <td class="text-center">
                    <span class="badge rounded-pill bg-danger-light text-danger border border-danger px-2 py-1 fs-xs fw-bold">
                      {{ $denialRate }}%
                    </span>
                    <div class="fs-xs text-muted mt-1">{{ $deniedWorkers }} of {{ $totalWorkers }} rejected</div>
                  </td>
                  <td class="text-end">
                    <span class="fs-sm fw-semibold text-dark">
                      {{ formatCurrency($campaign->campaign_amount, $cCurr) }}
                    </span>
                  </td>
                  <td>
                    <div class="fs-sm">{{ $campaign->flagged_at ? \Carbon\Carbon::parse($campaign->flagged_at)->format('M d, Y') : '-' }}</div>
                    <div class="fs-xs text-muted">{{ $campaign->flagged_at ? \Carbon\Carbon::parse($campaign->flagged_at)->format('h:i A') : '' }}</div>
                  </td>
                  <td>
                    <div class="fs-xs text-danger text-truncate" style="max-width: 200px;" title="{{ $campaign->flagged_reason }}">
                      {{ $campaign->flagged_reason ?: 'Automated denial threshold triggered' }}
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="{{ url('campaign/info/' . $campaign->id) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Inspect">
                        <i class="fa fa-eye"></i>
                      </a>
                      <a href="{{ url('admin/campaign/' . $campaign->id . '/disputes') }}" class="btn btn-sm btn-outline-danger" target="_blank" title="Disputes">
                        <i class="fa fa-exclamation-triangle"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#unflag-{{ $campaign->id }}" title="Unflag">
                        <i class="fa fa-flag"></i> Unflag
                      </button>
                    </div>
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
                              <option value="Offline">Pending Review</option>
                              <option value="Live">Live</option>
                              <option value="Paused">Paused</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-primary">Unflag Campaign</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fa fa-shield-alt fa-3x text-success mb-3 d-block opacity-25"></i>
                    No flagged campaigns found matching the selected filters.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="block-content border-top py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <span class="fs-sm text-muted">
            Showing {{ $campaigns->firstItem() ?? 0 }} to {{ $campaigns->lastItem() ?? 0 }} of {{ number_format($campaigns->total()) }} entries
          </span>
          <div>
            {!! $campaigns->appends(request()->query())->links('pagination::bootstrap-4') !!}
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
