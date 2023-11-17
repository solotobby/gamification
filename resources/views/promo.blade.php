@extends('layouts.master')
@section('title', 'Freebyz Promo')
@section('content')
 <!-- basic-breadcrumb start -->
 <div class="basic-breadcrumb-area gray-bg ptb-70">
    <div class="container">
        <div class="basic-breadcrumb text-center">
            <h3 class="">Freebyz Giveaway</h3>
            <ol class="breadcrumb text-xs">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li class="active">Freebyz Giveaway</li>
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
                        <h4>Christmas Giveaway</h4>

                        <p>
                           {{-- We are giving out <strong> 100 LIVE chickens and 50 Bags of Rice</strong> for Christmas, claim yours here --}}
                           As part of our dreams to put smiles on the faces of our numerous users, we are giving out
                            <strong>100 LIVE CHICKENS and 50 Bags of Rice</strong> to very active users this christmas.
                        </p>

                        <p>
                            <b>
                                Please read the steps to claim yours below
                            </b>
                                <br>
                                1. Users with 100 verified referrals between November 20th and December 20th, 2023 will be given 1 LIVE CHICKEN (in addition to their referral bonus).<br>
                                2. Users with 300 verified referrals between November 20th and December 20th, 2023 will be given 1 BAG of RICE  (in addition to their referral bonus).<br>
                                3. This giveaway is currently applicable to users globally. Users in Nigeria will provide their full address and active phone numbers for delivery through our agents.<br>
                                4. Only verified referrals between November 20th and December 20th, 2023 will be considered<br>
                                5. Additional monthly referral bonus for bronze, silver and gold membership will not apply during this period<br>
                                6. Users will be able to track their number of referrals from their dashboard. You are free to use any social media, blogs or paid adverts to promote your referral links<br>
                                7. Please provide your full address and active phone numbers below to receive your gift items. Thank you for using FREEBYZ<br>
                            <br>
                        </p>

                        <a href="{{ url('register') }}" class="btn btn-primary">Get Started Now</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection