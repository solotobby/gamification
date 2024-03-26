@extends('layouts.master')
@section('title', 'WellaHealth X Freebyz')


@section('content')

    <!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">WellaHealth</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">WellaHealth</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->
    <div class="basic-contact-area pt-90 pb-50">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 mb-40">
                    <div class="contact-person">
                        <h3>Health is Wealth</h3>
                        <p>
                            <b>Buy affordable healthcare plans for your friends and family and earn up to 7% discounts and bonuses.</b>
                            Wellahealth provides healthcare coverage (drugs, tests and treatment) for common illnesses such as malaria, 
                            typhoid, and cashback for hospital stays, starting at just N600/month. This allows you to access these healthcare 
                            in Pharmacies and Hospitals around you.
                        </p>
                        <p>

                            <b>How to make money from selling healthcare plans</b><br>
                            1. This is an amazing opportunity for you and your friends to spend less on drugs, medical check ups and hospital bills, ultimately to help you get reliable, affordable, and fast access to healthcare. <br>
                            2. To get started, reach out to your friends and family members to share the advantages of health insurance and the healthcare plans available (see the healthcare options available below).<br>
                            3. Generate your unique wellahealth link (not referral link) from your Freebyz dashboard to share with your friends and family who need the healthcare insurance package.<br>
                            4. Wellahealth care is currently accessible in Nigeria however as a Freebyz user outside Nigeria, you can share your Wellahealth link with your friends in Nigeria so that you’ll earn up to 7% when they pay for the healthcare plan.<br>
                            5. As soon as payment is made, the end user is contacted via email & Whatsapp by Wellahealth while you receive up to 7% bonus in your Freebyz account for referring them.<br>
                            6. Wellahealth is not a HMO or insurance company, but works with insurers to make healthcare more affordable and accessible. The opportunity to earn bonuses from selling healthcare plans is brought to you by Freebyz Technologies Ltd and Wellahealth Technologies Ltd to enable you to get reliable, affordable, and fast access to healthcare.<br>


                        </p>

                        <p>
                            <strong>Frequently Asked Questions</strong><br>
                            <b>How do I receive my bonus for selling Wellahealth care plans</b><br>
                            To receive your bonus for selling Wellahealth care plans, copy your wellahealth link from your Freebyz dashboard to share with your friends and family who need the healthcare insurance package. Once payment is made by the end user, your Freebyz wallet will be credited
                            <br><b>As a Freebyz user, can I buy an health cover plan for myself or my family</b><br>
                            Yes, you need reliable, affordable, and fast access to healthcare as well. You can copy your referral link and pay as an end user, you’ll still get your bonus
                           <br><b> How do I access the drugs or health plans after payment?</b><br>
                            As soon as payment is completed for a wellahealth cover,  you’ll receive an email and a call from a Wellahealth agent with instructions on your nearest pharmacy and hospital to receive the healthcare. 

                        </p>

                        <a class="btn" href="{{ url('register') }}">Get Started Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>



							

<!-- basic-contact-area end -->


@endsection

