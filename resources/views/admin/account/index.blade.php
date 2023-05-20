@extends('layouts.main.master')

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Create Transactions</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Accounts</li>
            <li class="breadcrumb-item active" aria-current="page">Transactions</li>
          </ol>
        </nav>
      </div>
    </div>
</div>

 <!-- Page Content -->
 <div class="content">

    <!-- Elements -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
          <h3 class="block-title">Freebyz Accounting System</h3>
        </div>

        <div class="block-content">
            <h5>Account Overview</h5>
            <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Type</th>
                    <th scope="col">Income (CR)</th>
                    <th scope="col">Expenses (DR)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>System</td>
                    <td>&#8358;{{ number_format($transactions->where('tx_type', 'Credit')->where('user_type', 'admin')->sum('amount')) }}</td>
                    <td>&#8358;{{ number_format($transactions->whereIn('type', ['databundle', 'cash_withdrawal', 'airtime_purchase'])->sum('amount')) }} (withdrawals, databundle, airtime) </td>
                  </tr>
                  {{-- <tr>
                    <th scope="row">1</th>
                    <td></td>
                    <td> {{ $transactions->where('tx_type', 'Credit')->where('user_type', 'admin')->sum('amount') }}</td>
                    <td> {{ $transactions->whereIn('type', ['databundle', 'cash_wthdrawal', 'airtime_purchase'])->sum('amount') }} (withdrawals, databundle, airtime) </td>
                  </tr> --}}
                </tbody>
              </table>
            <hr>
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('account.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row push">
                    <div class="col-lg-4">
                        <p class="text-muted">
                        Create Transactions
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input"> Transaction Type</label>
                            <select class="form-control" name="type" required>
                                <option value="">Select One</option>
                                <option value="Credit">Credit</option>
                                <option value="Debit">Debit</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Name</label>
                            <input type="text" class="form-control" id="example-text-input" name="name" placeholder="ASW Bill for April">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Amount</label>
                            <input type="number" class="form-control" id="example-text-input" name="amount" placeholder="50000">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Date</label>
                            <input type="date" class="form-control" id="example-text-input" name="date" placeholder="">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <input name="user_id" value="{{ auth()->user()->id }}" type="hidden">
                        <div class="mb-4">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


    </div>

 </div>

@endsection