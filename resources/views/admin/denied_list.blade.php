@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Declined Campaigns</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Campaigns</li>
            <li class="breadcrumb-item active" aria-current="page">Declined</li>
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
        <h3 class="block-title">Declined Campaigns - {{ $campaigns->count() }}</h3>
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

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Creator</th>
                    <th>Name</th>
                    <th>Number of Staff</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>When Created</th>
                    </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($campaigns as $camp)
                    <tr>
                        <th scope="row">{{ $i++ }}.</th>
                        <td class="fw-semibold"><a href="#" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $camp->id }}"> {{$camp->post_title }}</a></td>
                        <td>{{ $camp->user->name }}</td>
                        <td>{{ $camp->number_of_staff }}</td>
                        <td>&#8358;{{ number_format($camp->campaign_amount) }}</td>
                        <td>&#8358;{{ number_format($camp->total_amount) }}</td>
                        <td>{{ \Carbon\Carbon::parse($camp->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                    </tr>

                    <div class="modal fade" id="modal-default-popout-{{ $camp->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-popout" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                          <h5 class="modal-title">Info</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
  
                          <div class="modal-body pb-1">
                              <div class="col-xl-12">
                                  <!-- With Badges -->
                                  <div class="block block-rounded">
                                    <div class="block-header block-header-default">
                                      <h3 class="block-title">{{ $camp->post_title }}</h3>
                                    </div>

                                    <p>
                                      <h5>Description</h5>
                                      {!! $camp->description !!}
                                    </p>

                                    <p>
                                      <h5>Proof</h5>
                                      {!! $camp->proof !!}
                                    </p>

                                    <ul class="list-group push">
                                      
                                      <li class="list-group-item d-flex justify-content-between align-items-center">
                                          Category 
                                          <span class="badge rounded-pill bg-info">{{$camp->campaignType->name}}</span>
                                      </li>

                                      <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Sub Category 
                                        <span class="badge rounded-pill bg-info">{{$camp->campaignCategory->name}}</span>
                                     </li>

                                     <div class="mb-4 mt-4">
                                      {{-- <a href="{{ url('campaign/status/Live/'.$camp->id) }}" class="btn btn-alt-primary">Approve</a> --}}
                                     {{-- <a href="{{ url('campaign/status/Decline/'.$camp->id) }}" class="btn btn-alt-danger">Decline</a> --}}
                                     
                                      {{-- <button type="submit" name="action" value="approve" class="btn btn-success"><i class="fa fa-check"></i> Approve</button>
                                      <button type="submit" name="action" value="deny" class="btn btn-danger"><i class="fa fa-times"></i> Deny</button> --}}
                                     </div>
                                  </ul>
                                    


                                  </div>
                                  <!-- END With Badges -->
                                </div>
                              
                          </div>
                          
                          <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                          {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
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