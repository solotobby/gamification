@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">User Tracker</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">User Tracker</li>
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
        <h3 class="block-title">List</h3>
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
                    
                    <th>Name</th>
                    <th>Type</th>
                    <th>Ip</th>
                    <th>Country</th>
                    <th>Region</th>
                    <th>City</th>
                    <th>When</th>
                   
                    </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($userTracker as $tracker)
                    <tr>
                        <td class="fw-semibold"> <a href="{{ url('user/'.$tracker->user->id.'/info') }}"> {{$tracker->user->name }}</a></td>
                        <td>{{ $tracker->activity }}</td>
                        <td>{{ $tracker->ip }}</td>
                        <td>{{ $tracker->countryName }}</td>
                        <td>{{ $tracker->regionName}}</td>
                        <td>{{ $tracker->cityName }}</td>
                        <td>{{ \Carbon\Carbon::parse($tracker->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                    </tr>
                @endforeach
              
            </tbody>
          </table>
          <div class="d-flex">
            {!! $userTracker->links('pagination::bootstrap-4') !!}
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



<!-- Page JS Code -->
<script src="{{asset('src/assets/js/pages/be_tables_datatables.min.js')}}"></script>
@endsection