@extends('layouts.main.master')

@section('title', 'Approved Task Proofs')

@section('content')

  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <div>
          <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-1">Approved Task Proofs</h1>
          <span class="text-muted fs-sm">History of approved worker submissions and disbursed rewards</span>
        </div>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Tasks</li>
            <li class="breadcrumb-item active" aria-current="page">Approved Tasks</li>
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

    <!-- Approved Submissions Table Card -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Approved Submissions <span class="badge bg-success rounded-pill ms-2">{{ number_format($campaigns->total()) }}</span>
        </h3>
      </div>

      <div class="block-content p-0">
        <div class="table-responsive">
          <table class="table table-modern table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>Currency</th>
                <th>Campaign</th>
                <th>Worker</th>
                <th class="text-end">Reward Paid</th>
                <th>Status</th>
                <th>Approved Date</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($campaigns as $list)
                @php
                  $wCurr = @$list->currency ?: (@$list->campaign->currency ?: 'NGN');
                @endphp
                <tr>
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
                    <span class="badge rounded-pill bg-success-light text-success border border-success px-2 py-1">
                      <i class="fa fa-check-double me-1"></i> {{ ucfirst($list->status) }}
                    </span>
                  </td>
                  <td>
                    <div class="fs-sm">{{ \Carbon\Carbon::parse($list->created_at)->format('M d, Y') }}</div>
                    <div class="fs-xs text-muted">{{ \Carbon\Carbon::parse($list->created_at)->format('h:i A') }}</div>
                  </td>
                  <td class="text-center">
                    <a href="{{ url('reverse/transaction/' . $list->id) }}" 
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Reverse this task approval? The worker will be debited {{ formatCurrency(@$list->amount, $wCurr) }} and the campaigner refunded in {{ $wCurr }}.')">
                      <i class="fa fa-undo me-1"></i> Reverse
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fa fa-check-circle fa-3x text-muted mb-3 d-block opacity-25"></i>
                    No approved task submissions found.
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