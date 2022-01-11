@extends('layouts.master')
@section('title', 'Enter Bank Information');

@section('content')


    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">Enter Bank Information</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Enter Bank Information</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->

    <div class="basic-contact-form ptb-90">
			<div class="container">
				<div class="area-title">
					<h2></h2>
					<p>Provide Bank information you'll want the cash!!!</p>
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


						<form id="contact-form" action="{{ route('save.bank.information') }}" method="post">
                            @csrf
							<div class="row">
								<div class="col-md-12 form-group">
									<label class="sr-only">Select Bank</label>
                                    <select class="form-control input-lg" name="bank_code" required>
                                    <option value="">Select One</option>
                                    @foreach ($bankList as $bank)
                                        <option value="{{ $bank['code'] }}"> {{ $bank['name'] }}</option>
                                        {{--  <input type="hidden" name="bank_name" value="{{ $bank['name'] }}">  --}}
                                    @endforeach    
                                    </select> 
								</div>

								<div class="col-md-12 form-group">
									<label class="sr-only">Email</label>
									<input type="text" class="form-control input-lg" name="account_number" placeholder="Enter Account Number" required>
								</div>

                                <input type="hidden" name="user_score_id" value="{{ $id }}">
								
                                <div class="col-md-12 text-center">
									<button type="submit" class="btn btn-lg btn-round btn-dark"> Save </button>
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