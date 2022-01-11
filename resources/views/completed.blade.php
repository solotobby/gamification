@extends('layouts.master')

@section('content')

<!-- basic-breadcrumb start -->
		<div class="basic-breadcrumb-area gray-bg ptb-70">
			<div class="container">
				<div class="basic-breadcrumb text-center">
					<h3 class="">Game Completed</h3>
					<ol class="breadcrumb text-xs">
						<li><a href="{{ url('/') }}">Home</a></li>
						<li class="active">Completed</li>
					</ol>
				</div>
			</div>
		</div>
		<!-- basic-breadcrumb end -->

		<div class="404-area ptb-120">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2 text-center">
						<div class="error-text">
							<h1>{{ $score }} %</h1>
							<h2>Yheeepeee!!! You have completed the Game!!!</h2>
							{{--  <p class="lead">Just then her head struck against the roof of the hall: in fact she was now more than nine feet high, and she at once took up the little golden key and hurried off to the garden door.</p>  --}}
							<a href="{{ url('/') }}" class="btn btn-lg">Back Home ›</a>
						</div>
					</div>
				</div>
			</div>
		</div>


{{ $score }}
@endsection