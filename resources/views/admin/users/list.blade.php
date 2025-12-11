@extends('layouts.main.master')
@section('style')
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection

@section('content')

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Users</h1>
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
                <h3 class="block-title">Users List - {{ $users->total() }}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <!-- Date Filter Form -->
                <form action="{{ url('users') }}" method="GET" id="dateFilterForm">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ url('users') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh me-1"></i> Reset
                                </a>
                                <button type="button" class="btn btn-success" onclick="viewPhoneNumbers()">
                                    <i class="fa fa-phone me-1"></i> View Numbers ({{ number_format($phoneCount) }})
                                </button>
                                <button type="button" class="btn btn-info" onclick="downloadPhoneNumbers()">
                                    <i class="fa fa-download me-1"></i> Download All
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="{{ url('users/search') }}" method="GET">
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" value="{{ old('search') }}"
                                placeholder="Search user by name, email, phone number, referral_code" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>

                <form action="{{ url('user/currency/search') }}" method="GET">
                    <div class="mb-4">
                        <div class="input-group">
                            <select name="currency" class="form-control @error('currency') is-invalid @enderror" required>
                                <option value="">Select Country</option>
                                @foreach (currencyList(null, true) as $currency)
                                    <option value="{{ $currency->country }}">{{ $currency->country }}</option>
                                @endforeach
                                <option value="USD,Other">Others</option>
                            </select>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search me-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Entry Mode</th>
                                <th>Status</th>
                                <th>Country</th>
                                <th>When Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="fw-semibold"><a href="{{ url('user/' . $user->id . '/info') }}" target="_blank">
                                            {{$user->name }}</a></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ @$user->source}}</td>
                                    <td>{{ $user->is_verified == "1" ? 'Verified' : 'unverified' }}</td>
                                    <td>{{ $user->country }}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex">
                        {!! $users->appends(request()->query())->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons-jszip/jszip.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.print.min.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.html5.min.js')}}"></script>
    <script src="{{asset('src/assets/js/pages/be_tables_datatables.min.js')}}"></script>

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
