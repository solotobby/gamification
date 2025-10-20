@extends('layouts.main.master')

@section('style')
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
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

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Verified Users List - {{ $verifiedUsers->total() }}</h3>
                <div class="block-options">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#downloadModal">
                        <i class="fa fa-download me-1"></i> Download Users Income in CSV
                    </button>
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>

            <div class="block-content">
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

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Reg. Channel</th>
                                <th>When Verified</th>
                                <th>When Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($verifiedUsers as $user)
                                <tr>
                                    <th>{{ $i++ }}.</th>
                                    <td><a href="{{ url('user/' . $user->id . '/info') }}" target="_blank">{{ $user->name }}</a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->source }}</td>
                                    <td>{{ $user->verified_at ? \Carbon\Carbon::parse($user->verified_at)->format('d/m/Y @ h:i:s a') : '' }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex">
                        {!! $verifiedUsers->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Modal -->
    <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="downloadForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="downloadModalLabel">Download Verified Users CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Amount Range</label>
                            <select name="amount_range" class="form-control">
                                <option value="">All Amount</option>
                                <option value="below_10k">Below ₦10k</option>
                                <option value="10k_30k">₦10k - ₦30k</option>
                                <option value="30k_70k">₦30k - ₦70k</option>
                                <option value="70k_above">₦70k+</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date Range</label>
                            <select name="date_range" class="form-control">
                                <option value="">All Time</option>
                                <option value="last_30">Last 30 Days</option>
                                <option value="last_6_months">Last 6 Months</option>
                                <option value="last_1_year">Last 1 Year</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email (optional)</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email to send file">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-download me-1"></i> Proceed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('downloadForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const email = formData.get('email');
            const params = new URLSearchParams({
                amount_range: formData.get('amount_range'),
                date_range: formData.get('date_range'),
                email: email
            });

            if (email) {
                // Send to backend to email the CSV
                fetch(`{{ url('verified/users/email-csv') }}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        const modal = bootstrap.Modal.getInstance(document.getElementById('downloadModal'));
                        modal.hide();
                    })
                    .catch(() => alert('Error sending CSV email.'));
            } else {
                // Trigger direct download
                // window.location.href = `{{ url('verified/users/download') }}?${params.toString()}`;
                const modal = bootstrap.Modal.getInstance(document.getElementById('downloadModal'));
                modal.hide();
            }
        });
    </script>
@endsection
