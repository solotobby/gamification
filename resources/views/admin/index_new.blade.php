@extends('layouts.main.master')

@section('content')

    <div class="content">
        <!-- Header -->
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1 fw-bold">
                    Admin Command Center
                </h1>
                <p class="fw-medium mb-0 text-muted fs-sm">
                    Multi-currency overview, platform liabilities, and core operational metrics
                    @if(config('app.env') == 'Production')
                        &bull; <span class="badge bg-secondary-light text-dark"><i class="fa fa-map-marker-alt me-1 text-danger"></i> {{ currentLocation() }}</span>
                    @endif
                </p>
            </div>
            <div class="mt-4 mt-md-0 d-flex gap-2 justify-content-center">
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm btn-alt-primary px-3 shadow-sm" id="dropdown-analytics-overview"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-calendar-alt me-1"></i>
                        <span id="selected-option">
                            @if($period === 'today')
                                Today
                            @else
                                Last {{ $period }} days
                            @endif
                        </span>
                        <i class="fa fa-fw fa-angle-down ms-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end fs-sm shadow" aria-labelledby="dropdown-analytics-overview">
                        <a class="dropdown-item period-filter" data-period="today">Today</a>
                        <a class="dropdown-item period-filter" data-period="7">Last 7 days</a>
                        <a class="dropdown-item period-filter" data-period="14">Last 14 days</a>
                        <a class="dropdown-item period-filter" data-period="30">Last 30 days</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">

        @if(auth()->user()->role === 'super_admin' && isset($currencyWallets) && count($currencyWallets) > 0)
            <!-- Multi-Currency Liabilities & Treasury Section -->
            <div class="block block-rounded mb-4">
                <div class="block-header block-header-default">
                    <h3 class="block-title fs-sm fw-bold text-uppercase">
                        <i class="fa fa-wallet text-primary me-1"></i> Multi-Currency Wallet Liabilities & Balances
                    </h3>
                    <div class="block-options">
                        <span class="badge bg-primary rounded-pill">{{ count($currencyWallets) }} Active {{ Str::plural('Currency', count($currencyWallets)) }}</span>
                    </div>
                </div>
                <div class="block-content py-3">
                    <div class="row g-3">
                        @foreach($currencyWallets as $cw)
                            @php
                                $curr = $cw->currency;
                                $pendingForCurr = isset($pendingWithdrawalsByCurrency) ? $pendingWithdrawalsByCurrency->firstWhere('currency', $curr) : null;
                            @endphp
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="currency-stat-card h-100">
                                    <div class="stat-header">
                                        <span class="stat-title">{{ $curr }} Wallet Pool</span>
                                        <span class="badge-currency badge-currency-{{ strtolower($curr) }}">{{ $curr }}</span>
                                    </div>
                                    <div class="stat-value text-dark mb-1">
                                        {{ formatCurrency($cw->total_active_balance, $curr) }}
                                    </div>
                                    <div class="fs-xs text-muted mb-2">
                                        <i class="fa fa-users me-1"></i> {{ number_format($cw->count) }} Assigned Users
                                    </div>
                                    <div class="stat-footer pt-2 border-top d-flex justify-content-between align-items-center">
                                        @if($pendingForCurr && $pendingForCurr->total_amount > 0)
                                            <span class="badge bg-warning-light text-warning fw-semibold">
                                                Queued: {{ formatCurrency($pendingForCurr->total_amount, $curr) }}
                                            </span>
                                            <a href="{{ route('admin.withdrawal.queued', ['currency' => $curr]) }}" class="fs-xs text-primary fw-semibold">
                                                Review <i class="fa fa-arrow-right"></i>
                                            </a>
                                        @else
                                            <span class="text-success fs-xs fw-semibold"><i class="fa fa-check-circle me-1"></i> No pending payouts</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @php
            $colClass = auth()->user()->role === 'super_admin' ? 'col-sm-6 col-xl-3' : 'col-sm-6 col-xl-4';
        @endphp

        <!-- Core Operational Counters -->
        <div class="row items-push">
            <div class="{{ $colClass }}">
                <div class="block block-rounded text-center d-flex flex-column h-100 mb-0 shadow-sm">
                    <div class="block-content block-content-full flex-grow-1">
                        <div class="item rounded-3 bg-body mx-auto my-3" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fa fa-users fa-lg text-primary"></i>
                        </div>
                        <div class="fs-1 fw-bold" data-toggle="tooltip" title="Total users on platform">
                            <span id="totalUsers">--</span>
                        </div>
                        <div class="text-muted mb-3 fs-sm">Registered Users</div>
                        <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
                            <i class="fa fa-check-circle me-1"></i>
                            <span id="verifiedUsers">--</span> Verified
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                        <a class="fw-medium" href="{{ url('users') }}">
                            View All Users
                            <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="{{ $colClass }}">
                <div class="block block-rounded text-center d-flex flex-column h-100 mb-0 shadow-sm">
                    <div class="block-content block-content-full flex-grow-1">
                        <div class="item rounded-3 bg-body mx-auto my-3" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fa fa-level-up-alt fa-lg text-primary"></i>
                        </div>
                        <div class="fs-1 fw-bold"><span id="campaigns">--</span></div>
                        <div class="text-muted mb-3 fs-sm">Total Campaigns</div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                        <a class="fw-medium" href="{{ url('campaigns') }}">
                            View Campaigns
                            <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                        </a>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role === 'super_admin')
                <div class="{{ $colClass }}">
                    <div class="block block-rounded text-center d-flex flex-column h-100 mb-0 shadow-sm">
                        <div class="block-content block-content-full flex-grow-1">
                            <div class="item rounded-3 bg-body mx-auto my-3" style="width: 50px; height: 50px; line-height: 50px;">
                                <i class="fa fa-chart-line fa-lg text-success"></i>
                            </div>
                            <div class="fs-1 fw-bold">
                                &#8358;<span id="campaignValue">--</span>
                            </div>
                            <div class="text-muted mb-3 fs-sm">Campaigns Value (NGN)</div>
                            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
                                <i class="fa fa-caret-up me-1"></i>
                                Workers: &#8358;<span id="campaignWorker">--</span>
                            </div>
                        </div>
                        <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                            <a class="fw-medium" href="{{ url('campaigns') }}">
                                View Campaign Ledger
                                <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ $colClass }}">
                <div class="block block-rounded text-center d-flex flex-column h-100 mb-0 shadow-sm">
                    <div class="block-content block-content-full flex-grow-1">
                        <div class="item rounded-3 bg-body mx-auto my-3" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fa fa-user-clock fa-lg text-info"></i>
                        </div>
                        <div class="fs-1 fw-bold">
                            <span id="activeReg">--</span>
                        </div>
                        <div class="text-muted mb-3 fs-sm">Active Users In Period</div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
                        <a class="fw-medium" href="{{ url('users') }}">
                            Inspect Activity
                            <i class="fa fa-arrow-right ms-1 opacity-25"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="{{ $colClass }}">
                <div class="block block-rounded text-center d-flex flex-column h-100 mb-0 shadow-sm">
                    <div class="block-content block-content-full flex-grow-1">
                        <div class="item rounded-3 bg-body mx-auto my-3" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fa fa-mobile-alt fa-lg text-purple"></i>
                        </div>
                        <div class="fs-1 fw-bold">
                            {{ number_format($appUser ?? 0) }}
                        </div>
                        <div class="text-muted mb-3 fs-sm">Mobile App Users</div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light fs-sm text-muted">
                        Web Users: {{ number_format($webUser ?? 0) }}
                    </div>
                </div>
            </div>

            <div class="{{ $colClass }}">
                <div class="block block-rounded text-center d-flex flex-column h-100 mb-0 shadow-sm">
                    <div class="block-content block-content-full flex-grow-1">
                        <div class="item rounded-3 bg-body mx-auto my-3" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fa fa-fire fa-lg text-danger"></i>
                        </div>
                        <div class="fs-1 fw-bold">
                            {{ number_format($streak ?? 0) }}
                        </div>
                        <div class="text-muted mb-3 fs-sm">Daily Streaks Redeemed</div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light fs-sm text-muted">
                        Engagement metric
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Analytics Navigation -->
        <div class="row mt-2 mb-4">
            <div class="col-12 text-center">
                <a href="{{ url('admin/home/analytics') }}?period={{ $period }}" class="btn btn-lg btn-outline-primary px-4 shadow-sm">
                    <i class="fa fa-chart-bar me-2"></i>
                    Open Full System Analytics & Trends
                </a>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <script>
        $(document).ready(function () {
            var isSuperAdmin = {{ auth()->user()->role === 'super_admin' ? 'true' : 'false' }};

            // Period filter click handler
            $('.period-filter').click(function (e) {
                e.preventDefault();
                var period = $(this).data('period');
                var text = $(this).text();

                $('#selected-option').text(text);
                window.location.href = '{{ url("home") }}?period=' + period;
            });

            // Load initial stats via API
            function loadDashboardStats(period) {
                $.ajax({
                    url: '{{ url("admin/dashboard/api/default") }}',
                    method: 'GET',
                    data: { period: period },
                    success: function (response) {
                        var totalUsers = parseInt(response.registeredUser) || 0;
                        var verifiedUsers = parseInt(response.verifiedUser) || 0;
                        var campaigns = parseInt(response.campaigns) || 0;
                        var activeReg = parseInt(response.activeUsers) || 0;

                        document.getElementById("totalUsers").innerHTML = Intl.NumberFormat('en-US').format(totalUsers);
                        document.getElementById("verifiedUsers").innerHTML = Intl.NumberFormat('en-US').format(verifiedUsers);
                        document.getElementById("campaigns").innerHTML = Intl.NumberFormat('en-US').format(campaigns);
                        document.getElementById("activeReg").innerHTML = Intl.NumberFormat('en-US').format(activeReg);

                        if (isSuperAdmin && response.campaignValue !== undefined) {
                            var campaignValue = parseFloat(response.campaignValue) || 0;
                            var campaignWorker = parseFloat(response.campaignWorker) || 0;

                            document.getElementById("campaignValue").innerHTML = Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(campaignValue);

                            document.getElementById("campaignWorker").innerHTML = Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(campaignWorker);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error loading dashboard stats:', error);
                        document.getElementById("totalUsers").innerHTML = '0';
                        document.getElementById("verifiedUsers").innerHTML = '0';
                        document.getElementById("campaigns").innerHTML = '0';
                        document.getElementById("activeReg").innerHTML = '0';

                        if (isSuperAdmin) {
                            document.getElementById("campaignValue").innerHTML = '0.00';
                            document.getElementById("campaignWorker").innerHTML = '0.00';
                        }
                    }
                });
            }

            var currentPeriod = '{{ $period }}';
            loadDashboardStats(currentPeriod);
        });
    </script>
@endsection
