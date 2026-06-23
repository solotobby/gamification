@extends('layouts.main.master')

@section('style')
<link rel="stylesheet" href="{{ asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
@endsection

@section('content')

{{-- Page Header --}}
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Currency Exchange Rates</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.currencies.index') }}">Currencies</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Exchange Rates</li>
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

    <div class="row g-4">

        {{-- Left Column: Add Rate + Bulk Generate --}}
        <div class="col-lg-4">

            {{-- Add New Rate --}}
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Map New Currency Pair</h3>
                </div>
                <div class="block-content">
                    <form action="{{ route('admin.conversion-rates.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">From Currency <span class="text-danger">*</span></label>
                            <select class="form-select @error('from') is-invalid @enderror" name="from" required>
                                <option value="">— Select —</option>
                                @foreach ($currencies as $c)
                                    <option value="{{ $c->code }}" {{ old('from') == $c->code ? 'selected' : '' }}>
                                        {{ $c->code }} — {{ $c->country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">To Currency <span class="text-danger">*</span></label>
                            <select class="form-select @error('to') is-invalid @enderror" name="to" required>
                                <option value="">— Select —</option>
                                @foreach ($currencies as $c)
                                    <option value="{{ $c->code }}" {{ old('to') == $c->code ? 'selected' : '' }}>
                                        {{ $c->code }} — {{ $c->country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Exchange Rate <span class="text-danger">*</span>
                                <small class="text-muted fw-normal">(1 unit of "From" = X units of "To")</small>
                            </label>
                            <input type="number" step="0.000001" min="0.000001"
                                   class="form-control @error('rate') is-invalid @enderror"
                                   name="rate" value="{{ old('rate') }}" placeholder="e.g. 1650.00" required>
                            @error('rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-link me-1"></i> Map Currency Pair
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bulk Generate --}}
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Bulk Generate All Pairs</h3>
                </div>
                <div class="block-content">
                    <p class="text-muted small">
                        Automatically creates all possible currency pair combinations from active currencies
                        that don't already have a rate. You can update individual rates after.
                    </p>
                    <form action="{{ route('admin.conversion-rates.generate-all') }}" method="POST"
                          onsubmit="return confirm('Generate all missing currency pairs with this default rate?')">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Default Rate for New Pairs</label>
                            <input type="number" step="0.000001" min="0.000001" class="form-control"
                                   name="default_rate" value="1.000000" required>
                        </div>
                        <button type="submit" class="btn btn-alt-warning w-100">
                            <i class="fa fa-magic me-1"></i> Generate Missing Pairs
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- Right Column: Rate Table --}}
        <div class="col-lg-8">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Currency Pair Rates</h3>
                    <div class="block-options">
                        <span class="badge bg-primary">{{ $rates->count() }} pairs</span>
                    </div>
                </div>
                <div class="block-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter" id="rates-table">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Rate</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width:100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rates as $rate)
                                <tr>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $rate->from }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $rate->to }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ number_format($rate->rate, 6) }}</span>
                                    </td>
                                    <td>
                                        @if ($rate->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-alt-primary"
                                                data-bs-toggle="modal" data-bs-target="#rate-modal-{{ $rate->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.conversion-rates.destroy', $rate) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete {{ $rate->from }} → {{ $rate->to }} rate?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Edit Rate Modal --}}
                                <div class="modal fade" id="rate-modal-{{ $rate->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Edit Rate:
                                                    <span class="badge bg-info text-dark">{{ $rate->from }}</span>
                                                    <i class="fa fa-arrow-right mx-1"></i>
                                                    <span class="badge bg-success">{{ $rate->to }}</span>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.conversion-rates.update', $rate) }}" method="POST">
                                                    @csrf @method('PUT')

                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">
                                                            Exchange Rate
                                                            <small class="text-muted fw-normal">(1 {{ $rate->from }} = X {{ $rate->to }})</small>
                                                        </label>
                                                        <input type="number" step="0.000001" min="0.000001"
                                                               class="form-control" name="rate"
                                                               value="{{ $rate->rate }}" required>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="form-label fw-semibold">Status</label>
                                                        <select class="form-select" name="status">
                                                            <option value="1" {{ $rate->status ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ !$rate->status ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>

                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-save me-1"></i> Update Rate
                                                        </button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No currency pairs mapped yet. Add one on the left or use bulk generate.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('src/assets/js/plugins/datatables/dataTables.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('rates-table')) {
            new DataTable('#rates-table', {
                pageLength: 25,
                order: [[0, 'asc'], [1, 'asc']],
                columnDefs: [{ orderable: false, targets: 4 }]
            });
        }
    });
</script>
@endsection
