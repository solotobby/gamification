<!-- Onboarding Modal -->
<div class="modal fade" id="modal-onboarding" tabindex="-1" role="dialog" aria-labelledby="modal-onboarding" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content rounded overflow-hidden bg-image bg-image-bottom border-0" style="background-image: url({{asset('src/assets/media/photos/photo23.jpg')}});">
        <div class="row">
          <div class="col-md-5">
            <div class="p-3 text-end text-md-start">
              <a class="fw-semibold text-white" href="#" data-bs-dismiss="modal" aria-label="Close">
                Skip Intro
              </a>
            </div>
          </div>
          <div class="col-md-12">
            <div class="p-3 text-end text-md-start">
              <a class="fw-semibold text-white" href="#" data-bs-dismiss="modal" aria-label="Close">
                Skip Intro
              </a>
            </div>
            <div class="bg-body-extra-light shadow-lg">
              <div class="js-slider slick-dotted-inner" data-dots="true" data-arrows="false" data-infinite="false">
                <div class="p-5">
                 
                  @if(auth()->user()->is_verified == '0')

                  <h3 class="mb-2 text-center">
                    Get Access to More Jobs
                  </h3>

                  <h4 class="fw-normal text-muted text-center">
                    verify your account and have unlimited access to withdraw funds. When you refer up to 50 friends, 
                    you will earn &#8358;12,500 plus &#8358;5,000 extra bonus from us. 
                    <br>Got Payment Issues, transfer to 
                    {{-- 1014763749 - DOMINAHL TECH SERVICES (Zenith Bank) --}}
                    4600066074 - DOMINAHL TECH SERVICES (VFD Microfinance Bank) 
                    (Please add your Freebyz name, email address and date of transaction in the description while sending payment proof
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