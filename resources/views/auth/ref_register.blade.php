@extends('layouts.master')
@section('title', 'Register')

@section('style')
<link rel="stylesheet" href="https://cdn.tutorialjinni.com/intl-tel-input/17.0.3/css/intlTelInput.css"/>
<script src="https://cdn.tutorialjinni.com/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@endsection

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
					<p>Sign up below in just 2 minutes</p>
				</div>
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3">
                        <form id="contact-form" method="POST" action="{{ route('register') }}">
                        @csrf

							<div class="row">
                                <div class="col-md-12 form-group">

									<label>Full Name</label>
									<input id="text" type="text" class="form-control intput-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Enter Name" >
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>

								<div class="col-md-12 form-group">

									<label>Email Address</label>
                                      
									<input id="email" type="email" class="form-control intput-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Enter Email Address" >
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>

                                <div class="col-md-12 form-group">

									<label>Phone Number</label>
                                    <input type="tel" name="phone_number[main]" id="phone_number" class="form-control" placeholder="Phone Number" value="{{old('phone_number')}}" required size="100%" />
									{{-- <input id="text" type="text" class="form-control intput-lg @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="Enter Phone Number" > --}}
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>

								<div class="col-md-12 form-group">
									<label>Password</label>
                                    <input id="passwordj" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Enter Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>

                                <div class="col-md-12 form-group">
									<label>Confirm Password</label>
                                    <input id="passwordh" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required placeholder="Repeat Password">
                                    {{-- @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror --}}
								</div>

                                <div class="col-md-12 form-group">
                                    <label>How did you hear about Freebyz.com</label>
                                   <select class="form-control" name="source" required>
                                        <option value="">Select One</option>
                                        <option>Facebook</option>
                                        <option>WhatsApp</option>
                                        <option>Youtube</option>
                                        <option>TikTok</option>
                                        <option>Instagram</option>
                                        <option>Twitter</option>
                                        <option>Online Ads</option>
                                        <option>Referred by a Friend</option>
                                   </select>
                                   
                                </div>

                                <div class="col-md-12 form-group">

									<label>Referral Code</label>
									<input id="text" type="text" class="form-control intput-lg @error('name') is-invalid @enderror" name="referral_name" value="{{ $name->referral_code }}" required readonly>
								</div>
                                <div class="col-md-12 form-group">
                                    <input type="checkbox" name="terms" required> <span>I agree with the <a href="{{ url('terms') }}">Terms and Conditions</a></span> 
                                </div>

                                <input hidden name="ref_id" value="{{ $name->id }}">
								
								<div class="col-md-12 text-center">
									<button type="submit" class="btn btn-lg btn-round btn-dark">Register</button>
								</div>
                                <div class="col-md-12 text-center">
                                    <br><br>
                                    <a href="{{ route('login') }}" >Already a registered, Click Here to Login</a>
        
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

@section('script')

<script>

$("document").ready( function () {
       // alert("Hello, world");
   
    
    var phone_number = window.intlTelInput(document.querySelector("#phone_number"), {
        separateDialCode: true,
        preferredCountries:["ng", "gb", "us"],
        hiddenInput: "full",
        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
    });
    function myFunction() {
        var full_number = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
        $("input[name='phone_number[full]'").val(full_number);
        // alert(full_number)
    }

}); 
</script>

@endsection
