@extends('layouts.main.master')

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Safelock Funds</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Safelock Funds</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <!-- Page Content -->
  <div class="content">
    <div class="block block-rounded">
        <div class="block-content">
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
            <div class="alert alert-info">
              Lock funds to earn up to 5% interest in 3-6 months.
              Your funds are 100% safe  and secure. Your interest and capital will be paid to your local bank account at maturity.
            </div>
        <form action="{{ url('safelock') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row push">
                <div class="col-lg-4">
                    <p class="text-muted">
                    Lock minimum of &#8358;1,000 with an interest of 5% between 3 to 6 months
                    </p>
                </div>
                <div class="col-lg-8 col-xl-5">
                    <div class="mb-4">
                        <label class="form-label" for="example-text-input">Amount</label>
                        <input type="number" class="form-control" min="1000" name="amount" placeholder="1000">
                    </div>
                    <div class="mb-4">
                      <label class="form-label" for="example-text-input">Duration</label>
                      <select name="duration" class="form-control" required>
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                      </select>
                  </div>
                    <div class="mb-4">
                      <label class="form-label" for="example-text-input">Source of Fund</label>
                      <select name="source" class="form-control" required>
                        <option value="wallet">Wallet Balance</option>
                        {{-- <option value="paystack">Paystack</option> --}}
                      </select>
                    </div>
                    <div class="mb-4">
                        <button class="btn btn-primary" type="submit">SafeLock Funds</button>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
    <!-- Full Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Safelock List</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Amount Locked</th>
                      <th>Interest</th>
                      <th>Duration</th>
                      <th>Total PayOut</th>
                      <th>Maturity</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                <?php $i = 1; ?>
                @foreach ($safelocks as $lock)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>&#8358;{{ number_format($lock->amount_locked,2) }}</td>
                    <td>
                      {{ number_format($lock->interest_rate) }}%
                    </td>
                    <td>
                      {{ ($lock->duration) }} Months
                    </td>
                    <td>
                      &#8358;{{ number_format($lock->total_payment,2) }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($lock->maturity_date)->format('d F, Y') }}</td>
                    <td> <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $lock->id }}">View Info.</button></td>
                </tr>

                <div class="modal fade" id="modal-default-popout-{{ $lock->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title">SafeLock Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body pb-1">
                            <div class="col-xl-12">
                              
                                <div class="block block-rounded">
                                  <div class="block-content">
                                    
                                    <ul class="list-group push">
                                      <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Amount Locked
                                          <span class="badge rounded-pill bg-info">&#8358;{{ number_format($lock->amount_locked,2) }}</span>
                                        </li>  
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Duration
                                          <span class="badge rounded-pill bg-info">{{ number_format($lock->duration) }} Months</span>
                                        </li> 
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Interest Rate
                                          <span class="badge rounded-pill bg-info">{{ number_format($lock->interest_rate) }}%</span>
                                        </li> 
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Accrued Interest
                                          <span class="badge rounded-pill bg-info">&#8358;{{ number_format($lock->interest_accrued,2) }}</span>
                                        </li> 
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Total Payout
                                          <span class="badge rounded-pill bg-info">&#8358;{{ number_format($lock->total_payment,2) }}</span>
                                        </li>  
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Start Date
                                          <span class="badge rounded-pill bg-info">{{ \Carbon\Carbon::parse($lock->start_date)->format('d F, Y') }}</span>
                                        </li> 
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Maturity Date
                                          <span class="badge rounded-pill bg-info">{{ \Carbon\Carbon::parse($lock->maturity_date)->format('d F, Y') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Status
                                          <span class="badge rounded-pill bg-info">{{ $lock->status }}</span>
                                        </li>    
                                    </ul>
                                    <button class="btn btn-primary" disabled>Withdraw to Account</button>
                                  </div>
                                </div>
                              </div>
                            
                        </div>
                        
                        <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                    </div>
                </div>
                @endforeach 
              </tbody>
            </table>
        </div>
      </div>
    </div>



@endsection