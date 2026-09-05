@extends('layouts.main.master')

@section('title', 'Admin Transactions')

@section('content')

  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <div>
          <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-1">Admin Transactions</h1>
          <span class="text-muted fs-sm">Platform administration commissions, corporate debits, and system revenue</span>
        </div>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Financials</li>
            <li class="breadcrumb-item active" aria-current="page">Admin Transactions</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <div class="content">

    <!-- Multi-Currency Summary Cards -->
    @if(isset($currencyTotals) && count($currencyTotals) > 0)
      <div class="row g-3 mb-4">
        @foreach($currencyTotals as $tot)
          @php
            $currCode = $tot->currency ?: 'NGN';
          @endphp
          <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="currency-stat-card">
              <div class="stat-header">
                <span class="stat-title">{{ $currCode }} Admin Pool</span>
                <span class="badge-currency badge-currency-{{ strtolower($currCode) }}">{{ $currCode }}</span>
              </div>
              <div class="stat-value text-dark">
                {{ formatCurrency($tot->total_amount, $currCode) }}
              </div>
              <div class="stat-footer d-flex justify-content-between align-items-center">
                <span>{{ number_format($tot->count) }} Admin Entries</span>
                <a href="{{ request()->fullUrlWithQuery(['currency' => $currCode]) }}" class="fs-xs fw-semibold">Filter <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="block block-rounded">
      <div class="block-content py-3">
        <form method="GET" action="{{ url()->current() }}">
          <div class="row g-3 align-items-center">
            <div class="col-md-6 col-lg-5">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                <input type="text" class="form-control" name="search"
                       placeholder="Search reference, description, or type..."
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

          <!-- Currency Pills -->
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

    <!-- Admin Transactions Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Admin Financial Records <span class="badge bg-primary rounded-pill ms-2">{{ number_format($lists->total()) }}</span>
        </h3>
      </div>
      <div class="block-content p-0">
        <div class="table-responsive">
          <table class="table table-modern table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>Currency</th>
                <th>Reference</th>
                <th class="text-end">Amount</th>
                <th class="text-end">Balance After</th>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Date / Time</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($lists as $list)
                @php
                  $tCurr = $list->currency ?: 'NGN';
                  $isCredit = strtolower($list->tx_type) === 'credit';
                @endphp
                <tr>
                  <td>
                    <span class="badge-currency badge-currency-{{ strtolower($tCurr) }}">
                      <i class="fa fa-circle fs-xs"></i> {{ $tCurr }}
                    </span>
                  </td>
                  <td>
                    <div class="font-monospace fs-xs text-dark fw-medium">{{ $list->reference }}</div>
                    <div class="fs-xs text-muted">{{ $list->channel ?: 'system' }}</div>
                  </td>
                  <td class="text-end">
                    <span class="fs-sm fw-bold {{ $isCredit ? 'text-success' : 'text-danger' }}">
                      {{ $isCredit ? '+' : '-' }}{{ formatCurrency($list->amount, $tCurr) }}
                    </span>
                  </td>
                  <td class="text-end">
                    <span class="fs-sm text-dark fw-medium">
                      {{ formatCurrency($list->balance, $tCurr) }}
                    </span>
                  </td>
                  <td>
                    <span class="badge rounded-pill {{ $isCredit ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }} border px-2 py-1">
                      <i class="fa {{ $isCredit ? 'fa-arrow-down' : 'fa-arrow-up' }} me-1"></i> {{ ucfirst($list->tx_type ?? 'Tx') }}
                    </span>
                    <div class="fs-xs text-muted mt-1">{{ str_replace('_', ' ', $list->type) }}</div>
                  </td>
                  <td>
                    <div class="fs-sm text-muted text-truncate" style="max-width: 300px;" title="{{ $list->description }}">
                      {{ $list->description }}
                    </div>
                  </td>
                  <td>
                    <span class="badge rounded-pill bg-success-light text-success px-2 py-1">
                      <i class="fa fa-check me-1"></i> {{ ucfirst($list->status) }}
                    </span>
                  </td>
                  <td>
                    <div class="fs-sm">{{ \Carbon\Carbon::parse($list->created_at)->format('M d, Y') }}</div>
                    <div class="fs-xs text-muted">{{ \Carbon\Carbon::parse($list->created_at)->format('h:i A') }}</div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fa fa-receipt fa-3x text-muted mb-3 d-block opacity-25"></i>
                    No admin transactions match the selected filters.
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
            Showing {{ $lists->firstItem() ?? 0 }} to {{ $lists->lastItem() ?? 0 }} of {{ number_format($lists->total()) }} entries
          </span>
          <div>
            {!! $lists->appends(request()->query())->links('pagination::bootstrap-4') !!}
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection