@extends('layouts.main.master')

@section('content')

  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <div>
          <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-1">Queued Withdrawals</h1>
          <span class="text-muted fs-sm">Pending payout requests across all supported currencies</span>
        </div>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Financials</li>
            <li class="breadcrumb-item active" aria-current="page">Queued Withdrawals</li>
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
                <span class="stat-title">{{ $currCode }} Queued</span>
                <span class="badge-currency badge-currency-{{ strtolower($currCode) }}">{{ $currCode }}</span>
              </div>
              <div class="stat-value text-primary">
                {{ formatCurrency($tot->total_amount, $currCode) }}
              </div>
              <div class="stat-footer d-flex justify-content-between align-items-center">
                <span>{{ number_format($tot->count) }} Pending {{ Str::plural('Request', $tot->count) }}</span>
                <a href="{{ request()->fullUrlWithQuery(['currency' => $currCode]) }}" class="fs-xs fw-semibold">View <i class="fa fa-arrow-right"></i></a>
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

    @if (session('error'))
      <div class="alert alert-danger d-flex align-items-center shadow-sm" role="alert">
        <i class="fa fa-exclamation-circle me-2 fs-5"></i>
        <div>{{ session('error') }}</div>
      </div>
    @endif

    <!-- Filter and Search Bar -->
    <div class="block block-rounded">
      <div class="block-content py-3">
        <form method="GET" action="{{ url()->current() }}" id="withdrawalFilterForm">
          <div class="row g-3 align-items-center">
            <div class="col-md-6 col-lg-5">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                <input type="text" class="form-control" name="search"
                       placeholder="Search by name, email, phone, or PayPal email..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                  Search
                </button>
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

    <!-- Withdrawals Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Withdrawal Requests <span class="badge bg-primary rounded-pill ms-2">{{ number_format($withdrawals->total()) }}</span>
        </h3>
      </div>
      <div class="block-content p-0">
        <div class="table-responsive">
          <table class="table table-modern table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>Currency</th>
                <th>Recipient / User</th>
                <th>Contact</th>
                <th class="text-end">Amount</th>
                <th>Payout Destination</th>
                <th>Liquidation Due</th>
                <th>Date Requested</th>
                <th class="text-center" style="width: 140px;">Actions</th>
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
                    <span class="currency-amount fs-sm fw-bold text-dark">
                      {{ formatCurrency($with->amount, $wCurr) }}
                    </span>
                  </td>
                  <td>
                    @if($wCurr === 'USD')
                      <span class="badge bg-primary-light text-primary">
                        <i class="fab fa-paypal me-1"></i> {{ $with->paypal_email ?: @$with->user->email }}
                      </span>
                    @else
                      <div class="fs-sm fw-semibold text-dark">{{ @$with->user->accountDetails->bank_name ?? 'Bank N/A' }}</div>
                      <div class="fs-xs font-monospace text-muted">{{ @$with->user->accountDetails->account_number }} ({{ @$with->user->accountDetails->name }})</div>
                    @endif
                  </td>
                  <td>
                    <span class="fs-sm fw-medium text-warning">
                      {{ \Carbon\Carbon::parse($with->next_payment_date)->diffForHumans() }}
                    </span>
                  </td>
                  <td>
                    <div class="fs-sm">{{ \Carbon\Carbon::parse($with->created_at)->format('M d, Y') }}</div>
                    <div class="fs-xs text-muted">{{ \Carbon\Carbon::parse($with->created_at)->format('h:i A') }}</div>
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-with-{{ $with->id }}">
                      <i class="fa fa-bolt me-1"></i> Process
                    </button>
                  </td>
                </tr>

                <!-- Bank / Processing Modal -->
                <div class="modal fade" id="modal-with-{{ $with->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Process Withdrawal #{{ $with->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="p-3 bg-light rounded-3 mb-3 text-center">
                          <span class="fs-xs text-muted text-uppercase fw-bold">Payout Amount</span>
                          <div class="fs-3 fw-bold text-success">{{ formatCurrency($with->amount, $wCurr) }}</div>
                          <span class="badge-currency badge-currency-{{ strtolower($wCurr) }} mt-1">{{ $wCurr }}</span>
                        </div>

                        <ul class="list-group mb-3">
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Recipient Name</span>
                            <strong>{{ @$with->user->name }}</strong>
                          </li>
                          @if($wCurr === 'USD')
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              <span>PayPal Email</span>
                              <strong class="text-primary">{{ $with->paypal_email ?: @$with->user->email }}</strong>
                            </li>
                          @else
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              <span>Bank Name</span>
                              <strong>{{ @$with->user->accountDetails->bank_name ?? 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              <span>Account Name</span>
                              <strong>{{ @$with->user->accountDetails->name ?? 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              <span>Account Number</span>
                              <strong class="font-monospace">{{ @$with->user->accountDetails->account_number ?? 'N/A' }}</strong>
                            </li>
                          @endif
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>User Live Balance</span>
                            <span>{{ formatCurrency(walletBalance($with->user_id), $wCurr) }}</span>
                          </li>
                        </ul>
                      </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a href="{{ url('update/withdrawal/manual/'.$with->id) }}" class="btn btn-sm btn-outline-primary"
                           onclick="return confirm('Mark this withdrawal as manually sent?')">
                          <i class="fa fa-check me-1"></i> Manual Update
                        </a>
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modal-pin-{{ $with->id }}" data-bs-dismiss="modal">
                          <i class="fa fa-lock me-1"></i> Process via API (PIN)
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- PIN Verification Modal -->
                <div class="modal fade" id="modal-pin-{{ $with->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Enter PIN to Process Payout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form method="POST" action="{{ url('update/withdrawal/'.$with->id.'/verify-pin') }}">
                        @csrf
                        <div class="modal-body">
                          <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            You are about to disburse <strong>{{ formatCurrency($with->amount, $wCurr) }}</strong> to <strong>{{ $with->user->name }}</strong>.
                          </div>
                          <div class="mb-3">
                            <label class="form-label fw-semibold">Security PIN (6 Digits)</label>
                            <input type="password" name="pin" class="form-control form-control-lg text-center font-monospace"
                                   placeholder="******" maxlength="6" required pattern="\d{6}" inputmode="numeric" autofocus>
                            <small class="form-text text-muted">Enter admin 6-digit payout security PIN</small>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-check me-1"></i> Confirm & Disburse
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fa fa-wallet fa-3x text-muted mb-3 d-block opacity-25"></i>
                    No queued withdrawals match the selected filters.
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
