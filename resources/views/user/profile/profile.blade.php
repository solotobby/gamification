@extends('layouts.main.master')

@section('content')
    <!-- Hero -->
    <div class="bg-image" style="background-image: url({{ asset('src/assets/media/photos/photo17@2x.jpg') }});">
        <div class="bg-black-75">
            <div class="content content-full">
                <div class="py-5 text-center">
                    <a class="img-link" href="{{url('profile')}}">
                        <img class="img-avatar img-avatar96 img-avatar-thumb"
                            src="{{ asset('src/assets/media/avatars/avatar10.jpg')}}" alt="">
                    </a>
                    <h1 class="fw-bold my-2 text-white">{{auth()->user()->name}} <i class="fa fa-star" aria-hidden="true"
                            style="color: {{$badge['color']}}"></i> </h1>

                    <h2 class="h4 fw-bold text-white-75">
                        {{$badge['badge']}}
                    </h2>

                    @if(auth()->user()->wallet->base_currency == 'NGN')
                        @if(auth()->user()->is_verified == true)
                            <a class="btn btn-alt-info btn-sm" href="#">
                                <i class="fa fa-fw fa-check opacity-50"></i> Verified
                            </a>
                        @else
                            <a class="btn btn-alt-danger btn-sm" href="{{ url('upgrade') }}">
                                <i class="fa fa-fw fa-times opacity-50"></i> Unverified
                            </a>
                        @endif
                    @elseif(auth()->user()->wallet->base_currency == 'USD')
                        @if(auth()->user()->USD_verified)
                            <a class="btn btn-alt-info btn-sm" href="#">
                                <i class="fa fa-fw fa-check opacity-50"></i> Verified
                            </a>
                        @else
                            <a class="btn btn-alt-danger btn-sm" href="{{ url('upgrade') }}">
                                <i class="fa fa-fw fa-times opacity-50"></i> Unverified
                            </a>
                        @endif
                    @else
                        @if(auth()->user()->USD_verified)
                            <a class="btn btn-alt-info btn-sm" href="#">
                                <i class="fa fa-fw fa-check opacity-50"></i> Verified
                            </a>
                        @else
                            <a class="btn btn-alt-danger btn-sm" href="{{ url('upgrade') }}">
                                <i class="fa fa-fw fa-times opacity-50"></i> Unverified
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content content-full content-boxed">
        <div class="block block-rounded">
            <div class="block-content">

                <!-- User Profile -->
                <h2 class="content-heading pt-0">
                    <i class="fa fa-fw fa-user-circle text-muted me-1"></i> User Profile
                </h2>
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                            Your account's vital info.
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
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

                        @if (session('info'))
                            <div class="alert alert-info" role="alert">
                                {{ session('info') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <p>{{ auth()->user()->email }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Referral Code</label>
                            <p>@ {{auth()->user()->referral_code}}</p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Gender</label>
                            <p>{{ auth()->user()->gender }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Age Bracket</label>
                            <p>{{ auth()->user()->age_range }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Base Currency</label>
                            <p>{{ auth()->user()->wallet->base_currency }}</p>
                        </div>
                    </div>
                </div>
                <!-- END User Profile -->

                <!-- Bank Account Information -->
                @if(!$bankInfo)
                    <h2 class="content-heading pt-0">
                        <i class="fa fa-fw fa-asterisk text-muted me-1"></i> Add Bank Account
                    </h2>
                    <div class="row push">
                        <div class="col-lg-4">
                            <p class="text-muted">Add your bank details</p>
                        </div>
                        <div class="col-lg-8 col-xl-5">
                            <form id="bankForm" action="{{ url('save/bank/information') }}" method="POST">
                                @csrf
                                <input type="hidden" name="validated_name" id="validated_name">
                                <input type="hidden" name="bank_name" id="bank_name">

                                <div class="mb-4">
                                    <label class="form-label">Select Bank</label>
                                    <select class="form-control" name="bank_code" id="bank_code" required>
                                        <option value="">Select Bank</option>
                                        @foreach ($bankList as $bank)
                                            <option value="{{ $bank['code'] }}" data-name="{{ $bank['name'] }}">
                                                {{ $bank['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Enter Account Number</label>
                                    <input type="text" class="form-control" name="account_number" id="account_number"
                                        maxlength="10" pattern="\d{10}" required>
                                    <small class="text-muted">Account number must be exactly 10 digits</small>
                                </div>

                                <div class="mb-4" id="accountNameDiv" style="display:none;">
                                    <label class="form-label">Account Name</label>
                                    <p id="accountName" class="fw-bold text-success"></p>
                                </div>

                                <div class="mb-4">
                                    <button type="button" class="btn btn-alt-primary" id="validateBtn" disabled>
                                        <i class="fa fa-check me-1"></i> Validate Account
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="saveBtn" style="display:none;">
                                        <i class="fa fa-save me-1"></i> Save Bank Details
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <h2 class="content-heading pt-0">
                        <i class="fa fa-fw fa-asterisk text-muted me-1"></i> Bank Details
                    </h2>
                    <div class="row push">
                        <div class="col-lg-4">
                            <p class="text-muted">Your bank details (cannot be modified)</p>
                        </div>
                        <div class="col-lg-8 col-xl-5">
                            <div class="mb-4">
                                <label class="form-label">Bank Name</label>
                                <p>{{ $bankInfo->bank_name }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Account Name</label>
                                <p>{{ $bankInfo->name }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Account Number</label>
                                <p>{{ $bankInfo->masked_account }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- END Bank Account Information -->


                <!-- Change Password -->
                {{-- <h2 class="content-heading pt-0">
                    <i class="fa fa-fw fa-briefcase text-muted me-1"></i> Account Information
                </h2>
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                            Make a transfer to this account to fund your wallet
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label" for="dm-profile-edit-password">Account Name</label>
                            <p>{{ @auth()->user()->virtualAccount->account_name }}</p>
                            <input type="password" class="form-control" id="d /m-profile-edit-password"
                                name="dm-profile-edit-password">
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label" for="dm-profile-edit-password-new">Account Number</label>
                                <p>{{ @auth()->user()->virtualAccount->account_number }}</p>
                                <input type="password" class="form-control" id="dm-profile-edit-password-new"
                                    name="dm-profile-edit-password-new">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label" for="dm-profile-edit-password-new-confirm">Bank Name</label>
                                <p>{{ @auth()->user()->virtualAccount->bank_name }}</p>
                                <input type="password" class="form-control" id="dm-profile-edit-password-new-confirm"
                                    name="dm-profile-edit-password-new-confirm">
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- END Change Password -->


                <!-- Preferences -->
                <h2 class="content-heading pt-0">
                    <i class="fa fa-fw fa-cog text-muted me-1"></i> Preferences
                </h2>
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                            Your list of interests...
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-7">
                        <div class="row mb-4">
                            @foreach (auth()->user()->interests as $interest)
                                <div class="col-sm-12 col-md-4 col-xl-6 mt-1 d-md-flex align-items-md-center fs-sm mb-2">
                                    <a class="btn btn-sm btn-alt-secondary rounded-pill" href="javascript:void(0)">
                                        <i class="fa fa-fw fa-dharmachakra opacity-50 me-1"></i>
                                        {{$interest->name}}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- END Preferences -->

                <!-- Billing Information -->
                {{-- <h2 class="content-heading pt-0">
                    <i class="fab fa-fw fa-paypal text-muted me-1"></i> Billing Information
                </h2>
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                            Your billing information is never shown to other users and only used for creating your
                            invoices.
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label" for="dm-profile-edit-company-name">Company Name (Optional)</label>
                            <input type="text" class="form-control" id="dm-profile-edit-company-name"
                                name="dm-profile-edit-company-name">
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="dm-profile-edit-firstname">Firstname</label>
                                <input type="text" class="form-control" id="dm-profile-edit-firstname"
                                    name="dm-profile-edit-firstname">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="dm-profile-edit-lastname">Lastname</label>
                                <input type="text" class="form-control" id="dm-profile-edit-lastname"
                                    name="dm-profile-edit-lastname">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="dm-profile-edit-street-1">Street Address 1</label>
                            <input type="text" class="form-control" id="dm-profile-edit-street-1"
                                name="dm-profile-edit-street-1">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="dm-profile-edit-street-2">Street Address 2</label>
                            <input type="text" class="form-control" id="dm-profile-edit-street-2"
                                name="dm-profile-edit-street-2">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="dm-profile-edit-city">City</label>
                            <input type="text" class="form-control" id="dm-profile-edit-city" name="dm-profile-edit-city">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="dm-profile-edit-postal">Postal code</label>
                            <input type="text" class="form-control" id="dm-profile-edit-postal"
                                name="dm-profile-edit-postal">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="dm-profile-edit-vat">VAT Number</label>
                            <input type="text" class="form-control" id="dm-profile-edit-vat" name="dm-profile-edit-vat"
                                value="EA00000000" disabled>
                        </div>
                    </div>
                </div> --}}
                <!-- END Billing Information -->

                <!-- Submit -->
                {{-- <div class="row push">
                    <div class="col-lg-8 col-xl-5 offset-lg-4">
                        <div class="mb-4">
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-check-circle opacity-50 me-1"></i> Update Profile
                            </button>
                        </div>
                    </div>
                </div> --}}
                <!-- END Submit -->
                {{--
                </form> --}}
            </div>
        </div>
    </div>
    <!-- END Page Content -->

    <!-- Name Update Modal -->
    <div class="modal fade" id="nameUpdateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Your Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Your account name will be updated to match your bank records:</p>
                    <p><strong>Current Name:</strong> <span id="currentName"></span></p>
                    <p><strong>New Name:</strong> <span id="newName"></span></p>
                    <p class="text-muted">Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdate">Yes, Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Name Update Modal -->

@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
    const validateBtn = document.getElementById('validateBtn');
    const saveBtn = document.getElementById('saveBtn');
    const accountNumberInput = document.getElementById('account_number');
    const bankCodeSelect = document.getElementById('bank_code');
    const accountNameDiv = document.getElementById('accountNameDiv');
    const accountNameEl = document.getElementById('accountName');
    const validatedNameInput = document.getElementById('validated_name');
    const bankNameInput = document.getElementById('bank_name');

    if (!validateBtn || !saveBtn || !accountNumberInput || !bankCodeSelect) return;

    // Initial state
    validateBtn.disabled = true;
    saveBtn.style.display = "none";
    accountNameDiv.style.display = "none";

    // Enable validate button only if account number and bank selected
    function toggleValidateButton() {
        const acc = accountNumberInput.value.trim();
        const bank = bankCodeSelect.value.trim();
        validateBtn.disabled = !(acc.length === 10 && bank !== "");
    }

    accountNumberInput.addEventListener('input', toggleValidateButton);
    bankCodeSelect.addEventListener('change', toggleValidateButton);

    // Handle account validation
    validateBtn.addEventListener('click', async function () {
        validateBtn.disabled = true;
        validateBtn.textContent = "Validating...";

        const accountNumber = accountNumberInput.value.trim();
        const bankCode = bankCodeSelect.value.trim();

        try {
            const response = await fetch("{{ route('validate.bank') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    account_number: accountNumber,
                    bank_code: bankCode
                })
            });

            const data = await response.json();
            console.log("Bank Validation Response:", data);

            if (data.success && data.account_name) {
                // Display validated name under account number
                accountNameEl.textContent = data.account_name;
                validatedNameInput.value = data.account_name;

                // Set bank name
                bankNameInput.value = data.bank_name ?? bankCodeSelect.options[bankCodeSelect.selectedIndex].text;

                accountNameDiv.style.display = "block";
                saveBtn.style.display = "inline-block";
                saveBtn.disabled = false;

                // Show warning if name mismatch, but still allow save
                if (data.name_match === false) {
                    accountNameEl.innerHTML +=
                        ' <small class="text-warning"><br> (Name differs from your Freebyz account name, your freebyz account name will be updated automatically to your bank name)</small>';
                }
            } else {
                accountNameEl.textContent = data.message || "Unable to validate account.";
                accountNameDiv.style.display = "block";
                saveBtn.style.display = "none";
                saveBtn.disabled = true;
            }
        } catch (err) {
            console.error("Validation Error:", err);
            accountNameEl.textContent = "Error validating account.";
            accountNameDiv.style.display = "block";
            saveBtn.style.display = "none";
            saveBtn.disabled = true;
        }

        validateBtn.textContent = "Validate Account";
        toggleValidateButton();
    });
});
</script>

