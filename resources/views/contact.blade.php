@extends('layouts.master')
@section('title', 'Contact Us')

@section('content')


    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">Contact Us</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Contact Us</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->

    <!-- basic-contact-area -->
		<div class="basic-contact-area pt-90 pb-50">
			<div class="container">
				<div class="row multi-columns-row">

					<div class="col-sm-12 col-md-12 col-lg-12 mb-40">
						<div class="contact-person">
							<h4>Chief Editor</h4>
							<p>We are always ready to hear from you.... We would love to attend to your needs and concern. Just send us a mail 
								and that would solve it....  

							</p>
							<a href="mailto:info@dominahl.com">Send Us A Mail Here</a>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<!-- basic-contact-area end -->


@endsection
