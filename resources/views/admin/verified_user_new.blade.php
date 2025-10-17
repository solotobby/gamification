@extends('layouts.main.master')
@section('style')
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Verified Users</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Users</li>
                        <li class="breadcrumb-item active" aria-current="page">Users List</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content">
        <!-- Full Table -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Verified Users List - {{ $verifiedUsers->total() }}</h3>
                <div class="block-options">
                    <a href="{{ url('verified/users/download?' . http_build_query(request()->query())) }}"
                        class="btn btn-sm btn-primary"
                        onclick="setTimeout(() => window.location.href = '{{ url('verified/users?' . http_build_query(request()->query())) }}', 500); return true;">
                        <i class="fa fa-download me-1"></i> CSV
                    </a>
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <!-- Search (separate endpoint) -->
                <form action="{{ url('users/search') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ old('search') }}"
                            placeholder="Search by name, email, phone, referral_code">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i> Search
                        </button>
                    </div>
                </form>

                <!-- Filters (same page) -->
                <form method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="date_range" class="form-control" onchange="this.form.submit()">
                                <option value="">All Time</option>
                                <option value="last_30" {{ request('date_range') == 'last_30' ? 'selected' : '' }}>Last 30
                                    Days</option>
                                <option value="last_6_months" {{ request('date_range') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="last_1_year" {{ request('date_range') == 'last_1_year' ? 'selected' : '' }}>
                                    Last 1 Year</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="amount_range" class="form-control" onchange="this.form.submit()">
                                <option value="">All Amount</option>
                                <option value="below_10k" {{ request('amount_range') == 'below_10k' ? 'selected' : '' }}>Below
                                    ₦10k</option>
                                <option value="10k_30k" {{ request('amount_range') == '10k_30k' ? 'selected' : '' }}>₦10k -
                                    ₦30k</option>
                                <option value="30k_70k" {{ request('amount_range') == '30k_70k' ? 'selected' : '' }}>₦30k -
                                    ₦70k</option>
                                <option value="70k_above" {{ request('amount_range') == '70k_above' ? 'selected' : '' }}>₦70k+
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            @if(request('date_range') || request('amount_range'))
                                <a href="{{ url('verified/users') }}" class="btn btn-secondary w-100">
                                    <i class="fa fa-times me-1"></i> Clear Filters
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Currency</th>
                                <th>Income</th>
                                <th>Channel</th>
                                <th>Verified</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = ($verifiedUsers->currentPage() - 1) * 100 + 1; ?>
                            @forelse ($verifiedUsers as $user)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td class="fw-semibold">
                                        <a href="{{ url('user/' . $user->id . '/info') }}" target="_blank">{{ $user->name }}</a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->wallet->base_currency ?? 'NGN' }}</td>
                                    <td class="fw-semibold text-success">
                                        {{ number_format($user->income) }}
                                    </td>
                                    <td>{{ $user->source }}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->verified_at)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No verified users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex">
                    {!! $verifiedUsers->appends(request()->query())->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
        <!-- END Full Table -->
    </div>
@endsection

@section('script')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>

    <!-- Page JS Plugins -->
    <script src="{{asset('src/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons-jszip/jszip.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.print.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.html5.min.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{asset('src/assets/js/pages/be_tables_datatables.min.js')}}"></script>
@endsection
