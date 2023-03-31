@extends('layouts.main.master')

@section('script')
    <script>
        function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer <= 0) {
                    clearTimeout(timer);
                    doSomething();
                    //timer = duration;
                }
            }, 1000);
        }

        window.onload = function () {
            var fiveMinutes = 60 * "<?php print($game->time_allowed); ?>",
                display = document.querySelector('#time');
            startTimer(fiveMinutes, display);
        };


        function doSomething() {
            //alert("Hi");
            window.location = '/submit/answers';
        }
    </script>

@endsection

 @section('content')
	<!-- basic-breadcrumb start -->
	<div class="basic-breadcrumb-area gray-bg ptb-70">
		<div class="container">
			<div class="basic-breadcrumb text-center mt-5">
				<h3 class="">Quiz in Progress</h3>
			</div>
		</div>
	</div>
	<!-- basic-breadcrumb end -->




	<div class="content">
		{{-- <h2 class="content-heading">Basic <small>Animate elements and make them visible on scrolling</small></h2> --}}
		<div class="row">
		  <div class="col-sm-2">
			
		  </div>
		  <div class="col-sm-8">
			<form action="{{ route('store.answer') }}" method="POST">
				@csrf
				<div class="col-xl-8 col-lg-8 col-md-8">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							{{ 1 - $index  }} of {{ $game->number_of_questions }}
						</div>
						<div class="col-md-6 col-sm-6">
							<h5 class="text-center text-danger"><span id="time"></span> seconds!</h5>
						
						</div>
					</div>
					
					<div class="about-me-info mt-30 mb-30 pl-30">
						<h4>{{ $question->content }} </h3>
						
						<div class="info-me mb-30 mt-30">
							
								<input type="checkbox" id="vehicle2" name="option" value="{{ $question->option_A }}">
								<label for="vehicle2"> 
									{{ $question->option_A }}
								</label><br><br>

								<input type="checkbox" id="vehicle3" name="option" value="{{ $question->option_B }}">
								<label for="vehicle3"> 
									{{ $question->option_B }} 
								</label><br><br>

								<input type="checkbox" id="vehicle4" name="option" value="{{ $question->option_C }}">
								<label for="vehicle4"> 
									{{ $question->option_C }}
								</label><br><br>

								<input type="checkbox" id="vehicle5" name="option" value="{{ $question->option_C }}">
								<label for="vehicle5"> 
									{{ $question->option_D }}
								</label><br><br>

								<input type="hidden" name="question_id" value="{{ $question->id }}">

								<input type="hidden" name="game_id" value="{{ $game->id }}">

								<input type="submit" class="btn smoth-scroll" value="NEXT">
							
						</div>
					</div>
							
				</div>
			</form>
			
		  </div>
		  <div class="col-sm-2">
			
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