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
					<h2>Create Account</h2>
					<p>Fill the form below to login</p>
				</div>
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3">
                        <form id="contact-form" method="POST" action="{{ route('register') }}">
                        @csrf

							<div class="row">
                                <div class="col-md-12 form-group">

									<label class="sr-only">Full Name</label>
									<input id="text" type="text" class="form-control intput-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Enter Name" >
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>

								<div class="col-md-12 form-group">

									<label class="sr-only">Email</label>
									<input id="email" type="email" class="form-control intput-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Enter Email Address" >
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>

                                <div class="col-md-12 form-group">

									<label class="sr-only">Phone Number</label>
									<input id="text" type="text" class="form-control intput-lg @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="Enter Phone Number" >
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>

								<div class="col-md-12 form-group">
									<label class="sr-only">Password</label>
                                    <input id="passwordj" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Enter Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>

                                <div class="col-md-12 form-group">
									<label class="sr-only">Confirm Password</label>
                                    <input id="passwordh" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required placeholder="Repeat Password">
                                    {{-- @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror --}}
								</div>

                                <div class="col-md-12 form-group">

									<label class="sr-only">Referral Name</label>
									<input id="text" type="text" class="form-control intput-lg @error('name') is-invalid @enderror" name="referral_name" value="{{ $name->name }}" required readonly>
								</div>

                                <input hidden name="ref_id" value="{{ $name->id }}">
								
								<div class="col-md-12 text-center">
									<button type="submit" class="btn btn-lg btn-round btn-dark">Register</button>
								</div>
                                
                                	

							</div><!-- .row -->
						</form>

                        {{-- <div class="col-md-12 text-center">
                            <br><br>
							<a href="{{ url('auth/google') }}" class="btn btn-lg btn-round btn-dark">Register Using Google</a>
						</div> --}}
						<!-- Ajax response -->
						<div class="ajax-response text-center"></div>
					</div>
				</div>
			</div>
		</div>


@endsection
