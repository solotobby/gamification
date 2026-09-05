@extends('layouts.main.master')

@section('style')
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endsection

@section('content')

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <div>
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-1">User Management</h1>
                    <span class="text-muted fs-sm">Single-currency user directory, balances, and verification controls</span>
                </div>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content">

        <!-- Filter & Search Toolbar -->
        <div class="block block-rounded">
            <div class="block-content">
                <form action="{{ url('users') }}" method="GET" id="userFilterForm">
                    <div class="row g-3 align-items-end mb-3">
                        <!-- Search Box -->
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label fw-semibold fs-sm">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Name, email, phone, referral...">
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="col-lg-2 col-md-3 col-6">
                            <label class="form-label fw-semibold fs-sm">From Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        </div>

                        <!-- End Date -->
                        <div class="col-lg-2 col-md-3 col-6">
                            <label class="form-label fw-semibold fs-sm">To Date</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>

                        <!-- Currency Dropdown -->
                        <div class="col-lg-2 col-md-4 col-6">
                            <label class="form-label fw-semibold fs-sm">Currency</label>
                            <select name="currency" class="form-select" onchange="this.form.submit()">
                                <option value="ALL" {{ request('currency') == 'ALL' || !request('currency') ? 'selected' : '' }}>All Currencies</option>
                                @if(isset($activeCurrencies))
                                    @foreach($activeCurrencies as $curr)
                                        <option value="{{ $curr->code }}" {{ request('currency') == $curr->code ? 'selected' : '' }}>
                                            {{ $curr->code }} - {{ $curr->country }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="col-lg-2 col-md-8 col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ url('users') }}" class="btn btn-outline-secondary" title="Reset Filters">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Currency Quick Pills -->
                    <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top">
                        <span class="fs-xs fw-bold text-uppercase text-muted me-1">Filter by Currency:</span>
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

                        <div class="ms-auto d-flex gap-2">
                            @if(isset($phoneCount) && $phoneCount > 0)
                                <button type="button" class="btn btn-sm btn-outline-success" onclick="viewPhoneNumbers()">
                                    <i class="fa fa-phone me-1"></i> Numbers ({{ number_format($phoneCount) }})
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="downloadPhoneNumbers()">
                                    <i class="fa fa-download me-1"></i> Download
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Users Found <span class="badge bg-primary rounded-pill ms-2">{{ number_format($users->total()) }}</span>
                </h3>
            </div>
            <div class="block-content p-0">
                <div class="table-responsive">
                    <table class="table table-modern table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Contact Info</th>
                                <th>Assigned Currency</th>
                                <th class="text-end">Active Balance</th>
                                <th class="text-center">Verification</th>
                                <th>Joined</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                @php
                                    $userCurr = $user->currency_code;
                                    $activeBal = $user->active_balance;
                                    $isVerified = $user->isVerifiedInCurrency();
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2 rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-primary" style="width: 36px; height: 36px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <a href="{{ url('user/' . $user->id . '/info') }}" class="fw-semibold text-dark text-hover-primary">
                                                    {{ $user->name }}
                                                </a>
                                                <div class="fs-xs text-muted">
                                                    Ref: <span class="font-monospace">{{ $user->referral_code ?? 'N/A' }}</span>
                                                    @if($user->is_celebrity)
                                                        <span class="badge bg-warning text-dark ms-1">Celebrity</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fs-sm">{{ $user->email }}</div>
                                        <div class="fs-xs text-muted">{{ $user->phone ?? 'No phone' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-currency badge-currency-{{ strtolower($userCurr) }}">
                                            <i class="fa fa-circle fs-xs"></i> {{ $userCurr }}
                                        </span>
                                        @if($user->country)
                                            <div class="fs-xs text-muted mt-1">{{ $user->country }}</div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <span class="currency-amount fs-sm fw-bold {{ $activeBal > 0 ? 'text-success' : 'text-muted' }}">
                                            {{ formatCurrency($activeBal, $userCurr) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($isVerified)
                                            <span class="badge rounded-pill bg-success-light text-success border border-success-subtle px-2 py-1">
                                                <i class="fa fa-check-circle me-1"></i> Verified
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary-light text-muted border border-secondary-subtle px-2 py-1">
                                                Unverified
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fs-sm">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</div>
                                        <div class="fs-xs text-muted">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('user/' . $user->id . '/info') }}" class="btn btn-sm btn-outline-primary" title="Manage User">
                                            <i class="fa fa-pencil-alt me-1"></i> Manage
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa fa-users fa-3x text-muted mb-3 d-block opacity-25"></i>
                                        No users match the selected filters.
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
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ number_format($users->total()) }} users
                    </span>
                    <div>
                        {!! $users->appends(request()->query())->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function viewPhoneNumbers() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            let url = '{{ url("users/phone-numbers") }}';
            const params = new URLSearchParams();

            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);

            if (params.toString()) {
                url += '?' + params.toString();
            }

            window.open(url, '_blank');
        }

        function downloadPhoneNumbers() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            let url = '{{ url("users/phone-numbers/download") }}';
            const params = new URLSearchParams();

            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);

            if (params.toString()) {
                url += '?' + params.toString();
            }

            window.location.href = url;
        }
    </script>
@endsection
