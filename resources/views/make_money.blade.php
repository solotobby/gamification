@extends('layouts.master')
@section('title', 'Make Money')

@section('content')

    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">Make Money</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Make Money</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->

    <!-- basic-contact-area -->
		<div class="basic-contact-area pt-90 pb-50">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 mb-40">
						<div class="contact-person">
							<h4>Four ways you can make money on Freebyz</h4>
							<p>
                                1.	You can complete tasks and earn. As a verified user, you have access to limitless jobs above N10 <br>
                                2.	You can hire workers to engage your social media posts to earn on Youtube, TikTok, etc <br>
                                3.	You can invite your friends using your referral link and earn N500 on each verified friend. Some users have cashed out more than 10,000 at a time by just referring friends and not completing tasks at all.
                                Hurry now to invite your friends and earn big time.  <br>
                                4.	You automatically earn login points each day you login and copy your referral code to social media. These points can be used for airtime, data or even withdrawal. Feel free to use any of these methods to earn big time on Freebyz. <br>
                                
                                Enjoy Life with Freebyz
                                <br>
                                Click <a href="{{ url('register') }}">here</a> to Get started and start making cool cash.
                            </p>
                        </div>
                    </div>
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
                                    <p>Sign up to get your referral code and start earning 500 NGN on every referral.
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
@endsection