@extends('layouts.main.master')

@section('content')
    <div class="content">

        @php
            $userCurr = $info->currency_code;
            $activeBal = $info->active_balance;
            $isVerified = $info->isVerifiedInCurrency();
            $currParam = \App\Models\Currency::where('code', $userCurr)->first();
        @endphp

        <!-- User Header Profile Card -->
        <div class="block block-rounded">
            <div class="block-content text-center py-4">
                <div class="mb-3 position-relative d-inline-block">
                    <div class="avatar avatar-96 rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto text-primary fw-bold fs-1 shadow-sm" style="width: 90px; height: 90px;">
                        {{ strtoupper(substr($info->name, 0, 1)) }}
                    </div>
                    @if($isVerified)
                        <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle p-1" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center;" title="Verified Account">
                            <i class="fa fa-check fs-xs"></i>
                        </span>
                    @endif
                </div>

                <h1 class="fs-4 fw-bold mb-1">
                    {{ $info->name }}
                    @if($info->is_celebrity)
                        <span class="badge bg-warning text-dark fs-xs align-middle ms-1">Celebrity</span>
                    @endif
                    @if($info->is_business)
                        <span class="badge bg-primary text-white fs-xs align-middle ms-1">Business</span>
                    @endif
                </h1>

                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-2">
                    <span class="badge-currency badge-currency-{{ strtolower($userCurr) }} fs-sm py-1 px-2">
                        <i class="fa fa-circle fs-xs"></i> {{ $userCurr }} Account
                    </span>
                    <span class="text-muted fs-sm">&#8226;</span>
                    <span class="text-muted fs-sm">{{ $info->email }}</span>
                    <span class="text-muted fs-sm">&#8226;</span>
                    <span class="text-muted fs-sm">{{ $info->phone ?? 'No phone' }}</span>
                    @if($info->country)
                        <span class="text-muted fs-sm">&#8226;</span>
                        <span class="badge bg-secondary-light text-dark fs-xs">{{ $info->country }}</span>
                    @endif
                </div>

                <div class="text-muted fs-xs">
                    Member since {{ \Carbon\Carbon::parse($info->created_at)->format('M d, Y') }} ({{ \Carbon\Carbon::parse($info->created_at)->diffForHumans() }})
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="block-content bg-body-light border-top">
                <div class="row text-center py-2">
                    <div class="col-6 col-md-3 border-end">
                        <div class="fs-xs fw-semibold text-uppercase text-muted mb-1">Active Balance</div>
                        <div class="fs-3 fw-bold {{ $activeBal > 0 ? 'text-success' : 'text-dark' }}">
                            {{ formatCurrency($activeBal, $userCurr) }}
                        </div>
                        <div class="fs-xs text-muted">
                            <a href="{{ url('admin/user/transactions/' . $info->id) }}" target="_blank" class="text-primary">View Ledger <i class="fa fa-arrow-right fs-xs"></i></a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 border-end">
                        <div class="fs-xs fw-semibold text-uppercase text-muted mb-1">Campaigns</div>
                        <div class="fs-3 fw-bold text-dark">{{ number_format($info->my_campaigns_count) }}</div>
                        <div class="fs-xs text-muted">
                            <a href="{{ url('admin/user/campaigns/' . $info->id) }}" target="_blank" class="text-primary">Campaigns</a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 border-end">
                        <div class="fs-xs fw-semibold text-uppercase text-muted mb-1">Jobs Attempted</div>
                        <div class="fs-3 fw-bold text-dark">{{ number_format($info->my_jobs_count) }}</div>
                        <div class="fs-xs text-muted">
                            <a href="{{ url('admin/user/jobs/' . $info->id) }}" target="_blank" class="text-primary">User Jobs</a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="fs-xs fw-semibold text-uppercase text-muted mb-1">Referrals</div>
                        <div class="fs-3 fw-bold text-dark">{{ number_format($info->referees_count) }}</div>
                        <div class="fs-xs text-muted">
                            <a href="{{ url('admin/user/referral/' . $info->id) }}" target="_blank" class="text-primary">Referral Tree</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center shadow-sm" role="alert">
                <i class="fa fa-check-circle me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center shadow-sm" role="alert">
                <i class="fa fa-exclamation-circle me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info d-flex align-items-center shadow-sm" role="alert">
                <i class="fa fa-info-circle me-2 fs-5"></i>
                <div>{{ session('info') }}</div>
            </div>
        @endif

        <!-- Account Info Summary Cards -->
        <div class="row">
            <div class="col-lg-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title fs-sm fw-bold text-uppercase">Profile & Status Information</h3>
                    </div>
                    <div class="block-content fs-sm">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted fw-medium" style="width: 45%;">Account Verification</td>
                                    <td>
                                        @if($isVerified)
                                            <span class="badge bg-success-light text-success"><i class="fa fa-check me-1"></i> Verified in {{ $userCurr }}</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Unverified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Referral Code</td>
                                    <td><span class="font-monospace fw-bold">{{ $info->referral_code ?? 'None' }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Referred By</td>
                                    <td>{{ $referredBy ? $referredBy->name . ' (' . $referredBy->email . ')' : 'Direct Registration' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Email Status</td>
                                    <td>
                                        @if($info->email_verified_at)
                                            <span class="text-success"><i class="fa fa-check-circle me-1"></i> Verified</span>
                                        @else
                                            <span class="text-warning"><i class="fa fa-clock me-1"></i> Pending Verification</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Blacklisted / Banned</td>
                                    <td>
                                        @if($info->is_blacklisted)
                                            <span class="badge bg-danger">Banned / Blacklisted</span>
                                        @else
                                            <span class="badge bg-success-light text-success">Good Standing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-medium">Auth Device / Source</td>
                                    <td>{{ ucfirst($info->auth_device ?? 'Web') }} &#8226; {{ $info->source ?? 'organic' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title fs-sm fw-bold text-uppercase">Banking & Payout Channels</h3>
                    </div>
                    <div class="block-content fs-sm">
                        @if($info->accountDetails && $info->accountDetails->account_number)
                            <div class="mb-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold text-dark"><i class="fa fa-university me-1 text-primary"></i> Payout Account Details</div>
                                    <span class="badge-currency badge-currency-{{ strtolower($info->accountDetails->currency ?? $userCurr) }} fs-xs">
                                        {{ $info->accountDetails->currency ?? $userCurr }}
                                    </span>
                                </div>
                                <div><strong>Name:</strong> {{ $info->accountDetails->name }}</div>
                                <div><strong>Bank / Provider:</strong> {{ $info->accountDetails->bank_name }}</div>
                                <div><strong>Account / Phone Number:</strong> <span class="font-monospace fw-bold">{{ $info->accountDetails->account_number }}</span></div>
                                <div class="mt-2 text-end">
                                    <button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#quickEditPayoutCard" aria-expanded="false">
                                        <i class="fa fa-edit me-1"></i> Edit Payout Details
                                    </button>
                                </div>
                            </div>

                            <!-- Collapsible Edit Form -->
                            <div class="collapse mb-3" id="quickEditPayoutCard">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <h6 class="fw-bold mb-2 text-primary fs-xs text-uppercase"><i class="fa fa-edit me-1"></i> Update Payout Account ({{ $userCurr }})</h6>
                                    <form action="{{ route('admin.update.account.details') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $info->id }}">

                                        @if(in_array($userCurr, ['GHS', 'KES', 'UGX']))
                                            <div class="mb-2">
                                                <div class="d-flex gap-3 fs-xs">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="method" id="quickEditMethodBank" value="bank" checked onchange="toggleQuickEditMethodFields('bank')">
                                                        <label class="form-check-label" for="quickEditMethodBank">Bank</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="method" id="quickEditMethodMomo" value="mobile_money" onchange="toggleQuickEditMethodFields('mobile_money')">
                                                        <label class="form-check-label" for="quickEditMethodMomo">Mobile Money</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="method" value="bank">
                                        @endif

                                        <div class="mb-2" id="quickEditBankSelectGroup">
                                            <label class="form-label fs-xs fw-semibold">Bank <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" name="bank_code" id="quickEditBankCodeSelect" required>
                                                <option value="">-- Select Bank --</option>
                                                @foreach ($bankList as $bank)
                                                    <option value="{{ $bank['code'] }}" {{ @$info->accountDetails->bank_code == $bank['code'] ? 'selected' : '' }}>
                                                        {{ $bank['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @if(!empty($mobileMoneyNetworks))
                                            <div class="mb-2 d-none" id="quickEditMomoSelectGroup">
                                                <label class="form-label fs-xs fw-semibold">Network <span class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" name="momo_network" id="quickEditMomoNetworkSelect">
                                                    <option value="">-- Select Network --</option>
                                                    @foreach ($mobileMoneyNetworks as $net)
                                                        <option value="{{ $net['code'] }}" {{ @$info->accountDetails->bank_code == $net['code'] ? 'selected' : '' }}>
                                                            {{ $net['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="mb-2">
                                            <label class="form-label fs-xs fw-semibold" id="quickEditAccountNumberLabel">Account / Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm font-monospace" name="account_number" id="quickEditAccountNumberInput"
                                                placeholder="{{ $userCurr === 'NGN' ? '10-digit NGN Account Number' : 'Account or Mobile Phone Number' }}" required value="{{ @$info->accountDetails->account_number }}">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label fs-xs fw-semibold">Account Holder Name (Optional / Override)</label>
                                            <input type="text" class="form-control form-control-sm" name="account_name" id="quickEditAccountNameInput"
                                                placeholder="Auto-resolve via API or type override" value="{{ @$info->accountDetails->name }}">
                                            <div id="quickEditAccountNameStatus" class="form-text fs-xs mt-1"></div>
                                        </div>

                                        <div class="mt-2 text-end">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fa fa-save me-1"></i> Update Details
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Inline Form: Add Payout Account for User -->
                            <div class="mb-3 p-3 border border-primary-subtle rounded bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold text-dark"><i class="fa fa-plus-circle me-1 text-primary"></i> Add Payout Account ({{ $userCurr }})</div>
                                    <span class="badge-currency badge-currency-{{ strtolower($userCurr) }} fs-xs">{{ $userCurr }}</span>
                                </div>

                                <form action="{{ route('admin.update.account.details') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $info->id }}">

                                    @if(in_array($userCurr, ['GHS', 'KES', 'UGX']))
                                        <div class="mb-2">
                                            <label class="form-label fs-xs fw-semibold">Payout Channel Method <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-3 fs-xs">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="method" id="quickMethodBank" value="bank" checked onchange="toggleQuickMethodFields('bank')">
                                                    <label class="form-check-label" for="quickMethodBank">Bank Account</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="method" id="quickMethodMomo" value="mobile_money" onchange="toggleQuickMethodFields('mobile_money')">
                                                    <label class="form-check-label" for="quickMethodMomo">Mobile Money</label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="method" value="bank">
                                    @endif

                                    <div class="mb-2" id="quickBankSelectGroup">
                                        <label class="form-label fs-xs fw-semibold">Bank Name <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" name="bank_code" id="quickBankCodeSelect" required>
                                            <option value="">-- Select Bank --</option>
                                            @foreach ($bankList as $bank)
                                                <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if(!empty($mobileMoneyNetworks))
                                        <div class="mb-2 d-none" id="quickMomoSelectGroup">
                                            <label class="form-label fs-xs fw-semibold">Mobile Money Network <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" name="momo_network" id="quickMomoNetworkSelect">
                                                <option value="">-- Select Network --</option>
                                                @foreach ($mobileMoneyNetworks as $net)
                                                    <option value="{{ $net['code'] }}">{{ $net['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="mb-2">
                                        <label class="form-label fs-xs fw-semibold" id="quickAccountNumberLabel">Account / Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm font-monospace" name="account_number" id="quickAccountNumberInput"
                                            placeholder="{{ $userCurr === 'NGN' ? '10-digit NGN Account Number' : 'Account or Mobile Phone Number' }}" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label fs-xs fw-semibold">Account Holder Name (Optional / Override)</label>
                                        <input type="text" class="form-control form-control-sm" name="account_name" id="quickAccountNameInput"
                                            placeholder="Auto-resolved via API if left empty">
                                        <div id="quickAccountNameStatus" class="form-text fs-xs mt-1">
                                            <span class="text-muted">Will auto-resolve with {{ $userCurr === 'NGN' ? 'Paystack' : 'Flutterwave' }} as you type.</span>
                                        </div>
                                    </div>

                                    <div class="mt-2 text-end">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fa fa-save me-1"></i> Save Payout Details
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if($info->virtualAccount && $info->virtualAccount->account_number)
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold text-dark"><i class="fa fa-credit-card me-1 text-success"></i> Freebyz Dedicated Virtual Account</div>
                                    <span class="badge bg-success-light text-success fs-xs text-uppercase">{{ $info->virtualAccount->channel ?? 'Active' }}</span>
                                </div>
                                <div><strong>Bank:</strong> {{ $info->virtualAccount->bank_name }}</div>
                                <div><strong>Account Name:</strong> {{ $info->virtualAccount->account_name }}</div>
                                <div><strong>Account Number:</strong> <span class="font-monospace fw-bold">{{ $info->virtualAccount->account_number }}</span></div>
                                <div class="mt-2 text-end">
                                    <a href="{{ url('reactivate/virtual/account/' . $info->id) }}" class="btn btn-xs btn-outline-secondary" onclick="return confirm('Regenerate virtual account for this user?')">
                                        <i class="fa fa-sync me-1"></i> Regenerate
                                    </a>
                                </div>
                            </div>
                        @elseif($userCurr === 'NGN')
                            <div class="p-3 bg-light rounded d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-semibold text-dark">NGN Virtual Account</div>
                                    <span class="text-muted fs-xs">Wema Bank static virtual account via Interswitch</span>
                                </div>
                                <a href="{{ url('reactivate/virtual/account/' . $info->id) }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus me-1"></i> Generate (Interswitch)
                                </a>
                            </div>
                        @elseif($userCurr === 'GHS')
                            <div class="p-3 bg-light rounded d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-semibold text-dark">GHS Virtual Account</div>
                                    <span class="text-muted fs-xs">GHS static virtual account via Flutterwave</span>
                                </div>
                                <a href="{{ url('reactivate/virtual/account/' . $info->id) }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus me-1"></i> Generate (Flutterwave)
                                </a>
                            </div>
                        @else
                            <div class="p-3 bg-light rounded text-muted">
                                <i class="fa fa-info-circle me-1"></i> Static virtual accounts are not available for {{ $userCurr }} users.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Actions Tabs -->
        <div class="block block-rounded">
            <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-topup-btn" data-bs-toggle="tab" data-bs-target="#tab-topup" role="tab">
                        <i class="fa fa-wallet me-1 text-primary"></i> Wallet Topup & Debit
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-verification-btn" data-bs-toggle="tab" data-bs-target="#tab-verification" role="tab">
                        <i class="fa fa-check-double me-1 text-success"></i> Verification
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-switch-btn" data-bs-toggle="tab" data-bs-target="#tab-switch" role="tab">
                        <i class="fa fa-exchange-alt me-1 text-warning"></i> Switch Currency
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-bank-btn" data-bs-toggle="tab" data-bs-target="#tab-bank" role="tab">
                        <i class="fa fa-university me-1 text-info"></i> Bank Details
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-more-btn" data-bs-toggle="tab" data-bs-target="#tab-more" role="tab">
                        <i class="fa fa-cog me-1 text-muted"></i> Account Controls
                    </button>
                </li>
            </ul>

            <div class="block-content tab-content py-4">

                <!-- 1. WALLET TOPUP / DEBIT TAB -->
                <div class="tab-pane fade show active" id="tab-topup" role="tabpanel">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="mb-4 text-center">
                                <h4 class="fw-bold mb-1">Credit or Debit User Wallet</h4>
                                <p class="text-muted fs-sm">
                                    Current Active Balance: <strong>{{ formatCurrency($activeBal, $userCurr) }}</strong> ({{ $userCurr }})
                                </p>
                            </div>

                            <form action="{{ route('admin.wallet.topup') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $info->id }}">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Action Type <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select" required>
                                            <option value="credit">Credit User (Add Funds)</option>
                                            <option value="debit">Debit User (Deduct Funds)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                                        <select name="currency" class="form-select" required>
                                            <option value="{{ $userCurr }}" selected>{{ $userCurr }} (User Base Currency)</option>
                                            @if(isset($activeCurrencies))
                                                @foreach($activeCurrencies as $cur)
                                                    @if($cur->code !== $userCurr)
                                                        <option value="{{ $cur->code }}">{{ $cur->code }} - {{ $cur->country }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">{{ currencySymbol($userCurr) }}</span>
                                            <input type="number" step="0.01" min="0.01" class="form-control" name="amount" placeholder="0.00" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Reason / Description <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reason" placeholder="e.g. Administrative refund, dispute resolution, bonus..." required>
                                    </div>

                                    <div class="col-md-12 text-center pt-2">
                                        <button type="submit" class="btn btn-primary px-4 py-2" onclick="return confirm('Are you sure you want to process this wallet adjustment?')">
                                            <i class="fa fa-check me-1"></i> Process Transaction
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 2. VERIFICATION TAB -->
                <div class="tab-pane fade" id="tab-verification" role="tabpanel">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center py-3">
                            <div class="avatar avatar-64 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center {{ $isVerified ? 'bg-success-light text-success' : 'bg-warning-light text-warning' }}" style="width: 64px; height: 64px;">
                                <i class="fa {{ $isVerified ? 'fa-user-check' : 'fa-user-clock' }} fs-2"></i>
                            </div>

                            <h4 class="fw-bold mb-1">
                                {{ $isVerified ? 'Account is Currently Verified' : 'Account is Unverified' }}
                            </h4>
                            <p class="text-muted fs-sm mb-4">
                                Current Base Currency: <strong>{{ $userCurr }}</strong> &bull;
                                Standard Fee: <strong>{{ formatCurrency($currParam->upgrade_fee ?? 0, $userCurr) }}</strong> &bull;
                                Referral Commission: <strong>{{ formatCurrency($currParam->referral_commission ?? 0, $userCurr) }}</strong>
                            </p>

                            <div class="d-flex justify-content-center gap-3">
                                @if($isVerified)
                                    <a href="{{ route('admin.user.upgrade', $info->id) }}" 
                                       class="btn btn-warning px-4 py-2"
                                       onclick="return confirm('Are you sure you want to UNVERIFY this user? They will lose verified privileges.')">
                                        <i class="fa fa-times-circle me-1"></i> Unverify User Now
                                    </a>
                                @else
                                    <a href="{{ route('admin.user.upgrade', $info->id) }}" 
                                       class="btn btn-success px-4 py-2"
                                       onclick="return confirm('Verify user {{ $info->name }} in {{ $userCurr }}? Referral bonus will be disbursed.')">
                                        <i class="fa fa-check-circle me-1"></i> Verify User ({{ $userCurr }}) Now
                                    </a>
                                @endif
                            </div>

                            @if($userCurr === 'NGN')
                                <hr class="my-4">
                                <div class="text-center">
                                    <h5 class="fs-sm fw-bold text-muted text-uppercase mb-2">Dedicated Virtual Account (Interswitch / Wema)</h5>
                                    <a href="{{ url('reactivate/virtual/account/' . $info->id) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Generate or regenerate NGN Interswitch virtual account?')">
                                        <i class="fa fa-sync me-1"></i> Generate / Regenerate NGN Virtual Account
                                    </a>
                                </div>
                            @elseif($userCurr === 'GHS')
                                <hr class="my-4">
                                <div class="text-center">
                                    <h5 class="fs-sm fw-bold text-muted text-uppercase mb-2">Dedicated Virtual Account (Flutterwave GHS)</h5>
                                    <a href="{{ url('reactivate/virtual/account/' . $info->id) }}" class="btn btn-sm btn-outline-success" onclick="return confirm('Generate or regenerate GHS Flutterwave virtual account?')">
                                        <i class="fa fa-sync me-1"></i> Generate / Regenerate GHS Virtual Account
                                    </a>
                                </div>
                            @else
                                <hr class="my-4">
                                <div class="text-center text-muted fs-sm">
                                    <i class="fa fa-info-circle me-1"></i> Static virtual accounts are not available for <strong>{{ $userCurr }}</strong> users.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 3. SMART CURRENCY SWITCHER TAB -->
                <div class="tab-pane fade" id="tab-switch" role="tabpanel">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold mb-1">Single-Currency Base Switcher</h4>
                                <p class="text-muted fs-sm">
                                    Convert user's account and active balance from <strong>{{ $userCurr }}</strong> to a new supported currency.
                                </p>
                            </div>

                            <div class="card border-0 bg-light p-3 mb-4 rounded-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fs-xs text-muted text-uppercase fw-bold">Current Currency:</span>
                                        <div class="fs-5 fw-bold text-dark">
                                            <span class="badge-currency badge-currency-{{ strtolower($userCurr) }}">{{ $userCurr }}</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="fs-xs text-muted text-uppercase fw-bold">Current Live Balance:</span>
                                        <div class="fs-5 fw-bold text-success">{{ formatCurrency($activeBal, $userCurr) }}</div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ url('admin/switch/wallet') }}" method="POST" id="currencySwitchForm">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $info->id }}">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Select New Target Currency <span class="text-danger">*</span></label>
                                    <select name="currency" id="targetCurrencySelect" class="form-select form-select-lg" required>
                                        <option value="">-- Choose New Currency --</option>
                                        @if(isset($activeCurrencies))
                                            @foreach($activeCurrencies as $curr)
                                                @if($curr->code !== $userCurr)
                                                    <option value="{{ $curr->code }}" data-rate="{{ $curr->base_rate }}">
                                                        {{ $curr->code }} - {{ $curr->country }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- Conversion Alert & Explanation -->
                                <div class="alert alert-info border-0 shadow-none fs-sm">
                                    <i class="fa fa-info-circle me-1"></i>
                                    <strong>Automatic Balance Conversion:</strong>
                                    Switching will convert the user's active balance ({{ formatCurrency($activeBal, $userCurr) }}) to the new currency using current live exchange rates, update both <code>wallets.base_currency</code> and <code>users.base_currency</code>, zero out the old column, and log an audit transaction.
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2" onclick="return confirm('Are you sure you want to switch this user currency? Their active balance will be automatically converted.')">
                                        <i class="fa fa-exchange-alt me-1"></i> Convert & Switch Currency
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 4. BANK DETAILS TAB -->
                <div class="tab-pane fade" id="tab-bank" role="tabpanel">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="text-center mb-4">
                                <h4 class="fw-bold mb-1">Update Payout Account ({{ $userCurr }})</h4>
                                <p class="text-muted fs-sm">
                                    @if($userCurr === 'NGN')
                                        Resolves Nigerian account names with <strong>Paystack</strong> banking network.
                                    @else
                                        Supports Bank Accounts and Mobile Money networks via <strong>Flutterwave</strong>.
                                    @endif
                                </p>
                            </div>

                            <form action="{{ route('admin.update.account.details') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $info->id }}">

                                @if(in_array($userCurr, ['GHS', 'KES', 'UGX']))
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Payout Channel Method <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="method" id="methodBank" value="bank" checked onchange="toggleMethodFields('bank')">
                                                <label class="form-check-label" for="methodBank">Bank Account</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="method" id="methodMomo" value="mobile_money" onchange="toggleMethodFields('mobile_money')">
                                                <label class="form-check-label" for="methodMomo">Mobile Money</label>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="method" value="bank">
                                @endif

                                <div class="mb-3" id="bankSelectGroup">
                                    <label class="form-label fw-semibold">Bank Name <span class="text-danger">*</span></label>
                                    <select class="form-select" name="bank_code" id="bankCodeSelect" required>
                                        <option value="">-- Select Bank --</option>
                                        @foreach ($bankList as $bank)
                                            <option value="{{ $bank['code'] }}" {{ @$info->accountDetails->bank_code == $bank['code'] ? 'selected' : '' }}>
                                                {{ $bank['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if(!empty($mobileMoneyNetworks))
                                    <div class="mb-3 d-none" id="momoSelectGroup">
                                        <label class="form-label fw-semibold">Mobile Money Network <span class="text-danger">*</span></label>
                                        <select class="form-select" name="momo_network" id="momoNetworkSelect">
                                            <option value="">-- Select Network --</option>
                                            @foreach ($mobileMoneyNetworks as $net)
                                                <option value="{{ $net['code'] }}" {{ @$info->accountDetails->bank_code == $net['code'] ? 'selected' : '' }}>
                                                    {{ $net['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label fw-semibold" id="accountNumberLabel">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control font-monospace" name="account_number" id="accountNumberInput"
                                        placeholder="{{ $userCurr === 'NGN' ? '10-digit NGN Account Number' : 'Account or Mobile Phone Number' }}" required value="{{ @$info->accountDetails->account_number }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Account Holder Name (Optional / Override)</label>
                                    <input type="text" class="form-control" name="account_name" id="accountNameInput"
                                        placeholder="Full Name as registered on bank or Mobile Money" value="{{ @$info->accountDetails->name }}">
                                    <div id="accountNameStatus" class="form-text fs-xs mt-1">
                                        <span class="text-muted">Will auto-resolve with {{ $userCurr === 'NGN' ? 'Paystack' : 'Flutterwave' }} when account number is entered.</span>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2">
                                        <i class="fa fa-save me-1"></i> Save Payout Account Details
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 5. ACCOUNT CONTROLS TAB -->
                <div class="tab-pane fade" id="tab-more" role="tabpanel">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <!-- Celebrity Toggle -->
                            <div class="card border p-3 mb-4 rounded-3">
                                <h5 class="fw-bold mb-1">Celebrity / Influencer Status</h5>
                                <p class="text-muted fs-sm mb-3">
                                    Celebrity accounts do not receive regular referral bonuses and receive a 10% discount on verification fees.
                                </p>
                                <form action="{{ route('admin.celebrity') }}" method="POST" class="row g-2 align-items-center">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $info->id }}">
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="referral_code" 
                                            value="{{ $info->referral_code }}" placeholder="Custom Celebrity Referral Code" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-outline-primary w-100">Set Celebrity Code</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Business Account Toggle -->
                            <div class="card border p-3 mb-4 rounded-3">
                                <h5 class="fw-bold mb-1">Business Account Toggle</h5>
                                <p class="text-muted fs-sm mb-3">Current Status: <strong>{{ $info->is_business ? 'Business Account' : 'Regular User' }}</strong></p>
                                <form action="{{ route('admin.toggle.business') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $info->id }}">
                                    <button type="submit" class="btn btn-outline-info">
                                        <i class="fa fa-briefcase me-1"></i> Toggle Business Account Mode
                                    </button>
                                </form>
                            </div>

                            <!-- Blacklist / Ban -->
                            <div class="card border border-danger-subtle p-3 rounded-3 bg-danger-light">
                                <h5 class="fw-bold text-danger mb-1">Account Suspension</h5>
                                <p class="text-muted fs-sm mb-3">Suspending an account revokes access to payouts, tasks, and referrals.</p>
                                @if(!$info->is_blacklisted)
                                    <a class="btn btn-danger" href="{{ url('admin/blacklist/' . $info->id) }}"
                                       onclick="return confirm('Are you sure you want to BLACKLIST this user?')">
                                        <i class="fa fa-ban me-1"></i> Blacklist / Suspend User
                                    </a>
                                @else
                                    <span class="badge bg-danger py-2 px-3 fs-sm"><i class="fa fa-ban me-1"></i> Account is Currently Blacklisted</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('script')
<script>
function toggleMethodFields(method) {
    const bankGroup = document.getElementById('bankSelectGroup');
    const momoGroup = document.getElementById('momoSelectGroup');
    const bankSelect = document.getElementById('bankCodeSelect');
    const momoSelect = document.getElementById('momoNetworkSelect');
    const numLabel = document.getElementById('accountNumberLabel');

    if (method === 'mobile_money') {
        if (bankGroup) bankGroup.classList.add('d-none');
        if (momoGroup) momoGroup.classList.remove('d-none');
        if (bankSelect) { bankSelect.disabled = true; bankSelect.required = false; }
        if (momoSelect) { momoSelect.disabled = false; momoSelect.required = true; }
        if (numLabel) numLabel.innerHTML = 'Mobile Money Phone Number <span class="text-danger">*</span>';
    } else {
        if (bankGroup) bankGroup.classList.remove('d-none');
        if (momoGroup) momoGroup.classList.add('d-none');
        if (bankSelect) { bankSelect.disabled = false; bankSelect.required = true; }
        if (momoSelect) { momoSelect.disabled = true; momoSelect.required = false; }
        if (numLabel) numLabel.innerHTML = 'Account Number <span class="text-danger">*</span>';
    }
}

function toggleQuickMethodFields(method) {
    const bankGroup = document.getElementById('quickBankSelectGroup');
    const momoGroup = document.getElementById('quickMomoSelectGroup');
    const bankSelect = document.getElementById('quickBankCodeSelect');
    const momoSelect = document.getElementById('quickMomoNetworkSelect');
    const numLabel = document.getElementById('quickAccountNumberLabel');

    if (method === 'mobile_money') {
        if (bankGroup) bankGroup.classList.add('d-none');
        if (momoGroup) momoGroup.classList.remove('d-none');
        if (bankSelect) { bankSelect.disabled = true; bankSelect.required = false; }
        if (momoSelect) { momoSelect.disabled = false; momoSelect.required = true; }
        if (numLabel) numLabel.innerHTML = 'Mobile Money Phone Number <span class="text-danger">*</span>';
    } else {
        if (bankGroup) bankGroup.classList.remove('d-none');
        if (momoGroup) momoGroup.classList.add('d-none');
        if (bankSelect) { bankSelect.disabled = false; bankSelect.required = true; }
        if (momoSelect) { momoSelect.disabled = true; momoSelect.required = false; }
        if (numLabel) numLabel.innerHTML = 'Account / Phone Number <span class="text-danger">*</span>';
    }
}

function toggleQuickEditMethodFields(method) {
    const bankGroup = document.getElementById('quickEditBankSelectGroup');
    const momoGroup = document.getElementById('quickEditMomoSelectGroup');
    const bankSelect = document.getElementById('quickEditBankCodeSelect');
    const momoSelect = document.getElementById('quickEditMomoNetworkSelect');
    const numLabel = document.getElementById('quickEditAccountNumberLabel');

    if (method === 'mobile_money') {
        if (bankGroup) bankGroup.classList.add('d-none');
        if (momoGroup) momoGroup.classList.remove('d-none');
        if (bankSelect) { bankSelect.disabled = true; bankSelect.required = false; }
        if (momoSelect) { momoSelect.disabled = false; momoSelect.required = true; }
        if (numLabel) numLabel.innerHTML = 'Mobile Money Phone Number <span class="text-danger">*</span>';
    } else {
        if (bankGroup) bankGroup.classList.remove('d-none');
        if (momoGroup) momoGroup.classList.add('d-none');
        if (bankSelect) { bankSelect.disabled = false; bankSelect.required = true; }
        if (momoSelect) { momoSelect.disabled = true; momoSelect.required = false; }
        if (numLabel) numLabel.innerHTML = 'Account / Phone Number <span class="text-danger">*</span>';
    }
}

function attachAutoResolver(bankSelectId, accountNumberId, accountNameId, statusElementId, getMethodFn) {
    const bankSelect = document.getElementById(bankSelectId);
    const accInput = document.getElementById(accountNumberId);
    const nameInput = document.getElementById(accountNameId);
    const statusEl = document.getElementById(statusElementId);
    if (!accInput || !nameInput) return;

    let debounceTimer = null;

    function triggerLookup() {
        const method = typeof getMethodFn === 'function' ? getMethodFn() : 'bank';
        if (method === 'mobile_money') {
            if (statusEl) {
                statusEl.innerHTML = '<span class="text-muted"><i class="fa fa-info-circle me-1"></i> Mobile Money accounts use the registered subscriber name.</span>';
            }
            return;
        }

        const bankCode = bankSelect ? bankSelect.value : '';
        const accNum = accInput.value.trim();
        const userCurrency = '{{ $userCurr }}';
        const minLength = userCurrency === 'NGN' ? 10 : 6;

        if (!bankCode || accNum.length < minLength) {
            if (statusEl) {
                statusEl.innerHTML = '<span class="text-muted"><i class="fa fa-info-circle me-1"></i> Enter bank and account number to auto-resolve account name.</span>';
            }
            return;
        }

        if (statusEl) {
            statusEl.innerHTML = '<span class="text-primary"><i class="fa fa-spinner fa-spin me-1"></i> Resolving account name with Flutterwave...</span>';
        }

        fetch('{{ route("validate.bank") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                account_number: accNum,
                bank_code: bankCode,
                currency: userCurrency,
                method: method
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.account_name) {
                nameInput.value = data.account_name;
                if (statusEl) {
                    statusEl.innerHTML = '<span class="text-success fw-bold"><i class="fa fa-check-circle me-1"></i> Resolved Name: ' + data.account_name + '</span>';
                }
            } else {
                if (statusEl) {
                    statusEl.innerHTML = '<span class="text-warning"><i class="fa fa-exclamation-triangle me-1"></i> ' + (data.message || 'Could not auto-resolve name. You may enter it manually.') + '</span>';
                }
            }
        })
        .catch(err => {
            if (statusEl) {
                statusEl.innerHTML = '<span class="text-muted"><i class="fa fa-info-circle me-1"></i> Auto-resolution unavailable. You can enter the name manually.</span>';
            }
        });
    }

    if (bankSelect) {
        bankSelect.addEventListener('change', triggerLookup);
    }

    accInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const userCurrency = '{{ $userCurr }}';
        const triggerDelay = (userCurrency === 'NGN' && accInput.value.trim().length === 10) ? 100 : 500;
        debounceTimer = setTimeout(triggerLookup, triggerDelay);
    });

    accInput.addEventListener('blur', triggerLookup);
}

document.addEventListener('DOMContentLoaded', function () {
    // 1. Quick Add Form
    attachAutoResolver(
        'quickBankCodeSelect',
        'quickAccountNumberInput',
        'quickAccountNameInput',
        'quickAccountNameStatus',
        function () {
            const el = document.querySelector('input[name="method"]:checked');
            return el ? el.value : 'bank';
        }
    );

    // 2. Quick Edit Form
    attachAutoResolver(
        'quickEditBankCodeSelect',
        'quickEditAccountNumberInput',
        'quickEditAccountNameInput',
        'quickEditAccountNameStatus',
        function () {
            const el = document.querySelector('#quickEditPayoutCard input[name="method"]:checked');
            return el ? el.value : 'bank';
        }
    );

    // 3. Tab Bank Form
    attachAutoResolver(
        'bankCodeSelect',
        'accountNumberInput',
        'accountNameInput',
        'accountNameStatus',
        function () {
            const el = document.querySelector('#tab-bank input[name="method"]:checked');
            return el ? el.value : 'bank';
        }
    );
});
</script>
@endsection
