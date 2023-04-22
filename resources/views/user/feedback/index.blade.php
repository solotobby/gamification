@extends('layouts.main.master')
@section('content')
  <!-- Page Content -->
  <div class="row g-0 flex-md-grow-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
      <div class="content">
          <!-- New Message -->
          <a href="{{ url('feedback/create')}}" type="button" class="btn w-35 btn-alt-primary mb-3">
            <i class="fa fa-plus opacity-50 me-1"></i> New Ticket
          </a>
          <!-- END New Message -->
          <!-- Search Messages -->
          {{-- <form action="be_pages_generic_inbox.html" method="POST" onsubmit="return false;">
            <div class="mb-4">
              <div class="input-group">
                <input type="text" class="form-control border-0" placeholder="Search Messages..">
                <span class="input-group-text border-0 bg-body-extra-light">
                  <i class="fa fa-fw fa-search"></i>
                </span>
              </div>
            </div>
          </form> --}}
          <!-- END Search Messages -->

          <!-- Sorting/Filtering -->
          {{-- <div class="d-flex justify-content-between mb-2"> --}}
            {{-- <div class="dropdown">
              <button type="button" class="btn btn-sm btn-link fw-semibold dropdown-toggle" id="inbox-msg-sort" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sort by
              </button>
              <div class="dropdown-menu fs-sm" aria-labelledby="inbox-msg-sort">
                <a class="dropdown-item" href="javascript:void(0)">
                  <i class="fa fa-fw fa-sort-amount-down me-1"></i> Newest
                </a>
                <a class="dropdown-item" href="javascript:void(0)">
                  <i class="fa fa-fw fa-sort-amount-up me-1"></i> Oldest
                </a>
              </div>
            </div>
            <div class="dropdown">
              <button type="button" class="btn btn-sm btn-link fw-semibold dropdown-toggle" id="inbox-msg-filter" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Filter by
              </button>
              <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="inbox-msg-filter">
                <a class="dropdown-item active" href="javascript:void(0)">
                  <i class="fa fa-fw fa-asterisk me-1"></i> New
                </a>
                <a class="dropdown-item" href="javascript:void(0)">
                  <i class="fa fa-fw fa-archive me-1"></i> Archived
                </a>
                <a class="dropdown-item" href="javascript:void(0)">
                  <i class="fa fa-fw fa-times-circle me-1"></i> Deleted
                </a>
              </div>
            </div> --}}
          {{-- </div> --}}
          <!-- END Sorting/Filtering -->
          <!-- Messages -->
            <div class="list-group fs-sm mb-3">
                @foreach ($feedbacks as $feedback)
                    <a class="list-group-item list-group-item-action" href="{{url('feedback/view/'.$feedback->id)}}">
                        {{-- <span class="badge rounded-pill bg-dark m-1 float-end">3</span> --}}
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
            <!-- END Messages -->
        </div>
    </div>
   
  <!-- END Page Content -->
@endsection
@section('script')

<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Page JS Helpers (CKEditor 5 plugins) -->
<script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>
 
@endsection