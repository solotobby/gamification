<footer id="footer" class="footer footer-2">
                <div class="footer__bg js-lazy" data-src="img/bg/footer-2.svg"></div>
                <div class="wrapper">
                    <a href="{{ url('/') }}" class="logo-footer">
                        <img src="{{ asset('asset/img/logo.svg') }}" alt="Gamzoe">
                    </a>
                    <div class="socials-item footer-social">
                        <div class="footer-title">Find us here:</div>
                        <div class="socials">
                            <div class="socials__item">
                                <a href="#" target="_blank" class="socials__link">Fb</a>
                            </div>
                            <div class="socials__item">
                                <a href="#" target="_blank" class="socials__link">Ins</a>
                            </div>
                            
                        </div>
                    </div>
                    {{--  <div class="phone-item footer-phone">
                        <div class="footer-title footer-title_phone">Have a question? Call us!</div>
                        <div class="footer-phone__item">
                            <i class="icon-phone"></i><a href="tel:+15469872185">+1 546 987 21 85</a>
                        </div>
                    </div>  --}}
                    <a href="{{ url('auth/google') }}" class="btn-2 btn_started">get started</a>
                    {{--  <a href="#formOrder" class="btn-2 btn_started js-fancybox">get started</a>  --}}
                </div>
                <div class="footer-bottom">
                    <div class="wrapper">
                        <div class="copyrights">©All rights reserved. Gamzoe  {{ date('Y') }}</div>
                        <div class="footer-menu">
                            <ul class="js-menu-footer">
                                <li>
                                    <a href="#services">Services</a>
                                </li>
                                <li>
                                    <a href="#about">About</a>
                                </li>
                                <li>
                                    <a href="#steps">Steps</a>
                                </li>
                                <li>
                                    <a href="#price">Price</a>
                                </li>
                                <li>
                                    <a href="#testimonials">Testimonials</a>
                                </li>
                                <li>
                                    <a href="#blog">Blog</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>