<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Freebyz Business Promotion | {{$business->business_name}}</title>

    <meta name="description" content=" {{$business->business_name}}">
    <meta name="author" content="Freebyz">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content=" {{$business->business_name}}">
    <meta property="og:site_name" content="Freebyz">
    <meta property="og:description" content=" {{Str::limit($business->business_description,20)}}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="freebyz.com">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.png') }}">
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

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7484162262282358"
    crossorigin="anonymous"></script>


  </head>
  <body>


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

            <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo21@2x.jpg')}}');">
                {{-- <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo17@2x.jpg')}});"> --}}
                 <div class="bg-black-25">
                   <div class="content content-full">
                     <div class="py-5 text-center">
                       {{-- <a class="img-link" href="be_pages_generic_profile.html">
                         <img class="img-avatar img-avatar96 img-avatar-thumb" src="assets/media/avatars/avatar10.jpg" alt="">
                       </a> --}}
                       <h1 class="fw-bold my-2 text-white"> {{ isset($business) ? $business->business_name : 'SignUp your Business for Freebyz Promotion' }}</h1>
                       @if($business)
                       <h2 class="h4 fw-bold text-white-75">

                         Business Page <a class="text-primary-lighter" href="{{url('m/'.$business->business_link) }}" target="_blank">{{url('m/'.$business->business_link) }}</a>
                       </h2>
                       @else


                       @endif

                     </div>
                   </div>
                 </div>
               </div>

               <div class="content content-full content-boxed">

                    <div class="col-md-12 col-xl-12">
                        <div class="block block-rounded">
                            <div class="block block-rounded justify-content-center">

                             <div class="block-content block-content-full">
                                <i class="si si-briefcase me-1"></i> Business Information
                                <hr>

                                 {!! $business->description !!}
                             </div>

                            </div>
                        </div>

                    </div>


                    @if($business->products->count() > 0)
                    <h2 class="content-heading">
                        <i class="si si-list me-1"></i> My Product
                    </h2>


                    <div class="row">
                        @foreach ($business->products as $product)




                     <div class="col-md-6 col-xl-4">
                            <div class="block block-rounded text-center">
                              {{-- <div class="block-content block-content-full bg-image" style="background-image: url({{$product->img}});">
                                 </div> --}}
                                 <img src="{{ asset($product->img) }}" alt="Product Image" class="block-content block-content-full bg-image" style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="block-content block-content-full block-content-sm bg-body-light">
                                    <div class="fw-semibold">{{$product->name}}</div>
                                    <div class="fs-sm text-muted">&#8358;{{number_format($product->price,2)}}</div>
                                </div>
                              <div class="block-content block-content-full">
                                <a class="btn btn-sm btn-alt-secondary" href="tel:{{$business->business_phone}}">
                                  <i class="fa fa-user-circle text-muted me-1"></i> Contact Seller
                                </a>
                                {{-- <a class="btn btn-sm btn-alt-secondary" href="javascript:void(0)">
                                  <i class="fa fa-user-circle text-muted me-1"></i> Profile
                                </a> --}}
                              </div>
                            </div>
                          </div>
                        @endforeach


                      </div>

                      @endif

                      <h2 class="content-heading">
                        <i class="si si-note me-1"></i> Connect With Me
                      </h2>



                    <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ url('m/'.$business->business_link) }}">
                        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <h4 class="fs-base text-primary mb-0">
                            <i class="fa fa-newspaper text-muted me-1"></i> Freebyz Page
                        </h4>
                        <p class="fs-sm text-muted mb-0 ms-2 text-end">
                            {{ $business->status}}
                        </p>
                        </div>
                    </a>

                  <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->facebook_link == null ? '#' :  $business->facebook_link }}">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                      <h4 class="fs-base text-primary mb-0">
                        <i class="fab fa-facebook text-muted me-1"></i> Facebook
                      </h4>
                      <p class="fs-sm text-muted mb-0 ms-2 text-end">
                        {{ $business->facebook_link == null ? 'Not set' : $business->facebook_link }}
                      </p>
                    </div>
                  </a>

                  <a class="block block-rounded block-link-shadow mb-3" href="tel:{{$business->business_phone}}">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                      <h4 class="fs-base text-primary mb-0">
                        <i class="fab fa-instagram-square text-muted me-1"></i> Contact Business
                      </h4>
                      <p class="fs-sm text-muted mb-0 ms-2 text-end">

                        {{ substr($business->business_phone, 0, 5) . "*****"
 }}
                      </p>
                    </div>
                  </a>

                  <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->instagram_link == null ? '#' :  $business->instagram_link }}">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                      <h4 class="fs-base text-primary mb-0">
                        <i class="fab fa-instagram-square text-muted me-1"></i> Instagram
                      </h4>
                      <p class="fs-sm text-muted mb-0 ms-2 text-end">
                        {{ $business->instagram_link == null ? 'Not set' : $business->instagram_link }}
                      </p>
                    </div>
                  </a>

                  <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->x_link == null ? '#' :  $business->x_link }}">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                      <h4 class="fs-base text-primary mb-0">
                        <i class="fab fa-x-twitter text-muted me-1"></i> X
                      </h4>
                      <p class="fs-sm text-muted mb-0 ms-2 text-end">
                        {{ $business->x_link == null ? 'Not set' : $business->x_link }}
                      </p>
                    </div>
                  </a>

                  <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->tiktok_link == null ? '#' :  $business->tiktok_link }}">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                      <h4 class="fs-base text-primary mb-0">
                        <i class="fab fa-tiktok text-muted me-1"></i> Tiktok
                      </h4>
                      <p class="fs-sm text-muted mb-0 ms-2 text-end">
                        {{ $business->tiktok_link == null ? 'Not set' : $business->tiktok_link }}
                      </p>
                    </div>
                  </a>

                  <a class="block block-rounded block-link-shadow mb-3" target="_blank" href="{{ $business->pinterest_link == null ? '#' :  $business->pinterest_link }}">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                      <h4 class="fs-base text-primary mb-0">
                        <i class="fab fa-pinterest text-muted me-1"></i> Pinterest
                      </h4>
                      <p class="fs-sm text-muted mb-0 ms-2 text-end">
                        {{ $business->pinterest_link == null ? 'Not set' : $business->pinterest_link }}
                      </p>
                    </div>
                  </a>




               </div>


          </div>
          <!-- END Section 1 -->




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

    </body>

    <amp-auto-ads type="adsense"
        data-ad-client="ca-pub-7484162262282358">
    </amp-auto-ads>


  </html>
