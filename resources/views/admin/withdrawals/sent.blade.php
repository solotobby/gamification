@extends('layouts.main.master')

@section('content')

  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <div>
          <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-1">Disbursed / Sent Withdrawals</h1>
          <span class="text-muted fs-sm">History of successfully disbursed payouts across all currencies</span>
        </div>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Financials</li>
            <li class="breadcrumb-item active" aria-current="page">Sent Withdrawals</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <!-- Page Content -->
  <div class="content">

    <!-- Multi-Currency Summary Cards -->
    @if(isset($currencyTotals) && count($currencyTotals) > 0)
      <div class="row g-3 mb-4">
        @foreach($currencyTotals as $tot)
          @php
            $currCode = $tot->currency;
          @endphp
          <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="currency-stat-card">
              <div class="stat-header">
                <span class="stat-title">{{ $currCode }} Total Sent</span>
                <span class="badge-currency badge-currency-{{ strtolower($currCode) }}">{{ $currCode }}</span>
              </div>
              <div class="stat-value text-success">
                {{ formatCurrency($tot->total_amount, $currCode) }}
              </div>
              <div class="stat-footer d-flex justify-content-between align-items-center">
                <span>{{ number_format($tot->count) }} Disbursed {{ Str::plural('Payout', $tot->count) }}</span>
                <a href="{{ request()->fullUrlWithQuery(['currency' => $currCode]) }}" class="fs-xs fw-semibold">Filter <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success d-flex align-items-center shadow-sm" role="alert">
        <i class="fa fa-check-circle me-2 fs-5"></i>
        <div>{{ session('success') }}</div>
      </div>
    @endif

    <!-- Filter and Search Bar -->
    <div class="block block-rounded">
      <div class="block-content py-3">
        <form method="GET" action="{{ url()->current() }}">
          <div class="row g-3 align-items-center">
            <div class="col-md-6 col-lg-5">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                <input type="text" class="form-control" name="search"
                       placeholder="Search by name, email, phone, or PayPal email..."
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

    <!-- Sent Withdrawals Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Disbursed Withdrawals <span class="badge bg-success rounded-pill ms-2">{{ number_format($withdrawals->total()) }}</span>
        </h3>
      </div>
      <div class="block-content p-0">
        <div class="table-responsive">
          <table class="table table-modern table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>Currency</th>
                <th>Recipient</th>
                <th>Contact</th>
                <th class="text-end">Amount Paid</th>
                <th>Destination</th>
                <th>Status</th>
                <th>Date Paid</th>
                <th class="text-center" style="width: 100px;">User</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($withdrawals as $with)
                @php
                  $wCurr = $with->base_currency ?: ($with->is_usd ? 'USD' : 'NGN');
                @endphp
                <tr>
                  <td>
                    <span class="badge-currency badge-currency-{{ strtolower($wCurr) }}">
                      <i class="fa fa-circle fs-xs"></i> {{ $wCurr }}
                    </span>
                  </td>
                  <td>
                    <div class="fw-semibold text-dark">
                      <a href="{{ url('user/'.@$with->user->id.'/info') }}" target="_blank" class="text-dark text-hover-primary">
                        {{ @$with->user->name ?? 'User #' . $with->user_id }}
                      </a>
                    </div>
                    <div class="fs-xs text-muted">ID: #{{ $with->id }}</div>
                  </td>
                  <td>
                    <div class="fs-sm">{{ @$with->user->email }}</div>
                    <div class="fs-xs text-muted">{{ @$with->user->phone ?? 'No phone' }}</div>
                  </td>
                  <td class="text-end">
                    <span class="currency-amount fs-sm fw-bold text-success">
                      {{ formatCurrency($with->amount, $wCurr) }}
                    </span>
                  </td>
                  <td>
                    @if($wCurr === 'USD')
                      <span class="badge bg-primary-light text-primary">
                        <i class="fab fa-paypal me-1"></i> {{ $with->paypal_email ?: @$with->user->email }}
                      </span>
                    @else
                      <div class="fs-sm fw-semibold text-dark">{{ @$with->user->accountDetails->bank_name ?? 'Bank Transfer' }}</div>
                      <div class="fs-xs font-monospace text-muted">{{ @$with->user->accountDetails->account_number }}</div>
                    @endif
                  </td>
                  <td>
                    <span class="badge rounded-pill bg-success-light text-success border border-success-subtle px-2 py-1">
                      <i class="fa fa-check me-1"></i> Sent
                    </span>
                  </td>
                  <td>
                    <div class="fs-sm">{{ \Carbon\Carbon::parse($with->updated_at ?? $with->created_at)->format('M d, Y') }}</div>
                    <div class="fs-xs text-muted">{{ \Carbon\Carbon::parse($with->updated_at ?? $with->created_at)->format('h:i A') }}</div>
                  </td>
                  <td class="text-center">
                    <a href="{{ url('user/'.@$with->user->id.'/info') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                      Manage
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fa fa-history fa-3x text-muted mb-3 d-block opacity-25"></i>
                    No sent withdrawals match the selected filters.
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
            Showing {{ $withdrawals->firstItem() ?? 0 }} to {{ $withdrawals->lastItem() ?? 0 }} of {{ number_format($withdrawals->total()) }} entries
          </span>
          <div>
            {!! $withdrawals->appends(request()->query())->links('pagination::bootstrap-4') !!}
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
