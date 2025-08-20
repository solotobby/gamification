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
            <li class="breadcrumb-item">Campaigns</li>
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
        <h3 class="block-title">Campaign List - {{ $campaigns->count() }}</h3> <small>Same list as what users can see</small>
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
          {{-- <table class="table table-bordered table-striped table-vcenter"> --}}
          <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Creator</th>
                    <th>Name</th>
                    <th>Staffs</th>
                    <th>Completed</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Complete?</th>
                    <th>Priotize</th>
                    <th>When Created</th>
                    </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($campaigns as $camp)
                    <tr>
                        <th scope="row">{{ $camp->job_id }}.</th>
                        <td class="fw-semibold"><a href="{{ url('campaign/info/'.$camp->id) }}" target="_blank"> {{$camp->post_title }}</a></td>
                        <td><a href="{{ url('user/'.$camp->user->id.'/info') }}"> {{ $camp->user->name }} </td>
                        <td>{{ $camp->pending_count }}/{{ $camp->number_of_staff }} </td>
                        <td>{{ $camp->completed_count }}/{{ $camp->number_of_staff }} </td>
                        {{-- completed()->where('status', 'Approved')->count() --}}
                        <td>
                          @if($camp->currency == 'NGN')
                          &#8358;{{ number_format($camp->campaign_amount) }} 
                          @elseif($camp->currency == 'USD')
                          ${{ $camp->campaign_amount }} 
                          @else
                           {{ $camp->currency }} {{ $camp->campaign_amount }} 
                          @endif
                        </td>
                        <td>
                          @if($camp->currency == 'NGN')
                            &#8358;{{ number_format($camp->total_amount) }} 
                          @elseif($camp->currency == 'USD')
                            ${{ $camp->campaign_amount }} 
                          @else
                            {{ $camp->currency }} {{ $camp->total_amount }} 
                          @endif
                        </td>
                        <td>
                           <a href="{{ url('change/completed/'.$camp->id) }}"> {{$camp->is_completed == true ? 'YES' : 'NO' }}</a>
                        </td>
                        <td>
                          <a href="{{ url('priotize/'.$camp->id) }}"> {{$camp->approved == 'Pending' ? 'Unpriotized' : 'Priotized' }}</a>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($camp->created_at)->format('d/m/Y @ h:i:s a') }}</td>
                    </tr>
                @endforeach
              
            </tbody>
          </table>
          <div class="d-flex">
            {!! $campaigns->links('pagination::bootstrap-4') !!}
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