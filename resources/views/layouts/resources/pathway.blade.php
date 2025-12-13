<!-- Onboarding Modal -->
<div class="modal fade" id="modal-onboarding" tabindex="-1"  aria-labelledby="modal-onboarding" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content rounded overflow-hidden bg-image bg-image-bottom border-0" style="background-image: url({{asset('src/assets/media/photos/photo23.jpg')}});">
        <div class="row">
          <div class="col-md-12">
            {{-- <div class="p-3 text-end text-md-start">
              <a class="fw-semibold text-white" href="#" data-bs-dismiss="modal" aria-label="Close">
                Skip Intro
              </a>
            </div> --}}
            <div class="bg-body-extra-light shadow-lg">
              <div class="js-slider slick-dotted-inner" data-dots="true" data-arrows="false" data-infinite="false">
                <div class="p-5">

                  <h3 class="mb-2 text-center">
                   What would you like to do on Freebyz?
                  </h3>

                  <h4 class="fw-normal text-muted text-center">
                   Hire skilled and unskilled workers
                    <center>
                      <a class="btn btn-hero btn-primary mt-3" href="{{url('skills')}}" data-toggle="click-ripple">
                        Hire Worker
                      </a>
                      <br>
                    </center>

                  </h4>

                  <h4 class="fw-normal text-muted text-center">
                    Set up your profile to get hired for remote and full-time jobs
                    <center>
                      <a class="btn btn-hero btn-primary mt-3" href="{{url('create/skill')}}" data-toggle="click-ripple">
                        Setup Profile
                      </a>
                      <br>
                    </center>

                  </h4>

                  <h4 class="fw-normal text-muted text-center">
                    Start making money from Micro Jobs
                    <center>
                      <a class="btn btn-hero btn-primary mt-3" data-bs-dismiss="modal" aria-label="Close" data-toggle="click-ripple">
                        Start Earning
                      </a>
                      <br>
                    </center>

                  </h4>

                  <h4 class="fw-normal text-muted text-center">
                    Get Full Time Remote Jobs
                    <center>
                      <a class="btn btn-hero btn-primary mt-3"  href="{{url('career-hub/')}}" data-toggle="click-ripple">
                        Apply now
                      </a>
                      <br>
                    </center>

                  </h4>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
  <!-- END Onboarding Modal -->
