<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Freebyz') }} - @yield('title', 'Career Hub')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'freebyz-blue': '#0066FF',
                    }
                }
            }
        }
    </script>

    <!-- Additional Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .prose {
            max-width: 65ch;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        /* .container {
            padding-left: 60px !important;
            padding-right: 60px !important;
        } */
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 antialiased">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-24">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ url('src/assets/media/photos/freebyzlogo-blue.png') }}" alt="Freebyz Logo"
                        class="h-8 w-auto">
                </a>

                <!-- Desktop Navigation -->
                {{-- <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        Home
                    </a>
                    <a href="{{ url('/affiliate-program') }}"
                        class="text-gray-700 hover:text-blue-600 transition font-medium">
                        Affiliate Program
                    </a>
                    <a href="{{ route('career-hub.index') }}"
                        class="text-gray-700 hover:text-blue-600 transition font-medium">
                        Career Hub
                    </a>
                    <a href="{{ url('/about-us') }}" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        About us
                    </a>
                    <a href="{{ url('/contact') }}" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        Contact
                    </a>
                </div> --}}

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-700 text-white px-6 py-3.5 rounded-full hover:bg-blue-700 transition font-medium">
                            Sign up for free
                        </a>
                    @else
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition">
                                <div
                                    class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200">
                                <a href="{{ url('/home') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                    Dashboard
                                </a>

                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700" @click="mobileMenu = !mobileMenu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenu" x-cloak class="md:hidden pb-4 border-t">
                {{-- <a href="{{ url('/') }}" class="block py-3 text-gray-700 hover:text-blue-600 font-medium">
                    Home
                </a>
                <a href="{{ url('/affiliate-program') }}"
                    class="block py-3 text-gray-700 hover:text-blue-600 font-medium">
                    Affiliate Program
                </a>
                <a href="{{ route('career-hub.index') }}"
                    class="block py-3 text-gray-700 hover:text-blue-600 font-medium">
                    Career Hub
                </a>
                <a href="{{ url('/about-us') }}" class="block py-3 text-gray-700 hover:text-blue-600 font-medium">
                    About us
                </a>
                <a href="{{ url('/contact') }}" class="block py-3 text-gray-700 hover:text-blue-600 font-medium">
                    Contact
                </a> --}}

                @guest
                    <div class="pt-4 border-t mt-2">
                        <a href="{{ route('login', ['career_hub_redirect' => route('career-hub.index')]) }}"
                            class="block py-2 text-gray-700 font-medium">
                            Login
                        </a>


                        <a href="{{ route('register') }}" class="block py-2 text-blue-600 font-medium">
                            Sign up for free
                        </a>
                    </div>
                @else
                    <div class="pt-4 border-t mt-2">
                        <a href="{{ url('/home') }}" class="block py-2 text-gray-700">
                            Dashboard
                        </a>
                        {{-- @if(auth()->user()->is_admin ?? false)
                        <a href="{{ route('admin.career-hub.index') }}" class="block py-2 text-gray-700">
                            Admin Panel
                        </a>
                        @endif --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block py-2 text-red-600">
                                Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4" x-data="{ show: true }" x-show="show">
            <div class="container mx-auto px-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('error') || session('warning'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4" x-data="{ show: true }" x-show="show">
            <div class="container mx-auto px-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') ?? session('warning') }}</p>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4" x-data="{ show: true }" x-show="show">
            <div class="container mx-auto px-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-blue-700 font-medium">{{ session('info') }}</p>
                </div>
                <button @click="show = false" class="text-blue-500 hover:text-blue-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4" x-data="{ show: true }" x-show="show">
            <div class="container mx-auto px-4">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-red-700 font-medium mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- CTA Section (Before Footer) -->
    {{-- @if(!Request::is('register') && !Request::is('login'))
    <div class="bg-blue-600 py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">
                Sign up today and start earning in naira and dollars!
            </h2>
            <p class="text-blue-100 text-lg mb-8 max-w-3xl mx-auto">
                Start getting hired and making deposits. Free transfer. No limits. No sign-up fees. No monthly bills.
            </p>
            @guest
            <a href="{{ route('register') }}"
                class="inline-block bg-white text-blue-600 px-10 py-4 rounded-full hover:bg-gray-100 transition font-bold text-lg shadow-lg">
                Sign up for free
            </a>
            @endguest
        </div>
    </div>
    @endif --}}

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-12 mb-12">
                <!-- Brand -->
                <div class="md:col-span-1">
                    {{-- <div class="flex items-center space-x-2 mb-4">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" fill="currentColor" />
                            <circle cx="8" cy="10" r="1.5" fill="#0066FF" />
                            <circle cx="16" cy="10" r="1.5" fill="#0066FF" />
                            <path d="M7 14c0 2.5 2.5 4 5 4s5-1.5 5-4" stroke="#0066FF" stroke-width="2" fill="none"
                                stroke-linecap="round" />
                        </svg>
                        <span class="text-2xl font-bold text-white">Freebyz</span>
                    </div> --}}
                    <h5>
                        <img src="{{ url('src/assets/media/photos/Freebyz-logo-white.png') }}" alt="Freebyz Logo"
                            style="height: 30px; width: auto; margin-right: 8px;">
                    </h5>
                    {{-- <p class="text-blue-100 text-sm">
                        Making it easy for you to make money legitimately
                    </p> --}}
                </div>

                <!-- Quick links -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Quick links</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/') }}" class="text-blue-100 hover:text-white transition text-sm">Home</a>
                        </li>
                        <li><a href="{{ url('/register') }}"
                                class="text-blue-100 hover:text-white transition text-sm">Sign up</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="https://freebyz.com/about-us"
                                class="text-blue-100 hover:text-white transition text-sm">About us</a></li>
                        <li><a href="{{ url('/track-record') }}"
                                class="text-blue-100 hover:text-white transition text-sm">Track record</a></li>
                        <li><a href="https://freebyz.com/contact-us"
                                class="text-blue-100 hover:text-white transition text-sm">FAQs</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Legal</h4>
                    <ul class="space-y-3">
                        <li><a href="https://freebyz.com/terms-of-use"
                                class="text-blue-100 hover:text-white transition text-sm">Terms of use</a></li>
                        <li><a href="https://freebyz.com/privacy-policy"
                                class="text-blue-100 hover:text-white transition text-sm">Privacy policy</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Contact</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="mailto:hello@freebyz.com"
                                class="text-blue-100 hover:text-white transition text-sm">
                                Contact support
                            </a>
                        </li>

                        <li><a href="https://tawk.to/chat/6510bbe9b1aaa13b7a78ae75/1hb4ls2fd"
                                class="text-blue-100 hover:text-white transition text-sm">Live chat</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-blue-500">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <p class="text-blue-100 text-sm mb-4 md:mb-0">
                        All copyright © reserved by Freebyz {{ date('Y') }}<br class="md:hidden">
                        Freebyz By Dominahl Technology LLC
                    </p>
                    <div class="flex items-center space-x-6">
                        <a href="https://www.facebook.com/share/XzPhzupkGenQRLps/?mibextid=qi2Omg" target="_blank"
                            class="text-blue-100 hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/freebyzjobs" target="_blank"
                            class="text-blue-100 hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                            </svg>
                        </a>
                        <a href="https://x.com/FreebyzHQ" target="_blank"
                            class="text-blue-100 hover:text-white transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Initialize mobile menu state -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigation', () => ({
                mobileMenu: false
            }))
        })
    </script>

    @stack('scripts')
</body>

</html>
