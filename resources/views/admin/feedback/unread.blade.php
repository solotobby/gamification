@extends('layouts.main.master')
@section('style')
{{-- <link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}"> --}}
@endsection

@section('content')

 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Unread Feedbacks</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">Unread Feedbacks</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>


  <!-- Page Content -->
  <div class="content">
    
    <!-- Full Table -->
    {{-- <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Unread Feedback List</h3>
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
          <table class="table table-bordered table-striped table-vcenter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>When Created</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($feedbacks as $feedback)
                    <tr>
                        <th scope="row">{{ $i++ }}.</th>
                        <td>{{ $feedback->user->name }}</td>
                        <td>{{ $feedback->category }}</td>
                        <td>{{ $feedback->status == false ? 'Unread' : 'Read' }}/{{ $feedback->replies()->count() > 0 ? 'Replied' : 'Not Replied' }} </td>
                        
                        <td><a href="{{ url('admin/feedback/'.$feedback->id) }}" class="btn btn-primary btn-sm">View</a></td>
                        <td>{{ \Carbon\Carbon::parse($feedback->created_at)->format('d/m/Y @ h:i:sa') }}</td>
                        <td>{{ $feedback->created_at }}</td>
                    </tr>
                @endforeach
              
            </tbody>
          </table>
          <div class="d-flex">
            {!! $feedbacks->links('pagination::bootstrap-4') !!}
          </div>
        </div>


      </div>
    </div> --}}


    <div class="row g-0 flex-md-grow-1">
      <div class="col-md-12 col-lg-12 col-xl-12">
        
              <div class="list-group fs-sm mb-3">
                  @foreach ($feedbacks as $feedback)
                      <a class="list-group-item list-group-item-action" href="{{url('admin/feedback/'.$feedback->id)}}">
                       
                          <span class="badge rounded-pill bg-dark m-1 float-end">{{ $feedback->replies()->where('user_id', '!=', auth()->user()->id)->where('status', true)->count() }}</span>
                          <p class="fs-6 fw-bold mb-0">
                              Ticket #{{$feedback->id}}
                          </p>
                          <p class="text-muted mb-2">
                              {!! \Illuminate\Support\Str::words($feedback->message, 20) !!}
                          </p>
                          <p class="fs-sm text-muted mb-0">
                              <strong>{{$feedback->category}}</strong>, {{\Carbon\Carbon::parse($feedback->updated_at)->diffForHumans()}} 
                          </p>
                      </a>
                  @endforeach
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
{{-- <script src="{{asset('src/assets/js/pages/be_tables_datatables.min.js')}}"></script> --}}
@endsection