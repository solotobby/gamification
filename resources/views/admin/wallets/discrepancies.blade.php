@extends('layouts.main.master')

@section('title', 'Wallet Balance Discrepancies')

@section('content')

  <!-- Page Header -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <div>
          <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-1">
            <i class="fa fa-scale-balanced text-primary me-2"></i>Wallet Balance Discrepancies
          </h1>
          <span class="text-muted fs-sm">Audit live balances against immutable payment transaction ledgers to detect overcredits, deficits, and leakages</span>
        </div>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Financials</li>
            <li class="breadcrumb-item active" aria-current="page">Discrepancies</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <div class="content">

    <!-- Flash Notifications -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if(session('info'))
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fa fa-info-circle me-2"></i> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- KPI Summary Overview Cards -->
    <div class="row g-3 mb-4">
      @php
        $totalOvercreditedCount = $statsRaw->sum('overcredited_count');
        $totalUndercreditedCount = $statsRaw->sum('undercredited_count');
        $totalSyncedCount = $statsRaw->sum('synced_count');
        $totalWallets = $statsRaw->sum('total_wallets');
      @endphp

      <div class="col-xl-3 col-sm-6">
        <div class="block block-rounded block-link-shadow h-100 mb-0 border-start border-4 border-primary">
          <div class="block-content block-content-full d-flex justify-content-between align-items-center">
            <div>
              <div class="fs-xs fw-semibold text-uppercase text-muted">Audited Wallets</div>
              <div class="fs-2 fw-bold text-dark mt-1">{{ number_format($totalWallets) }}</div>
              <div class="fs-xs text-muted mt-1">Calculated via ledger batch</div>
            </div>
            <div class="item item-circle bg-primary-light text-primary">
              <i class="fa fa-users fs-4"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-sm-6">
        <div class="block block-rounded block-link-shadow h-100 mb-0 border-start border-4 border-danger">
          <div class="block-content block-content-full d-flex justify-content-between align-items-center">
            <div>
              <div class="fs-xs fw-semibold text-uppercase text-danger">Overcredited Accounts</div>
              <div class="fs-2 fw-bold text-danger mt-1">{{ number_format($totalOvercreditedCount) }}</div>
              <div class="fs-xs text-muted mt-1">Live balance exceeds ledger</div>
            </div>
            <div class="item item-circle bg-danger-light text-danger">
              <i class="fa fa-arrow-trend-up fs-4"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-sm-6">
        <div class="block block-rounded block-link-shadow h-100 mb-0 border-start border-4 border-warning">
          <div class="block-content block-content-full d-flex justify-content-between align-items-center">
            <div>
              <div class="fs-xs fw-semibold text-uppercase text-warning">Undercredited Accounts</div>
              <div class="fs-2 fw-bold text-warning mt-1">{{ number_format($totalUndercreditedCount) }}</div>
              <div class="fs-xs text-muted mt-1">Ledger balance exceeds live</div>
            </div>
            <div class="item item-circle bg-warning-light text-warning">
              <i class="fa fa-arrow-trend-down fs-4"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-sm-6">
        <div class="block block-rounded block-link-shadow h-100 mb-0 border-start border-4 border-success">
          <div class="block-content block-content-full d-flex justify-content-between align-items-center">
            <div>
              <div class="fs-xs fw-semibold text-uppercase text-success">Accurate / Synced</div>
              <div class="fs-2 fw-bold text-success mt-1">{{ number_format($totalSyncedCount) }}</div>
              <div class="fs-xs text-muted mt-1">100% matched with transactions</div>
            </div>
            <div class="item item-circle bg-success-light text-success">
              <i class="fa fa-check-double fs-4"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Multi-Currency Breakdown Cards -->
    @if($statsRaw->count() > 0)
      <div class="row g-3 mb-4">
        @foreach($statsRaw as $st)
          @if($st->overcredited_count > 0 || $st->undercredited_count > 0)
            <div class="col-xl-4 col-md-6">
              <div class="block block-rounded mb-0 shadow-sm border">
                <div class="block-header block-header-default bg-body-light py-2">
                  <h3 class="block-title fs-sm fw-bold">
                    <span class="badge bg-primary me-1">{{ $st->curr }}</span> Currency Audit
                  </h3>
                  <div class="block-options">
                    <a href="{{ request()->fullUrlWithQuery(['currency' => $st->curr]) }}" class="btn btn-sm btn-alt-secondary">
                      Filter <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                  </div>
                </div>
                <div class="block-content py-3">
                  <div class="d-flex justify-content-between mb-2">
                    <span class="fs-sm text-muted">Overcredit Exposure:</span>
                    <span class="fs-sm fw-bold text-danger">
                      {{ $st->curr }} {{ number_format($st->overcredited_amount, 2) }}
                      <span class="badge bg-danger-light text-danger">({{ $st->overcredited_count }})</span>
                    </span>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                    <span class="fs-sm text-muted">Undercredit Deficit:</span>
                    <span class="fs-sm fw-bold text-warning">
                      {{ $st->curr }} {{ number_format($st->undercredited_amount, 2) }}
                      <span class="badge bg-warning-light text-warning">({{ $st->undercredited_count }})</span>
                    </span>
                  </div>
                  <div class="d-flex justify-content-between">
                    <span class="fs-sm text-muted">Synced Accounts:</span>
                    <span class="fs-sm fw-bold text-success">{{ number_format($st->synced_count) }} / {{ number_format($st->total_wallets) }}</span>
                  </div>
                </div>
              </div>
            </div>
          @endif
        @endforeach
      </div>
    @endif

    <!-- Filters & Search Toolbar -->
    <div class="block block-rounded mb-4">
      <div class="block-content py-3">
        <form method="GET" action="{{ route('admin.wallet.discrepancies') }}">
          <div class="row g-3 align-items-center">
            <div class="col-lg-4 col-md-6">
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                <input type="text" class="form-control" name="search"
                       placeholder="Search name, email, phone, or ID..."
                       value="{{ request('search') }}">
              </div>
            </div>

            <div class="col-lg-3 col-md-3">
              <select name="currency" class="form-select" onchange="this.form.submit()">
                <option value="ALL" {{ request('currency', 'ALL') === 'ALL' ? 'selected' : '' }}>All Currencies</option>
                @foreach($activeCurrencies as $c)
                  <option value="{{ $c->code }}" {{ request('currency') === $c->code ? 'selected' : '' }}>
                    {{ $c->code }} - {{ $c->country ?? $c->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-3 col-md-3">
              <select name="filter" class="form-select" onchange="this.form.submit()">
                <option value="discrepancy" {{ request('filter', 'discrepancy') === 'discrepancy' ? 'selected' : '' }}>Only Discrepancies (Diff > 0)</option>
                <option value="overcredited" {{ request('filter') === 'overcredited' ? 'selected' : '' }}>Overcredited (Live > Ledger)</option>
                <option value="undercredited" {{ request('filter') === 'undercredited' ? 'selected' : '' }}>Undercredited (Live < Ledger)</option>
                <option value="synced" {{ request('filter') === 'synced' ? 'selected' : '' }}>Synced (Diff = 0)</option>
                <option value="all" {{ request('filter') === 'all' ? 'selected' : '' }}>All Accounts</option>
              </select>
            </div>

            <div class="col-lg-2 col-md-12 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1">
                <i class="fa fa-filter me-1"></i> Apply
              </button>
              @if(request()->hasAny(['search', 'currency', 'filter']))
                <a href="{{ route('admin.wallet.discrepancies') }}" class="btn btn-alt-secondary" title="Reset Filters">
                  <i class="fa fa-undo"></i>
                </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Discrepancy Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          <i class="fa fa-list text-muted me-1"></i> User Wallet Audit Ledger
          <span class="badge bg-secondary ms-1">{{ number_format($wallets->total()) }} Total Records</span>
        </h3>
      </div>
      <div class="block-content block-content-full p-0">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-vcenter mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 60px;">#ID</th>
                <th>User Details</th>
                <th>Currency</th>
                <th class="text-end">Live Balance</th>
                <th class="text-end">Ledger (Calculated)</th>
                <th class="text-end">Discrepancy (Live - Ledger)</th>
                <th>Calculated At</th>
                <th class="text-center" style="width: 170px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($wallets as $w)
                @php
                  $curr = strtoupper($w->base_currency ?: 'NGN');
                  if ($curr === 'NAIRA') $curr = 'NGN';
                  if ($curr === 'DOLLAR') $curr = 'USD';

                  $isOver = $w->diff > 0.01;
                  $isUnder = $w->diff < -0.01;
                  $isSynced = abs($w->diff) <= 0.01;
                @endphp
                <tr>
                  <td class="fw-semibold fs-sm text-muted">#{{ $w->user_id }}</td>
                  <td>
                    <div class="fw-semibold text-dark">
                      <a href="{{ url('user/transaction?search=' . $w->user_email) }}" class="text-dark" target="_blank" title="View User Transactions">
                        {{ $w->user_name }}
                      </a>
                      @if($w->is_verified)
                        <i class="fa fa-check-circle text-primary fs-xs" title="Verified User"></i>
                      @endif
                    </div>
                    <div class="fs-xs text-muted">{{ $w->user_email }} | {{ $w->user_phone ?? 'N/A' }}</div>
                  </td>
                  <td>
                    <span class="badge bg-primary-light text-primary fw-bold">{{ $curr }}</span>
                  </td>
                  <td class="text-end fw-semibold">
                    {{ $curr }} {{ number_format($w->live_balance, 2) }}
                  </td>
                  <td class="text-end fw-semibold text-primary">
                    {{ $curr }} {{ number_format($w->calculated_balance, 2) }}
                  </td>
                  <td class="text-end">
                    @if($isOver)
                      <span class="badge bg-danger fs-xs" title="Live balance exceeds transaction ledger">
                        +{{ $curr }} {{ number_format($w->diff, 2) }} (Overcredited)
                      </span>
                    @elseif($isUnder)
                      <span class="badge bg-warning text-dark fs-xs" title="Ledger balance exceeds live balance">
                        {{ $curr }} {{ number_format($w->diff, 2) }} (Deficit)
                      </span>
                    @else
                      <span class="badge bg-success-light text-success fs-xs">
                        <i class="fa fa-check me-1"></i> Synced (0.00)
                      </span>
                    @endif
                  </td>
                  <td class="fs-xs text-muted">
                    @if($w->temp_balance_calculated_at)
                      {{ \Carbon\Carbon::parse($w->temp_balance_calculated_at)->diffForHumans() }}
                    @else
                      <span class="text-danger">Not Yet Run</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <!-- Recalculate Button -->
                      <form method="POST" action="{{ route('admin.wallet.recalculate', $w->user_id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-alt-secondary" title="Recalculate Single User Ledger" onclick="return confirm('Recalculate transaction ledger for {{ $w->user_name }}?')">
                          <i class="fa fa-sync-alt"></i>
                        </button>
                      </form>

                      <!-- View Transactions -->
                      <a href="{{ url('user/transaction?search=' . $w->user_email) }}" target="_blank" class="btn btn-alt-info" title="Inspect User Transactions">
                        <i class="fa fa-history"></i>
                      </a>

                      <!-- Reconcile Modal Trigger -->
                      <button type="button" class="btn btn-alt-primary" title="Reconcile Balance"
                              onclick="openReconcileModal({{ json_encode([
                                'id' => $w->user_id,
                                'name' => $w->user_name,
                                'email' => $w->user_email,
                                'currency' => $curr,
                                'live_balance' => (float)$w->live_balance,
                                'calculated_balance' => (float)$w->calculated_balance,
                                'diff' => (float)$w->diff,
                                'abs_diff' => (float)$w->abs_diff,
                                'is_over' => $isOver,
                                'is_under' => $isUnder
                              ]) }})">
                        <i class="fa fa-wrench me-1"></i> Fix
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fa fa-check-circle text-success fs-1 mb-2 d-block"></i>
                    No wallet balance discrepancies found matching your criteria.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($wallets->hasPages())
        <div class="block-content block-content-full border-top py-3 d-flex justify-content-center">
          {!! $wallets->appends(request()->query())->links('pagination::bootstrap-4') !!}
        </div>
      @endif
    </div>

  </div>

  <!-- Reconciliation Modal -->
  <div class="modal fade" id="reconcileModal" tabindex="-1" aria-labelledby="reconcileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="POST" id="reconcileForm" action="">
          @csrf
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title text-white" id="reconcileModalLabel">
              <i class="fa fa-scale-balanced me-2"></i>Reconcile Wallet Balance
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- User Summary Box -->
            <div class="bg-body-light p-3 rounded mb-3 border">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold text-dark fs-sm" id="modalUserName">User Name</span>
                <span class="badge bg-primary" id="modalUserCurrency">NGN</span>
              </div>
              <div class="fs-xs text-muted mb-2" id="modalUserEmail">user@example.com</div>
              <div class="row g-2 text-center fs-xs pt-2 border-top">
                <div class="col-4">
                  <div class="text-muted">Live Balance</div>
                  <div class="fw-bold text-dark fs-sm" id="modalLiveBalance">0.00</div>
                </div>
                <div class="col-4">
                  <div class="text-muted">Ledger (Target)</div>
                  <div class="fw-bold text-primary fs-sm" id="modalTargetBalance">0.00</div>
                </div>
                <div class="col-4">
                  <div class="text-muted">Discrepancy</div>
                  <div class="fw-bold fs-sm" id="modalDiff">0.00</div>
                </div>
              </div>
            </div>

            <!-- Reconciliation Action -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Reconciliation Action <span class="text-danger">*</span></label>
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="action" id="actionSync" value="sync_to_calculated" checked onchange="toggleCustomAmount()">
                <label class="form-check-label" for="actionSync">
                  <strong>Auto-Sync to Ledger Balance</strong>
                  <div class="text-muted fs-xs" id="syncActionDescription">Automatically debit or credit the exact difference to match the immutable ledger.</div>
                </label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="action" id="actionDebit" value="debit" onchange="toggleCustomAmount()">
                <label class="form-check-label" for="actionDebit">
                  <strong>Custom Debit</strong> (Deduct specified excess funds)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="action" id="actionCredit" value="credit" onchange="toggleCustomAmount()">
                <label class="form-check-label" for="actionCredit">
                  <strong>Custom Credit</strong> (Add specified missing funds)
                </label>
              </div>
            </div>

            <!-- Custom Amount Field (hidden on auto-sync) -->
            <div class="mb-3 d-none" id="customAmountGroup">
              <label class="form-label fw-semibold" for="customAmount">Adjustment Amount <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text" id="modalCurrencySymbol">NGN</span>
                <input type="number" step="0.01" min="0.01" class="form-control" name="amount" id="customAmount" placeholder="0.00">
              </div>
            </div>

            <!-- Reason / Audit Note -->
            <div class="mb-3">
              <label class="form-label fw-semibold" for="reconcileReason">Audit Reason <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="reason" id="reconcileReason" placeholder="e.g. Correcting duplicate auto-approval credit" required value="Correcting wallet balance discrepancy to match payment transaction ledger">
              <div class="form-text fs-xs text-muted">This will be permanently recorded in PaymentTransactions and ActivityAuditLog.</div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-check me-1"></i> Confirm & Reconcile
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@section('js')
<script>
  let currentTargetData = null;

  function openReconcileModal(data) {
    currentTargetData = data;
    document.getElementById('reconcileForm').action = "{{ url('admin/wallet/reconcile') }}/" + data.id;
    document.getElementById('modalUserName').innerText = data.name + ' (#' + data.id + ')';
    document.getElementById('modalUserEmail').innerText = data.email;
    document.getElementById('modalUserCurrency').innerText = data.currency;
    document.getElementById('modalCurrencySymbol').innerText = data.currency;
    document.getElementById('modalLiveBalance').innerText = data.currency + ' ' + parseFloat(data.live_balance).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('modalTargetBalance').innerText = data.currency + ' ' + parseFloat(data.calculated_balance).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    const diffEl = document.getElementById('modalDiff');
    const syncDesc = document.getElementById('syncActionDescription');
    const absDiff = parseFloat(data.abs_diff).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    if (data.is_over) {
      diffEl.className = 'fw-bold fs-sm text-danger';
      diffEl.innerText = '+' + data.currency + ' ' + absDiff + ' (Over)';
      syncDesc.innerText = 'Will DEBIT ' + data.currency + ' ' + absDiff + ' to eliminate excess funds and bring live balance to ' + data.currency + ' ' + parseFloat(data.calculated_balance).toLocaleString('en-US', {minimumFractionDigits: 2});
    } else if (data.is_under) {
      diffEl.className = 'fw-bold fs-sm text-warning';
      diffEl.innerText = '-' + data.currency + ' ' + absDiff + ' (Deficit)';
      syncDesc.innerText = 'Will CREDIT ' + data.currency + ' ' + absDiff + ' to restore missing funds and bring live balance to ' + data.currency + ' ' + parseFloat(data.calculated_balance).toLocaleString('en-US', {minimumFractionDigits: 2});
    } else {
      diffEl.className = 'fw-bold fs-sm text-success';
      diffEl.innerText = '0.00 (Synced)';
      syncDesc.innerText = 'Wallet is already synchronized with calculated ledger balance.';
    }

    document.getElementById('actionSync').checked = true;
    toggleCustomAmount();

    const modal = new bootstrap.Modal(document.getElementById('reconcileModal'));
    modal.show();
  }

  function toggleCustomAmount() {
    const isCustom = document.getElementById('actionDebit').checked || document.getElementById('actionCredit').checked;
    const amountGroup = document.getElementById('customAmountGroup');
    const amountInput = document.getElementById('customAmount');

    if (isCustom) {
      amountGroup.classList.remove('d-none');
      amountInput.required = true;
      if (currentTargetData && (!amountInput.value || amountInput.value == '0')) {
        amountInput.value = parseFloat(currentTargetData.abs_diff).toFixed(2);
      }
    } else {
      amountGroup.classList.add('d-none');
      amountInput.required = false;
    }
  }
</script>
@endsection
