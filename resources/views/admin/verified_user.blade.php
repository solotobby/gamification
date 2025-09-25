@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')
 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Verified Users</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active" aria-current="page">Users List</li>
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
        <h3 class="block-title">Verified Users List - {{ $verifiedUsers->total() }}</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-vcenter">
            {{-- <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons"> --}}
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    {{-- <th>Balance</th>--}}
                    {{-- <th>Ref. No</th>  --}}
                    {{-- <th>Status</th> --}}
                    <th>Reg. Channel</th>
                    <th>When Created</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($verifiedUsers as $user)
                    <tr>
                        <th scope="row">{{ $i++ }}.</th>
                        <td class="fw-semibold"><a href="{{ url('user/'.$user->id.'/info') }}" target="_blank"> {{$user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        {{-- <td>&#8358;{{ number_format(@$user->wallet->balance) }}</td> --}}
                        {{-- <td>{{ @$user->referees->count() }}</td>  --}}
                        {{-- <td>{{ $user->is_verified == "1" ? 'Verified' : 'unverified' }}</td> --}}
                        <td>{{ $user->source }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                    </tr>
                @endforeach
            </tbody>
          </table>
          <div class="d-flex">
            {!! $verifiedUsers->links('pagination::bootstrap-4') !!}
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
