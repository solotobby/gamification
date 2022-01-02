@extends('layouts.master')


@section('content')

<main class="content">
                <div class="first-screen section-screen-main">
                    <div class="section-screen-main__bg" style="background-image: url({{ asset('asset/img/main.svg') }});"></div>
                    <div class="wrapper">
                        <div class="screen-main">
                            <div class="section-heading"><span>Be sure</span></div>
                            <h1 class="h1 h1-main">play and win in&nbsp;your&nbsp; prizes</h1>
                            <div class="screen-main__text">Agency with 12&nbsp;years of history, 15&nbsp;employees, Fortune 5000&nbsp;clients and proven results.</div>
                            @if(Auth::user())
                            <a href="{{ route('take.quiz') }}" class="btn btn_learn">Play Game</a>
                            @else
                            <a href="{{ url('auth/google') }}" class="btn btn_learn">Get Started</a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="section-about" id="about">
                    <div class="wrapper">
                        <div class="about">
                            <div class="about__img">
                                <div class="about__picture">
                                    <img data-src="{{ asset('asset/img/way.svg') }}" alt="" class="js-lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=">
                                </div>
                            </div>
                            <div class="about__content">
                                <div class="section-heading"><span>the history</span></div>
                                <div class="h2">Our way to succesful future</div>
                                <div class="section-subtitle">Sint nulla commodo qui magna eiusmod quis aliqua laboris officia excepteur non eu in.</div>
                                <div class="content-block__text">
                                    <p>Dolor duis voluptate enim exercitation consequat ex. Voluptate in sunt commodo aute do. Dolor enim dolor labore velit nulla sit exercitation irure esse proident velit commodo. Est non officia proident esse culpa commodo nulla Lorem do enderit esse do.</p>
                                </div>
                                <a href="https://www.youtube.com/watch?v=_sI_Ps7JSEk" class="about__btn play-video js-fancybox">
                                    <span class="play-icon">
                                    <i class="icon-play"></i>
                                    </span>
                                    <div class="play-video__text">
                                        <div class="play-video__title">about us</div>
                                        <div class="play-video__link">Watch our process!</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="about-details">
                            <div class="about-details__item">
                                <div class="about-details__val">$ 35k<span class="about-details__val_plus">+</span></div>
                                <div class="about-details__text">Clients revenue</div>
                                <div class="about-details__decor"></div>
                            </div>
                            <div class="about-details__item">
                                <div class="about-details__val">16k<span class="about-details__val_plus">+</span></div>
                                <div class="about-details__text">Leads for clients</div>
                                <div class="about-details__decor"></div>
                            </div>
                            <div class="about-details__item">
                                <div class="about-details__val">6.7k<span class="about-details__val_plus">+</span></div>
                                <div class="about-details__text">Phone calls</div>
                                <div class="about-details__decor"></div>
                            </div>
                            <div class="about-details__item">
                                <div class="about-details__val">254<span class="about-details__val_plus">+</span></div>
                                <div class="about-details__text">Successful projects</div>
                                <div class="about-details__decor"></div>
                            </div>
                        </div>
                    </div>
                    <div class="about-decor about-decor_1"></div>
                    <div class="about-decor about-decor_2"></div>
                    <div class="about-decor about-decor_3"></div>
                </div>
                <div class="section-get" id="winnings">
                    <div class="wrapper">
                        <div class="section-heading h-center"><span>Potential Winnings</span></div>
                        <div class="h-decor-1">
                            <h2 class="h2 h-center"><span>what you will get with us</span></h2>
                            <div class="section-subtitle h-center">Play some trivia games and win prizes </div>
                        </div>
                        <div class="get-list">
                            <div class="get-list__item">
                                <div class="get-list__heading">
                                    <div class="get-list__icon">
                                        <img src="{{ asset('asset/img/icons-svg/get-1.svg') }}"  alt="" loading="lazy">
                                    </div>
                                    <div class="get-list__title">Cash</div>
                                </div>
                                <div class="get-list__text">Dolor duis voluptate enim exercitation consequat ex. Voluptate in sunt commodo aute do. Dolor enim dolor labore velit nulla sit exercitation irure esse proid.</div>
                            </div>
                            <div class="get-list__item">
                                <div class="get-list__heading">
                                    <div class="get-list__icon">
                                        <img src="{{ asset('asset/img/icons-svg/get-2.svg') }}"  alt="" loading="lazy">
                                    </div>
                                    <div class="get-list__title">Recharge Cards</div>
                                </div>
                                <div class="get-list__text">Voluptate in sim dolor labore velit nuunt commodo aute do. Dolor enim dolor labore im dolor labore velit te dolor enim dolor labore velit nul.</div>
                            </div>
                            <div class="get-list__item">
                                <div class="get-list__heading">
                                    <div class="get-list__icon">
                                        <img src="{{ asset('asset/img/icons-svg/get-3.svg') }}"  alt="" loading="lazy">
                                    </div>
                                    <div class="get-list__title">Data Bundles</div>
                                </div>
                                <div class="get-list__text">Pariatur magna cupidatat magna sit incididunt non pariatur. Sint nulla commodo qui magna eiusmod quis aliqua laboris officia excepteur non eu in.</div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="section-consultation" id="contact">
                    <div class="section-consultation__bg js-lazy" data-src="{{ asset('asset/img/bg/bg-2.svg') }}"></div>
                    <div class="wrapper">
                        <div class="consultation-form-wrap">
                            <div class="consultation-form">
                                <div class="section-heading"><span>get started</span></div>
                                <h2 class="h2">get in touch with us</h2>
                                <div class="content-block__text">
                                    <p>You have any concern or suggesion, reach out to us here</p>
                                </div>
                                <div class="consultation-form__form">
                                    <form onsubmit="successSubmit();return false;">
                                        <div class="box-fileds">
                                            <div class="box-filed">
                                                <input type="text" placeholder="First name">
                                            </div>
                                            
                                            <div class="box-filed">
                                                <input type="email" placeholder="Enter your email">
                                            </div>
                                            <div class="box-filed">
                                                <textarea name="message" placeholder="We would love to have a feel"> </textarea>
                                                {{--  <input type="text" placeholder="Second name">  --}}
                                            </div>
                                            <div class="box-filed">
                                                {{--  <input type="text" placeholder="Second name">  --}}
                                            </div>
                                            <div class="box-filed box-filed_btn">
                                                <input type="submit" class="btn" value="Submit">
                                            </div>
                                        
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="consultation-img">
                                <img data-src="{{ asset('asset/img/consultation.svg') }}" alt="" class="js-lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=">
                            </div>
                        </div>
                    </div>
                </div>
                
                {{--    --}}
                
                <div class="section-newsletter">
                    <div class="section-newsletter__bg js-lazy" data-src="{{ asset('asset/img/bg/bg-3.svg') }}"></div>
                    <div class="wrapper">
                        <div class="newsletter">
                            <div class="newsletter__content">
                                <h3 class="h3">Newsletter</h3>
                                <div class="newsletter__text">
                                    <p>Pariatur magna cupidatat magna sit incididunt non pariatur. Sint nulla commodo qui magna eiusmod quis aliqua laboris officia excepteur non eu in.</p>
                                </div>
                                <form>
                                    <div class="box-fileds-newsletter">
                                        <div class="box-filed box-filed_1">
                                            <input type="email" placeholder="Enter your email">
                                        </div>
                                        <div class="box-filed box-filed_submit">
                                            <input type="submit" class="btn" value="Subscribe">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="newsletter__img">
                                <div class="newsletter__picture">
                                    <img data-src="{{ asset('asset/img/newsletter.svg') }}" alt="" class="js-lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-testimonials" id="testimonials">
                    <div class="wrapper">
                        <div class="testimonials">
                            <div class="testimonials__img">
                                <div class="testimonials__picture">
                                    <img data-src="{{ asset('asset/img/testimonials.svg') }}" alt="" class="js-lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=">
                                </div>
                            </div>
                            <div class="testimonials__content">
                                <div class="section-heading"><span>they say</span></div>
                                <div class="h2">Testimonials</div>
                                <div class="swiper-container reviews-slider js-slider-1">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide testimonials-card">
                                            <div class="testimonials-card__text">
                                                <p>“Dolor duis voluptate enim exercitation consequat ex. Voluptate in sunt commodo aute do. Dolor enim dolor labore velit nulla sit exercitation irure esse proident.”</p>
                                            </div>
                                            <div class="author">
                                                <img class="author__img js-lazy" data-src="{{ asset('asset/img/examples/avatar_1.jpg') }}" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" alt="">
                                                <div class="author__details">
                                                    <div class="author__title">Kathryn Murphy</div>
                                                    <div class="author__position">Marketing Coordinator</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide testimonials-card">
                                            <div class="testimonials-card__text">
                                                <p>“2 Dolor duis voluptate enim exercitation consequat ex. Voluptate in sunt commodo aute do. Dolor enim dolor labore velit nulla sit exercitation”</p>
                                            </div>
                                            <div class="author">
                                                <img class="author__img js-lazy" data-src="{{ asset('asset/img/examples/avatar_1.jpg') }}" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" alt="">
                                                <div class="author__details">
                                                    <div class="author__title">Kathryn Murp</div>
                                                    <div class="author__position">Marketing Coord</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </main>
@endsection