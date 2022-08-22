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
     <!-- Special Offer -->
     <div class="bg-body-light">
      <div class="content content-boxed content-full">
        <div class="py-5">
          <h2 class="mb-2 text-center">
           Get Verified...
          </h2>
          <h3 class="fw-light text-muted push text-center">
            If you get gerified today you will get all the following at no extra cost.
          </h3>
        </div>
        <div class="row py-3">
          <div class="col-sm-6 col-md-4 mb-5">
            <div class="my-3">
              <i class="fa fa-2x fa-phone text-xeco"></i>
            </div>
            <h4 class="h5 mb-2">
              Lifetime access to jobs
            </h4>
            <p class="mb-0 text-muted">
              You will get unlimited access to available jobs including premium ones.  
            </p>
          </div>
          <div class="col-sm-6 col-md-4 mb-5">
            <div class="my-3">
              <i class="fa fa-2x fa-users text-danger"></i>
            </div>
            <h4 class="h5 mb-2">
              Hire Workers
            </h4>
            <p class="mb-0 text-muted">
              You will have unlimited access to hire workers to promote jobs.
            </p>
          </div>
          <div class="col-sm-6 col-md-4 mb-5">
            <div class="my-3">
              <i class="fa fa-2x fa-share text-xinspire"></i>
            </div>
            <h4 class="h5 mb-2">
              Unlimited Withdrawals
            </h4>
            <p class="mb-0 text-muted">
              Access to withdraw all your earnings and other juicy offers coming up shortly.
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- END Special Offer -->

    <!-- Call to Action -->
    <div class="content content-boxed text-center">
      <div class="py-5">
        <h2 class="mb-3 text-center">
          Why Upgrade?
        </h2>
        <h3 class="h4 fw-light text-muted push text-center">
          Getting verified can help you expand your business reach and acquire much more customers!
        </h3>
        <span class="m-2 d-inline-block">
          @if(auth()->user()->is_verified == '0')
          <a href="{{ route('make.payment') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
            <i class="fa fa-link opacity-50 me-1"></i>Get Verified 
          </a>
         
          @else
          <a class="btn btn-hero btn-primary disabled" href="javascript:void(0)" data-toggle="click-ripple">
            <i class="fa fa-link opacity-50 me-1"></i> Verification Completed
          </a>
         
          @endif

         
        </span>
      </div>
    </div>
  </div>

@endsection