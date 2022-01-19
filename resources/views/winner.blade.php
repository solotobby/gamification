@extends('layouts.master')
@section('title', 'Winner List')

@section('content')
    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">List of Winner</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Winner List</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->

  <!-- basic-team-area start -->
		<div class="basic-team-area  pt-90 pb-60">
			<div class="container">
				<div class="area-title text-center">
					<h2>Our Winners</h2>
					<p></p>
				</div>
				<div class="row">
                    @foreach ($winners as $winner)
                    <div class="col-sm-6 col-md-3">
						<div class="team-item">
							<div class="team-item-image">
                                <img src="https://www.pngitem.com/pimgs/m/111-1114839_circle-people-icon-flat-png-avatar-icon-transparent.png" alt="{{ $winner->user->name }}">
                                
                                {{--  @if($winner->user->avatar == '')
                                <img src="https://www.pngitem.com/pimgs/m/111-1114839_circle-people-icon-flat-png-avatar-icon-transparent.png" alt="{{ $winner->user->name }}">
                                @else
								<img src="{{ $winner->user->avatar }}" alt="{{ $winner->user->name }}">
                                @endif  --}}
							</div>
							<div class="team-item-detail">
								<h4 class="team-item-name">{{ $winner->user->name }}</h4>
								<span class="team-item-role">{{ $winner->reward_type }} | {{ $winner->score }}%</span>
                                <hr>
								<h3 class="team-item-name">{{ $winner->game->name }}</h3>
								{{--  <div class="team-social-icon">
									<a href="#">
										<i class="ion-social-facebook"></i>
									</a>
									<a href="#">
										<i class="ion-social-googleplus"></i>
									</a>
									<a href="#">
										<i class="ion-social-instagram"></i>
									</a>
									<a href="#">
										<i class="ion-social-dribbble"></i>
									</a>
								</div>  --}}
							</div>
						</div>
					</div>
                        
                    @endforeach

				</div>
			</div>
		</div>
		<!-- basic-team-area end -->
		

@endsection