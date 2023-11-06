@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Users</h1>
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
        <h3 class="block-title">Users List - {{ $virtual->count() }}</h3>
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
        <div class="table-responsive">
          {{-- <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons"> --}}
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
                <tr>
                    
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Account Number</th>
                    <th>customerCode</th>
                    <th>When Created</th>
                    <th></th>
                    </tr>
            </thead>
            <tbody>
                {{-- <?php $i = 1; ?> --}}
                @foreach ($virtual as $user)
                    <tr>
                        
                        <td>{{ $user->user->name }}</td>
                        <td>{{ $user->user->phone }}</td>
                        <td>{{ $user->account_number }}</td>
                        <td>{{ $user->customer_id}}</td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</td>
                         <td class="fw-semibold">
                            @if($user->account_number == null)
                            <a href="{{ url('reactivate/virtual/account/'.$user->user_id) }}" class="btn btn-success btn-sm"> Activate VA</a>
                            @else
                            <a href="#" @disabled(true)> Okay</a>
                            @endif
                        </td>
                    
                    </tr>
                @endforeach
            </tbody>
          </table>
          <div class="d-flex">
            {{-- {!! $users->links('pagination::bootstrap-4') !!} --}}
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