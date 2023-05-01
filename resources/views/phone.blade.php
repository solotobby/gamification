@extends('layouts.master')
@section('title', 'Enter Phone Number');
@section('style')
<link rel="stylesheet" href="https://cdn.tutorialjinni.com/intl-tel-input/17.0.3/css/intlTelInput.css"/>
<script src="https://cdn.tutorialjinni.com/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}
{{-- <link rel="stylesheet" href="{{asset('dist/css/bootstrap-select-country.min.css')}}" /> --}}

@endsection
@section('content')


    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">Enter Phone Number</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Enter Phone Number</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->

    <div class="basic-contact-form ptb-90">
			<div class="container">
				<div class="area-title">
					<h2></h2>
					<p>Please enter your phone number</p>
				</div>
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
                         @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

						<form id="contact-form" action="{{ route('save.phone.information') }}" method="post">
                            @csrf
							
								<div class="col-md-12 form-group">
									<label class="sr-only">Phone Number</label>
                                    <input type="tel" name="phone_number[main]" id="phone_number" class="form-control" placeholder="Phone Number" value="{{old('phone_number')}}" required size="100%" />
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
								</div>
                                <div class="col-md-12 form-group">
									<label>Select Country</label>
                                    @include('layouts.resources.countries')
                                    {{-- <select class="selectpicker countrypicker form-control" data-flag="true" data-live-search="true" required name="country"></select> --}}
								</div>
                                <div class="col-md-12 form-group">
                                    <label>How did you hear about Freebyz.com</label>
                                   <select class="form-control" name="source" required>
                                        <option value="">Select One</option>
                                        <option>Facebook</option>
                                        <option>WhatsApp</option>
                                        <option>Youtube</option>
                                        <option>Instagram</option>
                                        <option>Twitter</option>
                                        <option>Online Ads</option>
                                        <option>Referred by a Friend</option>
                                   </select>
                                   
                                </div>								
                                <div class="col-md-12 text-center">
									<button type="submit" class="btn btn-lg btn-round btn-dark"> Complete Registration</button>
								</div>

							</div><!-- .row -->
						</form>
						<!-- Ajax response -->
						<div class="ajax-response text-center"></div>
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

