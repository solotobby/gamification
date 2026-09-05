@extends('layouts.main.master')

@section('title', 'Task Disputes')

@section('content')

  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <div>
          <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-1">Task Disputes</h1>
          <span class="text-muted fs-sm">Worker appeals on rejected task submissions requiring administrator adjudication</span>
        </div>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Campaigns & Tasks</li>
            <li class="breadcrumb-item active" aria-current="page">Disputes</li>
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
                       placeholder="Search by Job ID, campaign title, or worker..."
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
                   class="pill-filter {{ request('currency') === $c->code ? 'active' : '' }}">
                  <span class="badge-currency badge-currency-{{ strtolower($c->code) }} py-0 px-1">{{ $c->code }}</span>
                </a>
              @endforeach
            @endif
          </div>
        </form>
      </div>
    </div>

    <!-- Disputes Table Card -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Unresolved Disputes <span class="badge bg-danger rounded-pill ms-2">{{ number_format($disputes->total()) }}</span>
        </h3>
      </div>

      <div class="block-content p-0">
        <div class="table-responsive">
          <table class="table table-modern table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>Currency</th>
                <th>Campaign</th>
                <th>Worker (Appealer)</th>
                <th>Campaign Poster</th>
                <th class="text-end">Disputed Payout</th>
                <th>Status</th>
                <th>Dispute Raised</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($disputes as $camp)
                @php
                  $cCurr = @$camp->campaign->currency ?: 'NGN';
                  $amount = @$camp->amount ?: @$camp->campaign->campaign_amount;
                @endphp
                <tr>
                  <td>
                    <span class="badge-currency badge-currency-{{ strtolower($cCurr) }}">
                      <i class="fa fa-circle fs-xs"></i> {{ $cCurr }}
                    </span>
                  </td>
                  <td>
                    <div class="fw-semibold text-dark">
                      <a href="{{ url('campaign/info/' . @$camp->campaign->id) }}" class="text-dark text-hover-primary" target="_blank">
                        {{ @$camp->campaign->post_title ?? 'Campaign #' . $camp->campaign_id }}
                      </a>
                    </div>
                    <div class="fs-xs text-muted">Job ID: #{{ @$camp->campaign->job_id }}</div>
                  </td>
                  <td>
                    <div class="fw-medium">
                      <a href="{{ url('user/' . @$camp->user->id . '/info') }}" class="text-muted" target="_blank">
                        {{ @$camp->user->name ?? 'User #' . $camp->user_id }}
                      </a>
                    </div>
                    <div class="fs-xs text-muted">{{ @$camp->user->email }}</div>
                  </td>
                  <td>
                    <div class="fw-medium">
                      <a href="{{ url('user/' . @$camp->campaign->user->id . '/info') }}" class="text-muted" target="_blank">
                        {{ @$camp->campaign->user->name ?? 'User #' . @$camp->campaign->user_id }}
                      </a>
                    </div>
                    <div class="fs-xs text-muted">{{ @$camp->campaign->user->email }}</div>
                  </td>
                  <td class="text-end">
                    <span class="fs-sm fw-bold text-success">
                      {{ formatCurrency($amount, $cCurr) }}
                    </span>
                  </td>
                  <td>
                    <span class="badge rounded-pill bg-danger-light text-danger border border-danger px-2 py-1">
                      <i class="fa fa-exclamation-triangle me-1"></i> Disputed
                    </span>
                  </td>
                  <td>
                    <div class="fs-sm">{{ \Carbon\Carbon::parse($camp->created_at)->format('M d, Y') }}</div>
                    <div class="fs-xs text-muted">{{ \Carbon\Carbon::parse($camp->created_at)->diffForHumans() }}</div>
                  </td>
                  <td class="text-center">
                    <a href="{{ url('admin/campaign/disputes/' . $camp->id) }}" class="btn btn-sm btn-primary">
                      <i class="fa fa-balance-scale me-1"></i> Review & Adjudicate
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fa fa-check-circle fa-3x text-success mb-3 d-block opacity-25"></i>
                    No open task disputes found! All worker appeals have been resolved.
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
            Showing {{ $disputes->firstItem() ?? 0 }} to {{ $disputes->lastItem() ?? 0 }} of {{ number_format($disputes->total()) }} entries
          </span>
          <div>
            {!! $disputes->appends(request()->query())->links('pagination::bootstrap-4') !!}
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection