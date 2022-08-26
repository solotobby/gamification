<footer>
			<div class="basic-footer text-center ptb-90">
				<div class="container">
					<div class="footer-logo mb-30">
						<h3><a href="{{ url('/') }}"> <span class="icon-happy"></span> Freebyz</a></h3>
					</div>
					<div class="social-icon">
						<a href="#"><i class="ion-social-facebook"></i></a>
						{{-- <a href="#"><i class="ion-social-googleplus"></i></a> --}}
						<a href="#"><i class="ion-social-instagram"></i></a>
						<a href="#"><i class="ion-social-twitter"></i></a>
						{{-- <a href="#"><i class="ion-social-dribbble"></i></a> --}}
					</div>
					<div class="footer-menu mt-30">
						<nav>
							<ul>
								<li><a href="{{ url('/') }}">Home</a></li>
								<li><a href="{{ route('goal') }}">Goal</a></li>
								<li><a href="{{ route('FAQ') }}">FAQ</a></li>
                                <li><a href="{{ route('track.record') }}">Track Rcord</a></li>
								<li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
								<li><a href="{{ route('terms') }}">Terms of Use</a></li>
							</ul>
						</nav>
					</div>
					<div class="copyright mt-20">
						<p>All copyright © reserved by <a href="{{ url('/') }}"><span class="icon-happy"></span> Freebyz</a> <?php echo date('Y') ?></p>
					</div>
				</div>
			</div>
		</footer>