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
            <div class="list-group fs-sm mb-3">
                @foreach ($feedbacks as $feedback)
                    <a class="list-group-item list-group-item-action" href="{{url('feedback/view/'.$feedback->id)}}">
                      {{-- Number of unread messages --}}
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