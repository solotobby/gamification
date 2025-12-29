@extends('layouts.main.master')

@section('title', 'Add More Workers')

@section('content')
    <div class="bg-image" style="background-image: url('src/assets/media/photos/photo21@2x.jpg')">
        <div class="bg-black-75">
            <div class="content content-boxed text-center py-5">
                <h1 class="h2 text-white mb-2">Add More Workers to Campaign</h1>
            </div>
        </div>
    </div>

    <div class="content content-boxed">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('addmore.workers') }}" method="POST" id="addWorkerForm">
            @csrf
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <h2 class="content-heading">Current Campaign Information</h2>

                    <div class="row items-push">
                        <div class="col-lg-4">
                            <p class="text-muted">Review your campaign details and add additional workers</p>
                        </div>

                        <div class="col-lg-6 offset-lg-1">
                            <div class="alert alert-info">
                                <strong>Campaign:</strong> {{ $campaign->post_title }}<br>
                                <strong>Current Workers:</strong> {{ $campaign->number_of_staff }}<br>
                                <strong>Value per Job:</strong> {{ baseCurrency() }}
                                {{ number_format(currencyConverter($campaign->currency, baseCurrency(), $campaign->campaign_amount), 2) }}<br>
                                <strong>Allow Upload:</strong> {{ $campaign->allow_upload ? 'Yes' : 'No' }}<br>
                                <strong>Wallet Balance:</strong> {{ baseCurrency() }}
                                <span id="walletBalance">{{ number_format(walletBalance(auth()->id()), 2) }}</span>
                            </div>

                            <hr>

                            <div class="mb-4">
                                <label class="form-label">Number of Workers to Add</label>
                                <input type="number"
                                       class="form-control"
                                       name="new_number"
                                       id="newWorkers"
                                       min="1"
                                       required>
                            </div>

                            <input type="hidden" name="id" value="{{ $campaign->job_id }}">

                            <!-- Preview Section -->
                            <div id="previewSection" style="display:none;">
                                <div class="alert alert-secondary">
                                    <h5>Cost Breakdown</h5>
                                    <p class="mb-1"><strong>Base Amount:</strong> {{ baseCurrency() }} <span id="baseAmount">0.00</span></p>
                                    <p class="mb-1"><strong>Platform Fee ({{ auth()->user()->is_business ? '100' : '60' }}%):</strong> {{ baseCurrency() }} <span id="platformFee">0.00</span></p>
                                    <p class="mb-1" id="uploadFeeRow" style="display:none;">
                                        <strong>Upload Fee:</strong> {{ baseCurrency() }} <span id="uploadFee">0.00</span>
                                    </p>
                                    <hr>
                                    <p class="mb-0 fs-4"><strong>Total Cost:</strong> {{ baseCurrency() }} <span id="totalCost">0.00</span></p>
                                </div>
                            </div>

                            <p id="errorMessage" class="text-danger mt-2" style="display:none;"></p>

                            <div class="mb-4">
                                <button class="btn btn-primary" id="submitBtn" type="submit" disabled>
                                    <i class="fa fa-plus me-1"></i> Add Workers
                                </button>
                                <a href="{{ route('my.campaigns') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left me-1"></i> Back to Campaigns
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
<script>
    const newWorkersInput = document.getElementById('newWorkers');
    const submitBtn = document.getElementById('submitBtn');
    const previewSection = document.getElementById('previewSection');
    const errorMessage = document.getElementById('errorMessage');
    const campaignId = '{{ $campaign->job_id }}';
    const allowUpload = {{ $campaign->allow_upload ? 'true' : 'false' }};

    let debounceTimer;

    newWorkersInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);

        const workers = parseInt(this.value) || 0;

        if (workers < 1) {
            previewSection.style.display = 'none';
            submitBtn.disabled = true;
            errorMessage.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchPreview(workers);
        }, 500);
    });

    function fetchPreview(workers) {
        fetch('{{ route("preview.add.worker") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                campaign_id: campaignId,
                new_workers: workers
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showError(data.error);
                return;
            }

            // Update preview
            document.getElementById('baseAmount').textContent = parseFloat(data.base_amount).toFixed(2);
            document.getElementById('platformFee').textContent = parseFloat(data.platform_fee).toFixed(2);
            document.getElementById('totalCost').textContent = parseFloat(data.total_cost).toFixed(2);

            if (allowUpload) {
                document.getElementById('uploadFeeRow').style.display = 'block';
                document.getElementById('uploadFee').textContent = parseFloat(data.upload_fee).toFixed(2);
            }

            previewSection.style.display = 'block';

            // Check balance
            if (!data.has_sufficient_balance) {
                const deficit = data.total_cost - data.wallet_balance;
                showError(`Insufficient balance. You need ${data.currency} ${deficit.toFixed(2)} more.`);
                submitBtn.disabled = true;
            } else {
                errorMessage.style.display = 'none';
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Failed to fetch preview. Please try again.');
        });
    }

    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        previewSection.style.display = 'none';
        submitBtn.disabled = true;
    }
</script>
@endsection
