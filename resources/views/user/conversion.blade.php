@extends('layouts.main.master')
@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Conversion</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Conversion</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>


  <div class="content">
    {{-- <h2 class="content-heading">Basic <small>Animate elements and make them visible on scrolling</small></h2> --}}
    <div class="row">
    <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <div class="block block-rounded invisible" data-toggle="appear">
          <div class="block-content block-content-full">
            <div class="py-5 text-center">
              <div class="item item-2x item-circle bg-danger text-white mx-auto">
                <i class="fa fa-2x fa-info"></i>
              </div>

              @if (session('error'))
                <div class="alert alert-danger mt-2" role="alert">
                    {{ session('error') }}
                </div>
              @endif

              <div class="fs-4 fw-semibold pt-3 mb-0">Opps</div>
              @if(auth()->user()->wallet->base_currency == 'Naira')
              
                    @if(auth()->user()->is_verified)
                            <p> You are verified for naira jobs but not elligible for dollar jobs. 
                                Please click the button below to get a verified dollar wallet!</p>
                                <p>A dollar wallet verification fee is $5 (&#8358;{{ number_format(dollar_naira() * 5,2) }}). Since you are Naira verified, you are qualified for our 20% discount!</p>
                            <a href="{{ url('upgrade/full/'.dollar_naira() * 4) }}" class="btn btn-primary"> <li class="fa fa-link"> </li> Get Verified for Dollar Jobs! </a>
                    @else
                            <p> You are not verified for dollar jobs. Please click the button below to get a verified dollar wallet!</p>
                            <p> A dollar wallet verification fee is $5 (&#8358;{{ number_format(dollar_naira() * 5,2) }}). </p>
                            <a href="{{ url('upgrade/full/'.dollar_naira() * 5) }}" class="btn btn-primary"> <li class="fa fa-link"> </li> Get Verified for Dollar Jobs! </a>
                    @endif

              @else

                <p> You are not verified yet. Please click the button below to get Verified!</p>
                <a href="{{ url('upgrade') }}" class="btn btn-primary"> <li class="fa fa-link"> </li> Get Verified! </a>

              @endif
            </div>
          
          </div>
        </div>
      </div>
    <div class="col-sm-2"></div>
    </div>

  </div>


@endsection

@section('script')
 <!-- jQuery (required for jQuery Appear plugin) -->
 <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>

 <!-- Page JS Plugins -->
 <script src="{{asset('src/assets/js/plugins/jquery-appear/jquery.appear.min.js')}}"></script>

 <!-- Page JS Helpers (jQuery Appear plugin) -->
 <script>Dashmix.helpersOnLoad(['jq-appear']);</script>
@endsection

