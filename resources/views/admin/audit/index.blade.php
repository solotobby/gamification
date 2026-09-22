@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
<style>
    .badge-web { background-color: #E0F2FE; color: #0369A1; font-weight: 600; padding: .35em .65em; border-radius: 6px; font-size: 0.8rem; }
    .badge-app { background-color: #DCFCE7; color: #15803D; font-weight: 600; padding: .35em .65em; border-radius: 6px; font-size: 0.8rem; }
    .badge-api { background-color: #F3E8FF; color: #7E22CE; font-weight: 600; padding: .35em .65em; border-radius: 6px; font-size: 0.8rem; }
    .badge-admin { background-color: #FEE2E2; color: #B91C1C; font-weight: 600; padding: .3em .6em; border-radius: 6px; font-size: 0.78rem; }
    .badge-regular { background-color: #F1F5F9; color: #475569; font-weight: 600; padding: .3em .6em; border-radius: 6px; font-size: 0.78rem; }
    .stat-card { border-radius: 12px; transition: transform .15s; }
    .stat-card:hover { transform: translateY(-2px); }
    .device-info { font-size: 0.82rem; color: #4B5563; line-height: 1.35; }
    .ip-tag { font-family: monospace; font-size: 0.82rem; background: #F8FAFC; padding: 2px 6px; border-radius: 4px; border: 1px solid #E2E8F0; }
</style>
@endsection

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Activity & Device Audit Trail</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Audit Trail</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <!-- Page Content -->
  <div class="content">

    <!-- Stats Overview Cards -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-4 col-xl-2">
        <div class="block block-rounded stat-card mb-0 shadow-sm">
          <div class="block-content block-content-full p-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase">Total Activities</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ number_format($summary['total'] ?? 0) }}</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-xl-2">
        <div class="block block-rounded stat-card mb-0 shadow-sm border-start border-4 border-primary">
          <div class="block-content block-content-full p-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase">Today's Activity</div>
            <div class="fs-4 fw-bold text-primary mt-1">{{ number_format($summary['today'] ?? 0) }}</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-xl-2">
        <div class="block block-rounded stat-card mb-0 shadow-sm border-start border-4 border-info">
          <div class="block-content block-content-full p-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase"><i class="fa fa-globe text-info me-1"></i> Web Actions</div>
            <div class="fs-4 fw-bold text-info mt-1">{{ number_format($summary['web'] ?? 0) }}</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-xl-2">
        <div class="block block-rounded stat-card mb-0 shadow-sm border-start border-4 border-success">
          <div class="block-content block-content-full p-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase"><i class="fa fa-mobile-alt text-success me-1"></i> App Actions</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ number_format($summary['app'] ?? 0) }}</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-xl-2">
        <div class="block block-rounded stat-card mb-0 shadow-sm">
          <div class="block-content block-content-full p-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase"><i class="fa fa-mobile me-1"></i> Mobile Devices</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ number_format($summary['mobile'] ?? 0) }}</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-xl-2">
        <div class="block block-rounded stat-card mb-0 shadow-sm">
          <div class="block-content block-content-full p-3">
            <div class="fs-sm fw-semibold text-muted text-uppercase"><i class="fa fa-desktop me-1"></i> Desktop</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ number_format($summary['desktop'] ?? 0) }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Block -->
    <div class="block block-rounded shadow-sm">
      <div class="block-header block-header-default py-3">
        <h3 class="block-title fs-sm fw-semibold text-uppercase text-muted"><i class="fa fa-filter me-1"></i> Filter & Search Activities</h3>
      </div>
      <div class="block-content">
        <form action="{{ url('audit/trail') }}" method="GET">
          <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label fs-sm">Search Keyword / User / IP</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, Email, IP, Device, Action..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fs-sm">Source / Platform</label>
                <select name="client_type" class="form-select form-select-sm">
                    <option value="">All Sources</option>
                    <option value="web" {{ request('client_type') === 'web' ? 'selected' : '' }}>Web Platform</option>
                    <option value="app" {{ request('client_type') === 'app' ? 'selected' : '' }}>Mobile App (iOS/Android)</option>
                    <option value="api" {{ request('client_type') === 'api' ? 'selected' : '' }}>Direct API</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fs-sm">Device Type</label>
                <select name="device" class="form-select form-select-sm">
                    <option value="">All Devices</option>
                    <option value="mobile" {{ request('device') === 'mobile' ? 'selected' : '' }}>Mobile</option>
                    <option value="desktop" {{ request('device') === 'desktop' ? 'selected' : '' }}>Desktop</option>
                    <option value="tablet" {{ request('device') === 'tablet' ? 'selected' : '' }}>Tablet</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fs-sm">User Type</label>
                <select name="user_type" class="form-select form-select-sm">
                    <option value="">All Users</option>
                    <option value="regular" {{ request('user_type') === 'regular' ? 'selected' : '' }}>Regular Users</option>
                    <option value="admin" {{ request('user_type') === 'admin' ? 'selected' : '' }}>Admins / Staff</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fs-sm">Activity Type</label>
                <select name="activity_type" class="form-select form-select-sm">
                    <option value="">All Activities</option>
                    <option value="login" {{ request('activity_type') === 'login' ? 'selected' : '' }}>Login</option>
                    <option value="account_creation" {{ request('activity_type') === 'account_creation' ? 'selected' : '' }}>Account Creation</option>
                    <option value="google_account_creation" {{ request('activity_type') === 'google_account_creation' ? 'selected' : '' }}>Google Signup</option>
                    <option value="campaign_submission" {{ request('activity_type') === 'campaign_submission' ? 'selected' : '' }}>Campaign Submission</option>
                    <option value="withdrawal_request" {{ request('activity_type') === 'withdrawal_request' ? 'selected' : '' }}>Withdrawal Request</option>
                    <option value="withdrawal_sent" {{ request('activity_type') === 'withdrawal_sent' ? 'selected' : '' }}>Withdrawal Sent</option>
                    <option value="wallet_topup" {{ request('activity_type') === 'wallet_topup' ? 'selected' : '' }}>Wallet Topup</option>
                    <option value="account_verification" {{ request('activity_type') === 'account_verification' ? 'selected' : '' }}>Account Verification</option>
                    <option value="survey_points" {{ request('activity_type') === 'survey_points' ? 'selected' : '' }}>Survey Points</option>
                    <option value="admin_action" {{ request('activity_type') === 'admin_action' ? 'selected' : '' }}>Admin Action</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fs-sm">Start Date</label>
                <input type="date" class="form-control form-control-sm" name="start" value="{{ request('start') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fs-sm">End Date</label>
                <input type="date" class="form-control form-control-sm" name="end" value="{{ request('end') }}">
            </div>
            <div class="col-md-6 d-flex align-items-end gap-2">
              <button type="submit" class="btn btn-primary btn-sm px-3">
                <i class="fa fa-search me-1"></i> Apply Filters
              </button>
              <a href="{{ url('audit/trail') }}" class="btn btn-alt-secondary btn-sm px-3">
                <i class="fa fa-undo me-1"></i> Reset
              </a>
            </div>
          </div>
        </form>

        <div class="table-responsive mt-3">
          <table class="table table-bordered table-striped table-hover table-vcenter align-middle">
            <thead class="table-light">
                <tr>
                    <th style="min-width: 170px;">User</th>
                    <th>Activity Type</th>
                    <th>Description</th>
                    <th style="min-width: 100px;">Source</th>
                    <th style="min-width: 160px;">Device & OS</th>
                    <th>IP Address</th>
                    <th>User Type</th>
                    <th style="min-width: 140px;">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($audits as $audit)
                    <tr>
                        <td class="fw-semibold">
                            @if ($audit->user)
                                <a href="{{ url('user/'.$audit->user->id.'/info') }}" target="_blank" class="text-primary text-decoration-none">
                                    {{ $audit->user->name }}
                                </a>
                                <div class="text-muted fs-xs">{{ $audit->user->email }}</div>
                            @else
                                <span class="text-muted">Guest / System (ID: {{ $audit->user_id ?? 'N/A' }})</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary-light text-dark font-monospace fs-xs">
                                {{ $audit->activity_type }}
                            </span>
                            @if (!empty($audit->action))
                                <div class="text-muted fs-xs mt-1">{{ $audit->action }}</div>
                            @endif
                        </td>
                        <td style="max-width: 280px; word-break: break-word;">
                            {{ $audit->description }}
                        </td>
                        <td>
                            @php
                                $client = strtolower($audit->client_type ?? 'web');
                            @endphp
                            @if ($client === 'app' || $audit->is_app)
                                <span class="badge-app"><i class="fa fa-mobile-alt me-1"></i> App</span>
                            @elseif ($client === 'api')
                                <span class="badge-api"><i class="fa fa-code me-1"></i> API</span>
                            @else
                                <span class="badge-web"><i class="fa fa-globe me-1"></i> Web</span>
                            @endif
                        </td>
                        <td>
                            <div class="device-info">
                                <div>
                                    @if (strtolower($audit->device ?? '') === 'mobile' || $audit->is_app)
                                        <i class="fa fa-mobile text-success me-1"></i>
                                    @elseif (strtolower($audit->device ?? '') === 'tablet')
                                        <i class="fa fa-tablet-alt text-info me-1"></i>
                                    @else
                                        <i class="fa fa-desktop text-muted me-1"></i>
                                    @endif
                                    <strong>{{ $audit->platform ?: ($audit->is_app ? 'Mobile' : 'Web') }}</strong>
                                </div>
                                @if ($audit->device_model && $audit->device_model !== $audit->platform)
                                    <div class="text-muted fs-xs">{{ $audit->device_model }}</div>
                                @endif
                                @if ($audit->browser)
                                    <div class="text-muted fs-xs">{{ $audit->browser }}</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if (!empty($audit->ip_address))
                                <span class="ip-tag">{{ $audit->ip_address }}</span>
                            @else
                                <span class="text-muted fs-xs">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if (strtolower($audit->user_type ?? '') === 'admin')
                                <span class="badge-admin">Admin</span>
                            @else
                                <span class="badge-regular">Regular</span>
                            @endif
                        </td>
                        <td class="fs-xs">
                            <div>{{ $audit->created_at ? $audit->created_at->format('M d, Y H:i:s') : 'N/A' }}</div>
                            <div class="text-muted">{{ $audit->created_at ? $audit->created_at->diffForHumans() : '' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fa fa-info-circle me-1"></i> No activity logs found matching the selected criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
          </table>

          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted fs-sm">
                Showing {{ $audits->firstItem() ?? 0 }} to {{ $audits->lastItem() ?? 0 }} of {{ number_format($audits->total()) }} records
            </div>
            <div>
                {!! $audits->links('pagination::bootstrap-4') !!}
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END Full Table -->

  </div>
@endsection

@section('script')
<script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>
@endsection