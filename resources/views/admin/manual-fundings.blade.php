@extends('layouts.main.master')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full">
            <h1 class="fs-3 fw-semibold my-3">Manual Funding Requests</h1>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Pending & Processed Manual Fundings</h3>
            </div>
            <div class="block-content">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Search --}}
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search"
                            placeholder="Search by name, email, reference..." value="{{ request('search') }}">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        @if(request('search'))
                            <a href="{{ route('admin.manual.fundings') }}" class="btn btn-secondary">Clear</a>
                        @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th>Proof</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fundings as $funding)
                                <tr>
                                    <td>{{ $funding->user->name }}<br><small>{{ $funding->user->email }}</small></td>
                                    <td>{{ $funding->payment_method }}</td>
                                    <td>{{ $funding->reference }}</td>
                                    <td>{{ number_format($funding->amount) }}</td>
                                    <td>{{ $funding->currency }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $funding->status === 'approved' ? 'success' : ($funding->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($funding->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($funding->proof_image)
                                            <a href="{{ displayImage($funding->proof_image) }}" target="_blank"
                                                class="btn btn-sm btn-info">View</a>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($funding->created_at)->format('d/m/Y h:i a') }}</td>
                                    <td>
                                        @if($funding->status === 'pending')
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modal-funding-{{ $funding->id }}">
                                                Review
                                            </button>
                                        @else
                                            <span class="text-muted">{{ $funding->admin_note ?? '—' }}</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Review Modal --}}
                                @if($funding->status === 'pending')
                                    <div class="modal fade" id="modal-funding-{{ $funding->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-popout">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Review Manual Funding — {{ $funding->user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('admin.manual.fundings.action', $funding->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <ul class="list-group mb-3">
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                Payment Method <span
                                                                    class="badge bg-info">{{ $funding->payment_method }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                Reference <span
                                                                    class="badge bg-info">{{ $funding->reference }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                Currency <span class="badge bg-info">{{ $funding->currency }}</span>
                                                            </li>
                                                        </ul>

                                                        @if($funding->proof_image)
                                                            <div class="mb-3 text-center">
                                                                <img src="{{ displayImage($funding->proof_image) }}"
                                                                    class="img-fluid rounded" style="max-height:250px;" alt="Proof">
                                                            </div>
                                                        @endif

                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Amount (editable)</label>
                                                            <input type="number" name="amount" class="form-control"
                                                                value="{{ $funding->amount }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Admin Note</label>
                                                            <textarea name="admin_note" class="form-control" rows="2"
                                                                placeholder="Optional note to user..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="action" value="rejected"
                                                            class="btn btn-sm btn-danger">Reject</button>
                                                        <button type="submit" name="action" value="approved"
                                                            class="btn btn-sm btn-success">Approve & Fund</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No manual funding requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>Showing {{ $fundings->firstItem() ?? 0 }} to {{ $fundings->lastItem() ?? 0 }} of
                            {{ $fundings->total() }} entries</div>
                        <div>{!! $fundings->appends(['search' => request('search')])->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @section('script')
        <script>
            document.querySelectorAll('form[action*="manual-fundings"]').forEach(form => {
                form.addEventListener('submit', function (e) {
                    const clickedBtn = e.submitter;
                    if (!clickedBtn) return;

                    form.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.disabled = true;
                    });

                    clickedBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
                });
            });
        </script>
    @endsection
@endsection
