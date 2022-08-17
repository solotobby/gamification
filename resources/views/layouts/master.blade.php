{{--  <!DOCTYPE html>
<html lang="zxx">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
        <title>Welcome Gamzoe</title>
        <meta name='description' content="" />
        <meta name="keywords" content="" />
        <meta name="it-rating" content="it-rat-cd303c3f80473535b3c667d0d67a7a11" />
        <meta name="cmsmagazine" content="3f86e43372e678604d35804a67860df7" />
        <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/first-screen.css') }}" />
        <!-- <link rel="stylesheet" type="text/css" href="css/first-screen-inner.css" /> -->
        <link rel="preload " href="{{ asset('asset/fonts/AleoBold.woff2') }}" as="font" crossorigin>
        <link rel="preload " href="{{ asset('asset/fonts/Lato/LatoRegular.woff2') }}" as="font" crossorigin>
        <link rel="preload " href="{{ asset('asset/fonts/Lato/LatoBold.woff2') }}" as="font" crossorigin>
        <link rel="preload"  href="{{ asset('asset/css/style.css') }}" as="style">
    </head>
    <body class="home loaded">
         <div class="main-wrapper">
            
            @yield('content')

            
            @include('layouts.header')
            @include('layouts.footer')
            
        </div>
        <!-- BODY EOF   -->
        <!-- popups -->
        <div class="window-open">
            <div class="popup" id="formOrder" tabindex="0">
                <div class="block-popup">
                    <div class="popup-title-wrap">
                        <div class="popup-title">Get a free consultation</div><div class="popup-decor-top"></div>
                    </div>
                    <div class="popup-text">Culpa non ex tempor qui nulla laborum. Laboris culpa ea incididunt dolore ipsum tempor duis do ullamc.</div>
                    
                     <form onsubmit="successSubmit();return false;">
                        <div class="popup-form">
                            <div class="box-field">
                                <input type="text" placeholder="Name">
                            </div>
                            <div class="box-field">
                                <input type="email"  placeholder="Email">
                            </div>
                            <div class="box-field">
                                <textarea placeholder="Message"></textarea>
                            </div>
                            <div class="box-fileds box-fileds_2">
                                <div class="box-filed box-filed_btn">
                                    <input type="submit" class="btn" value="Submit">
                                </div>
                                <div class="box-filed box-field__accept">
                                    <label class="checkbox-element">
                                    <input type="checkbox" >
                                    <span class="checkbox-text">I accept the <a href="#" target="_blank">Terms and Conditions.</a></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>  
                </div>
                <div class="popup-decor"></div>
            </div>
            <div class="popup popup-succsess" id="succsesOrder">
                <div class="block-popup">
                    <div class="popup-title"><span>Sank you</span></div>
                    <div class="popup-result">Dolor duis voluptate enim exercitation consequat ex. Voluptate </div>
                    <svg width="200" height="184" viewBox="0 0 200 184" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M178.566 91.6643C178.566 139.987 139.392 179.162 91.0686 179.162C42.744 179.162 3.57129 139.987 3.57129 91.6643C3.57129 43.3397 42.744 4.16699 91.0686 4.16699C139.392 4.16699 178.566 43.3397 178.566 91.6643Z" fill="rgba(255, 154, 160, 0.991703)"/>
                        <path d="M91.6644 183.327C41.1242 183.327 0 142.205 0 91.6644C0 41.1242 41.1242 0 91.6644 0C109.23 0 126.33 5.01694 141.112 14.4908C144.02 16.3585 144.863 20.2249 143.004 23.124C141.138 26.0246 137.262 26.8745 134.371 25.0084C121.597 16.833 106.839 12.4996 91.6644 12.4996C48.0149 12.4996 12.4996 48.0149 12.4996 91.6644C12.4996 135.312 48.0149 170.828 91.6644 170.828C135.312 170.828 170.828 135.312 170.828 91.6644C170.828 89.0552 170.703 86.472 170.461 83.9315C170.129 80.4984 172.645 77.4391 176.086 77.1141C179.494 76.6731 182.569 79.2975 182.903 82.7307C183.185 85.6725 183.327 88.6478 183.327 91.6644C183.327 142.205 142.205 183.327 91.6644 183.327Z" fill="rgba(249, 73, 115, 0.991703)"/>
                        <path d="M102.08 112.496C100.481 112.496 98.8799 111.887 97.6638 110.663L60.165 73.1643C57.7237 70.7214 57.7237 66.7634 60.165 64.3221C62.6063 61.8808 66.5643 61.8808 69.0057 64.3221L102.089 97.4052L189.327 10.1657C191.77 7.72438 195.728 7.72438 198.169 10.1657C200.61 12.607 200.61 16.5651 198.169 19.0064L106.505 110.671C105.279 111.887 103.68 112.496 102.08 112.496Z" fill="rgba(249, 73, 115, 0.991703)"/>
                    </svg>
                    <div class="popup-text">Dolor duis voluptate enim exercitation consequat ex. Voluptate </div>
                    <div class="popup-button_succsees">
                        <div class="btn btn-popup" data-fancybox-close>Ok</div>
                    </div>
                </div>
            </div>
        </div>        
        <script>
            var body = document.body;
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
                body.classList.add('ios');
            } else {
                body.classList.add('web')
            }
            setTimeout(function() {
                body.classList.add("content-loaded");
            },50)
                
        </script>
        <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/style.css') }}" />
        <script src="{{ asset('asset/js/jquery-3.5.1.min.js') }}" defer></script>
        <script src="{{ asset('asset/js/components/jquery.lazy.min.js') }}" defer></script>
        <script src="{{ asset('asset/js/components/jquery.fancybox.min.js') }}" defer></script>
        <script src="{{ asset('asset/js/components/jquery.singlePageNav.min.js') }}" defer></script>
        <script src="{{ asset('asset/js/components/swiper.js') }}" defer></script>
        <script src="{{ asset('asset/js/custom.js') }}" defer></script>
    </body>
</html>  --}}



<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title')</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- favicon icon -->
        <link rel="icon" href="{{ asset('asset/img/favicon.png') }}">

		<!-- All CSS Files Here -->
        <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/css/et-line-fonts.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/css/magnific-popup.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/css/meanmenu.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/css/global.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/style.css') }}">
        <link rel="stylesheet" href="{{ asset('asset/css/responsive.css') }}">
        <script src="{{ asset('asset/js/vendor/modernizr-2.8.3.min.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-G7C4X8TR6T"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-G7C4X8TR6T');
        </script>

        @yield('script')
        @yield('style')
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
 		<!-- PRELOADER -->
		<div class="page-loader">
			<div class="loader">Loading...</div>
		</div>
		<!-- /PRELOADER -->
		<!-- header start -->
		@include('layouts.header')
		<!-- header end -->
		<!-- basic-slider start -->

        @yield('content')
		
		<!-- footer start -->
		@include('layouts.footer')
		<!-- footer end -->


		<!-- All js plugins here -->
        <script src="{{ asset('asset/js/vendor/jquery-1.12.0.min.js') }}"></script>
        <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('asset/js/isotope.pkgd.min.js') }}"></script>
        <script src="{{ asset('asset/js/imagesloaded.pkgd.min.js') }}"></script>
        <script src="{{ asset('asset/js/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('asset/js/jquery.meanmenu.js') }}"></script>
        <script src="{{ asset('asset/js/plugins.js') }}"></script>
        <script src="{{ asset('asset/js/main.js') }}"></script>
    </body>
</html>
