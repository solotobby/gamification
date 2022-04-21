@extends('layouts.master')

@section('title', 'List of Games')

@section('content')

{{--  This will display the  games  that hwas created by the admin  --}}


    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area white-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">List of Game</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">List of Games</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->


    <div class="basic-service-area gray-bg pt-90 pb-50">
			<div class="container">
				<div class="area-title text-center">
					<h2>List of Games</h2>
					{{-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi tempora veritatis nemo aut ea iusto eos est
						expedita, quas ab adipisci.</p> --}}
				</div>
				<div class="row">

                    @foreach ($games as $game)
                        <div class="col-md-4 col-sm-6 mb-40">
						<div class="service-box white-bg">
							<div class="service-icon">
								<span class="icon-trophy"></span>
							</div>
							<div class="service-content">
								<h3>{{ $game->name }}</h3>

                                <div class="row">
                                     @if(Auth::user())
                                            @if($game->status == '1')
                                                <a class="btn btn-round btn-dark" href="{{ route('instruction') }}">Play Game</a>
                                            @else
                                                <a class="btn btn-round btn-dark" href="" disabled>Game Play Exipred</a>
                                            @endif
                                        @else
                                            @if($game->status == '1')
                                                <a class="btn btn-round btn-dark" href="{{ url('auth/google') }}">Get Started</a>
                                            @else
                                                <a class="btn btn-round btn-dark" href="" disabled>Game Play Exipred</a>
                                            @endif
                                    @endif
								
                                </div>

                                
							</div>
						</div>
					</div>
                    @endforeach
					
				</div>
			</div>
		</div>



@endsection