@extends('layouts.main.master')
@section('title', 'Opps!! Game Completed Already')
@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Opps</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">Game Played</li>
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
              <div class="item item-2x item-circle bg-danger text-white mx-auto">
                <i class="fa fa-2x fa-info"></i>
              </div>

			  <h1>Opps!!!</h1>
			  <h2>You have playe this Game already</h2>
              {{-- <div class="fs-4 fw-semibold pt-3 mb-0">Payment Successful</div> --}}
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

<!-- basic-breadcrumb start -->
		{{-- <div class="basic-breadcrumb-area gray-bg ptb-70">
			<div class="container">
				<div class="basic-breadcrumb text-center">
					<h3 class="">Game Completed Already</h3>
					<ol class="breadcrumb text-xs">
						<li><a href="{{ url('/') }}">Home</a></li>
						<li class="active">Completed</li>
					</ol>
				</div>
			</div>
		</div> --}}
		<!-- basic-breadcrumb end -->

		{{-- <div class="404-area ptb-120">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2 text-center">
						<div class="error-text">
							<h1>Opps!!!</h1>
							<h2>You have playe this Game already</h2>
							{{--  <p class="lead">Just then her head struck against the roof of the hall: in fact she was now more than nine feet high, and she at once took up the little golden key and hurried off to the garden door.</p>  --}}
							{{-- <a href="{{ url('/') }}" class="btn btn-lg">Back Home ›</a>
						</div>
					</div>
				</div>
			</div>
		</div>  --}}
{{-- @endsection --}}