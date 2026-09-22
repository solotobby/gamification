@extends('layouts.main.master')
@section('title', 'Fraud Management — Duplicate Accounts Detection')

@section('style')
<style>
.group-card { 
    border: 1px solid #e2e8f0; 
    border-radius: 10px; 
    margin-bottom: 1.5rem; 
    overflow: hidden; 
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    background: #ffffff;
    transition: all 0.2s ease;
}
.group-card:hover {
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
}
.group-header { 
    background: #f8fafc; 
    padding: 12px 18px; 
    border-bottom: 1px solid #e2e8f0; 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    flex-wrap: wrap; 
    gap: 10px; 
}
.group-header h5 { 
    margin: 0; 
    font-size: 1.05rem; 
    font-weight: 700; 
    color: #1e293b;
}
.badge-count { 
    background: #ef4444; 
    color: #fff; 
    border-radius: 20px; 
    padding: 3px 10px; 
    font-size: .8rem; 
    font-weight: 600;
}
.vector-nav-btn {
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.2s;
}
.pagination {
    margin-bottom: 0;
    gap: 3px;
    display: flex;
    flex-wrap: wrap;
}
.pagination .page-item .page-link {
    border-radius: 6px !important;
    padding: 5px 11px;
    font-size: 0.8rem;
    color: #4b5563;
    border: 1px solid #e5e7eb;
    box-shadow: none;
    text-decoration: none;
}
.pagination .page-item.active .page-link {
    background-color: #0665d0 !important;
    border-color: #0665d0 !important;
    color: #ffffff !important;
    font-weight: 600;
}
.pagination .page-item.disabled .page-link {
    color: #9ca3af;
    background-color: #f9fafb;
    border-color: #e5e7eb;
}
.pagination svg {
    width: 1rem !important;
    height: 1rem !important;
}
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="bg-body-light">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
      <div>
        <h1 class="fs-3 fw-semibold my-2 my-sm-1">
          <i class="fa fa-user-shield text-danger me-2"></i>Duplicate Account Detection
        </h1>
        <span class="text-muted fs-sm">
          Detect and prevent multi-accounting fraud across Bank Beneficiary Names, Bank Account Numbers, Phone Numbers, User Full Names, and Shared IP Addresses (excluding admin/staff).
        </span>
      </div>
      <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Fraud Management</li>
          <li class="breadcrumb-item active" aria-current="page">Duplicate Accounts</li>
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

  <!-- KPI Metric Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
      <div class="block block-rounded block-link-shadow h-100 mb-0 border-start border-4 border-danger">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <div>
            <div class="fs-xs fw-semibold text-uppercase text-muted">Duplicate Groups</div>
            <div class="fs-2 fw-bold text-danger">{{ number_format($stats['total_groups'] ?? 0) }}</div>
            <div class="fs-xs text-muted">Matching {{ ucwords(str_replace('_', ' ', $type)) }}</div>
          </div>
          <div class="item item-circle bg-danger-light">
            <i class="fa fa-users text-danger fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-sm-6">
      <div class="block block-rounded block-link-shadow h-100 mb-0 border-start border-4 border-primary">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <div>
            <div class="fs-xs fw-semibold text-uppercase text-muted">Total Accounts</div>
            <div class="fs-2 fw-bold text-primary">{{ number_format($stats['total_accounts'] ?? 0) }}</div>
            <div class="fs-xs text-muted">Linked user profiles</div>
          </div>
          <div class="item item-circle bg-primary-light">
            <i class="fa fa-id-card text-primary fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-sm-6">
      <div class="block block-rounded block-link-shadow h-100 mb-0 border-start border-4 border-success">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <div>
            <div class="fs-xs fw-semibold text-uppercase text-muted">Active Accounts</div>
            <div class="fs-2 fw-bold text-success">{{ number_format($stats['active_accounts'] ?? 0) }}</div>
            <div class="fs-xs text-muted">Eligible for tasks & payouts</div>
          </div>
          <div class="item item-circle bg-success-light">
            <i class="fa fa-user-check text-success fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-sm-6">
      <div class="block block-rounded block-link-shadow h-100 mb-0 border-start border-4 border-dark">
        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
          <div>
            <div class="fs-xs fw-semibold text-uppercase text-muted">Blacklisted Accounts</div>
            <div class="fs-2 fw-bold text-dark">{{ number_format($stats['blacklisted_accounts'] ?? 0) }}</div>
            <div class="fs-xs text-muted">Suspended for multi-accounting</div>
          </div>
          <div class="item item-circle bg-dark-light">
            <i class="fa fa-ban text-dark fs-4"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Detection Vector Navigation Tabs -->
  <div class="block block-rounded mb-4">
    <div class="block-content py-3">
      <div class="d-flex flex-wrap gap-2 align-items-center">
        <span class="fs-sm fw-bold text-uppercase text-muted me-2"><i class="fa fa-filter me-1"></i> Detect By:</span>
        
        <a href="{{ url('remove/duplicate/account?type=bank_name') }}" 
           class="btn btn-sm vector-nav-btn {{ $type === 'bank_name' ? 'btn-primary' : 'btn-alt-secondary' }}">
          <i class="fa fa-signature me-1"></i> Bank Beneficiary Names
          <span class="badge {{ $type === 'bank_name' ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1">
            {{ number_format($vectorCounts['bank_name'] ?? 0) }}
          </span>
        </a>

        <a href="{{ url('remove/duplicate/account?type=account_number') }}" 
           class="btn btn-sm vector-nav-btn {{ $type === 'account_number' ? 'btn-primary' : 'btn-alt-secondary' }}">
          <i class="fa fa-credit-card me-1"></i> Bank Account Numbers
          <span class="badge {{ $type === 'account_number' ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1">
            {{ number_format($vectorCounts['account_number'] ?? 0) }}
          </span>
        </a>

        <a href="{{ url('remove/duplicate/account?type=phone') }}" 
           class="btn btn-sm vector-nav-btn {{ $type === 'phone' ? 'btn-primary' : 'btn-alt-secondary' }}">
          <i class="fa fa-phone me-1"></i> Phone Numbers
          <span class="badge {{ $type === 'phone' ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1">
            {{ number_format($vectorCounts['phone'] ?? 0) }}
          </span>
        </a>

        <a href="{{ url('remove/duplicate/account?type=name') }}" 
           class="btn btn-sm vector-nav-btn {{ $type === 'name' ? 'btn-primary' : 'btn-alt-secondary' }}">
          <i class="fa fa-user-tag me-1"></i> User Full Names
          <span class="badge {{ $type === 'name' ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1">
            {{ number_format($vectorCounts['name'] ?? 0) }}
          </span>
        </a>

        <a href="{{ url('remove/duplicate/account?type=ip_address') }}" 
           class="btn btn-sm vector-nav-btn {{ $type === 'ip_address' ? 'btn-primary' : 'btn-alt-secondary' }}">
          <i class="fa fa-network-wired me-1"></i> Shared IP Addresses
          <span class="badge {{ $type === 'ip_address' ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1">
            {{ number_format($vectorCounts['ip_address'] ?? 0) }}
          </span>
        </a>
      </div>
    </div>
  </div>

  <!-- Filter & Search Toolbar (GET form with 50 per page) -->
  <div class="block block-rounded mb-4">
    <div class="block-content py-3">
      <form method="GET" action="{{ url('remove/duplicate/account') }}">
        <input type="hidden" name="type" value="{{ $type }}">
        
        <div class="row g-3 align-items-center">
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
              <input type="text" class="form-control" name="search" value="{{ $search }}" 
                     placeholder="Search across user names, emails, phones, banks, or account numbers...">
            </div>
          </div>
          
          <div class="col-md-3">
            <select class="form-select" name="status">
              <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Accounts Status</option>
              <option value="active_only" {{ $statusFilter === 'active_only' ? 'selected' : '' }}>Active Accounts Only</option>
              <option value="blacklisted_only" {{ $statusFilter === 'blacklisted_only' ? 'selected' : '' }}>Blacklisted Accounts Only</option>
            </select>
          </div>

          <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
              <i class="fa fa-filter me-1"></i> Filter
            </button>
            <a href="{{ url('remove/duplicate/account?type=' . $type) }}" class="btn btn-alt-secondary btn-sm" title="Reset Filters">
              <i class="fa fa-undo"></i>
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Duplicate Groups Container -->
  <div id="duplicateGroupsContainer">
    @forelse ($paginatedDuplicates as $matchValue => $accounts)
      @php
        $hasBlacklisted = $accounts->contains(fn($r) => (bool)$r->is_blacklisted);
        $firstAccount = $accounts->first();
      @endphp

      <div class="group-card">
        <div class="group-header">
          <div class="d-flex align-items-center gap-2 flex-wrap">
            @if($type === 'account_number')
              <i class="fa fa-credit-card text-primary fs-5"></i>
              <h5>Account No: <span class="text-primary font-monospace">{{ $matchValue }}</span></h5>
            @elseif($type === 'phone')
              <i class="fa fa-phone text-success fs-5"></i>
              <h5>Phone: <span class="text-success font-monospace">{{ $matchValue }}</span></h5>
            @elseif($type === 'name')
              <i class="fa fa-user text-info fs-5"></i>
              <h5>User Name: <span class="text-dark">{{ $matchValue }}</span></h5>
            @elseif($type === 'ip_address')
              <i class="fa fa-network-wired text-warning fs-5"></i>
              <h5>IP Address: <span class="font-monospace text-dark">{{ $matchValue }}</span></h5>
            @else
              <i class="fa fa-university text-primary fs-5"></i>
              <h5>Bank Beneficiary Name: <span class="text-dark">{{ $matchValue }}</span></h5>
            @endif

            <span class="badge-count">{{ $accounts->count() }} Accounts Linked</span>

            @if($hasBlacklisted)
              <span class="badge bg-danger text-white fs-xs"><i class="fa fa-ban me-1"></i> Has Blacklisted Account</span>
            @endif
          </div>

          <div class="fs-xs text-muted">
            Matching Vector: <strong>{{ $firstAccount->match_type_label ?? ucwords(str_replace('_', ' ', $type)) }}</strong>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter mb-0 fs-sm">
            <thead class="table-dark">
              <tr>
                <th style="min-width: 200px;">User Profile & Contact</th>
                <th style="min-width: 170px;">Bank Account Details</th>
                <th style="min-width: 130px;">Wallet Balance</th>
                <th style="min-width: 130px;">Activity Metrics</th>
                <th style="min-width: 130px;">Telemetry & Device</th>
                <th style="min-width: 100px;">Status</th>
                <th style="min-width: 120px;" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($accounts as $row)
                @php
                  $curr = strtoupper($row->currency ?? 'NGN');
                @endphp
                <tr class="{{ $row->is_blacklisted ? 'table-danger' : '' }}">
                  <td>
                    <div class="fw-bold text-dark">
                      <a href="{{ url('user/' . $row->user_id . '/info') }}" target="_blank" class="text-primary text-decoration-none">
                        {{ $row->user_name }} <i class="fa fa-external-link-alt fs-xs ms-1"></i>
                      </a>
                    </div>
                    <div class="fs-xs text-muted">{{ $row->email }}</div>
                    <div class="fs-xs text-muted">{{ $row->phone ?: 'No phone' }}</div>
                    <div class="fs-xs text-muted font-monospace">UID: #{{ $row->user_id }}</div>
                    @if($row->country)
                      <span class="badge bg-secondary-light text-dark fs-xs mt-1">{{ $row->country }}</span>
                    @endif
                  </td>

                  <td>
                    @if($row->bank_name || $row->account_number)
                      <div class="fw-semibold text-dark">{{ $row->bank_name ?: 'Unknown Bank' }}</div>
                      <div class="font-monospace fw-bold text-primary">{{ $row->account_number ?: 'N/A' }}</div>
                      <div class="fs-xs text-muted">Name: {{ $row->bank_account_name ?: 'N/A' }}</div>
                    @else
                      <span class="text-muted fs-xs"><i class="fa fa-info-circle me-1"></i> No bank details linked</span>
                    @endif
                  </td>

                  <td>
                    <div class="fw-bold fs-sm text-dark">
                      {{ $curr }} {{ number_format((float) $row->balance, 2) }}
                    </div>
                    <span class="badge bg-secondary-light text-secondary fs-xs">Active Balance</span>
                  </td>

                  <td>
                    <div class="fs-xs"><strong>Jobs:</strong> {{ number_format($row->job_count) }}</div>
                    <div class="fs-xs"><strong>Refs:</strong> {{ number_format($row->referral_count) }}</div>
                    <div class="fs-xs"><strong>Paid:</strong> {{ number_format((float) $row->total_withdrawn, 2) }}</div>
                  </td>

                  <td>
                    <div class="fs-xs text-dark">
                      <i class="fa fa-globe me-1 text-muted"></i>
                      {{ !empty($row->auth_device) ? ucfirst($row->auth_device) : 'Web' }}
                    </div>
                    <div class="fs-xs text-muted font-monospace mt-1">
                      IP: {{ !empty($row->last_ip) ? $row->last_ip : 'N/A' }}
                    </div>
                  </td>

                  <td>
                    @if($row->is_blacklisted)
                      <span class="badge bg-danger">Blacklisted</span>
                    @else
                      <span class="badge bg-success">Active</span>
                    @endif
                  </td>

                  <td class="text-center">
                    <div class="d-flex flex-column gap-1">
                      <a href="{{ url('user/' . $row->user_id . '/info#tab-activity') }}" target="_blank" class="btn btn-xs btn-outline-primary" title="View User Profile & Activities">
                        <i class="fa fa-user me-1"></i> Profile & Logs
                      </a>

                      @if(!$row->is_blacklisted)
                        <form method="POST" action="{{ route('admin.fraud.blacklist', $row->user_id) }}" onsubmit="return confirm('Blacklist and suspend {{ $row->user_name }}?')">
                          @csrf
                          <button class="btn btn-xs btn-danger w-100"><i class="fa fa-ban me-1"></i> Block</button>
                        </form>
                      @else
                        <form method="POST" action="{{ route('admin.fraud.unblacklist', $row->user_id) }}" onsubmit="return confirm('Unblock {{ $row->user_name }}?')">
                          @csrf
                          <button class="btn btn-xs btn-warning w-100"><i class="fa fa-unlock me-1"></i> Unblock</button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @empty
      <div class="block block-rounded text-center py-5">
        <div class="block-content">
          <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
          <h4>No Duplicate Accounts Detected</h4>
          <p class="text-muted">No multiple user accounts matching the selected vector ({{ ucwords(str_replace('_', ' ', $type)) }}) were found.</p>
        </div>
      </div>
    @endforelse
  </div>

  <!-- Pagination Controls (50 groups on a page) -->
  @if($paginatedDuplicates->hasPages())
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4">
      <div class="fs-sm text-muted mb-2 mb-sm-0">
        Showing <strong>{{ $paginatedDuplicates->firstItem() }}</strong> to <strong>{{ $paginatedDuplicates->lastItem() }}</strong> of <strong>{{ $paginatedDuplicates->total() }}</strong> duplicate groups (50 per page)
      </div>
      <div>
        {{ $paginatedDuplicates->appends(request()->query())->links() }}
      </div>
    </div>
  @endif

</div>
@endsection
