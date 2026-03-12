@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<style>
.group-card { border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 1.5rem; overflow: hidden; }
.group-header { background: #f8f9fa; padding: 12px 16px; border-bottom: 1px solid #dee2e6; display: flex; align-items: center; gap: 10px; }
.group-header h5 { margin: 0; font-size: 1rem; }
.badge-count { background: #dc3545; color: #fff; border-radius: 20px; padding: 2px 10px; font-size: .8rem; }
</style>
@endsection

@section('content')
<div class="bg-body-light">
  <div class="content content-full">
    <h1 class="fs-3 fw-semibold my-3">Fraud Management — Duplicate Account Names</h1>
  </div>
</div>

<div class="content">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Grouped by Account Name — {{ $duplicates->count() }} groups</h3>
    </div>
    <div class="block-content">

      @foreach ($duplicates as $accountName => $accounts)
      <div class="group-card">
        <div class="group-header">
          <h5>{{ $accountName }}</h5>
          <span class="badge-count">{{ $accounts->count() }} accounts</span>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter mb-0">
            <thead class="table-dark">
              <tr>
                <th>User</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Bank / Acc No</th>
                <th>Wallet Balance</th>
                <th>Jobs</th>
                <th>Referrals</th>
                <th>Withdrawn</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($accounts as $row)
              <tr class="{{ $row->is_blacklisted ? 'table-danger' : '' }}">
                <td>{{ $row->user_name }}<br><small class="text-muted">ID: {{ $row->user_id }}</small></td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->phone }}</td>
                <td>{{ $row->bank_name }}<br><small>{{ $row->account_number }}</small></td>
                <td>
                  {{ strtoupper($row->base_currency ?? 'NGN') }}
                  {{ number_format($row->base_currency === 'USD' ? $row->usd_balance : $row->balance, 2) }}
                </td>
                <td>{{ number_format($row->job_count) }}</td>
                <td>{{ number_format($row->referral_count) }}</td>
                <td>{{ number_format($row->total_withdrawn, 2) }}</td>
                <td>
                  @if($row->is_blacklisted)
                    <span class="badge bg-danger">Blacklisted</span>
                  @else
                    <span class="badge bg-success">Active</span>
                  @endif
                </td>
                <td>
                  @if(!$row->is_blacklisted)
                  <form method="POST" action="{{ route('admin.fraud.blacklist', $row->user_id) }}" onsubmit="return confirm('Blacklist {{ $row->user_name }}?')">
                    @csrf
                    <button class="btn btn-sm btn-danger">Block</button>
                  </form>
                  @else
                  <form method="POST" action="{{ route('admin.fraud.unblacklist', $row->user_id) }}" onsubmit="return confirm('Unblock {{ $row->user_name }}?')">
                    @csrf
                    <button class="btn btn-sm btn-warning">Unblock</button>
                  </form>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</div>
@endsection
