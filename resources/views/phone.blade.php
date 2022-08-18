@extends('layouts.master')
@section('title', 'Enter Phone Number');

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
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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