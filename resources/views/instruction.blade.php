@extends('layouts.master')
@section('title', 'Instruction Page')

@section('content')

		<!-- basic-breadcrumb start -->
		<div class="basic-breadcrumb-area gray-bg ptb-70">
			<div class="container">
				<div class="basic-breadcrumb text-center">
					<h3 class="">Instruction</h3>
					<ol class="breadcrumb text-xs">
						<li><a href="{{ url('/') }}">Home</a></li>
						<li class="active">Instruction</li>
					</ol>
				</div>
			</div>
		</div>
		<!-- basic-breadcrumb end -->

<!-- features-area start -->
		<div id="about" class="features-area pt-90 pb-70">
			<div class="container">
				<div class="row">
					<div class="col-xl-12 col-lg-12 col-md-12">
						<div class="about-me-info mt-30 mb-30 pl-30">
							<p>
                                Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however
								a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.
                            </p>
							<p>We have awesome team for your next project.</p>
							<a href="{{ route('take.quiz') }}" class="btn smoth-scroll">Continue to Quiz</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- features-area end -->

@endsection