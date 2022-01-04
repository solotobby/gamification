
<header id="sticky-header">
    <div class="header-area">
        <div class="container sm-100">
            <div class="row">
                <div class="col-md-3 col-sm-2">
                    <div class="logo text-upper">
                        <h4><a href="{{ url('/') }}">GameSuit</a></h4>
                    </div>
                </div>
                @if(Auth::user())
                <div class="col-md-9 col-sm-10">
                    <div class="menu-area hidden-xs">
                        <nav>
                            <ul class="basic-menu clearfix">
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="about.html">about</a></li>
                                <li><a href="service.html">Service</a></li>
                                <li><a href="contact.html">Contact</a></li>
                                <li><a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                </a></li>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                
                            </ul>
                        </nav>
                    </div>
                    <!-- basic-mobile-menu -->
                    <div class="basic-mobile-menu visible-xs">
                        <nav id="mobile-nav">
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="about.html">about</a></li>
                                <li><a href="service.html">Service</a></li>
                                <li><a href="contact.html">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                @else
                <div class="col-md-9 col-sm-10">
                    <div class="menu-area hidden-xs">
                        <nav>
                            <ul class="basic-menu clearfix">
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="about.html">about</a></li>
                                <li><a href="service.html">Service</a></li>
                                <li><a href="contact.html">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                    <!-- basic-mobile-menu -->
                    <div class="basic-mobile-menu visible-xs">
                        <nav id="mobile-nav">
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="about.html">about</a></li>
                                <li><a href="service.html">Service</a></li>
                                <li><a href="contact.html">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>

                @endif
            </div>
        </div>
    </div>
</header>