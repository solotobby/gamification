
            <header class="header" id="header">
                <div class="header-top">
                    <div class="wrapper">
                        <div class="socials ">
                            <div class="footer-title">Find us here:</div>
                            <div class="socials">
                                <div class="socials__item">
                                    <a href="#" target="_blank" class="socials__link">Facebook</a>
                                </div>
                                <div class="socials__item">
                                    <a href="#" target="_blank" class="socials__link">Instagram</a>
                                </div>
                            </div>
                        </div>
                        {{--  <div class="phone-item">
                            <div class="footer-title header-title-phone">Have a question? Call us!</div>
                            <div class="footer-phone__item">
                                <i class="icon-phone"></i><a href="tel:+15469872185">+1 546 987 21 85</a>
                            </div>
                        </div>  --}}
                    </div>
                </div>
                <div class="wrapper">
                    <div class="nav-logo">
                        <a href="{{ url('/') }}" class="logo">
                            <img src="{{ asset('asset/img/logo.svg') }}" alt="Numerio">
                        </a>
                    </div>
                    <div class="header-right">
                        <div id="mainNav" class="menu-box">
                            @if(Auth::user())
                            <nav class="nav-inner">
                                <ul class="main-menu js-menu" id="mainMenu">
                                    <li>
                                        <a href="#about">About Us</a>
                                    </li>
                                    <li>
                                        <a href="#contact">Contact Us</a>
                                    </li>
                                    <li>
                                        <a href="#winnings">What You Get</a>
                                    </li>
                                    
                                    <li>
                                        <a href="#testimonials">Testimonials</a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>
                                    
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            
                                </ul>
                            </nav>
                            @else
                            <nav class="nav-inner">
                                <ul class="main-menu js-menu" id="mainMenu">
                                    <li>
                                        <a href="#about">About Us</a>
                                    </li>
                                    <li>
                                        <a href="#contact">Contact Us</a>
                                    </li>
                                    <li>
                                        <a href="#winnings">What You Get</a>
                                    </li>
                                    
                                    <li>
                                        <a href="#testimonials">Testimonials</a>
                                    </li>
                                </ul>
                            </nav>
                            @endif
                            <div class="socials-item">
                                <div class="footer-title">Find us here:</div>
                                <div class="socials">
                                    <div class="socials__item">
                                        <a href="#" target="_blank" class="socials__link">Fb</a>
                                    </div>
                                    <div class="socials__item">
                                        <a href="#" target="_blank" class="socials__link">Ins</a>
                                    </div>
                                </div>
                            </div>
                            {{--  <div class="phone-item">
                                <div class="footer-title footer-title_phone">Have a question? Call us!</div>
                                <div class="footer-phone__item">
                                    <i class="icon-phone"></i><a href="tel:+15469872185">+1 546 987 21 85</a>
                                </div>
                            </div>  --}}
                        </div>
                        @if(Auth::user())
                        <a href="{{ route('take.quiz') }}" class="btn-2 btn_started-header">Play Games</a>
                        @else
                        <a href="{{ url('auth/google') }}" class="btn-2 btn_started-header">get started</a>
                        @endif
                    </div>
                    <div class="bars-mob js-button-nav">
                        <div class="hamburger">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="cross">
                            <span></span><span></span>
                        </div>
                    </div>
                </div>
            </header>