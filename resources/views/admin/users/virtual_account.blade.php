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
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
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
                    <th></th>
                    </tr>
            </thead>
            <tbody>
                {{-- <?php $i = 1; ?> --}}
                @foreach ($virtual as $user)
                    <tr>
                        <td><a href="{{ url('user/'.$user->user_id.'/info') }}" target="_blank"> {{ $user->user->name }} </a></td>
                        <td>{{ $user->user->phone }}</td>
                        <td>{{ $user->account_number }}</td>
                        <td>{{ $user->customer_id}}</td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</td>
                        <td>
                            @if($user->account_number == null)
                            {{-- <button type="button" class="btn btn-alt-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-edit-naira-{{ $user->id }}">Actvate VA</button> --}}
                            <a href="{{ url('reactivate/virtual/account/'.$user->id) }}" class="btn btn-success btn-sm">Activate VA</a>
                            @else
                            <a href="#" @disabled(true)> Okay</a>
                            @endif
                        </td>
                         <td class="fw-semibold">
                            @if($user->account_number == null)
                            {{-- <button type="button" class="btn btn-alt-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-edit-naira-{{ $user->id }}">Actvate VA</button> --}}
                            <a href="{{ url('remove/virtual/account/'.$user->id) }}" class="btn btn-danger btn-sm"> Remove VA</a>
                            @else
                            <a href="#" @disabled(true)> Okay</a>
                            @endif
                           
                        </td>
                    
                    </tr>


                    <div class="modal fade" id="modal-default-popout-edit-naira-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title">Edit SubCategories(Naira)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
        
                            <div class="modal-body pb-1">
                                <div class="col-xl-12">
                                    <!-- With Badges -->
                                    <div class="block block-rounded">
                                      <div class="block-header block-header-default">
                                        <h3 class="block-title">{{ $user->name }}</h3>
                                      </div>
                                      <div class="block-content">
                                        <form action="{{ url('reactivate/virtual/account') }}" method="POST">
                                          @csrf
                                           

                                            <?php 
                                            @$name = $user->bankInformation->name;
                                            @$splitedName = explode(" ", $name);
                                            @$fname = $splitedName[0];
                                            @$lname = $splitedName[1];
                                            
                                            ?>
                                            <div class="mb-4">
                                                <label class="form-label" for="example-text-input">First Name</label>
                                                <input type="text" name="first_name" class="form-control" value="{{  @$fname = $splitedName[0] }}" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label" for="example-text-input">Last Name</label>
                                                <input type="text" name="last_name" class="form-control" value="{{  @$lname = $splitedName[1] }}" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label" for="example-text-input">Number</label>
                                                <input type="text" name="phone_number" class="form-control" value="{{ $user->user->phone }}" required>
                                            </div>
                                            <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                                           
                                      
                                        <button class="btn btn-primary" type="submit">Update VA</button>
                                        </form>
                                      </div>
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