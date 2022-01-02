@extends('layouts.master')


@section('content')

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

@endsection