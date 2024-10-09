<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Freebyz Pro</title>

    <meta name="description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="Dashmix">
    <meta property="og:description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('src/assets/media/favicons/favicon.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('src/assets/media/favicons/favicon-192x192.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('src/assets/media/favicons/apple-touch-icon-180x180.png')}}">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Fonts and Dashmix framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ asset('src/assets/css/dashmix.min.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/select2/css/select2.min.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/dropzone/min/dropzone.min.css')}}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/flatpickr/flatpickr.min.css')}}"> --}}
        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="src/assets/css/themes/xwork.min.css"> -->
    <link rel="stylesheet" href="{{ asset('src/assets/js/plugins/simplemde/simplemde.min.css')}}">
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
    <div id="page-container" class="sidebar-dark side-scroll page-header-fixed page-header-glass main-content-boxed">

      <!-- Sidebar -->
      <!--
        Sidebar Mini Mode - Display Helper classes

        Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
        Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
          If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

        Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
        Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
        Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
      -->
      <nav id="sidebar" aria-label="Main Navigation">
        <!-- Side Header -->
        <div class="bg-header-dark">
          <div class="content-header bg-white-5">
            <!-- Logo -->
            <a class="fw-semibold text-white tracking-wide" href="">
              <span class="smini-visible">
                Free<span class="opacity-75">byz</span>
              </span>
              <span class="smini-hidden">
                Free<span class="opacity-75">byz</span>
              </span>
            </a>
            <!-- END Logo -->

            <!-- Options -->
            <div>
              <!-- Toggle Sidebar Style -->
              <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
              <!-- Class Toggle, functionality initialized in Helpers.dmToggleClass() -->
              <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle" data-target="#sidebar-style-toggler" data-class="fa-toggle-off fa-toggle-on" onclick="Dashmix.layout('sidebar_style_toggle');">
                <i class="fa fa-toggle-off" id="sidebar-style-toggler"></i>
              </button>
              <!-- END Toggle Sidebar Style -->

              <!-- Close Sidebar, Visible only on mobile screens -->
              <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
              <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_close">
                <i class="fa fa-times-circle"></i>
              </button>
              <!-- END Close Sidebar -->
            </div>
            <!-- END Options -->
          </div>
        </div>
        <!-- END Side Header -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
          <!-- Side Navigation -->
          <div class="content-side">
            <ul class="nav-main">
              <li class="nav-main-item">
                <a class="nav-main-link active" href="gs_landing.html">
                  <i class="nav-main-link-icon fa fa-home"></i>
                  <span class="nav-main-link-name">Home</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="javascript:void(0)">
                  <i class="nav-main-link-icon fa fa-rocket"></i>
                  <span class="nav-main-link-name">Features</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="javascript:void(0)">
                  <i class="nav-main-link-icon fab fa-paypal"></i>
                  <span class="nav-main-link-name">Pricing</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="javascript:void(0)">
                  <i class="nav-main-link-icon fa fa-envelope"></i>
                  <span class="nav-main-link-name">Contact</span>
                </a>
              </li>
              <li class="nav-main-heading">Extra</li>
              <li class="nav-main-item ms-lg-auto">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                  <i class="nav-main-link-icon fa fa-brush"></i>
                  <span class="nav-main-link-name">Themes</span>
                </a>
                <ul class="nav-main-submenu nav-main-submenu-right">
                  <li class="nav-main-item">
                    <a class="nav-main-link" data-toggle="theme" data-theme="default" href="#">
                      <i class="nav-main-link-icon fa fa-circle text-default"></i>
                      <span class="nav-main-link-name">Default</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xwork.min.css" href="#">
                      <i class="nav-main-link-icon fa fa-circle text-xwork"></i>
                      <span class="nav-main-link-name">xWork</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xmodern.min.css" href="#">
                      <i class="nav-main-link-icon fa fa-circle text-xmodern"></i>
                      <span class="nav-main-link-name">xModern</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xeco.min.css" href="#">
                      <i class="nav-main-link-icon fa fa-circle text-xeco"></i>
                      <span class="nav-main-link-name">xEco</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xsmooth.min.css" href="#">
                      <i class="nav-main-link-icon fa fa-circle text-xsmooth"></i>
                      <span class="nav-main-link-name">xSmooth</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xinspire.min.css" href="#">
                      <i class="nav-main-link-icon fa fa-circle text-xinspire"></i>
                      <span class="nav-main-link-name">xInspire</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xdream.min.css" href="#">
                      <i class="nav-main-link-icon fa fa-circle text-xdream"></i>
                      <span class="nav-main-link-name">xDream</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xpro.min.css" href="#">
                      <i class="nav-main-link-icon fa fa-circle text-xpro"></i>
                      <span class="nav-main-link-name">xPro</span>
                    </a>
                  </li>
                  <li class="nav-main-item">
                    <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xplay.min.css" href="#">
                      <i class="nav-main-link-icon fa fa-circle text-xplay"></i>
                      <span class="nav-main-link-name">xPlay</span>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
          <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
      </nav>
      <!-- END Sidebar -->

      <!-- Header -->
      {{-- <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
          <!-- Left Section -->
          <div class="d-flex align-items-center">
            <!-- Logo -->
            <a class="link-fx fs-lg fw-semibold text-dark" href="index.html">
              Dash<span class="text-primary">mix</span>
            </a>
            <!-- END Logo -->
          </div>
          <!-- END Left Section -->

          <!-- Right Section -->
          <div class="d-flex align-items-center">
            <!-- Menu -->
            <div class="d-none d-lg-block">
              <ul class="nav-main nav-main-horizontal nav-main-hover">
                <li class="nav-main-item">
                  <a class="nav-main-link active" href="gs_landing.html">
                    <i class="nav-main-link-icon fa fa-home"></i>
                    <span class="nav-main-link-name">Home</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="javascript:void(0)">
                    <i class="nav-main-link-icon fa fa-rocket"></i>
                    <span class="nav-main-link-name">Features</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="javascript:void(0)">
                    <i class="nav-main-link-icon fab fa-paypal"></i>
                    <span class="nav-main-link-name">Pricing</span>
                  </a>
                </li>
                <li class="nav-main-item">
                  <a class="nav-main-link" href="javascript:void(0)">
                    <i class="nav-main-link-icon fa fa-envelope"></i>
                    <span class="nav-main-link-name">Contact</span>
                  </a>
                </li>
                <li class="nav-main-heading">Extra</li>
                <li class="nav-main-item ms-lg-auto">
                  <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon fa fa-brush"></i>
                    <span class="nav-main-link-name">Themes</span>
                  </a>
                  <ul class="nav-main-submenu nav-main-submenu-right">
                    <li class="nav-main-item">
                      <a class="nav-main-link" data-toggle="theme" data-theme="default" href="#">
                        <i class="nav-main-link-icon fa fa-circle text-default"></i>
                        <span class="nav-main-link-name">Default</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xwork.min.css" href="#">
                        <i class="nav-main-link-icon fa fa-circle text-xwork"></i>
                        <span class="nav-main-link-name">xWork</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xmodern.min.css" href="#">
                        <i class="nav-main-link-icon fa fa-circle text-xmodern"></i>
                        <span class="nav-main-link-name">xModern</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xeco.min.css" href="#">
                        <i class="nav-main-link-icon fa fa-circle text-xeco"></i>
                        <span class="nav-main-link-name">xEco</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xsmooth.min.css" href="#">
                        <i class="nav-main-link-icon fa fa-circle text-xsmooth"></i>
                        <span class="nav-main-link-name">xSmooth</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xinspire.min.css" href="#">
                        <i class="nav-main-link-icon fa fa-circle text-xinspire"></i>
                        <span class="nav-main-link-name">xInspire</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xdream.min.css" href="#">
                        <i class="nav-main-link-icon fa fa-circle text-xdream"></i>
                        <span class="nav-main-link-name">xDream</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xpro.min.css" href="#">
                        <i class="nav-main-link-icon fa fa-circle text-xpro"></i>
                        <span class="nav-main-link-name">xPro</span>
                      </a>
                    </li>
                    <li class="nav-main-item">
                      <a class="nav-main-link" data-toggle="theme" data-theme="assets/css/themes/xplay.min.css" href="#">
                        <i class="nav-main-link-icon fa fa-circle text-xplay"></i>
                        <span class="nav-main-link-name">xPlay</span>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
            <!-- END Menu -->

            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-alt-secondary d-lg-none ms-1" data-toggle="layout" data-action="sidebar_toggle">
              <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- END Toggle Sidebar -->
          </div>
          <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

        <!-- Header Search -->
        <div id="page-header-search" class="overlay-header bg-primary">
          <div class="content-header">
            <form class="w-100" method="POST">
              <div class="input-group">
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-primary" data-toggle="layout" data-action="header_search_off">
                  <i class="fa fa-fw fa-times-circle"></i>
                </button>
                <input type="text" class="form-control border-0" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
              </div>
            </form>
          </div>
        </div>
        <!-- END Header Search -->

        <!-- Header Loader -->
        <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
        <div id="page-header-loader" class="overlay-header bg-primary-darker">
          <div class="content-header">
            <div class="w-100 text-center">
              <i class="fa fa-fw fa-2x fa-sun fa-spin text-white"></i>
            </div>
          </div>
        </div>
        <!-- END Header Loader -->
      </header> --}}
      <!-- END Header -->

      <!-- Main Container -->
      <main id="main-container">
      
        <!-- Hero -->
        {{-- <div class="hero hero-lg bg-body-extra-light overflow-hidden">
            
           <div class="hero-inner">
            <div class="content content-full">
              <div class="row">
                <div class="col-lg-5 text-center text-lg-start d-lg-flex align-items-lg-center">
                  <div>
                    <h1 class="h2 fw-bold mb-3">
                      Product Title
                    </h1>
                    <p class="fs-4 text-muted mb-5">
                      Lead paragraph containing the main purpose of your product.
                    </p>
                    <div>
                      <a class="btn btn-primary px-3 py-2 m-1" href="javascript:void(0)">
                        <i class="fa fa-fw fa-link opacity-50 me-1"></i> Call to action
                      </a>
                      <a class="btn btn-alt-primary px-3 py-2 m-1" href="javascript:void(0)">
                        <i class="fa fa-fw fa-link opacity-50 me-1"></i> Call to action
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 offset-lg-1 d-none d-lg-block">
                  <img class="img-fluid rounded" src="{{ asset('src/assets/media/various/promo_dashboard.png')}}" srcset="{{ asset('src/assets/media/various/promo_dashboard@2x.png 2x')}}"  alt="Hero Promo">
                </div>
              </div>
            </div>
          </div>
          <div class="hero-meta">
            <div>
              <span class="d-inline-block animated bounce infinite">
                <i class="si si-arrow-down text-muted fa-2x"></i>
              </span>
            </div>
          </div> 
        </div> --}}
        <!-- END Hero -->
        
        <!-- Section 1 -->
        <div class="bg-body-light">
            <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo21@2x.jpg')}}');">
                <div class="bg-black-75">
                  <div class="content py-6">
                    <h3 class="text-center text-white mb-0">
                        Register as A Professional
                    </h3>
                  </div>
                </div>
            </div>
          {{-- <div class="content content-full">
            <div class="py-5 push">
              <h2 class="mb-2 text-center">
                Title 1
              </h2>
              <h3 class="text-muted mb-0 text-center">
                Subtitle
              </h3>
            </div>
            <div class="text-center">
              <p>
                Your content..
              </p>
            </div>
          </div> --}}
        </div>
        <!-- END Section 1 -->



        <!-- Section 2 -->
        <div class="bg-body-extra-light">
          
          <div class="content content-full">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="py-3 push">
                        <h2 class="mb-2">
                          Title 2
                        </h2>
                        <h3 class="text-muted mb-0">
                          Subtitle
                        </h3>
                    </div>

                    
                </div>
                <div class="col-2"></div>
            </div>
          </div>
        </div>
        <!-- END Section 2 -->

        <!-- Section 3 -->
        <div class="bg-body-light">
          <div class="content content-full">
            <form action="" method="POST">
              @csrf
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-8">

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


                        <div class="mb-4">
                            <label class="form-label" for="post-title">Select Category</label>
                            <select name="professional_category_id" class="form-control" id="skill_set" required> 

                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="post-title">Select Sub Category</label>
                            <select name="professional_sub_category_id" class="form-control" id="skill_set_sub_categories" required> 

                            </select>
                        </div>

                        <div class="mb-4">
                          <label class="form-label" for="post-title">Title </label>
                          <input type="text" class="form-control" id="post-title" name="title" value="{{ old('title') }}" required>
                          <small><i>Enter the title of your role, Graphics designer, UI/UX, Content Writer, SEO Specialist</i></small>
                      </div>
                      
                        <div class="mb-4">
                            <label class="form-label" for="post-title">Full Name</label>
                            <input type="text" class="form-control" id="post-title" name="full_name" value="{{ old('full_name') }}" required>
                            {{-- <small><i>Please give a simple campaign title e.g Facebook Like or Youtube comment</i></small> --}}
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="post-title">Email Address</label>
                            <input type="email" class="form-control" id="post-title" name="email" value="{{ old('email') }}" required>
                            <small><i>You can use email associated with Freebyz Account. If you don't have an account on Freebyz, use your email</i></small>
                        </div>
                        <div class="mb-4">
                          <label class="form-label" for="post-title">Phone Number</label>
                          <input type="text" class="form-control" id="post-title" name="phone" value="{{ old('phone') }}" required>
                          {{-- <small><i>Please give a simple campaign title e.g Facebook Like or Youtube comment</i></small> --}}
                      </div>
                        <div class="mb-4">
                            <label class="form-label" for="post-title">Username</label>
                            <input type="text" class="form-control" id="post-title" name="username" value="{{ old('username') }}" required>
                            {{-- <small><i>Please give a simple campaign title e.g Facebook Like or Youtube comment</i></small> --}}
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Current Employment Status</label>
                            <div class="space-x-2">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="example-radios-inline1" name="employment_status" value="Freelancer">
                                <label class="form-check-label" for="example-radios-inline1">Freelancer</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="example-radios-inline2" name="employment_status" value="Fulltime">
                                <label class="form-check-label" for="example-radios-inline2">Full time Employee</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="example-radios-inline3" name="employment_status" value="Hybrid" >
                                <label class="form-check-label" for="example-radios-inline3">Hybrid(Part time freelancer)</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="example-radios-inline3" name="employment_status" value="Agency" >
                                <label class="form-check-label" for="example-radios-inline3">Agency</label>
                              </div>
                            </div>
                        </div>

                        <div class="mb-4">
                          <label class="form-label" for="post-title">Main Production Domain</label>
                          <select name="main_production_domain" class="form-control" id="professional_domain" required> 

                          </select>
                      </div>

                      <div class="mb-4">
                        <label class="form-label" for="dm-project-new-description">Choose Skills Used</label>
                        <select class="js-select2 form-select" id="example-select2-multiple" name="tools[]" style="width: 100%;" data-placeholder="Choose as many skills..." multiple required>
                          <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                              @foreach ($tools as $tool)
                                  <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                              @endforeach
                        </select>
                      </div>
                    
                      <div class="mb-4">
                        <label class="form-label" for="dm-project-new-description">Share your work and business experience</label>
                      
                        <!-- SimpleMDE Container -->
                        <textarea class="js-simplemde" id="simplemde" name="work_experience"></textarea>
                      </div>

                      

                      <div class="mb-4">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-plus-circle me-1 opacity-50"></i> Submit
                        </button>
                      </div>

                    </div>
                    <div class="col-2"></div>
                </div>
            </form>
           
          </div>
        </div>
        <!-- END Section 3 -->
      </main>
      <!-- END Main Container -->

     
      <!-- Footer -->
      <footer id="page-footer" class="footer-static bg-body-extra-light">
        <div class="content py-3">
          <!-- Footer Copyright -->
          <div class="row fs-sm pt-4">
            <div class="col-sm-6 order-sm-2 mb-1 mb-sm-0 text-center text-sm-end">
              <i class="far fa-smile text-primary"></i> Freebyz  by <a class="fw-semibold" href=#" target="_blank">Dominahl Technologies LLC</a>
            </div>
            <div class="col-sm-6 order-sm-1 text-center text-sm-start">
              <a class="fw-semibold" href="#" target="_blank">Freebyz</a> &copy; <span data-toggle="year-copy"></span>
            </div>
          </div>
          <!-- END Footer Copyright -->
        </div>
      </footer>
      <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    <!--
      Dashmix JS

      Core libraries and functionality
      webpack is putting everything together at assets/_js/main/app.js
    -->
    <script src="{{ asset('src/assets/js/lib/jquery.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/dashmix.app.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('src/assets/js/plugins/simplemde/simplemde.min.js')}}"></script>
    <script>Dashmix.helpersOnLoad([ 'jq-select2', 'js-simplemde']);</script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script>
    $(document).ready(function(){ 
        // alert('ok');

        $.ajax({
                    url: '{{ url("professional/categories") }}',
                    type: "GET",
                    data: {
                        //  country_id: country_id,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    // context: document.body,
                    success: function(result) {
                        // console.log(result);
                        $('#skill_set').html('<option value="">Select Category</option>');
                        $.each(result, function(key, value) {
                            $("#skill_set").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });

                $('#skill_set').change(function(){
                    var skill_set_ID = this.value;

                    $("#skill_set_sub_categories").html('');
                        $.ajax({
                            
                            url: '{{ url("professional/sub/categories") }}/' + encodeURI(skill_set_ID),
                            type: "GET",
                            data: {
                                //  country_id: country_id,
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function(result) {
                                // var new_result = result.sort(function(a, b) {
                                //     return a.name.localeCompare(b.name);
                                // });

                                // console.log(result)

                                $('#skill_set_sub_categories').html('<option value="">Select Sub Category</option>');
                                $.each(result, function(key, value) {
                                    // document.getElementById("number-of-staff").value = value.amount;
                                    $("#skill_set_sub_categories").append('<option value="' + value.id + '">' + value.name + '</option>');  
                                });
                                // $('#city-dropdown').html('<option value="">Select Region/State First</option>');
                            }
                    });
                });

                $('#skill_set_sub_categories').change(function(){
                  var sub_prof_ID = this.value; 

                  $("#professional_domain").html('');
                        $.ajax({
                            
                            url: '{{ url("professional/domain") }}/' + encodeURI(sub_prof_ID),
                            type: "GET",
                            data: {
                                //  country_id: country_id,
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function(result) {
                                // var new_result = result.sort(function(a, b) {
                                //     return a.name.localeCompare(b.name);
                                // });

                                // console.log(result)

                                $('#professional_domain').html('<option value="">Select Sub Category</option>');
                                $.each(result, function(key, value) {
                                    // document.getElementById("number-of-staff").value = value.amount;
                                    $("#professional_domain").append('<option value="' + value.id + '">' + value.name + '</option>');  
                                });
                                // $('#city-dropdown').html('<option value="">Select Region/State First</option>');
                            }
                    });

                });


     });
     </script>
  </body>
</html>
