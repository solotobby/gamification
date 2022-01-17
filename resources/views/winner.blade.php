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

					<div class="col-sm-6 col-md-3">
						<div class="team-item">
							<div class="team-item-image">
								<img src="img/team/team01.jpg" alt="team member">
							</div>
							<div class="team-item-detail">
								<h4 class="team-item-name">Rchard Pol</h4>
								<span class="team-item-role">Designer</span>
								<div class="team-social-icon">
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
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- basic-team-area end -->
		

@endsection