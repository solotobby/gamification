<footer>
			<div class="basic-footer text-center ptb-90">
				<div class="container">
					<div class="footer-logo mb-30">
						<h3><a href="{{ url('/') }}"> <span class="icon-happy"></span> Freebyz</a></h3>
					</div>
					<div class="social-icon">
						<a href="https://www.facebook.com/groups/freebyz/?ref=share_group_link" target="_blank"><i class="ion-social-facebook"></i></a>
						{{-- <a href="#"><i class="ion-social-googleplus"></i></a> --}}
						<a href="https://www.instagram.com/freebyzjobs" target="_blank"><i class="ion-social-instagram"></i></a>
						<a href="https://twitter.com/FreebyzHQ?t=DxsnTp0_sOdDSb08G_z45Q&s=09" target="_blank"><i class="ion-social-twitter"></i></a>
						{{-- <a href="https://www.tiktok.com/@freebyzjobs" target="_blank"><i class="fa-brands fa-tiktok"></i></a> --}}
					</div>
					<div class="footer-menu mt-30">
						<nav>
							<ul>
								<li><a href="{{ url('/') }}">Home</a></li>
								<li><a href="{{ route('goal') }}">Goal</a></li>
								<li><a href="{{ route('faq') }}">FAQ</a></li>
                                <li><a href="{{ route('track.record') }}">Track Record</a></li>
								<li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
								<li><a href="{{ route('terms') }}">Terms of Use</a></li>
							</ul>
						</nav>
					</div>
					<div class="copyright mt-20">
						<p>All copyright © reserved by <a href="{{ url('/') }}"><span class="icon-happy"></span> Freebyz</a> <?php echo date('Y') ?></p> 
						{{-- <p><span class="icon-happy"></span> Freebyz By <b>Dominahl Technology LLC</b></p> --}}
					</div>
				</div>
			</div>
		</footer>