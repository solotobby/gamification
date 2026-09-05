@extends('layouts.main.master')

@section('title', 'Pending Task Proof Approvals')

@section('content')

  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <div>
          <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-1">Pending Task Approvals</h1>
          <span class="text-muted fs-sm">Review and approve worker task submissions queued for reward disbursement</span>
        </div>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Tasks</li>
            <li class="breadcrumb-item active" aria-current="page">Unapproved Proofs</li>
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

    <!-- Filter Card -->
    <div class="block block-rounded">
      <div class="block-content py-3">
        <form action="{{ url('unapproved') }}" method="GET">
          <div class="row g-3 align-items-center">
            <div class="col-md-4">
              <label class="form-label fs-xs fw-bold text-muted text-uppercase">Start Date</label>
              <input type="date" class="form-control" name="start" value="{{ request('start') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fs-xs fw-bold text-muted text-uppercase">End Date</label>
              <input type="date" class="form-control" name="end" value="{{ request('end') }}">
            </div>
            <div class="col-md-4 pt-md-4">
              <button type="submit" class="btn btn-primary me-2">
                <i class="fa fa-filter me-1"></i> Filter Dates
              </button>
              @if(request()->has(['start', 'end']))
                <a href="{{ url('unapproved') }}" class="btn btn-outline-secondary">
                  <i class="fa fa-times me-1"></i> Reset
                </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Submissions Table Card -->
    <div class="block block-rounded">
      <form action="{{ url('mass/approval') }}" method="POST" id="massApprovalForm">
        @csrf
        <div class="block-header block-header-default d-flex justify-content-between align-items-center">
          <h3 class="block-title">
            Pending Submissions <span class="badge bg-warning text-dark rounded-pill ms-2">{{ number_format($campaigns->total()) }}</span>
          </h3>
          <div class="block-options">
            <button class="btn btn-sm btn-success" type="submit" onclick="return confirm('Approve all selected task proofs and disburse rewards?')">
              <i class="fa fa-check-double me-1"></i> Approve Selected
            </button>
          </div>
        </div>

        <div class="block-content p-0">
          <div class="table-responsive">
            <table class="table table-modern table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th style="width: 40px;" class="text-center">
                    <input type="checkbox" class="form-check-input" id="checkAll">
                  </th>
                  <th>Currency</th>
                  <th>Campaign</th>
                  <th>Worker</th>
                  <th class="text-end">Reward Amount</th>
                  <th>Status</th>
                  <th>Submitted</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($campaigns as $list)
                  @php
                    $wCurr = @$list->currency ?: (@$list->campaign->currency ?: 'NGN');
                  @endphp
                  <tr>
                    <td class="text-center">
                      <input type="checkbox" class="form-check-input itemCheckbox" name="id[]" value="{{ $list->id }}">
                    </td>
                    <td>
                      <span class="badge-currency badge-currency-{{ strtolower($wCurr) }}">
                        <i class="fa fa-circle fs-xs"></i> {{ $wCurr }}
                      </span>
                    </td>
                    <td>
                      <div class="fw-semibold text-dark">
                        <a href="{{ url('campaign/info/' . @$list->campaign_id) }}" class="text-dark text-hover-primary" target="_blank">
                          {{ @$list->campaign->post_title ?? 'Campaign #' . $list->campaign_id }}
                        </a>
                      </div>
                      <div class="fs-xs text-muted">Job ID: #{{ @$list->campaign->job_id }}</div>
                    </td>
                    <td>
                      <div class="fw-medium">
                        <a href="{{ url('user/' . @$list->user->id . '/info') }}" class="text-muted" target="_blank">
                          {{ @$list->user->name ?? 'User #' . $list->user_id }}
                        </a>
                      </div>
                      <div class="fs-xs text-muted">{{ @$list->user->email }}</div>
                    </td>
                    <td class="text-end">
                      <span class="fs-sm fw-bold text-success">
                        {{ formatCurrency(@$list->amount, $wCurr) }}
                      </span>
                    </td>
                    <td>
                      <span class="badge rounded-pill bg-warning-light text-warning border border-warning px-2 py-1">
                        <i class="fa fa-clock me-1"></i> {{ ucfirst($list->status) }}
                      </span>
                    </td>
                    <td>
                      <div class="fs-sm">{{ \Carbon\Carbon::parse($list->created_at)->format('M d, Y') }}</div>
                      <div class="fs-xs text-muted">{{ \Carbon\Carbon::parse($list->created_at)->diffForHumans() }}</div>
                    </td>
                    <td class="text-center">
                      <a href="{{ url('campaign/info/' . @$list->campaign_id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                        Inspect
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                      <i class="fa fa-check-circle fa-3x text-success mb-3 d-block opacity-25"></i>
                      No pending task proofs require approval.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="block-content border-top py-3">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <button class="btn btn-sm btn-success" type="submit" onclick="return confirm('Approve all selected task proofs and disburse rewards?')">
              <i class="fa fa-check-double me-1"></i> Approve Selected Tasks
            </button>
            <div>
              {!! $campaigns->appends(request()->query())->links('pagination::bootstrap-4') !!}
            </div>
          </div>
        </div>
      </form>
    </div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const checkAll = document.getElementById('checkAll');
      if (checkAll) {
        checkAll.addEventListener('change', function() {
          const checkboxes = document.querySelectorAll('.itemCheckbox');
          checkboxes.forEach(cb => cb.checked = checkAll.checked);
        });
      }
    });
  </script>
@endsection