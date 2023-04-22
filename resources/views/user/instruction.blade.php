@extends('layouts.main.master')
{{-- @section('title', 'Instruction Page') --}}

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Success</h1>
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
				<p>
					You are about to play a cool game. In this game, you are to answer <b>{{ $games->number_of_questions }}</b> questions.  You are also required to spend <b>{{ $games->time_allowed * 60 }}</b> seconds on each question.
					<br>
					Do not refresh the page as your activites will be wiped out. <b><i> Goodluck!! </i></b>
				</p>
			
				<a href="{{ route('take.quiz') }}" class="btn btn-primary">Continue to Quiz</a>

			 

			  {{-- <h1>Opps!!!</h1>
			  <h2>You have playe this Game already</h2>
              <div class="fs-4 fw-semibold pt-3 mb-0">Payment Successful</div> --}}
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
