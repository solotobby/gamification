@extends('layouts.main.master')

@section('style')
<style>
    .phone-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 1rem;
        min-height: 200px;
        font-family: monospace;
        word-wrap: break-word;
    }
</style>
@endsection

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Phone Numbers</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('users') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Phone Numbers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Phone Numbers - Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                </h3>
                <div class="block-options">
                    @if($startDate || $endDate)
                        <span class="badge bg-info me-2">
                            Filtered:
                            @if($startDate) From {{ $startDate }} @endif
                            @if($endDate) To {{ $endDate }} @endif
                        </span>
                    @endif
                    <span class="badge bg-primary">
                        Total: {{ number_format($totalCount) }} numbers
                    </span>
                </div>
            </div>
            <div class="block-content">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="alert alert-info mb-0">
                            <i class="fa fa-info-circle me-1"></i>
                            <strong>Current Page:</strong> {{ $count }} numbers displayed<br>
                            <strong>Total Numbers:</strong> {{ number_format($totalCount) }} numbers
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ url('users') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back to Users
                        </a>
                        <button type="button" class="btn btn-primary" onclick="copyToClipboard()">
                            <i class="fa fa-copy me-1"></i> Copy This Page
                        </button>
                        <button type="button" class="btn btn-success" onclick="downloadAll()">
                            <i class="fa fa-download me-1"></i> Download All
                        </button>
                    </div>
                </div>

                <div class="alert alert-success" id="copyAlert" style="display: none;">
                    <i class="fa fa-check me-1"></i> Phone numbers copied to clipboard!
                </div>

                <div class="form-group mb-4">
                    <label class="form-label fw-bold">
                        Phone Numbers ({{ $count }} on this page - separated by commas)
                    </label>
                    <div class="phone-box" id="phoneBox" onclick="selectText()">
                        {{ $phones }}
                    </div>
                    <small class="text-muted">Click on the box to select all numbers, then Ctrl+C (Cmd+C on Mac) to copy</small>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ number_format($totalCount) }} numbers
                    </div>
                    <div>
                        {!! $users->appends([
                            'start_date' => request('start_date'),
                            'end_date' => request('end_date')
                        ])->links('pagination::bootstrap-4') !!}
                    </div>
                </div>

                <!-- Quick Navigation -->
                <div class="mt-3">
                    <div class="btn-group" role="group">
                        @if($users->currentPage() > 1)
                            <a href="{{ $users->url(1) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-angle-double-left"></i> First
                            </a>
                        @endif

                        @if($users->previousPageUrl())
                            <a href="{{ $users->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-angle-left"></i> Previous
                            </a>
                        @endif

                        <button class="btn btn-sm btn-primary" disabled>
                            Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                        </button>

                        @if($users->nextPageUrl())
                            <a href="{{ $users->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">
                                Next <i class="fa fa-angle-right"></i>
                            </a>
                        @endif

                        @if($users->currentPage() < $users->lastPage())
                            <a href="{{ $users->url($users->lastPage()) }}" class="btn btn-sm btn-outline-primary">
                                Last <i class="fa fa-angle-double-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function selectText() {
            const phoneBox = document.getElementById('phoneBox');
            const range = document.createRange();
            range.selectNodeContents(phoneBox);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        }

        function copyToClipboard() {
            const phoneBox = document.getElementById('phoneBox');
            const textToCopy = phoneBox.textContent;

            // Modern approach
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    showCopyAlert();
                }).catch(err => {
                    // Fallback to old method
                    fallbackCopy(textToCopy);
                });
            } else {
                // Fallback for older browsers
                fallbackCopy(textToCopy);
            }
        }

        function fallbackCopy(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();

            try {
                document.execCommand('copy');
                showCopyAlert();
            } catch (err) {
                alert('Failed to copy. Please select the text manually and press Ctrl+C (Cmd+C on Mac)');
            }

            document.body.removeChild(textarea);
        }

        function showCopyAlert() {
            const alert = document.getElementById('copyAlert');
            alert.style.display = 'block';

            setTimeout(() => {
                alert.style.display = 'none';
            }, 3000);
        }

        function downloadAll() {
            const startDate = '{{ request("start_date") }}';
            const endDate = '{{ request("end_date") }}';

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
