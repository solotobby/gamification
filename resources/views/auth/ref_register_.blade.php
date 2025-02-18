@extends('layouts.master')
@section('title', 'Register')

@section('style')
<link rel="stylesheet" href="https://cdn.tutorialjinni.com/intl-tel-input/17.0.3/css/intlTelInput.css"/>
<script src="https://cdn.tutorialjinni.com/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}
{{-- <link rel="stylesheet" href="{{asset('dist/css/bootstrap-select-country.min.css')}}" />  --}}

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
                        @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                        @endif
            
                        @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                        @endif
            
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form id="contact-form" method="POST" action="{{ url('register/user') }}">
                        @csrf

							<div class="row">
                                @include('layouts.resources.reg')

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

                       
					</div>
				</div>
			</div>
		</div>


@endsection

@section('script')
<!-- Latest compiled and minified JavaScript -->
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

<script src="{{asset('dist/js/bootstrap-select-country.min.js')}}"></script> --}}

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
