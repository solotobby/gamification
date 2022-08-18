@extends('layouts.main.master')
@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Get Verified</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">Verification</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <!-- END Hero -->


  <div class="content">
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Get verified</h3>
      </div>
      <div class="block-content">
        <div class="row items-push">
          <div class="col-md-12">
            <!-- Simple -->
            <p class="text-muted mb-2">
                With just N500, your account is verified and that simply gives you <br>
                (a) unlimited access to available jobs<br>
                 (b) post jobs to hire workers<br>
                 (c) access to withdraw all your earnings and other juicy offers coming up shortly.
                 <br>
            </p> 
            @if(auth()->user()->is_verified == '0')
            <a href="{{ route('make.payment') }}" class="btn btn-primary"> <i class="fa fa-share"></i> Get Verified </a>
            @else
            <a href="#" class="btn btn-primary disabled"> <i class="fa fa-share"></i>  Verification Completed</a>
            @endif
            </div>
        </div>
      </div>
    </div>
  </div>

@endsection