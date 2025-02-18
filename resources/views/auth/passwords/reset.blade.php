{{-- @extends('layouts.app')
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
                {{-- <p>Fill the form below to login</p> --}
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
@endsection --}}




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Freebyz - Reset Password</title>

    <meta name="description" content="">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="">
    <meta property="og:site_name" content="Dashmix">
    <meta property="og:description" content="">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Fonts and Dashmix framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ asset('src/assets/css/dashmix.min.css')}}">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/xwork.min.css"> -->
    <!-- END Stylesheets -->
  </head>
  <body>
    <!-- Page Container -->
    <!--
      Available classes for #page-container:

      GENERIC

        'remember-theme'                            Remembers active color theme and dark mode between pages using localStorage when set through
                                                    - Theme helper buttons [data-toggle="theme"],
                                                    - Layout helper buttons [data-toggle="layout" data-action="dark_mode_[on/off/toggle]"]
                                                    - ..and/or Dashmix.layout('dark_mode_[on/off/toggle]')

      SIDEBAR & SIDE OVERLAY

        'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
        'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
        'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
        'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
        'sidebar-dark'                              Dark themed sidebar

        'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
        'side-overlay-o'                            Visible Side Overlay by default

        'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

        'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

      HEADER

        ''                                          Static Header if no class is added
        'page-header-fixed'                         Fixed Header


      FOOTER

        ''                                          Static Footer if no class is added
        'page-footer-fixed'                         Fixed Footer (please have in mind that the footer has a specific height when is fixed)

      HEADER STYLE

        ''                                          Classic Header style if no class is added
        'page-header-dark'                          Dark themed Header
        'page-header-glass'                         Light themed Header with transparency by default
                                                    (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
        'page-header-glass page-header-dark'         Dark themed Header with transparency by default
                                                    (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

      MAIN CONTENT LAYOUT

        ''                                          Full width Main Content if no class is added
        'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
        'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)

      DARK MODE

        'sidebar-dark page-header-dark dark-mode'   Enable dark mode (light sidebar/header is not supported with dark mode)
    -->
    <div id="page-container">

      <!-- Main Container -->
      <main id="main-container">
        <!-- Page Content -->
        <div class="bg-image" style="background-image: url('{{ asset('src/assets/media/photos/photo17@2x.jpg')}}');">
          <div class="row g-0 bg-gd-fruit-op">
            <!-- Main Section -->
            <div class="hero-static col-md-6 d-flex align-items-center bg-body-extra-light">
              <div class="p-3 w-100">
                <!-- Header -->
                <div class="text-center">
                  <a class="link-fx fw-bold fs-1" href="https://freebyz.com">
                    <span class="text-dark">Free</span><span class="text-primary">byz</span>
                  </a>
                  <p class="text-uppercase fw-bold fs-sm text-muted">Password Reset</p>
                </div>
                <!-- END Header -->

                <!-- Reminder Form -->
                <!-- jQuery Validation (.js-validation-reminder class is initialized in js/pages/op_auth_reminder.min.js which was auto compiled from _js/pages/op_auth_reminder.js) -->
                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                <div class="row g-0 justify-content-center">
                  <div class="col-sm-8 col-xl-6">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="py-3 mb-4">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus disabled >
                          @error('email')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror

                          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">
                          @error('password')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror

                           <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                      </div>


                      <div class="text-center mb-4">
                        <button type="submit" class="btn w-100 btn-lg btn-hero btn-primary">
                          <i class="fa fa-fw fa-reply opacity-50 me-1"></i>Send Reset Link
                        </button>
                        
                      </div>
                      
                    </form>
                  </div>
                </div>
                <!-- END Reminder Form -->
              </div>
            </div>
            <!-- END Main Section -->

            <!-- Meta Info Section -->
            <div class="hero-static col-md-6 d-none d-md-flex align-items-md-center justify-content-md-center text-md-center">
              <div class="p-3">
                <p class="display-4 fw-bold text-white mb-0">
                  Be ready to fail..
                </p>
                <p class="fs-1 fw-semibold text-white-75 mb-0">
                  ..to be able to succeed!
                </p>
              </div>
            </div>
            <!-- END Meta Info Section -->
          </div>
        </div>
        <!-- END Page Content -->
      </main>
      <!-- END Main Container -->
    </div>
    <!-- END Page Container -->

    <!--
      Dashmix JS

      Core libraries and functionality
      webpack is putting everything together at assets/_js/main/app.js
    -->

    <script src="{{ asset('src/assets/js/dashmix.app.min.js')}}"></script>

    <!-- jQuery (required for jQuery Validation plugin) -->
    <script src="{{ asset('src/assets/js/lib/jquery.min.js')}}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('src/assets/js/pages/op_auth_signin.min.js')}}"></script>

  </body>
</html>




