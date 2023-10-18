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


  /*  */

  .slideshows {
    width: 100%;
    /* margin: auto; */
    position: relative;
    /* object-fit: scale-down; */
  }

  .slides {
    display: none;
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

          @if(auth()->user()->wallet->base_currency == "Naira")
          <p class="fs-3 text-dark mb-0">&#8358;{{ number_format(auth()->user()->wallet->balance) }}</p>
          @else
          <p class="fs-3 text-dark mb-0">${{ number_format(auth()->user()->wallet->usd_balance,2) }}</p>
          @endif

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
          <p class="fs-3 text-dark mb-0">{{ auth()->user()->myAttemptedJobs()->where('status', 'Approved')->count() }}</p>
          <p class="text-muted mb-0">
            Completed Jobs
          </p>
        </div>
      </div>
      @if($announcement)
      <div class="alert alert-info mt-3">
        {!! @$announcement->content !!}
        {{-- Dear user, Our Anniversary is here! Click <a href="https://bit.ly/freebyzhng" target="_blank">https://bit.ly/freebyzhng</a> to sign up for our VIRTUAL HANGOUT (6pm/18thAug). You'll enjoy free airtime,data &lots more. --}}
      </div>
      @endif
       
      <div class="mt-3">
          @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
          @endif
          <center>
          <form action="{{ url('switch/wallet') }}" method="POST">
            @csrf
              @if(auth()->user()->wallet->base_currency == 'Naira')
              <input type="hidden" name="currency" value="Dollar">
              <button class="btn btn-secondary btn-sm" type="submit"><i class="fa fa-fw fa-share opacity-50"></i>Switch Currency to Dollar</button>
              @else
              <input type="hidden" name="currency" value="Naira">
              <button class="btn btn-secondary btn-sm" type="submit"><i class="fa fa-fw fa-share opacity-50"></i>Switch Currency to Naira</button>
              @endif
          </form>
          <br>

          {{-- <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout">Get bank Account</button> --}}
          {{-- <a href="{{ url('assign/virtual/account')}}" class="btn btn-info btn-sm">Virtual account</a> --}}

          </center>
      </div>

        <!-- Pop Out Default Modal -->
        <div class="modal fade" id="modal-default-popout" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
          <div class="modal-dialog modal-dialog-popout" role="document">
          <div class="modal-content">
            <form action="{{ url('assign/virtual/account')}}" method="POST">
               @csrf
              <div class="modal-header">
              <h5 class="modal-title"> Create Account Number </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body pb-1">
                 
                  <div class="mb-4">
                    <label class="form-label" for="post-files">Please Enter your BVN</small></label>
                        <input class="form-control" name="bvn" type="text" max="10" required>
                  </div>
                  <div class="mb-4">
                    {{-- <button class="btn btn-primary" type="submit">Get Account</button> --}}
                  </div>
                 
              </div>
              
              <div class="modal-footer">
              {{-- <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button> --}}
              <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Submit</button>
              </div>
            </form>
          </div>
          </div>
      </div>

      {{-- <marquee>
        <ul class="list-inline">
          @foreach ($activity_log as $activity)
              <li class="list-inline-item">&bull; {{$activity->description}}</li>
          @endforeach
        </ul>
      </marquee> --}}
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
                <i class="fa fa-briefcase text-muted me-1"></i> Available Jobs
            </h2>
              <iframe width="100%" height="250" src="https://www.youtube.com/embed/hvy02mfgg2I?controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              
              {{-- <a href="{{ url('ad/'.$ads->banner_id.'/view')}}" target="_blank">
                  <img src="{{ url($ads->banner_url)  }}" width="100%" height="250" class="img-responsive">
              </a> --}}

              {{-- Campaign list --}}
              <div class="col-lg-12">
                  <ul class="nav nav-tabs nav-tabs-block align-items-center" role="tablist">
                    <li class="nav-item">
                      <button class="nav-link active" id="btabswo-static-home-tab" data-bs-toggle="tab" data-bs-target="#btabswo-static-home" role="tab" aria-controls="btabswo-static-home" aria-selected="true">Available Jobs</button>
                    </li>
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
                            <a href="{{ url('campaign/'.$job['job_id']) }}"> 
                                <div class="card p-3 mb-2">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex flex-row align-items-center">
                                            <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                                            <div class="ms-2 c-details" style="color:#191918">
                                              @if($job['currency'] == 'NGN')
                                                <h6 class="mb-0">&#8358;{{ number_format($job['campaign_amount'],2)}}</h6>   
                                              @else
                                                <h6 class="mb-0">${{ $job['campaign_amount']}}</h6> 
                                              @endif
                                                <span>{{  @$job['type'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <h3 class="heading" style="color:#191918">{!! $job['post_title'] !!}</h3>
                                        <div class="mt-2">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{$job['progress']}}%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="mt-3" style="color:#191918"> <span class="text1">{{  $job['completed'] }} completed <span class="text2">out of {{ $job['number_of_staff'] }} workers</span></span> </div>
                                            
                                            {{-- @if($job['is_completed'] == true)
                                            <div class="mt-3" style="color:#191918"> <span class="text1">Completed <li class="fa fa-check"></li></span> </div>
                                            @else
                                            
                                            @endif --}}
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
    </div>

    <!-- END Call to Action -->
    @if(auth()->user()->profile->is_welcome == 0)
      {{-- Show welcome pop up --}}
      @include('layouts.resources.welcome')

    {{-- @elseif(!auth()->user()->accountDetails)
      
      @include('layouts.resources.account_details') --}}


    @elseif(auth()->user()->is_verified == 0)
    
        @include('layouts.resources.unverified')

    @endif
    
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


  // set index and transition delay
    let index = 0;
    let transitionDelay = 5000;

    // get div containing the slides
    let slideContainer = document.querySelector(".slideshow");
    // get the slides
    let slides = slideContainer.querySelectorAll(".slide");

    // set transition delay for slides
    for (let slide of slides) {
      slide.style.transition = `all ${transitionDelay/1000}s linear`;
    }

    // show the first slide
    showSlide(index);

    // show a specific slide
    function showSlide(slideNumber) {
      slides.forEach((slide, i) => {
        slide.style.display = i == slideNumber ? "block" : "none";
      });
      // next index
      index++;
      // go back to 0 if at the end of slides
      if (index >= slides.length) {
        index = 0;
      }
    }

    // transition to next slide every x seconds
    setInterval (() => showSlide(index), transitionDelay);
  
  </script>

@endsection




