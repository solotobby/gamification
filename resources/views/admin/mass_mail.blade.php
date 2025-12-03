@extends('layouts.main.master')
@section('style')
    <script src="https://cdn.tiny.cloud/1/d8iwvjd0vuxf9luaztf5x2ejuhnudtkzhxtnbh3gjjrgw4yx/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <div>
                    <h1 class="fs-3 fw-semibold my-2 my-sm-3">Mass Communication</h1>
                </div>

                <a href="{{ route('mass.mail.campaigns') }}" class="btn btn-primary my-2 my-sm-0">
                    <i class="fa fa-paper-plane me-1"></i> Sent Campaigns
                </a>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Send Broadcast Message</h3>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('send.mass.communication') }}" method="POST" id="massCommForm">
                    @csrf

                    <!-- Audience Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label>Audience Type</label>
                            <select class="form-control filter-input" name="type" required>
                                <option value="registered">All Registered Users</option>
                                <option value="verified">Account Verified Users</option>
                                <option value="email_verified">Email Verified Users</option>
                                <option value="phone_verified">Phone Verified Users</option>
                                <option value="performed_job">Perform A Job</option>
                                <option value="test_user">Test User</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Date Range</label>
                            <select class="form-control filter-input" name="date_range_type" id="dateRangeType">
                                <option value="all">All Time</option>
                                <option value="preset">Preset Range</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="presetRangeDiv" style="display:none;">
                            <label>Preset Period</label>
                            <select class="form-control filter-input" name="days">
                                <option value="7">Last 7 Days</option>
                                <option value="14">Last 14 Days</option>
                                <option value="30">Last 30 Days</option>
                                <option value="60">Last 60 Days</option>
                                <option value="90">Last 3 months</option>
                                <option value="180">Last 6 months</option>
                                <option value="270">Last 9 months</option>
                                <option value="365">Last 1 year</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Country</label>
                            <select class="form-control filter-input" name="country">
                                <option value="">All Countries</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Custom Date Range Row -->
                    <div class="row mb-4" id="customRangeDiv" style="display:none;">
                        <div class="col-md-6">
                            <label>Start Date</label>
                            <input type="date" class="form-control filter-input" name="start_date" id="startDate">
                        </div>
                        <div class="col-md-6">
                            <label>End Date</label>
                            <input type="date" class="form-control filter-input" name="end_date" id="endDate">
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="alert alert-info mb-4" id="previewSection">
                        <strong>Audience Preview:</strong>
                        <div id="previewContent" class="mt-2">
                            <span class="text-muted">Loading...</span>
                        </div>
                    </div>

                    <!-- Communication Type -->
                    <div class="mb-4">
                        <label>Send Via</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="send_channel" id="sendEmail"
                                    value="email" checked>
                                <label class="form-check-label" for="sendEmail">Email</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="send_channel" id="sendSMS" value="sms">
                                <label class="form-check-label" for="sendSMS">SMS</label>
                            </div>
                        </div>
                    </div>


                    <!-- Email Fields -->
                    <div id="emailFields">
                        <div class="mb-4">
                            <label>Email Subject</label>
                            <input type="text" class="form-control" name="subject" value="{{ old('subject') }}">
                        </div>
                        <div class="mb-4">
                            <label>Email Message</label>
                            <textarea id="js-ckeditor5-classic" name="message">{{ old('message') }}</textarea>
                        </div>
                    </div>

                    <!-- SMS Fields -->
                    <div id="smsFields" style="display:none;">
                        <div class="mb-4">
                            <label>SMS Message <small class="text-muted">(160 chars recommended)</small></label>
                            <textarea class="form-control" name="sms_message" rows="3"
                                maxlength="500">{{ old('sms_message') }}</textarea>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-paper-plane"></i> Send Communication
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
    <script src="{{asset('src/assets/js/plugins/ckeditor/ckeditor.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>

    <script>
        $(document).ready(function () {
            // Toggle date range options
            $('#dateRangeType').on('change', function() {
                const type = $(this).val();
                $('#presetRangeDiv').toggle(type === 'preset');
                $('#customRangeDiv').toggle(type === 'custom');

                if (type === 'all') {
                    $('select[name="days"]').val('');
                    $('#startDate').val('');
                    $('#endDate').val('');
                }

                updatePreview();
            });

            // Update preview when filters change
            $('.filter-input').on('change', updatePreview);

            // Toggle email/sms fields
            $('#sendEmail, #sendSMS').on('change', function () {
                $('#emailFields').toggle($('#sendEmail').is(':checked'));
                $('#smsFields').toggle($('#sendSMS').is(':checked'));
            });

            function updatePreview() {
                const dateRangeType = $('#dateRangeType').val();
                let ajaxData = {
                    _token: '{{ csrf_token() }}',
                    type: $('select[name="type"]').val(),
                    country: $('select[name="country"]').val(),
                    date_range_type: dateRangeType
                };

                if (dateRangeType === 'preset') {
                    ajaxData.days = $('select[name="days"]').val();
                } else if (dateRangeType === 'custom') {
                    ajaxData.start_date = $('#startDate').val();
                    ajaxData.end_date = $('#endDate').val();
                }

                $.ajax({
                    url: '{{ route("preview.audience") }}',
                    method: 'POST',
                    data: ajaxData,
                    success: function (data) {
                        $('#previewContent').html(`
                            <strong>${data.total}</strong> total users<br>
                            <strong>${data.with_email}</strong> with email<br>
                            <strong>${data.with_phone}</strong> with phone
                        `);
                        $('#previewSection').show();
                    }
                });
            }

            // Initial preview
            updatePreview();
        });
    </script>
@endsection
