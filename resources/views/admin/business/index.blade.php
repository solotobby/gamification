@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Business</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Business</li>
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
        <h3 class="block-title">List of Business</h3>
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
          <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
          {{-- <table class="table table-bordered table-striped table-vcenter"> --}}
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Business Name</th>
                    <th>Phone</th>
                    <th>Selection</th>
                    <th>Status</th>
                    <th></th>
                    {{-- <th>Date</th> --}}
                   
                    </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($business as $bus)
                  
                        <tr>
                            <th scope="row">{{ $i++ }}.</th>
                            {{-- <td class="fw-semibold"><a href="{{ url('staff/'.$staff->id.'/info') }}" target="_blank"> {{$staff->name }}</a></td> --}}
                            <td>{{ @$bus->user->name }}</td>
                            <td>{{ @$bus->business_name }}</td>
                            <td>{{ @$bus->business_phone }}</td>
                           
                            <td>{{ @$bus->is_live == false ? 'Not Selected' : 'Selected' }}</td>
                            <td>{{ @$bus->status }}</td>
                            <td><button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $bus->id }}">View</button></td>
                            {{-- <td>{{ \Carbon\Carbon::parse($bus->created_at)->format('d/m/Y') }}</td> --}}
                        </tr>

                        <div class="modal fade" id="modal-default-popout-{{ $bus->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popout" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title">{{ $bus->business_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
        
                                <div class="modal-body pb-1">
                                    <div class="col-xl-12">
                                        <!-- With Badges -->
                                        <div class="block block-rounded">
                                          <div class="block-header block-header-default">
                                            <h3 class="block-title"> <a href="{{ url('m/'.$bus->business_link) }}"> {{ url('m/'.$bus->business_link) }} </a></h3>
                                          </div>
                                          <div class="block-content">
                                            <ul class="list-group push">
                                                {{--  --}}
                                            
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                   Phone Number: {{$bus->business_phone}}
                                                </li>
                                             
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Description: {!! $bus->description !!}
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Facebook: {{ $bus->facebook_link == null ? 'NOT SET' :  $bus->facebook_link }}
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    X Link: {{ $bus->x_link == null ? 'NOT SET' :  $bus->x_link }}
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Tiktok Link: {{ $bus->tiktok_link == null ? 'NOT SET' :  $bus->tiktok_link }}
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                   Instagram Link: {{ $bus->instagram_link == null ? 'NOT SET' :  $bus->instagram_link }}
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                   Pinterest Link: {{ $bus->pinterest_link == null ? 'NOT SET' :  $bus->pinterest_link }}
                                                 </li>
                                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Status: {{ $bus->status }}
                                                 </li>
                                              
                                              
                                            </ul>
                                          </div>
                                        </div>
                                        <!-- END With Badges -->
                                      </div>
                                    
                                </div>
                                
                                <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                                @if($bus->status == 'PENDING')
                                    <a class="btn btn-sm btn-primary" href="{{ url('admin/status/'.$bus->business_link.'/ACTIVE') }}">Activate</a>
                                    <a class="btn btn-sm btn-danger" href="{{ url('admin/status/'.$bus->business_link.'/DENIED') }}">Deny</a>
                                @elseif($bus->status == 'ACTIVE')
                                    <a class="btn btn-sm btn-danger" href="{{ url('admin/status/'.$bus->business_link.'/DENIED') }}">Deny</a>
                                @else
                                    <a class="btn btn-sm btn-primary" href="{{ url('admin/status/'.$bus->business_link.'/ACTIVE') }}">Activate</a>
                                @endif
                                {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
                                </div>
                            </div>
                            </div>
                        </div>
                  
                @endforeach
            </tbody>
          </table>

          <a href="{{ route('random.business.selection') }}" class="btn btn-info mb-3">Random Selection</a>
          {{-- <div class="d-flex">
            {!! $users->links('pagination::bootstrap-4') !!}
          </div> --}}
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