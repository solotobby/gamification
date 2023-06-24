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

  /* Campaign list Card css  */

  .card {
      border: none;
      border-radius: 10px
  }

  .c-details span {
      font-weight: 300;
      font-size: 13px
  }

  .icon {
      width: 50px;
      height: 50px;
      background-color: #eee;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 39px
  }

  .badge span {
      background-color: #1e1a0912;
      width: 60px;
      height: 25px;
      padding-bottom: 3px;
      border-radius: 5px;
      display: flex;
      color: #191918;
      justify-content: center;
      align-items: center
  }

  .progress {
      height: 10px;
      border-radius: 10px
  }

  .progress div {
      /* background-color: red */
  }

  .text1 {
      font-size: 14px;
      font-weight: 600
  }

  .text2 {
      color: #a5aec0
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
            <div class="col-md-6 mb-2">
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
        </div>
      </div>
      
      <div class="d-flex justify-content-center align-items-center">
        <div class="px-2 px-sm-5">
          <p class="fs-3 text-dark mb-0">&#8358;{{ number_format(auth()->user()->wallet->balance) }}</p>
          <p class="text-muted mb-0">
            Wallet Balance
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
      
      <div class="alert alert-info mt-3">
        {{-- Important Notice: There is a scheduled maintenance from our Card payments partner on Saturday 17th June, 2023. Please use manual payment (4600066074 - DOMINAHL TECH SERVICES -VFD Microfinance Bank) --}}
        Login Points: You'll get 50 points on daily login! You can redeem the points to win cash and amazing prizes!!
      </div>
      <marquee>
        <ul class="list-inline">
          @foreach ($activity_log as $activity)
              <li class="list-inline-item">&bull; {{$activity->description}}</li>
          @endforeach
        </ul>
      </marquee>
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
              <iframe width="100%" height="250" src="https://www.youtube.com/embed/hvy02mfgg2I?controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

              {{-- Campaign list --}}
              <div class="col-lg-12">
                  <ul class="nav nav-tabs nav-tabs-block align-items-center" role="tablist">
                    <li class="nav-item">
                      <button class="nav-link active" id="btabswo-static-home-tab" data-bs-toggle="tab" data-bs-target="#btabswo-static-home" role="tab" aria-controls="btabswo-static-home" aria-selected="true">Available Jobs</button>
                    </li>
                    {{-- <li class="nav-item">
                      <button class="nav-link" id="btabswo-static-profile-tab" data-bs-toggle="tab" data-bs-target="#btabswo-static-profile" role="tab" aria-controls="btabswo-static-profile" aria-selected="false">Profile</button>
                    </li> --}}
                    
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="btabswo-static-home" role="tabpanel" aria-labelledby="btabswo-static-home-tab">
                      @if($user->interests()->count() == 0)
                      {{-- Survey card --}}
                      <div class="row mt-2">
                        <a href="{{ route('survey') }}">
                            <div class="card p-3 mb-2">
                              <div class="d-flex justify-content-between">
                                  <div class="d-flex flex-row align-items-center">
                                      <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                                      <div class="ms-2 c-details" style="color:#191918">
                                          <h6 class="mb-0">100 points</h6> <span>Survey</span>
                                      </div>
                                  </div>
                              </div>
                              <div class="mt-2">
                                  <h3 class="heading" style="color:#191918">Earn 100 points</h3>
                                  <div class="mt-2">
                                      <div class="progress">
                                          <div class="progress-bar" role="progressbar" style="width: 30%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div>
                                      {{-- <div class="mt-3" style="color:#191918"> <span class="text1">{{  $job['completed'] }} completed <span class="text2">out of 9000 capacity</span></span> </div> --}}
                                  </div>
                              </div>
                            </div>
                        </a>
                      </div>
                      @endif
                     

                      @foreach ($available_jobs as $job)
                        <div class="row mt-2">
                          @if(auth()->user()->is_verified)
                              @if($job['is_completed'] == true)
                                <a href="#">
                              @else
                                <a href="{{ url('campaign/'.$job['job_id']) }}">
                              @endif
                              
                            @elseif(!auth()->user()->is_verified && $job['campaign_amount'] <= 10)
                              @if($job['is_completed'] == true)
                                  <a href="#">
                              @else
                                  <a href="{{ url('campaign/'.$job['job_id']) }}">
                              @endif
                            @else
                              <a href="{{ url('info') }}">
                            @endif
                                <div class="card p-3 mb-2">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex flex-row align-items-center">
                                            <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                                            <div class="ms-2 c-details" style="color:#191918">
                                                <h6 class="mb-0">&#8358;{{ $job['campaign_amount']}}</h6> <span>{{  @$job['type'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h3 class="heading" style="color:#191918">{!! $job['post_title'] !!}</h3>
                                        <div class="mt-2">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{$job['progress']}}%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            @if($job['is_completed'] == true)
                                            <div class="mt-3" style="color:#191918"> <span class="text1">Completed <li class="fa fa-check"></li></span> </div>
                                            @else
                                            <div class="mt-3" style="color:#191918"> <span class="text1">{{  $job['completed'] }} completed <span class="text2">out of {{ $job['number_of_staff'] }} capacity</span></span> </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                      @endforeach
                      {{-- {!! $available_jobs->links('pagination::bootstrap-4') !!} --}}
                       
                    </div>
                    {{-- <div class="tab-pane" id="btabswo-static-profile" role="tabpanel" aria-labelledby="btabswo-static-profile-tab">
                      <h4 class="fw-normal">Profile Content</h4>
                      <p>...</p>
                    </div> --}}
                  </div>
              
                </div>
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
                            <br>Got Payment Issues, transfer to 
                            1014763749 - DOMINAHL TECH SERVICES (Zenith Bank)
                            (Please add your name, email address and date of transaction in the description while sending payment proof)
                            {{-- 4600066074 - DOMINAHL TECH SERVICES (VFD Microfinance Bank)  --}}
                            then upload proof of evidence via our <b>Talk To Us</b> panel
                            <br>
                            <center>
                              <a class="btn btn-hero btn-primary" href="{{route('upgrade')}}" data-toggle="click-ripple">
                                Get Verified Now!
                              </a>
                              <br> <br> 
                              Can't pay in Naira, Click the Link Button Below
                              <br>
                              <a class="btn btn-hero btn-secondary" href="https://flutterwave.com/pay/payfreebyz" target="_blank" data-toggle="click-ripple">
                                Get Verified with USD!
                              </a>
                            </center>
                          </h4>
                          @else
                          <h3 class="mb-2 text-center">
                            Refer friends and cashout out every Friday
                          </h3>

                          <h4 class="fw-normal text-muted text-center">
                            We'll reward you with &#8358;500 on each verified friend and instant cash of &#8358;5,000 bonus when you reach 50 verified referrals. 
                          </h4>
                          <center>
                            <div class="col-md-8 mb-2">
                              <div class="input-group">
                                <input type="text" value="{{url('register/'.auth()->user()->referral_code)}}" class="form-control form-control-alt" id="myInput">
                                <button type="button" class="btn btn-alt-secondary" onclick="myFunction()" onmouseout="outFunc()">
                                  <i class="fa fa-copy"></i>
                                </button>
                              </div>
                            </div>
                          </center>
                          {{-- <center>{{url('register/'.auth()->user()->referral_code)}}</center> --}}
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




