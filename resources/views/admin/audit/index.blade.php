@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">

@endsection

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Audit Trail</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Audit Trail</li>
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
        <h3 class="block-title"></h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
        <form action="{{ url('audit/trail') }}" method="GET">
          <div class="mb-2 row">
            <div class="col-md-3">
                <label>Activity Type</label>
                <select name="activity_type" class="form-control" required>
                    <option value="login">account login</option>
                    <option value="account_creation">account creation</option>
                    <option value="google_account_creation">google account creation</option>
                    <option value="wallet_topup">wallet topup</option>
                    <option value="campaign_submission">campaign submission</option>
                    <option value="account_verification">account verification</option>
                    <option value="campaign_payment">campaign payment</option>
                    <option value="withdrawal_request">withdrawal request</option>
                    <option value="survey_points">survey points</option>
                    
                </select>
            </div>
            <div class="col-md-3">
                <label>User Type</label>
                <select name="user_type" class="form-control" required>
                    <option value="regular">Regular</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Start Date</label>
                <input type="date" class="form-control" id="example-group3-input1" name="start" value="{{ old('start') }}" required>
            </div>
            <div class="col-md-3">
                <label>End Date</label>
                <input type="date" class="form-control" id="example-group3-input1" name="end" value="{{ old('end') }}" required>
            </div>
            {{-- <div class="input-group">
              <input type="text" class="form-control" id="example-group3-input1" name="search" value="{{ old('search') }}" placeholder="Search Name, Phone, Email or Referral code" required>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-search me-1"></i> Search
              </button>
            </div> --}}
          </div>
          <button type="submit" class="btn btn-primary mb-3">
            <i class="fa fa-search me-1"></i> Search
          </button>
        </form>
        <div class="table-responsive">
          {{-- <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons"> --}}
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Activity</th>
                    <th>Description</th>
                    <th>User</th>
                    <th>When Created</th>
                    </tr>
            </thead>
            <tbody>
                {{-- <?php $i = 1; ?> --}}
                @foreach ($audits as $audit)
                    <tr>
                        <td class="fw-semibold"><a href="{{ url('user/'.$audit->user->id.'/info') }}" target="_blank"> {{@$audit->user->name }}</a></td>
                        <td>{{ $audit->activity_type}}</td>
                        <td>{{ $audit->description}}</td>
                        <td>{{ $audit->user_type }}</td>
                        <td>
                            {{-- {{ \Carbon\Carbon::parse($audit->created_at)->diffForHumans() }} --}}
                            {{ $audit->created_at }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
          <div class="d-flex">
            {!! $audits->links('pagination::bootstrap-4') !!}
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