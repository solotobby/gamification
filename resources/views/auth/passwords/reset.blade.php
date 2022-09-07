{{-- @extends('layouts.app') --}}
@extends('layouts.master')
@section('title', 'Reset Password')

@section('content')


    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">Reset Password</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Reset Password</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->

    <div class="basic-contact-form ptb-90">
        <div class="container">
            <div class="area-title text-center">
                <h2>Enter New Password</h2>
                {{-- <p>Fill the form below to login</p> --}}
            </div>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                 

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">


                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="sr-only">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-12 form-group">
                                <label class="sr-only">Email</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>

                            <div class="col-md-12 form-group">
                                <label class="sr-only">Email</label>
                                <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                            </div>



                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-lg btn-round btn-dark">Reset Password</button>
                            </div>
                        </div><!-- .row -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
