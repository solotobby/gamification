@extends('layouts.main.master')

@section('style')

<style>
  .tooltip {
    position: relative;
    display: inline-block;
    color: :black;
  }
  
  .tooltip .tooltiptext {
    visibility: hidden;
    width: 140px;
    background-color: #555;
    color: black;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 150%;
    left: 50%;
    margin-left: -75px;
    opacity: 0;
    transition: opacity 0.3s;
  }
  
  .tooltip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
  }
  
  .tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
  }
  </style>

@endsection

@section('content')
 <!-- Hero Section -->
 <div class="bg-body-extra-light text-center">
  
    <div class="content content-boxed content-full py-5 py-md-7">
      <div class="row justify-content-center">
        <div class="col-md-10 col-xl-10">
          <h1 class="h2 mb-2">

            
            Complete simple jobs today and get <span class="text-primary">paid</span>.
          </h1>
       
          <p>Earn 500 NGN each time you refer a friend. <br>
            <small style="color: chocolate">Note: Your friend must be a verified user</small></p>
       
          </p>

         
          <center>
            <div class="col-md-6 mb-3">
              <div class="input-group">
                <input type="text" value="{{url('register/'.auth()->user()->referral_code)}}" class="form-control form-control-alt" id="myInput">
                <button type="button" class="btn btn-alt-secondary" onclick="myFunction()" onmouseout="outFunc()">
                  <i class="fa fa-copy"></i>
                </button>
              </div>
            </div>
          </center>
        
          
         
          <p style="color:brown">
            We'll reward you with ₦55,000($78) when you refer 100 verified users and ₦510,000 ($728) when you refer 1,000 verified users.
          </p>
          {{-- <p>
            <a href="{{ route('assign.virtual.account') }}" class="btn btn-primary">Get Virtual Account</a>
          </p> --}}
          
        </div>
      </div>
      
      <div class="d-flex justify-content-center align-items-center mt-5">
        <div class="px-2 px-sm-5">
          <p class="fs-3 text-dark mb-0">&#8358;{{ number_format(auth()->user()->wallet->balance) }}</p>
          <p class="text-muted mb-0">
            Wallet Balance
          </p>
        </div>
        <div class="px-2 px-sm-5 border-start">
          <p class="fs-3 text-dark mb-0">&#8358;{{ number_format(auth()->user()->wallet->bonus) }}</p>
          <p class="text-muted mb-0">
            Bonus Balance
          </p>
        </div>
        <div class="px-2 px-sm-5 border-start">
          <p class="fs-3 text-dark mb-0">{{ auth()->user()->referees()->count() }}</p>
          <p class="text-muted mb-0">
            Referrals
          </p>
        </div>
        <div class="px-2 px-sm-5 border-start">
          <p class="fs-3 text-dark mb-0">{{ $completed }}</p>
          <p class="text-muted mb-0">
            Completed Jobs
          </p>
        </div>
      </div>
      <div class="alert alert-info mt-3">Login Points: You'll get 50 points on daily login! You can redeem the points to win cash and amazing prizes!!</div>
    </div>
  </div>
  <!-- END Hero Section -->
  
        <!-- Page Content -->
        <div class="content content-boxed content-full">
          @if (session('success'))
          <div class="alert alert-success" role="alert">
              {{ session('success') }}
          </div>
          @endif

            <h2 class="content-heading">
                <i class="fa fa-briefcase text-muted me-1"></i> Available jobs
              </h2>

              <div class="bg-body-dark mb-5">
                <div class="content content-full text-center">
                  <div class="py-3">
                    <h3 class="mb-2 text-center">
                      How to make money with freebyz
                    </h3>
                    
                    {{-- <h4 class="fw-normal text-muted text-center">
                   Only verified users have unlimited access to jobs! 
                    </h4> --}}

                    <iframe width="100%" height="250" src="https://www.youtube.com/embed/hvy02mfgg2I?controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                  </div>
              </div>
              
  
            <!-- Jobs -->
            @foreach ($available_jobs as $job)
            {{-- @if($job->completed()->count() >= $job->number_of_staff)
            @else --}}
              <!-- Story -->
              <div class="block block-rounded">
                <div class="block-content p-0 overflow-hidden">
                  <div class="row g-0">
                    <div class="col-md-3 col-lg-3 overflow-hidden d-flex align-items-center">

                      @if($job->completed()->where('status', 'Approved')->count() >= $job->number_of_staff)
                          <a href="#">
                            <img class="img-fluid img-link" src="{{asset('src/assets/media/photos/photo25.jpg')}}" alt="">
                          </a>
                      @else
                          <a href="{{ url('campaign/'.$job->job_id) }}">
                            <img class="img-fluid img-link" src="{{asset('src/assets/media/photos/photo25.jpg')}}" alt="">
                          </a>
                      @endif
                     
                    </div>
                    <div class="col-md-9 col-lg-9 d-flex align-items-center">
                      <div class="px-4 py-3">
                        <h4 class="mb-1">
                          @if($job->completed()->where('status', 'Approved')->count() >= $job->number_of_staff)
                              <a class="text-dark" href="#"> {!! $job->post_title !!}</a>
                          @else
                              {{-- <em class="text-muted">  Number of Worker - {{  $job->completed()->where('status', 'Approved')->count(); }} / {{ $job->number_of_staff }}</em> --}}
                              <a class="text-dark" href="{{ url('campaign/'.$job->job_id) }}"> {!! $job->post_title !!}</a>
                          @endif
                          
                        </h4>
                        <div class="fs-sm mb-2">
                            <strong>&#8358;{{ $job->campaign_amount}}</strong> ·
                              @if($job->completed()->where('status', 'Approved')->count() >= $job->number_of_staff)
                                  <em class="text-muted">Completed</em> <li class="fa fa-check"></li>
                               @else
                                  <em class="text-muted">  Number of Worker - {{  $job->completed()->where('status', 'Approved')->count(); }} / {{ $job->number_of_staff }}</em>
                               @endif
                        </div>
                        <p class="mb-0">
                          {!! \Illuminate\Support\Str::words($job->description, 20) !!}
                        </p>
                        <br>
                        <span class="badge bg-primary">{{  @$job->campaignType->name }}</span>
                        <span class="badge bg-primary">{{ @$job->campaignCategory->name }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- END Story -->
            @endforeach     
        </div>


           
            <!-- END Jobs -->
          </div>
          <!-- END Page Content -->
  
          <!-- Call to Action -->
          <div class="bg-body-dark">
            <div class="content content-full text-center">
              <div class="py-3">
                <h3 class="mb-2 text-center">
                  Get Access to More Jobs
                </h3>
                <h4 class="fw-normal text-muted text-center">
               Only verified users have unlimited access to jobs! 
                </h4>
                @if(auth()->user()->is_verified == '0')
                <a class="btn btn-hero btn-primary" href="{{route('upgrade')}}" data-toggle="click-ripple">
                  Get Verified Now!
                </a>
                @else
                <a class="btn btn-hero btn-primary disabled" href="#" data-toggle="click-ripple">
                  Verification Successfull
                </a>
                @endif
              </div>
          </div>

          <!-- Onboarding Modal -->
          <div class="modal fade" id="modal-onboarding" tabindex="-1" role="dialog" aria-labelledby="modal-onboarding" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content rounded overflow-hidden bg-image bg-image-bottom border-0" style="background-image: url({{asset('src/assets/media/photos/photo23.jpg')}});">
                <div class="row">
                  {{-- <div class="col-md-5">
                    <div class="p-3 text-end text-md-start">
                      <a class="fw-semibold text-white" href="#" data-bs-dismiss="modal" aria-label="Close">
                        Skip Intro
                      </a>
                    </div>
                  </div> --}}
                  <div class="col-md-12">
                    <div class="p-3 text-end text-md-start">
                      <a class="fw-semibold text-white" href="#" data-bs-dismiss="modal" aria-label="Close">
                        Skip Intro
                      </a>
                    </div>
                    <div class="bg-body-extra-light shadow-lg">
                      <div class="js-slider slick-dotted-inner" data-dots="true" data-arrows="false" data-infinite="false">
                        <div class="p-5">
                          {{-- <i class="fa fa-user-check fa-3x text-muted my-4"></i> --}}
                          
                          {{-- <h3 class="fs-2 fw-light">Let us know your name</h3> --}}
                          @if(auth()->user()->is_verified == '0')

                          <h3 class="mb-2 text-center">
                            Get Access to More Jobs
                          </h3>

                          <h4 class="fw-normal text-muted text-center">
                            verify your account and have unlimited access to withdraw funds. When you refer up to 50 friends, 
                            you will earn &#8358;12,500 plus &#8358;5,000 extra bonus from us. 
                            <br>Got Payment Issues, transfer to 4600066074 - DOMINAHL TECH SERVICES (VFD Microfinance Bank) then upload proof of evidence via our <b>Talk To Us</b> panel
                            {{-- <br><span style="color:brown">From 1st April, verification fee would now be ₦1000 and you'll have opportunity to win amazing prices on a weekly basis!!</span> --}}
                          </h4>

                          <center>
                            <a class="btn btn-hero btn-primary" href="{{route('upgrade')}}" data-toggle="click-ripple">
                              Get Verified Now!
                            </a>
                          </center>
                          
                          @else
                          <h3 class="mb-2 text-center">
                            Refer friends and cashout out every Friday
                          </h3>

                          <h4 class="fw-normal text-muted text-center">
                            We'll reward you with &#8358;500 on each verified friend and instant cash of &#8358;5,000 bonus when you reach 50 verified referrals. 
                          </h4>
                          <center>{{url('register/'.auth()->user()->referral_code)}}</center>
                        </div>

                         {{-- <div class="slick-slide p-5">
                          <iframe width="100%" height="250" src="https://www.youtube.com/embed/hvy02mfgg2I?controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                         
                          <button type="button" class="btn btn-primary mb-4" data-bs-dismiss="modal" aria-label="Close">
                            Close <i class="fa fa-times opacity-50 ms-1"></i>
                          </button> --}}
                        </div>

                        @endif

                        {{-- <div class="slick-slide p-5">
                          <i class="fa fa-award fa-3x text-muted my-4"></i>
                          <h3 class="fs-2 fw-light mb-2">Welcome to your Freebyz.com!</h3>
                          <p class="text-muted">
                            Freebyz was created for you to make cool cash everyday by doing simple social media jobs 
                            or increasing your business visibility and organic growth through engagements on your
                             posts on Facebook, Instagram, YouTube, TikTok, WhatsApp and Twitter.<br>
                             On top of that, we reward you with 250 NGN everytime you referral a friend. 
						                  We just want to reward you for every minute you spend on Freebyz!
                             
                          </p>
                          <button type="button" class="btn btn-alt-primary mb-4" onclick="jQuery('.js-slider').slick('slickGoTo', 2);">
                            Watch Video Guide <i class="fa fa-arrow-right ms-1"></i>
                          </button>
                        </div> --}}

                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END Onboarding Modal -->


    </div>
          <!-- END Call to Action -->

          
@endsection
@section('script')
 <!-- jQuery (required for Slick Slider plugin) -->
 <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>

 <!-- Page JS Plugins -->
 <script src="{{asset('src/assets/js/plugins/slick-carousel/slick.min.js')}}"></script>

 <!-- Page JS Code -->
 <script src="{{asset('src/assets/js/pages/be_comp_onboarding.min.js')}}"></script>


 <script>
  function myFunction() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    var tooltip = document.getElementById("myTooltip");
    tooltip.innerHTML = "Copied: " + copyText.value;
  }
  
  function outFunc() {
    var tooltip = document.getElementById("myTooltip");
    tooltip.innerHTML = "Copy to clipboard";
  }
  </script>

@endsection




