<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Freebyz'))</title>

    <!-- Meta Tags -->
    @yield('meta')

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-blue: #0066FF;
            --primary-blue-dark: #004DB3;
            --secondary-dark: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Header Styles */
        .site-header {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .site-header .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-dark);
        }

        .site-header .nav-link {
            color: #333;
            margin: 0 15px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .site-header .nav-link:hover {
            color: var(--primary-blue);
        }

        .btn-primary {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-primary:hover {
            background: var(--primary-blue-dark);
            border-color: var(--primary-blue-dark);
        }

    /* ===== Footer ===== */
.site-footer {
    background-color: #2563eb; /* blue-600 */
    color: #ffffff;
}

.site-footer .container {
    padding-top: 64px;
    padding-bottom: 48px;
}

/* Column spacing (Tailwind gap-12 equivalent) */
.site-footer .row > div {
    padding-left: 24px;
    padding-right: 24px;
}

/* Logo alignment */
.site-footer img {
    margin-top: 4px;
}

/* Section titles */
.footer-title {
    font-weight: 700;
    font-size: 17px;
    margin-bottom: 16px;
}

/* Links */
.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    color: #bfdbfe; /* blue-100 */
    font-size: 14px;
    text-decoration: none;
    transition: color 0.2s ease;
}

.footer-links a:hover {
    color: #ffffff;
}

/* Bottom bar */
.footer-bottom {
    margin-top: 48px;
    padding-top: 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.35);
}

.footer-copy {
    color: #bfdbfe;
    font-size: 14px;
    line-height: 1.6;
}

/* Social icons */
.footer-social a {
    color: #bfdbfe;
    margin-left: 28px;
    font-size: 20px;
    transition: color 0.2s ease;
}

.footer-social a:hover {
    color: #ffffff;
}


        /* Mobile Menu */
        @media (max-width: 991px) {
            .site-header .nav-link {
                margin: 10px 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ url('src/assets/media/photos/freebyzlogo-blue.png') }}" alt="Freebyz Logo"
                        style="height: 30px; width: auto; margin-right: 8px;">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav align-items-center">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary text-white px-4 ms-2" href="{{ route('register') }}">
                                    Sign up for free
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                            style="width: 32px; height: 32px; font-size: 14px; font-weight: 600;">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <span>{{ auth()->user()->name }}</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/home') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <div class="container">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error') || session('warning'))
        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <div class="container">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error!</strong> {{ session('error') ?? session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
            <div class="container">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
            <div class="container">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer bg-blue">
        <div class="container py-5">
            <div class="row mb-4">
                <!-- Brand -->
                <div class="col-md-2 mb-4 mb-md-0">
                    <img src="{{ url('src/assets/media/photos/Freebyz-logo-white.png') }}" alt="Freebyz Logo"
                        style="height:30px;">
                </div>

                <!-- Quick Links -->
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="footer-title">Quick links</h6>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('register') }}">Sign up</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="footer-title">Company</h6>
                    <ul class="footer-links">
                        <li><a href="https://freebyz.com/about-us">About us</a></li>
                        <li><a href="{{ url('/track-record') }}">Track record</a></li>
                        <li><a href="https://freebyz.com/contact-us">FAQs</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="footer-title">Legal</h6>
                    <ul class="footer-links">
                        <li><a href="https://freebyz.com/terms-of-use">Terms of use</a></li>
                        <li><a href="https://freebyz.com/privacy-policy">Privacy policy</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-md-2">
                    <h6 class="footer-title">Contact</h6>
                    <ul class="footer-links">
                        <li><a href="mailto:hello@freebyz.com">Contact support</a></li>
                        <li>
                            <a href="https://tawk.to/chat/6510bbe9b1aaa13b7a78ae75/1hb4ls2fd">
                                Live chat
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="footer-bottom pt-4 mt-4 border-top">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p class="footer-copy mb-0">
                            All copyright © reserved by Freebyz {{ date('Y') }}<br>
                            Freebyz By Dominahl Technology LLC
                        </p>
                    </div>

                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-social">
                            <a href="https://www.facebook.com/share/XzPhzupkGenQRLps/?mibextid=qi2Omg" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/freebyzjobs" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://x.com/FreebyzHQ" target="_blank">
                                <i class="fab fa-x-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
