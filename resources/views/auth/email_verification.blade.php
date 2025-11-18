@extends('layouts.main.master')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
        <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title" id="modalTitle">Verify Your Email</h5>
                    </div>
                    <div class="modal-body text-center py-4">
                        <!-- Verification Section -->
                        <div id="verificationSection">
                            <div class="mb-4">
                                <i class="fa fa-envelope-o fa-4x text-primary"></i>
                            </div>
                            <p class="text-muted mb-4">
                                We've sent a 6-digit verification code to<br>
                                <strong id="displayEmail">{{ auth()->user()->email }}</strong>
                            </p>

                            <p class="text-muted mb-4">
                                📩 If you don't see the email in your inbox,
                                <br>please check your <strong>Spam</strong> or
                                <strong>Junk</strong> folder.
                            </p>

                            <div id="successAlert" class="alert alert-success d-none mb-3">
                                <i class="fa fa-check-circle me-2"></i>
                                <span id="successMessage"></span>
                            </div>

                            <div id="errorAlert" class="alert alert-danger d-none mb-3">
                                <i class="fa fa-exclamation-circle me-2"></i>
                                <span id="errorMessage"></span>
                            </div>

                            <form id="verificationForm">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label">Enter Verification Code</label>
                                    <input type="text" class="form-control form-control-lg text-center"
                                        id="verificationCode" name="code" maxlength="6"
                                        placeholder="000000" required autocomplete="off">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mb-3" id="verifyBtn">
                                    <i class="fa fa-check me-2"></i> Verify Email
                                </button>
                            </form>

                            <div class="text-center">
                                <button type="button" class="btn btn-link text-decoration-none" id="resendBtn"
                                    onclick="resendCode()">
                                    Resend Code
                                </button>
                                <div id="countdown" class="text-muted small d-none">
                                    Resend in <span id="timer">120</span>s
                                </div>
                            </div>

                            <hr class="my-3">

                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showUpdateEmailForm()">
                                <i class="fa fa-edit me-2"></i>Incorrect Email?  Update Now!
                            </button>
                        </div>

                        <!-- Update Email Section -->
                        <div id="updateEmailSection" class="d-none">
                            <div class="mb-4">
                                <i class="fa fa-edit fa-4x text-warning"></i>
                            </div>
                            <p class="text-muted mb-4">
                                Enter your new email address to receive the verification code
                            </p>

                            <form id="updateEmailForm">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label">New Email Address</label>
                                    <input type="email" class="form-control form-control-lg"
                                        id="newEmail" name="new_email"
                                        placeholder="your@email.com" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Enter Verification Code</label>
                                    <input type="text" class="form-control form-control-lg text-center"
                                        id="newEmailCode" name="code" maxlength="6"
                                        placeholder="000000" required autocomplete="off">
                                </div>

                                <button type="button" class="btn btn-info w-100 mb-2" id="sendNewEmailCodeBtn"
                                    onclick="sendNewEmailCode()">
                                    <i class="fa fa-paper-plane me-2"></i> Send Code to New Email
                                </button>

                                <button type="submit" class="btn btn-primary w-100 mb-3" id="updateEmailBtn">
                                    <i class="fa fa-check me-2"></i> Verify & Update Email
                                </button>
                            </form>

                            <button type="button" class="btn btn-link text-decoration-none" onclick="showVerificationForm()">
                                <i class="fa fa-arrow-left me-2"></i> Back to Verification
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>
    <script>
        let countdownInterval;

        $(document).ready(function () {
            sendVerificationCode();
        });

        // Handle verification form submission
        $('#verificationForm').on('submit', function (e) {
            e.preventDefault();

            const code = $('#verificationCode').val();

            if (code.length !== 6) {
                showError('Please enter a valid 6-digit code');
                return;
            }

            $('#verifyBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Verifying...');

            $.ajax({
                url: '{{ route("verification.verify") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    code: code
                },
                success: function (response) {
                    showSuccess('Email verified successfully! Redirecting...');
                    setTimeout(() => {
                        window.location.href = '{{ url("/home") }}';
                    }, 1500);
                },
                error: function (xhr) {
                    $('#verifyBtn').prop('disabled', false).html('<i class="fa fa-check me-2"></i> Verify Email');

                    if (xhr.status === 422) {
                        showError(xhr.responseJSON.message || 'Invalid verification code');
                    } else {
                        showError('Something went wrong. Please try again.');
                    }
                }
            });
        });

        // Handle update email form submission
        $('#updateEmailForm').on('submit', function (e) {
            e.preventDefault();

            const newEmail = $('#newEmail').val();
            const code = $('#newEmailCode').val();

            if (code.length !== 6) {
                showError('Please enter a valid 6-digit code');
                return;
            }

            $('#updateEmailBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Updating...');

            $.ajax({
                url: '{{ route("verification.update-email") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    new_email: newEmail,
                    code: code
                },
                success: function (response) {
                    showSuccess('Email updated and verified successfully! Redirecting...');
                    setTimeout(() => {
                        window.location.href = '{{ url("/home") }}';
                    }, 1500);
                },
                error: function (xhr) {
                    $('#updateEmailBtn').prop('disabled', false).html('<i class="fa fa-check me-2"></i> Verify & Update Email');

                    if (xhr.status === 422) {
                        showError(xhr.responseJSON.message || 'Invalid verification code or email already exists');
                    } else {
                        showError('Something went wrong. Please try again.');
                    }
                }
            });
        });

        function sendVerificationCode() {
            $.ajax({
                url: '{{ route("verification.send") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    showSuccess('Verification code sent to your email');
                    startCountdown();
                },
                error: function () {
                    showError('Failed to send code. Please try again.');
                }
            });
        }

        function sendNewEmailCode() {
            const newEmail = $('#newEmail').val();

            if (!newEmail) {
                showError('Please enter your new email address');
                return;
            }

            $('#sendNewEmailCodeBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Sending...');

            $.ajax({
                url: '{{ route("verification.send-new-email") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    new_email: newEmail
                },
                success: function (response) {
                    showSuccess('Verification code sent to ' + newEmail);
                    $('#sendNewEmailCodeBtn').prop('disabled', false).html('<i class="fa fa-paper-plane me-2"></i> Send Code to New Email');
                },
                error: function (xhr) {
                    $('#sendNewEmailCodeBtn').prop('disabled', false).html('<i class="fa fa-paper-plane me-2"></i> Send Code to New Email');

                    if (xhr.status === 422) {
                        showError(xhr.responseJSON.message || 'Invalid email address or email already exists');
                    } else {
                        showError('Failed to send code. Please try again.');
                    }
                }
            });
        }

        function resendCode() {
            $('#resendBtn').prop('disabled', true);
            sendVerificationCode();
        }

        function startCountdown() {
            let timeLeft = 120;
            $('#resendBtn').addClass('d-none');
            $('#countdown').removeClass('d-none');

            countdownInterval = setInterval(() => {
                timeLeft--;
                $('#timer').text(timeLeft);

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    $('#countdown').addClass('d-none');
                    $('#resendBtn').removeClass('d-none').prop('disabled', false);
                }
            }, 1000);
        }

        function showUpdateEmailForm() {
            $('#verificationSection').addClass('d-none');
            $('#updateEmailSection').removeClass('d-none');
            $('#modalTitle').text('Update Email Address');
            hideAlerts();
        }

        function showVerificationForm() {
            $('#updateEmailSection').addClass('d-none');
            $('#verificationSection').removeClass('d-none');
            $('#modalTitle').text('Verify Your Email');
            hideAlerts();
        }

        function showSuccess(message) {
            $('#successMessage').text(message);
            $('#successAlert').removeClass('d-none');
            $('#errorAlert').addClass('d-none');

            setTimeout(() => {
                $('#successAlert').addClass('d-none');
            }, 5000);
        }

        function showError(message) {
            $('#errorMessage').text(message);
            $('#errorAlert').removeClass('d-none');
            $('#successAlert').addClass('d-none');
        }

        function hideAlerts() {
            $('#successAlert').addClass('d-none');
            $('#errorAlert').addClass('d-none');
        }

        // Only allow numbers in verification codes
        $('#verificationCode, #newEmailCode').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
@endsection
