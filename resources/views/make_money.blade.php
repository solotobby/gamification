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
							<h4></h4>
							<p>
                                Welcome to Freebyz<br>

                                You can earn as much as  10,000 NGN daily when you like, share, comment on a post or play games on Freebyz!
                               
                               You can also hire social media workers to engage your posts on your blog and all social media channels for organic growth &  increased visibility for more sales.
                               
                               On Freebyz, you earn N250 on each referral using your referral code on your dashboard.<br>
                               Click <a href="{{ url('register') }}">here</a> to Get started and start making cool cash.

                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection