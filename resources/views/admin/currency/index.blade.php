@extends('layouts.main.master')

@section('style')
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
@endsection

@section('content')

    {{-- Page Header --}}
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Currency Management</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active" aria-current="page">Currencies</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Create Currency Card --}}
        <div class="block block-rounded mb-4">
            <div class="block-header block-header-default">
                <h3 class="block-title">Add New Currency</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-bs-toggle="collapse"
                        data-bs-target="#create-currency-form">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div class="collapse" id="create-currency-form">
                <div class="block-content">
                    <form action="{{ route('admin.currencies.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">

                            {{-- Basic Info --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Currency Code <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                                    value="{{ old('code') }}" placeholder="e.g. NGN, USD, GBP" required maxlength="10">
                                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror"
                                    name="country" value="{{ old('country') }}" placeholder="e.g. Nigeria" required>
                                @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Base Rate (vs USD) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.000001"
                                    class="form-control @error('base_rate') is-invalid @enderror" name="base_rate"
                                    value="{{ old('base_rate') }}" placeholder="0.00" required min="0">
                                @error('base_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Status</label>
                                <select class="form-select" name="is_active">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            {{-- Fees --}}
                            <div class="col-12">
                                <hr class="my-1">
                                <p class="text-muted fw-semibold mb-0">Fees & Commissions</p>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Upgrade Fee</label>
                                <input type="number" step="0.01" class="form-control" name="upgrade_fee"
                                    value="{{ old('upgrade_fee', 0) }}" min="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Min Upgrade Amount</label>
                                <input type="number" step="0.01" class="form-control" name="min_upgrade_amount"
                                    value="{{ old('min_upgrade_amount', 0) }}" min="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Referral Commission</label>
                                <input type="number" step="0.01" class="form-control" name="referral_commission"
                                    value="{{ old('referral_commission', 0) }}" min="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Upload Fee</label>
                                <input type="number" step="0.01" class="form-control" name="allow_upload"
                                    value="{{ old('allow_upload', 0) }}" min="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Prioritize Fee</label>
                                <input type="number" step="0.01" class="form-control" name="priotize"
                                    value="{{ old('priotize', 0) }}" min="0">
                            </div>

                            {{-- Withdrawal --}}
                            <div class="col-12">
                                <hr class="my-1">
                                <p class="text-muted fw-semibold mb-0">Withdrawal Settings</p>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Min Withdrawal</label>
                                <input type="number" step="0.01" class="form-control" name="min_withdrawal_amount"
                                    value="{{ old('min_withdrawal_amount', 0) }}" min="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Withdrawal % </label>
                                <input type="number" step="0.01" class="form-control" name="withdrawal_percent"
                                    value="{{ old('withdrawal_percent', 0) }}" min="0" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Freebyz Withdrawal %</label>
                                <input type="number" step="0.01" class="form-control" name="freebyz_withdrawal_percent"
                                    value="{{ old('freebyz_withdrawal_percent', 0) }}" min="0" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Referral Withdrawal %</label>
                                <input type="number" step="0.01" class="form-control" name="referral_withdrawal_percent"
                                    value="{{ old('referral_withdrawal_percent', 0) }}" min="0" max="100">
                            </div>

                            {{-- Points --}}
                            <div class="col-12">
                                <hr class="my-1">
                                <p class="text-muted fw-semibold mb-0">Points & Clicks Amounts</p>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Banner Clicks Amount</label>
                                <input type="number" step="0.000001" class="form-control" name="banner_clicks_amount"
                                    value="{{ old('banner_clicks_amount', 0) }}" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Hire Worker Points Amount</label>
                                <input type="number" step="0.000001" class="form-control" name="hire_worker_points_amount"
                                    value="{{ old('hire_worker_points_amount', 0) }}" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Job Points Amount</label>
                                <input type="number" step="0.000001" class="form-control" name="job_points_amount"
                                    value="{{ old('job_points_amount', 0) }}" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Job Listing Amount</label>
                                <input type="number" step="0.000001" class="form-control" name="job_listing_amount"
                                    value="{{ old('job_listing_amount', 0) }}" min="0">
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-plus me-1"></i> Create Currency
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Currency List --}}
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">All Currencies</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter" id="currency-table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Country</th>
                                {{-- <th>Base Rate (USD)</th> --}}
                                <th>Upgrade Fee</th>
                                <th>Ref. Com.</th>
                                <th>Upload Fee</th>
                                <th>Prioritize Fee</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($currencies as $currency)
                                <tr>
                                    <td><span class="badge bg-info text-dark fs-xs">{{ $currency->code }}</span></td>
                                    <td>{{ $currency->country }}</td>
                                    {{-- <td>{{ number_format($currency->base_rate, 4) }}</td> --}}
                                    <td>{{ number_format($currency->upgrade_fee, 2) }}</td>
                                    <td>{{ number_format($currency->referral_commission, 2) }}</td>
                                    <td>{{ number_format($currency->allow_upload, 2) }}</td>
                                    <td>{{ number_format($currency->priotize, 2) }}</td>
                                    <td>
                                        @if ($currency->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                        {{-- Toggle Status --}}
                                        <form action="{{ route('admin.currencies.toggle', $currency) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $currency->is_active ? 'btn-alt-warning' : 'btn-alt-success' }}"
                                                onclick="return confirm('Toggle status for {{ $currency->code }}?')">
                                                <i class="fa fa-power-off"></i>
                                                {{ $currency->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        {{-- Edit --}}
                                        <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                            data-bs-target="#edit-modal-{{ $currency->id }}">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>

                                        {{-- Toggle Status --}}
                                        {{-- <form action="{{ route('admin.currencies.toggle', $currency) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $currency->is_active ? 'btn-alt-warning' : 'btn-alt-success' }}"
                                                onclick="return confirm('Toggle status for {{ $currency->code }}?')">
                                                <i class="fa fa-power-off"></i> {{ $currency->is_active ? 'Deactivate' :
                                                'Activate' }}
                                            </button>
                                        </form> --}}

                                        {{-- Delete --}}
                                        {{-- <form action="{{ route('admin.currencies.destroy', $currency) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-danger"
                                                onclick="return confirm('Delete {{ $currency->code }}? This cannot be undone.')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form> --}}
                                    </td>
                                </tr>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="edit-modal-{{ $currency->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Currency — {{ $currency->code }}
                                                    ({{ $currency->country }})</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.currencies.update', $currency) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="row g-3">

                                                        <div class="col-md-3">
                                                            <label class="form-label fw-semibold">Currency Code *</label>
                                                            <input type="text" class="form-control" name="code"
                                                                value="{{ $currency->code }}" required maxlength="10">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Country *</label>
                                                            <input type="text" class="form-control" name="country"
                                                                value="{{ $currency->country }}" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label fw-semibold">Base Rate (vs USD) *</label>
                                                            <input type="number" step="0.000001" class="form-control"
                                                                name="base_rate" value="{{ $currency->base_rate }}" required
                                                                min="0">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label fw-semibold">Status</label>
                                                            <select class="form-select" name="is_active">
                                                                <option value="1" {{ $currency->is_active ? 'selected' : '' }}>
                                                                    Active</option>
                                                                <option value="0" {{ !$currency->is_active ? 'selected' : '' }}>
                                                                    Inactive</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12">
                                                            <hr class="my-1">
                                                            <p class="text-muted fw-semibold mb-0">Fees & Commissions</p>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label class="form-label">Upgrade Fee</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="upgrade_fee" value="{{ $currency->upgrade_fee }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Min Upgrade Amount</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="min_upgrade_amount"
                                                                value="{{ $currency->min_upgrade_amount }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Referral Commission</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="referral_commission"
                                                                value="{{ $currency->referral_commission }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Upload Fee</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="allow_upload" value="{{ $currency->allow_upload }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Prioritize Fee</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="priotize" value="{{ $currency->priotize }}">
                                                        </div>

                                                        <div class="col-12">
                                                            <hr class="my-1">
                                                            <p class="text-muted fw-semibold mb-0">Withdrawal Settings</p>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label class="form-label">Min Withdrawal</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="min_withdrawal_amount"
                                                                value="{{ $currency->min_withdrawal_amount }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Withdrawal %</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="withdrawal_percent"
                                                                value="{{ $currency->withdrawal_percent }}" max="100">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Freebyz Withdrawal %</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="freebyz_withdrawal_percent"
                                                                value="{{ $currency->freebyz_withdrawal_percent }}" max="100">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Referral Withdrawal %</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="referral_withdrawal_percent"
                                                                value="{{ $currency->referral_withdrawal_percent }}" max="100">
                                                        </div>

                                                        <div class="col-12">
                                                            <hr class="my-1">
                                                            <p class="text-muted fw-semibold mb-0">Points & Clicks Amounts</p>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label class="form-label">Banner Clicks Amount</label>
                                                            <input type="number" step="0.000001" class="form-control"
                                                                name="banner_clicks_amount"
                                                                value="{{ $currency->banner_clicks_amount }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Hire Worker Points Amount</label>
                                                            <input type="number" step="0.000001" class="form-control"
                                                                name="hire_worker_points_amount"
                                                                value="{{ $currency->hire_worker_points_amount }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Job Points Amount</label>
                                                            <input type="number" step="0.000001" class="form-control"
                                                                name="job_points_amount"
                                                                value="{{ $currency->job_points_amount }}">
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label class="form-label">Job Listing Amount</label>
                                                            <input type="number" step="0.000001" class="form-control"
                                                                name="job_listing_amount"
                                                                value="{{ $currency->job_listing_amount }}">
                                                        </div>

                                                        <div class="col-12 mt-2">
                                                            <button type="submit" class="btn btn-primary px-4">
                                                                <i class="fa fa-save me-1"></i> Save Changes
                                                            </button>
                                                            <button type="button" class="btn btn-secondary ms-2"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">No currencies found. Add one above.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="{{ asset('src/assets/js/plugins/datatables/dataTables.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Expand form automatically if there were validation errors
            @if ($errors->any())
                var el = document.getElementById('create-currency-form');
                if (el) el.classList.add('show');
            @endif
        });
    </script>
@endsection
