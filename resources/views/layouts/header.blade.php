
<header id="sticky-header">
    <div class="header-area">
        <div class="container sm-100">
            <div class="row">
                <div class="col-md-3 col-sm-2">
                    <div class="logo text-upper">
                        <h4><a href="{{ url('/') }}"><span class="icon-happy"></span> Freebyz</a></h4>
                    </div>
                </div>
                
                <div class="col-md-9 col-sm-10">
                    <div class="menu-area hidden-xs">
                        <nav>
                            <ul class="basic-menu clearfix">
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="{{ route('goal') }}">Goal</a></li>
                                {{-- <li><a href="{{  route('game.list') }}">Game List</a></li> --}}
                                {{-- <li><a href="{{  route('winner.list') }}">Winners List</a></li> --}}
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                                @if(Auth::user())
                                    <li><a href="{{ route('instruction') }}">Play Game</a></li>
                                @else
                                    <li><a href="{{ url('login') }}">Login</a></li>
                                    <li><a href="{{ url('register') }}">Register</a></li>
                                    {{-- <li><a href="{{ url('auth/google') }}">Get Started</a></li> --}}
                                @endif
                            
                                @if(Auth::user())
                                    <li><a href="{{ route('score.list') }}">Score List</a></li>
                                    <li><a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                    </a></li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @endif

                            </ul>
                        </nav>
                    </div>
                    <!-- basic-mobile-menu -->
                    <div class="basic-mobile-menu visible-xs">
                        <nav id="mobile-nav">
                            <ul>
                                <li><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="{{ route('goal') }}">Goal</a></li>
                                {{-- <li><a href="{{  route('game.list') }}">Game List</a></li> --}}
                                {{-- <li><a href="{{  route('winner.list') }}">Winners List</a></li> --}}
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                                @if(Auth::user())
                                    <li><a href="{{ route('instruction') }}">Play Game</a></li>
                                @else
                                    <li><a href="{{ url('login') }}">Login</a></li>
                                    <li><a href="{{ url('register') }}">Register</a></li>
                                    {{-- <li><a href="{{ url('auth/google') }}">Get Started</a></li> --}}
                                @endif

                                @if(Auth::user())
                                    <li><a href="{{ route('score.list') }}">Score List</a></li>
                                    <li><a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                    </a></li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @endif

                            </ul>
                        </nav>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</header>