@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Safelock Mgt</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Safelock</li>
            <li class="breadcrumb-item active" aria-current="page">List</li>
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
        <h3 class="block-title">List - {{ $safelocks->count() }} | Total Locked Fund - &#8358;{{ number_format($safelocks->sum('amount_locked')) }} | Total Expected Payout - &#8358;{{ number_format($safelocks->sum('total_payment')) }}</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        {{-- <form action="{{ url('users') }}" method="GET">
          <div class="mb-4">
            <div class="input-group">
              <input type="text" class="form-control" id="example-group3-input1" name="search" value="{{ old('search') }}" placeholder="Search Name, Phone, Email or Referral code" required>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-search me-1"></i> Search
              </button>
            </div>
          </div>
        </form> --}}
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
            
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Amount Locked</th>
                      <th>Interest</th>
                      <th>Duration</th>
                      <th>Total PayOut</th>
                      <th>DateCreated</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                <?php $i = 1; ?>
                @foreach ($safelocks as $lock)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td><a href="{{ url('user/'.$lock->user->id.'/info') }}" target="_blank"> {{ $lock->user->name }}</a></td>
                    <td>&#8358;{{ number_format($lock->amount_locked) }}</td>
                    <td>
                      {{ number_format($lock->interest_rate) }}%
                    </td>
                    <td>
                      {{ ($lock->duration) }} Months
                    </td>
                    <td>
                      &#8358;{{ number_format($lock->total_payment) }}
                    </td>
                    <td>
                      {{$lock->created_at }}
                    </td>
                   
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
                                      <?php 
                                        $maturity = $lock->maturity_date;
                                        $current = now();
                                      ?>
                                      @if($current >= $maturity)

                                          @if($lock->status == 'Redeemed')
                                              <button class="btn btn-success" disabled>Safelock Redeemed</button>
                                          @else
                                          <a href="{{ url('admin/safelock/'.$lock->id) }}" class="btn btn-primary">Update withdrawal </a>
                                              {{-- <form action="{{ url('redeem/safelock') }}" method="POST">
                                                @csrf()
                                              <input type="hidden" name="id" value="{{ $lock->id }}"> --}}
                                              {{-- <button class="btn btn-primary" type="submit">Eligible for withdrawal</button> --}}
                                              {{-- </form> --}}
                                          @endif

                                      @else
                                          <button class="btn btn-primary" disabled>Withdraw to Account</button>
                                      @endif

                                  
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
    <!-- END Full Table -->

  </div>
@endsection

@section('script')

<!-- jQuery (required for DataTables plugin) -->
<script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>

<!-- Page JS Plugins -->
<script src="{{asset('src/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-jszip/jszip.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.print.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.html5.min.js')}}"></script>

<!-- Page JS Code -->
<script src="{{asset('src/assets/js/pages/be_tables_datatables.min.js')}}"></script>
@endsection