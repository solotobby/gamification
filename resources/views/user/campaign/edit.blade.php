@extends('layouts.main.master')

@section('title', 'Winner List')
@section('style')
    <script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea'
        });
    </script>
@endsection

@section('content')

    <!-- Hero Section -->
    <div class="bg-image" style="background-image: ('src/assets/media/photos/photo21@2x.jpg')">
        <div class="bg-black-75">
            <div class="content content-boxed text-center py-5">
                <h1 class="h2 text-white mb-2">
                    Add More Worker to Campaign
                </h1>
                {{-- <p class="fs-lg fw-normal text-white-75 mb-0">
          1 more remaining, <a href="javascript:void(0)">upgrade your account today</a>!
        </p> --}}
            </div>
        </div>
    </div>
    <!-- END Hero Section -->

    <!-- Page Content -->

    <div class="content content-boxed">
        <!-- Post Job form -->


        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('addmore.workers') }}" method="POST">
            @csrf
            <div class="block block-rounded">

                <!-- Job Meta section -->
                <!-- Select2 (.js-select2 class is initialized in Helpers.jqSelect2()) -->
                <!-- For more info and examples you can check out https://github.com/select2/select2 -->
                <div class="block-content block-content-full">
                    <h2 class="content-heading">Current Campaign Information</h2>
                    <div class="row items-push">
                        <div class="col-lg-4">
                            <p class="text-muted">
                                Please provide detailed information about your campaign
                            </p>
                        </div>
                        <div class="col-lg-6 offset-lg-1">

                            Current Number of Workers - {{ $campaign->number_of_staff }} <br>
                            Value per Job - {{ baseCurrency() }}
                            {{ currencyConverter($campaign->currency, baseCurrency(), $campaign->campaign_amount) }}<br>
                            Total Value of Job - {{ baseCurrency() }}
                            {{ currencyConverter($campaign->currency, baseCurrency(), $campaign->total_amount) }} <br>
                            Wallet Balance - {{ baseCurrency() }}  {{ walletBalance(auth()->user()->id) }}<br>
                            <br>

                            <hr>

                            <div class="mb-4">
                                <label class="form-label" for="post-files">Number of
                                    Worker</small></label>
                                <input class="form-control" name="new_number" min="1" id="staff" type="number" required>
                            </div>
                            <input type="hidden" name="id" value="{{ $campaign->job_id }}">
                           

                            <input type="hidden" name="id" value="{{ $campaign->job_id }}">
                            <input type="hidden" name="amount"
                                value="{{ currencyConverter($campaign->currency, baseCurrency(), $campaign->campaign_amount) }}">

                            <input type="hidden" name="wallet_balance" id="wallet_balance" value="{{ walletBalance(auth()->user()->id) }}">

                            <input type="hidden" name="campaign_amount" id="campaignAmount"
                                value="{{currencyConverter($campaign->currency, baseCurrency(), $campaign->campaign_amount) }}">

                            <input type="hidden" name="currency" id="currency" value="{{ baseCurrency() }}">

                            <input type="hidden" id="total" name="total" value="0">

                            <input type="hidden" id="uploadFeeAmount"
                                value="{{ currencyParameter(baseCurrency())->allow_upload }}">

                                <input type="hidden"  id="platformRevenueInput" name="revenue" />


                            <p id="totalDisplay" class="mb-2 fw-bolds">Estimated Cost: {{ baseCurrency() }} 0</p>

                            <p id="message" class="text-danger mt-1" style="display:none;"></p>


                            <div class="mb-4">
                                <button class="btn btn-alt-primary" id="addBtn" type="submit"><i
                                        class="fa fa-plus opacity-50 me-1"></i> Add More Worker </button>
                                <a href="{{ url('my/campaigns') }}" class="btn btn-sm btn-alt-secondary float-end"><i
                                        class="fa fa-arrow-left me-1"></i> Back to Campaigns </a>


                            </div>









                        </div>
                    </div>
                </div>
                <!-- END Files section -->


                <!-- END Submit Form -->
            </div>
        </form>
        <!-- END Post Job Form -->
    </div>
    <!-- END Page Content -->

@endsection

@section('script')

    <!-- Page JS Plugins -->
    {{-- <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script> --}}
    {{-- <script src="{{asset('src/assets/js/plugins/ckeditor/ckeditor.js')}}"></script> --}}
    {{-- <script src="{{ asset('src/assets/js/plugins/simplemde/simplemde.min.js')}}"></script> --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> --}}
    <!-- Page JS Helpers (CKEditor 5 plugins) -->
    {{-- <script>Dashmix.helpersOnLoad(['js-ckeditor5', 'js-simplemde']);</script> --}}


    <script>
        const pricePerJob = document.getElementById('campaignAmount').value;

        const walletBalance = document.getElementById('wallet_balance').value;
        const staffInput = document.getElementById('staff');
        const addBtn = document.getElementById('addBtn');
        const message = document.getElementById('message');
        const totalDisplay = document.getElementById('totalDisplay');
        const totalInput = document.getElementById('total');
        const uploadFeeInput = document.getElementById('uploadFeeAmount');


        const currencyElement = document.getElementById('currency');
        const currency = String(currencyElement?.value || 'USD'); // default fallback

        function checkBalance() {
            // const staffCount = parseInt(staffInput.value) || 0;
            // const total = staffCount * pricePerJob;
            // const percent = (60 / 100) * total;
            // const uploadFee = staffCount * uploadFeeInput.value;
            // const totalCost = total + percent + uploadFee;



            const staffCount = Number.parseInt(staffInput?.value) || 0;
            const uploadFeeValue = Number.parseFloat(uploadFeeInput?.value) || 0;
            const pricePerJobValue = Number(pricePerJob) || 0;

            // Defensive checks
            if (isNaN(staffCount) || isNaN(uploadFeeValue) || isNaN(pricePerJobValue)) {
                console.error("Invalid input detected. Please enter valid numbers.");
            } else {
                const total = staffCount * pricePerJobValue;
                const percent = 0.6 * total; // 60% of total
                const uploadFee = staffCount * uploadFeeValue;
                const totalCost = total + percent + uploadFee;
                const platformRevenue = percent + uploadFee;
                const platformRevenueInput = document.getElementById("platformRevenueInput");

                if (platformRevenueInput) {
                    platformRevenueInput.value = platformRevenue.toFixed(2); // keep 2 decimal places
                }


                console.log({ total, pricePerJob, percent, uploadFee, totalCost });

                totalDisplay.textContent = `Total: ${currency}${totalCost.toLocaleString()}`;
                totalInput.value = totalCost; // numeric value stored for form submission

                // Validation
                if (staffCount <= 0) {
                    addBtn.disabled = true;
                    message.textContent = 'Please enter a valid number of staff.';
                    message.style.display = 'block';
                    return;
                }

                if (totalCost >= walletBalance) {
                    addBtn.disabled = true;
                    const deficit = totalCost - walletBalance;
                    message.textContent = `Insufficient balance — you need ${currency} ${deficit.toLocaleString()} more.`;
                    message.style.display = 'block';
                } else {
                    addBtn.disabled = false;
                    message.style.display = 'none';
                }

            }

        }

        // Run check when typing
        staffInput.addEventListener('keyup', checkBalance);
        staffInput.addEventListener('input', checkBalance);

        addBtn.disabled = true;
        totalDisplay.textContent = `Estimated Cost: ${currency} 0`;
        totalInput.value = 0;
    </script>




@endsection
