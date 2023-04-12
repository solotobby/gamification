<h1>System Under maitenance</h1>
{{-- @extends('layouts.master')
@section('title', 'Login')
@section('content')

    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">Login</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Login</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->


		<div class="basic-contact-form ptb-90">
			<div class="container">
				<div class="area-title text-center">
					<h2>Login to Account</h2>
					<p>Fill the form below to login</p>
				</div>
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3">
                        <form id="contact-form" method="POST" action="{{ route('login') }}">
                        @csrf

							<div class="row">
								<div class="col-md-12 form-group">

									<label class="sr-only">Email</label>
									<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Email Address" >
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>

								<div class="col-md-12 form-group">
									<label class="sr-only">Password</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
								</div>
								
								<div class="col-md-12 text-center">
									<button type="submit" class="btn btn-lg btn-round btn-dark">Login</button>
								</div>
                                
                                	

							</div><!-- .row -->
						</form>

                        <div class="col-md-12 text-center">
                            <br><br>
							<a href="{{ url('auth/google') }}" class="btn btn-lg btn-round btn-dark">Login Using Google</a>
						</div>
                       
                        <div class="col-md-12 text-center">
                            <br><br>
                            <a href="{{ route('register') }}" >Not registered yet, Click Here to Register</a>

                        </div>
						<!-- Ajax response -->
						
					</div>
				</div>
			</div>
		</div>
@endsection --}}
