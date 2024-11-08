@extends('layouts.main.master')

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">This Week Queued Withdrawal Request</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">This Week Queued Withdrawal Request</li>
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
        <h3 class="block-title">This Week Queued Withdrawal Request - &#8358;{{ number_format($withdrawals->where('status', false)->sum('amount')) }}</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
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

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
                <tr>
                    {{-- <th>USD</th> --}}
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>View</th>
                    <th>Date Rquested</th>
                    <th>Liq. Date</th>
                    </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($withdrawals as $with)
                    <tr>
                        {{-- <td>{{ $with->user->is_usd == true ? 'USD' : 'Naira' }} - {{ $with->paypal_email }}</td> --}}
                        <td class="fw-semibold"> 
                          
                          @if($with->is_usd == true)
                                ${{ number_format($list->amount) }}
                              @elseif($with->base_currency == null)

                              <a href="" data-bs-toggle="modal" data-bs-target="#modal-default-popout-upgrade-{{ $with->id }}"> {{$with->user->name }}</a>

                              @else
                             
                              <a href="" data-bs-toggle="modal" data-bs-target="#modal-default-popout-upgrade-other-{{ $with->id }}"> {{$with->user->name }}</a>
                              @endif
                        </td>
                        <td>{{ $with->user->email }}</td>
                        <td>{{ $with->user->phone }}</td>
                        <td>
                          {{-- &#8358;{{ number_format(@$with->amount) }} --}}

                              @if($with->is_usd == true)
                                ${{ number_format($list->amount) }}
                              @elseif($with->base_currency == null)

                                &#8358;{{ number_format($with->amount) }}

                              @else
                                {{$with->base_currency}} {{ number_format($with->amount) }}
                              @endif

                        </td>
                        <td>{{ $with->status == '1' ? 'Sent' : 'Queued'}}</td>
                        <td><a href="{{ url('user/'.@$with->user->id.'/info') }}" target="_blank" class="btn btn-primary btn-sm">User</a></td>
                        <td>{{ \Carbon\Carbon::parse($with->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                        <td>{{ \Carbon\Carbon::parse($with->next_payment_date)->diffForHumans() }}</td>
                    </tr>


                    <div class="modal fade" id="modal-default-popout-upgrade-{{ $with->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-popout" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                          <h5 class="modal-title">Bank Account Information</h5> 
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
          
                          <div class="modal-body pb-1">
                             
                             
                              <div class="block-content">
                                <ul class="list-group push">
                                  <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                    Bank Name 
                                     <span class="badge rounded-pill bg-info">{{ @$with->user->accountDetails->bank_name }} </span>
                                    
                                   </li>
                                   <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                    Account Name
                                     <span class="badge rounded-pill bg-info">{{ @$with->user->accountDetails->name }} </span>
                                    
                                   </li>
                                   <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                    Account Number
                                     <span class="badge rounded-pill bg-info">{{ @$with->user->accountDetails->account_number }} </span>
                                    
                                   </li>

                                   <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                    Amount
                                     <span class="badge rounded-pill bg-info">&#8358;{{ number_format($with->amount) }} </span>
                                    
                                   </li>
                                </ul>
                              </div>
                              
                          </div>
                          
                          <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                          @if($with->status != '1')
                              
                                @if(@$with->user->accountDetails->bank_name == '')
                                  {{-- <a href="{{ url('update/withdrawal/manual/'.$with->id) }}" class="btn btn-sm btn-primary">Manual Approval</a> --}}
                                @else
                                  {{-- <a href="{{ url('update/withdrawal/'.$with->id) }}" class="btn btn-sm btn-primary">Approve</a> --}}
                                @endif

                                <a href="{{ url('update/withdrawal/manual/'.$with->id) }}" class="btn btn-sm btn-secondary">Update Approval</a>
                              
                          @else
                          <a href="#" class="btn btn-sm btn-success diasbled">Approved</a>
                          @endif
                          </div>
                      </div>
                      </div>
                  </div>

                  <div class="modal fade" id="modal-default-popout-upgrade-other-{{ $with->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title">Payment Information</h5> 
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
        
                        <div class="modal-body pb-1">
                           
                            <div class="block-content">
                              <ul class="list-group push">
                               

                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                  Beneficiary Name
                                   <span class="badge rounded-pill bg-info">dsdfd </span>
                                </li>

                                 

                             <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                  Account Name
                                   <span class="badge rounded-pill bg-info">{{ @$with->user->accountDetails->name }} </span>
                                  
                                 </li>
                                 <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                  Account Number
                                   <span class="badge rounded-pill bg-info">{{ @$with->user->accountDetails->account_number }} </span>
                                  
                                 </li>

                                 <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                  Amount
                                   <span class="badge rounded-pill bg-info">&#8358;{{ number_format($with->amount) }} </span>
                                  
                                 </li>

                              </ul>
                            </div>
                            
                        </div>
                        
                        <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                        {{-- <a href="{{ url('update/withdrawal/'.$with->id) }}" class="btn btn-sm btn-primary">Approve Payment</a> --}}
                         @if($with->status != '1')
                                
                                  {{-- @if(@$with->user->accountDetails->bank_name == '')
                                    <a href="{{ url('update/withdrawal/manual/'.$with->id) }}" class="btn btn-sm btn-primary">Manual Approval</a>
                                  @else
                                    <a href="{{ url('update/withdrawal/'.$with->id) }}" class="btn btn-sm btn-primary">Approve</a>
                                  @endif --}}

                                  <a href="{{ url('update/withdrawal/manual/'.$with->id) }}" class="btn btn-sm btn-secondary">Update Approval</a>
                                
                            @else
                                <a href="#" class="btn btn-sm btn-success diasbled">Approved</a>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
                @endforeach
              
            </tbody>
          </table>
          <div class="d-flex">
            {!! $withdrawals->links('pagination::bootstrap-4') !!}
          </div>
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
{{-- <script src="{{asset('src/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-jszip/jszip.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.print.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.html5.min.js')}}"></script> --}}

<!-- Page JS Code -->
<script src="{{asset('src/assets/js/pages/be_tables_datatables.min.js')}}"></script>
@endsection