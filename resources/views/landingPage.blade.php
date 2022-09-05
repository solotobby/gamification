@extends('layouts.master')
@section('title', 'Affiliate Marketing Hub | Make Money Online')
@section('style')

<style>
	.carousel-item {
		height: 100vh;
		min-height: 350px;
		background: no-repeat center center scroll;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}
	</style>
@endsection
@section('content')
<!-- basic-slider start -->

{{-- <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url({{ asset('asset/img/freebyz_3.jpg') }})"> --}}
        {{-- <div class="carousel-caption">
          <h5>First slide label</h5>
          <p>Some representative placeholder content for the first slide.</p>
        </div> --}}
      {{-- </div> --}}
      {{-- <div class="carousel-item" style="background-image: url({{ asset('asset/img/freebyz_1.jpg') }})"> --}}
        {{-- <div class="carousel-caption">
          <h5>Second slide label</h5>
          <p>Some representative placeholder content for the second slide.</p>
        </div> --}}
      {{-- </div> --}}
      {{-- <div class="carousel-item" style="background-image: url('https://source.unsplash.com/szFUQoyvrxM/1920x1080')"> --}}
        {{-- <div class="carousel-caption">
          <h5>Third slide label</h5>
          <p>Some representative placeholder content for the third slide.</p>
        </div> --}}
      {{-- </div> --}}
    {{-- </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div> --}}


<div class="slider-section">
	
	<div class="slider-active owl-carousel">
		<div class="single-slider"  style="background-image: url({{ asset('asset/img/cup.jpg') }});">
			<div class="container">
				<div class="slider-content">
					<h2 style="color: aliceblue">Earn &#8358;10,000+ Daily on Social Media Jobs...
						{{-- <span class="dot"></span>
						<span class="dot"></span>
						<span class="dot"></span> --}}
					</h2>
					{{-- <p>doing social work</p> --}}
					<a class="btn" href="{{ url('register') }}">Get Started Now</a>
				</div>
			</div>
		</div>
		{{-- <div class="single-slider" style="background-image: url({{ asset('asset/img/freebyz_2.jpg') }});">
			<div class="container">
				<div class="slider-content">
					<h2><br /><br /><br /><br /> --}}
						{{-- <span class="dot"></span>
						<span class="dot"></span>
						<span class="dot"></span> --}}
					{{-- </h2> --}}
					{{-- <p>Design | Development | Branding</p>
					<a class="btn" href="#">About Us</a> --}}
				{{-- </div>
			</div>
		</div> --}}
		{{-- <div class="single-slider" style="background-image: url({{ asset('asset/img/freebyz_1.jpg') }});">
			<div class="container">
				<div class="slider-content">
					<h2><br /><br /><br /><br />  --}}
						{{-- <span class="dot"></span>
						<span class="dot"></span>
						<span class="dot"></span> --}}
					{{-- </h2> --}}
					{{-- <p>Design | Development | Branding</p>
					<a class="btn" href="#">About Us</a> --}}
				{{-- {{-- </div> --}}
			{{-- </div>
		</div>
	</div>  --}}
</div>
<!-- basic-slider end -->

        {{-- <div class="ptb-150 border-t-b" style="background-image: url({{ asset('asset/img/cup.jpg') }})">
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
                                    {{-- <a class="btn" href="{{ url('auth/google') }}" style="color: azure">Get Started</a> --}}
                                @endif
						{{-- </div>
					</div>
				</div>
			</div>
		</div>  --}}
		<!-- basic-slider end -->


        <div class="basic-service-area white-bg pt-90 pb-50">
			<div class="container">
				<div class="area-title text-center">
					<h2>Welcome to Freebyz</h2>
					<p>
						Freebyz was created for you to make cool cash everyday by doing simple social media jobs on Youtube, Facebook, TikTok, Instagram and other social media platforms. <br>
						Use Freebyz to answer survey questions, play games and install applications to make money online daily.
						On Freebyz, you can hire online workers to increase your business visibility and organic growth through engagements on Facebook, Instagram, YouTube, TikTok, WhatsApp and Twitter. 
						<br>Our workers will engage your post real time to drive massive sales for you.
						When you tell your friends about Freebyz, We will reward you with N250 on each friend and instant bonus of N5,000 when you reach 50 verified referral limit. 
					</p>
					{{-- <p>
						Freebyz was created for you to make cool cash everyday by doing simple social media jobs 
						or increasing your business visibility and organic growth through engagements on your
						 posts on Facebook, Instagram, YouTube, TikTok, WhatsApp and Twitter.<br>
						On top of that, we reward you with 250 NGN everytime you referral a friend. 
						We just want to reward you for every minute you spend on Freebyz!
					</p> --}}
					 {{-- <p>The platform is created to help build your mind, educate you and then sprinkle a bit of love 
						 and smile on your faces. No matter how small it is, you are rewarded with airtime, data bundles 
						 and cash for playing our weekly games. <br>This might look a bit off, but it is what it is! Just play
						  games that are available, perform exceptionally well and earn a little more. We hope to do more, 
						  change the world and spread a little kindness across! :) 
					</p>  --}}
				</div>

			</div>
		</div>

        <div class="basic-service-area gray-bg pt-90 pb-50">
			<div class="container">
				<div class="area-title text-center">
					<h2>How it works</h2>
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
								<h3>Sign Up</h3>
									<p>Sign up to get your referral code and start earning 250 NGN on every referral.
										 Your referral code is your affiliate link to earn more money. Start Earning Now...
										 {{-- More referrals means fat wallet for you. We pay every Friday. --}}
										 </p>
								{{-- <p>Candidates or users that perform excellently will be eligible for our
									 cash rewards. The cash will be paid directly into the candidates account. 
									 The amount to be paid to our best performing candidates would be determined by the
									  Freebys administrator. We hope to see you do well!
								</p> --}}
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 mb-40">
						<div class="service-box white-bg">
							{{-- <div class="service-icon">
								<span class="icon-gears"></span>
							</div> --}}
							<div class="service-content">
								<h3>Earn and Cash out</h3>
								<p>
									Login to your dashboard to access available jobs. You can earn as much as 
									10,000 NGN daily when you like, share, comment on a post or play games.
									{{-- Airtime rewards will be sent to candidates that perform well. 
									This would be sent to their desired phone numbers. The candidates can redeem 
									their rewards electronically. Our Airtime reward value is also determined by the Freebys Administrator --}}
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
								<h3>Hire Workers</h3>
								<p>
									Hire social media workers to engage your posts on your blog and all 
									social media channels for organic growth &  increased visibility for more sales.
									{{-- We also give Data Bundles to encourage you to study more and make 
									good researches. This will be processed the same way the Airtime rewards are shared. 
									A minimum of 1gig data will be sent to candidates with good scores. This reward is still being 
									processed at the moment! --}}
								</p>
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
							<h3>wonna start  Earning...</h3>
							<p>Click the Get Started button to start earning cool cash from your social media accounts.</p>
							{{-- <p>Click on the <b>Get Started</b> button to start on the available game.</p> --}}
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 text-right">
						<div class="call-to-action">
                            @if(Auth::user())
                                    {{-- <a class="btn btn-large" href="{{ route('instruction') }}">Play Game</a> --}}
                                @else
                                    <a class="btn btn-large" href="{{ url('register') }}">Get Started</a>
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
							{{-- <div class="counter-icon">
								<span class=" icon-trophy"></span>
							</div> --}}
							<div class="counter-text">
								<h1 class="counter">150</h1>
								<span>Available Jobs</span>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
						<div class="counter-wrapper text-center mb-30 wow fadeInUp" data-wow-delay=".6s">
							{{-- <div class="counter-icon">
								<span class="icon-alarmclock"></span>
							</div> --}}
							<div class="counter-text">
								<h1 class="counter">2546</h1>
								<span>Users</span>
							</div>
						</div>
					</div>
					{{-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
						<div class="counter-wrapper text-center mb-30 wow fadeInUp" data-wow-delay=".9s">
							<div class="counter-icon">
								<span class="icon-happy"></span>
							</div>
							<div class="counter-text">
								<h1 class="counter">{{ $gameplayed }}</h1>
								<span>Games Played</span>
							</div>
						</div>
					</div> --}}
					 <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
						<div class="counter-wrapper text-center mb-30 wow fadeInUp" data-wow-delay="1.2s">
							{{-- <div class="counter-icon">
								<span class="icon-megaphone"></span>
							</div> --}}
							<div class="counter-text">
								<h1 class="counter">565</h1>
								<span>Completed Jobs</span>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</div>
		<div class="basic-contact-area pt-90 pb-50">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 mb-40">
						<div class="area-title text-center">
							<h2>Frequently Asked Questions</h2>
							
						</div>
						<div class="contact-person">
							<h4>How do I start earning on Freebyz?</h4>
							<p>
                                Click on <a href="https:/freebyz.com/register"><b>www.freebyz.com</b></a> then sign up to start earning on Freebyz. Several online Jobs are waiting for you. You can earn big by referring your friends. We will reward you with 250 on each friend you refer to Freebyz.
                            </p>

                            <h4>How can I withdraw my money on Freebyz?</h4>
							<p>
								You can withdraw your funds at any time in Naira or Dollars. We pay out every Friday to your bank account and via Paypal. The minimum withdraw fund is ₦500 (or $5 via Paypal).  Sign up to start earning now. Click on www.freebyz.com then sign up to start earning on Freebyz. Several online Jobs are waiting for you. You can earn big by referring your friends. We will reward you with N250 on each friend you refer to Freebyz.
							</p>
							<h4>How do I withdraw in Dollars?</h4>
							<p>Once you place a withdrawal request to withdraw in Dollars, your earnings (funds) will be converted to dollars and then sent to the Paypal account you supplied.</p>
                            
                            <h4>How do I become an affiliate marketer on Freebyz?</h4>
                            <p>
								We want you to enjoy our referral bonus hence we created an affiliate link/referral code for everyone who signs up on Freebyz. We will reward you with N250 on each friend you refer to Freebyz. Your friends must have a verified account for you to earn referral bonus.
							</p>
							<h4>What are the advantages of verifying my account?</h4>
							<p>A verified account is an authorized user on Freebyz which gives you 1. Lifetime access to jobs- You will get unlimited access to available jobs including premium ones. 2. Access to Hire Workers- You will have unlimited access to hire workers to promote your youtube channel, social handles and promote your flyers/event banners. 3. Unlimited Withdrawals- Access to withdraw all your earnings and other juicy offers coming up shortly</p>


                            <h4>What are the advantages of verifying my account?</h4>
                            <p>A verified account is an authorized user on Freebyz which gives you access to
                                Lifetime access to jobs- You will get unlimited access to available jobs including premium ones
                                Hire Workers- You will have unlimited access to hire workers to promote jobs.
                                Unlimited Withdrawals- Access to withdraw all your earnings and other juicy offers coming up shortly
                            </p>

                            <h4>How authentic is Freebyz.com?</h4>
                            <p>
                                Seeing is believing. We have a track record and social proof. Since 2018, we have published Job opportunities to millions of Job seekers via our blog - <a href="https://myhotjobz.com">www.myhotjobz.com.</a><br>
                                Our new digital solution, Freebyz is borne out of a keen passion to connect small and medium scale businesses, creators and entrepreneurs with online workers who will work and earn money online legitimately. 
								Feel Free to use FREEBYZ to create wealth from online jobs and our affiliate marketing referral rewards.
                            </p>

                            <h4>How can I get real time (organic) followers and subscribers for my Youtube Channel, Facebook, TikTok, Twitter and Instagram on Freebyz?</h4>
                            <p>
								Freebyz is committed to increasing your business visibility and organic growth through engagements on your posts on Facebook, Instagram, YouTube, TikTok, WhatsApp and Twitter. Sign up to hire real time workers to engage your post now
							</p>

                            <h4>How can I sell my product online through Freebyz?</h4>
                            <p>
								You can hire online workers to share your flyers online on Whatsapp status, Whatsapp group, Facebook, Twitter Instagram and other social media platforms at the cheapest rate. Sign up now on freebyz.com to hire workers instantly.
                            </p>
                            <h4>I have issues with my card. How can I fund my wallet or verify my account without using my debit can?
                            </h4>
                            <p>You can do Manual Funding by sending the Fee to our account. We will activate/verify your account from our back end. Kindly attach a receipt of payment to freebyzcom@gmail.com after payment. 
                                <br><b>ACCOUNT DETAILS: 1014763749 - DOMINAHL TECH SERVICES (ZENITH)</b>
                            </p>
                            <h4> How much can I withdraw from FREEBYZ</h4>
                            <p>
                                You can withdraw as low as N500 to your account. Your account number must match the name you used to register on FREEBYZ. You can also use your money to buy airtime/recharge card on FREEBYZ
                            </p>
                            <h4>
                                Can I work and make money on Freebyz without verifying my account?
                            </h4>
                            <p>
                                Yes, you can work and make money on Freebyz without verifying your account but you will only have access to small jobs (not highly paid jobs) and will not be able to withdraw your account because your identity is not verified. Learn more on benefits of verifying your accounts <a href="{{ url('faq') }}" target="_blank">here</a>
                            </p>
                            <h4>
                                How can I hire workers on FREEBYZ?
                            </h4>
                            <p>
                                To hire workers on Freebyz, sign up or login to your account and select ‘post campaign’. You will be able to select the available job categories and then hire the workers.
                            </p>
						</div>
					</div>
				</div>
			</div>
		</div>
		



@endsection