@extends('layouts.main.master')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Campaigns</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Campaign</li>
                        <li class="breadcrumb-item active" aria-current="page">View Campaign</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Full Table -->
        <div class="alert alert-warning">
            Campaigns with Activity Status <b>Completed</b> will not be available on the dashboard.
        </div>
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="fa fa-exclamation-triangle me-2"></i>
            <span>
                To ensure fairness, kindly avoid denying task submissions without genuine reasons.
                Repeated denials will lead to your campaigns being suspended without refund.
            </span>
        </div>
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Campaign List</h3> <small><span style="color: chocolate"></span></small>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Approved</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Activity Status </th>
                                {{-- <th>Upload Allowed</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                                    <?php
                                $campaignStat = checkCampaignCompletedStatus($list->id);
                                                            ?>
                                                    <tr>
                                                        <td>
                                                            {{ $list->job_id }}
                                                        </td>
                                                        <td>
                                                            {{ $list->post_title }}
                                                        </td>
                                                        <td>
                                                            {{ @$campaignStat['counts']['Approved'] }}/{{ $list->number_of_staff }}
                                                            {{-- {{ $list->completed()->where('status', '=', 'Approved')->count(); }}/{{
                                                            $list->number_of_staff }} --}}
                                                        </td>
                                                        <td>
                                                            {{-- @if ($list->currency == 'NGN')
                                                            &#8358;{{ $list->campaign_amount }}
                                                            @else
                                                            ${{ $list->campaign_amount }}
                                                            @endif --}}
                                                            {{ baseCurrency() }}
                                                            {{ currencyConverter($list->currency, baseCurrency(), $list->campaign_amount) }}

                                                        </td>
                                                        <td>


                                                            {{ baseCurrency() }}
                                                            {{ currencyConverter($list->currency, baseCurrency(), $list->total_amount) }}
                                                        </td>


                                                        <td>{{ $list->status }}</td>
                                                        <?php
                                // $c = $campaignStat['Pending']  + $campaignStat['Approved'] ;
                                                                ?>
                                                        <td>
                                                            {{ $list->is_completed ? 'Completed' : 'Not Completed' }}
                                                            {{-- {{ $c >= $list->number_of_staff ? 'Completed' : 'Not Completed' }} --}}
                                                        </td>

                                                        {{-- <td>{{ $list->allow_upload == true ? 'Yes' : 'No' }}</td> --}}
                                                        <td>
                                                            @if ($list->status == 'Offline' || $list->status == 'Flagged')
                                                                <!-- Disabled state -->
                                                            @else
                                                                <a href="{{ url('campaign/activities/' . $list->job_id) }}"
                                                                    class="btn btn-success btn-sm">
                                                                    View Activities
                                                                </a>
                                                                <a href="{{ url('campaign/activities/pause/' . $list->job_id) }}"
                                                                    class="btn btn-warning btn-sm">
                                                                    Pause Campaign
                                                                </a>
                                                            @endif

                                                            <a href="{{ route('campaign.add.worker', $list->job_id) }}"
                                                                class="btn btn-alt-primary btn-sm">
                                                                <i class="fa fa-plus me-1"></i> Add More Workers
                                                            </a>

                                                        </td>
                                                    </tr>

                            @endforeach

                        </tbody>
                    </table>
                    <div class="d-flex">
                        {!! $lists->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- END Full Table -->

    </div>
@endsection

@section('script')
    <script src="{{ asset('src/assets/js/pages/be_ui_progress.min.js') }}"></script>

    <script>
        const pricePerJob = document.getElementById('campaignAmount').value;

        const walletBalance = document.getElementById('wallet_balance').value;
        const staffInput = document.getElementById('staff');
        const addBtn = document.getElementById('addBtn');
        const message = document.getElementById('message');
        const totalDisplay = document.getElementById('totalDisplay');
        const totalInput = document.getElementById('total');

        const currencyElement = document.getElementById('currency');
        const currency = String(currencyElement?.value || 'USD'); // default fallback

        function checkBalance() {
            const staffCount = parseInt(staffInput.value) || 0;
            const totalCost = staffCount * pricePerJob;

            // Update total display
            // totalDisplay.textContent = `Total: $${totalCost.toLocaleString()}`;
            totalDisplay.textContent = `Total: ${currency}${totalCost.toLocaleString()}`;
            totalInput.value = totalCost; // numeric value stored for form submission

            // Validation
            if (staffCount <= 0) {
                addBtn.disabled = true;
                message.textContent = 'Please enter a valid number of staff.';
                message.style.display = 'block';
                return;
            }

            if (totalCost > walletBalance) {
                addBtn.disabled = true;
                const deficit = totalCost - walletBalance;
                // message.textContent = `Insufficient balance — you need $${deficit.toLocaleString()} more.`;
                message.textContent = `Insufficient balance — you need ${currency} ${deficit.toLocaleString()} more.`;
                message.style.display = 'block';
            } else {
                addBtn.disabled = false;
                message.style.display = 'none';
            }
        }

        // Run check when typing
        staffInput.addEventListener('keyup', checkBalance);
        staffInput.addEventListener('input', checkBalance);

        // Disabled by default
        // addBtn.disabled = true;
        // totalDisplay.textContent = `Total: ${currency}0`;

        addBtn.disabled = true;
        totalDisplay.textContent = `${currency}0`;
        totalInput.value = 0;
    </script>
@endsection
