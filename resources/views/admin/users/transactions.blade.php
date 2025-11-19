@extends('layouts.main.master')
@section('style')
<style>
    #loader {
        display: none;
        text-align: center;
        padding: 20px;
    }
    .transaction-row.credit {
        color: forestgreen;
    }
    .transaction-row.debit {
        color: chocolate;
    }
</style>
@endsection

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Transaction List</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Users</li>
                        <li class="breadcrumb-item active" aria-current="page">Transaction List</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="content">
        <!-- Full Table -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Transaction List for <i>{{ $user->name }}</i></h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-settings"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <!-- Search Box -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="searchInput" class="form-control"
                               placeholder="Search by reference, type, description...">
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge bg-info">Total: <span id="totalCount">0</span> transactions</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Action</th>
                                <th>Balance</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTable">
                            <!-- Transactions will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <div id="loader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Loading more transactions...</p>
                </div>

                <div id="noMoreData" style="display: none; text-align: center; padding: 20px;">
                    <p class="text-muted">No more transactions to load</p>
                </div>
            </div>
        </div>
        <!-- END Full Table -->
    </div>
@endsection

@section('script')
    <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>

    <script>
        let page = 1;
        let loading = false;
        let hasMore = true;
        let allTransactions = [];
        let filteredTransactions = [];
        const userId = {{ $user->id }};

        $(document).ready(function() {
            loadTransactions();

            // Infinite scroll
            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                    if (!loading && hasMore) {
                        loadTransactions();
                    }
                }
            });

            // Search functionality
            $('#searchInput').on('keyup', function() {
                const searchTerm = $(this).val().toLowerCase();

                if (searchTerm === '') {
                    filteredTransactions = [...allTransactions];
                } else {
                    filteredTransactions = allTransactions.filter(function(transaction) {
                        return transaction.reference.toLowerCase().includes(searchTerm) ||
                               transaction.type.toLowerCase().includes(searchTerm) ||
                               transaction.description.toLowerCase().includes(searchTerm) ||
                               transaction.amount.toString().includes(searchTerm);
                    });
                }

                renderTransactions(filteredTransactions, true);
            });
        });

        function loadTransactions() {
            if (loading || !hasMore) return;

            loading = true;
            $('#loader').show();

            $.ajax({
                url: '{{ route("admin.user.transactions.paginate", $user->id) }}',
                method: 'GET',
                data: {
                    page: page
                },
                success: function(response) {
                    if (response.data.length > 0) {
                        allTransactions = allTransactions.concat(response.data);
                        filteredTransactions = [...allTransactions];
                        renderTransactions(response.data, false);
                        page++;

                        if (!response.has_more) {
                            hasMore = false;
                            $('#noMoreData').show();
                        }
                    } else {
                        hasMore = false;
                        if (page === 1) {
                            $('#transactionTable').html('<tr><td colspan="9" class="text-center">No transactions found</td></tr>');
                        } else {
                            $('#noMoreData').show();
                        }
                    }

                    updateCount();
                    loading = false;
                    $('#loader').hide();
                },
                error: function() {
                    loading = false;
                    $('#loader').hide();
                    alert('Error loading transactions');
                }
            });
        }

        function renderTransactions(transactions, clearTable) {
            if (clearTable) {
                $('#transactionTable').empty();
            }

            $.each(transactions, function(index, transaction) {
                const rowClass = transaction.tx_type === 'Credit' ? 'credit' : 'debit';

                const actionHtml = (transaction.type === 'wallet_topup' || transaction.type === 'transfer_topup')
                    ? `<button class="btn btn-sm btn-primary verify-btn" data-id="${transaction.id}">Verify</button>
                       <span class="verify-status"></span>`
                    : '-';

                const row = `
                    <tr class="transaction-row ${rowClass}">
                        <td>${transaction.reference}</td>
                        <td>${transaction.type}</td>
                        <td>${transaction.amount}</td>
                        <td>${actionHtml}</td>
                        <td>${transaction.balance}</td>
                        <td>${transaction.currency}</td>
                        <td>${transaction.status}</td>
                        <td>${transaction.description}</td>
                        <td>${formatDate(transaction.created_at)}</td>
                    </tr>
                `;

                $('#transactionTable').append(row);
            });

            // Reattach verify button handlers
            attachVerifyHandlers();
        }

        function attachVerifyHandlers() {
            $('.verify-btn').off('click').on('click', function() {
                const btn = $(this);
                const id = btn.data('id');
                const statusSpan = btn.siblings('.verify-status');

                btn.prop('disabled', true).text('Verifying...');

                $.ajax({
                    url: '{{ url("admin/user/transactions/verify") }}/' + id,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.verified) {
                            statusSpan.html('<span class="badge bg-success">Verified</span>');
                            btn.remove();
                        } else {
                            statusSpan.html('<span class="badge bg-danger">Unverified</span>');
                            btn.prop('disabled', false).text('Verify');
                        }
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message || 'Verification failed';
                        alert('Error: ' + errorMessage);
                        btn.prop('disabled', false).text('Verify');
                    }
                });
            });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString();
        }

        function updateCount() {
            $('#totalCount').text(allTransactions.length);
        }
    </script>
@endsection
