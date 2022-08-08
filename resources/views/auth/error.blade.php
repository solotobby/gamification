@extends('layouts.master')
@section('title', 'Register')
@section('content')

    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">Register</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Create Account</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->
		<div class="basic-contact-form ptb-90">
			<div class="container">
				<div class="area-title text-center">
					<h2>Error!!!</h2>
					<p>{{ $error }}</p>
				</div>
			</div>
		</div>


@endsection
