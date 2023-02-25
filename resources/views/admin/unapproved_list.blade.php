@extends('layouts.main.master')
@section('style')
{{-- <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}"> --}}

@endsection

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">UnApproved</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">UnApproved</li>
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
        <h3 class="block-title">UnApproved Campaigns - {{ $campaigns->count() }}</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        
        <div class="table-responsive">
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


            <form action="{{ url('mass/approval') }}" method="POST">
                @csrf
          <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Camp. Name</th>
                    <th>Worker</th>
                    <th>Poster</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>When Created</th>
                    </tr>
            </thead>
            <tbody>
                
                <?php $i = 1; ?>
                @foreach ($campaigns as $list)
                    <tr>
                        <th scope="row"><input type="checkbox" name="id[]" value="{{ $list->id }}"></th>
                        <td>{{ $list->campaign->post_title }}</td>
                        <td>{{ $list->user->name }}</td>
                        <td>{{ @$list->campaign->user->name }}</td>
                        <td>&#8358;{{ number_format(@$list->amount) }}</td>
                        <td>{{ $list->status }}</td>
                        <td>{{ \Carbon\Carbon::parse($list->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                    </tr>
                @endforeach
              
            </tbody>
          </table>
          <button class="btn btn-primary mb-2" type="submit">Approve All</button>
            </form>
        </div>
      </div>
    </div>
    <!-- END Full Table -->

  </div>
@endsection

@section('script')

{{-- <!-- jQuery (required for DataTables plugin) -->
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
<script src="{{asset('src/assets/js/pages/be_tables_datatables.min.js')}}"></script> --}}
@endsection