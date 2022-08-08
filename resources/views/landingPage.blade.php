@extends('layouts.master')
@section('title', 'Get Rewards quizzes')

@section('content')

        <div class="ptb-150 border-t-b" style="background-image: url({{ asset('asset/img/cup.jpg') }})">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<div class="slider-content" >
							<h2 style="color: azure">Holla....Amazing stuffs for Amazing people
							</h2>
							{{-- <p style="color: azure">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by
								the
								charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound
								to
								ensue; and equal blame belongs to those.</p> --}}
                                @if(Auth::user())
                                    {{-- <a class="btn" href="{{ route('instruction') }}" style="color: azure">Play Game</a> --}}
                                @else
                                    <a class="btn" href="{{ url('auth/google') }}" style="color: azure">Get Started</a>
                                @endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- basic-slider end -->


        <div class="basic-service-area white-bg pt-90 pb-50">
			<div class="container">
				<div class="area-title text-center">
					<h2>Welcome to Freebyz</h2>
					 <p>The platform is created to help build your mind, educate you and then sprinkle a bit of love 
						 and smile on your faces. No matter how small it is, you are rewarded with airtime, data bundles 
						 and cash for playing our weekly games. <br>This might look a bit off, but it is what it is! Just play
						  games that are available, perform exceptionally well and earn a little more. We hope to do more, 
						  change the world and spread a little kindness across! :) 
					</p> 
				</div>

			</div>
		</div>

        <div class="basic-service-area gray-bg pt-90 pb-50">
			<div class="container">
				<div class="area-title text-center">
					<h2>Our Rewards</h2>
					{{-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi tempora veritatis nemo aut ea iusto eos est
						expedita, quas ab adipisci.</p> --}}
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-6 mb-40">
						<div class="service-box white-bg">
							{{-- <div class="service-icon">
								<span class="icon-pencil"></span>
							</div> --}}
							<div class="service-content">
								<h3>Cash Reward</h3>
								<p>Candidates or users that perform excellently will be eligible for our
									 cash rewards. The cash will be paid directly into the candidates account. 
									 The amount to be paid to our best performing candidates would be determined by the
									  Freebys administrator. We hope to see you do well!
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 mb-40">
						<div class="service-box white-bg">
							{{-- <div class="service-icon">
								<span class="icon-gears"></span>
							</div> --}}
							<div class="service-content">
								<h3>Airtime Reward</h3>
								<p>
									Airtime rewards will be sent to candidates that perform well. 
									This would be sent to their desired phone numbers. The candidates can redeem 
									their rewards electronically. Our Airtime reward value is also determined by the Freebys Administrator
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 mb-40">
						<div class="service-box white-bg">
							{{-- <div class="service-icon">
								<span class="icon-mobile"></span>
							</div> --}}
							<div class="service-content">
								<h3>Data Bundle Reward</h3>
								<p>We also give Data Bundles to encourage you to study more and make 
									good researches. This will be processed the same way the Airtime rewards are shared. 
									A minimum of 1gig data will be sent to candidates with good scores. This reward is still being 
									processed at the moment!</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


        <div class="call-to-action-area ptb-60">
			<div class="container">
				<div class="row">
					<div class="col-md-9 col-sm-9 col-xs-12">
						<div class="call-to-action">
							<h3>wonna start winning...</h3>
							<p>Click on the <b>Get Started</b> button to start on the available game.</p>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 text-right">
						<div class="call-to-action">
                            @if(Auth::user())
                                    <a class="btn btn-large" href="{{ route('instruction') }}">Play Game</a>
                                @else
                                    <a class="btn btn-large" href="{{ url('auth/google') }}">Get Started</a>
                            @endif
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="counter-area pt-150 pb-120 gray-bg">
			<div class="container">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
						<div class="counter-wrapper text-center mb-30 wow fadeInUp" data-wow-delay=".3s">
							<div class="counter-icon">
								<span class=" icon-trophy"></span>
							</div>
							<div class="counter-text">
								<h1 class="counter">{{ $prizesWon }}</h1>
								<span>Prizes Won</span>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
						<div class="counter-wrapper text-center mb-30 wow fadeInUp" data-wow-delay=".6s">
							<div class="counter-icon">
								<span class="icon-alarmclock"></span>
							</div>
							<div class="counter-text">
								<h1 class="counter">{{ $user }}</h1>
								<span>Users</span>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
						<div class="counter-wrapper text-center mb-30 wow fadeInUp" data-wow-delay=".9s">
							<div class="counter-icon">
								<span class="icon-happy"></span>
							</div>
							<div class="counter-text">
								<h1 class="counter">{{ $gameplayed }}</h1>
								<span>Games Played</span>
							</div>
						</div>
					</div>
					{{--  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
						<div class="counter-wrapper text-center mb-30 wow fadeInUp" data-wow-delay="1.2s">
							<div class="counter-icon">
								<span class="icon-megaphone"></span>
							</div>
							<div class="counter-text">
								<h1 class="counter">265</h1>
								<span></span>
							</div>
						</div>
					</div>  --}}
				</div>
			</div>
		</div>
		



@endsection