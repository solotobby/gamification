@extends('layouts.main.master')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Survey</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Survey</li>
            <li class="breadcrumb-item active" aria-current="page">Survey completed</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>


  <div class="content">
    {{-- <h2 class="content-heading">Basic <small>Animate elements and make them visible on scrolling</small></h2> --}}
    <div class="row">
    
      <div class="col-sm-12">
        <div class="block block-rounded invisible" data-toggle="appear">
          <div class="block-content block-content-full">
            <div class="py-5 text-center">
              <div class="item item-2x item-circle bg-success text-white mx-auto">
                <i class="fa fa-2x fa-check"></i>
              </div>
              <div class="fs-4 fw-semibold pt-3 mb-0">Survey Successfully Completed</div>
              <p>Thank you for completing this survey.</p>
              <br>
              <a href="{{ url('home') }}" class="btn btn-primary btn-sm"> <li class="fa fa-home"> </li> Continue </a>
            </div>
          </div>
        </div>
      </div>
      
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