@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Campaign List</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active" aria-current="page">Campaign List</li>
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
        <h3 class="block-title">Campaigns Created by <i>{{ $user->name }}</i> </h3>
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
                    <th>Job Id</th>
                    <th>Campaign Name</th>
                    <th>Staffs</th>
                    <th>Completed</th>
                    <th>Unit Amount</th>
                    <th>No. of Worker</th>
                    <th>Total Value</th>
                    <th>When Done</th>
                    {{-- <th></th> --}}
                  </tr>
                </thead>
                <tbody>
                    @foreach ($campaigns as $campaign)
                    <tr>
                      <td>{{ $campaign->job_id }} </td>
                        <td>
                          {{ @$campaign->post_title }}
                        </td>
                        <td>{{ @$campaign->pending_count }}/{{ @$campaign->number_of_staff }} </td>
                        <td>{{ @$campaign->completed_count }}/{{ @$campaign->number_of_staff }} </td>
                      
                        <td>
                            &#8358;{{ number_format(@$campaign->campaign_amount,2) }}
                        </td>
                        <td>
                            {{ @$campaign->number_of_staff }}
                        </td>
                        <td>
                            &#8358;{{ number_format(@$campaign->total_amount,2) }}
                        </td>
                        
                        <td>
                            {{ @$campaign->created_at }}
                        </td>
                        
                      </tr>
                    @endforeach
                  
                </tbody>
              </table>
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