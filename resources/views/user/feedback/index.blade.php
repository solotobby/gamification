@extends('layouts.main.master')
@section('content')
<div class="bg-body-light">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
      <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Feedback</h1>
      <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
        {{-- <ol class="breadcrumb">
          <li class="breadcrumb-item">Feedback</li>
          <li class="breadcrumb-item active" aria-current="page"></li>
        </ol> --}}
      </nav>
    </div>
  </div>
</div>

<div class="content">
  {{-- <h2 class="content-heading">Basic <small>Animate elements and make them visible on scrolling</small></h2> --}}
  <div class="row">
  
    <div class="col-sm-12">
      <div class="block block-rounded">
        <div class="block-content block-content-full">
          <div class="py-5 text-center">
            <div class="item item-2x item-circle bg-success text-white mx-auto">
              <i class="fa fa-2x fa-phone"></i>
            </div>
            <div class="fs-4 fw-semibold pt-3 mb-0">Click the button below to contact Freebyz Customer Center</div>
            <br>
            <a href="https://api.whatsapp.com/send/?phone=13074170027&text&type=phone_number&app_absent=0" class="btn btn-primary btn-sm" target="_blank"> <li class="fab fa-whatsapp"> </li> Contact Freebyz </a>
          </div>
        </div>
      </div>
    </div>
    
  </div>

</div>

  <!-- Page Content -->
  {{-- <div class="row g-0 flex-md-grow-1">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="content">
          <a href="{{ url('feedback/create')}}" type="button" class="btn w-35 btn-alt-primary mb-3">
            <i class="fa fa-plus opacity-50 me-1"></i> New Ticket
          </a>
            <div class="list-group fs-sm mb-3">
                @foreach ($feedbacks as $feedback)
                    <a class="list-group-item list-group-item-action" href="{{url('feedback/view/'.$feedback->id)}}">
                     
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
  </div> --}}
   
  <!-- END Page Content -->
@endsection
@section('script')

<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Page JS Helpers (CKEditor 5 plugins) -->
<script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>
 
@endsection