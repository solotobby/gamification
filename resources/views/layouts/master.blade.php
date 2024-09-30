<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title')</title>
        <meta name="description" content="afiliate, afiliate marketing, money, cash, online, online money, make money online">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- favicon icon -->
        <link rel="icon" href="{{ asset('favicon.png') }}">

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

        {{-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4211954660767269"
     crossorigin="anonymous"></script> --}}

        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script> --}}

        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css"> --}}
        
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7484162262282358"
            crossorigin="anonymous"></script>


            <!-- Google tag (gtag.js) current Active google tag -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11361481559"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'AW-11361481559');
            </script>



            <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11361481559"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'AW-11361481559');
            </script>

              <!-- Google tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-238432357-1">
            </script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-238432357-1');
            </script>

        
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-G7C4X8TR6T"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-G7C4X8TR6T');
            </script>

            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-Y2YM3K06L8">
            </script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-Y2YM3K06L8');
            </script>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

           
        <style>

            .float{
                position:fixed;
                width:60px;
                height:60px;
                bottom:40px;
                right:40px;
                background-color:lightblue;
                color:#FFF;
                border-radius:50px;
                text-align:center;
                font-size:30px;
                box-shadow: 2px 2px 3px #999;
                z-index:100;
            }

            .my-float{
                margin-top:16px;
            }
        </style>


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
		
        <a href="https://tawk.to/chat/6510bbe9b1aaa13b7a78ae75/1hb4ls2fd" class="float" target="_blank">
            <i class="fa fa-comments fa-lg" aria-hidden="true"></i>
           
        </a>
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

        @yield('script')
    </body>

    <amp-auto-ads type="adsense"
        data-ad-client="ca-pub-7484162262282358">
    </amp-auto-ads>

</html>
