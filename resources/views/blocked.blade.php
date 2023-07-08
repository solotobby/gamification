@extends('layouts.master')
@section('title', 'Blcoked')

@section('content')


    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">Blocked</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Blocked</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->

    <!-- basic-contact-area -->
		<div class="basic-contact-area pt-90 pb-50">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 mb-40">
						<div class="contact-person">
							<center><h2>Thank you, your account has been blocked!!!</h2>
                            <a href="{{url('/')}}" class="btn btn-primary">Click Me</a>
                            </center>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- basic-contact-area end -->


@endsection