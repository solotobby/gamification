@extends('layouts.main.master')
@section('style')
<style>
  .rate {
      float: left;
      height: 46px;
      padding: 0 10px;
      }
      .rate:not(:checked) > input {
      position:absolute;
      display: none;
      }
      .rate:not(:checked) > label {
      float:right;
      width:1em;
      overflow:hidden;
      white-space:nowrap;
      cursor:pointer;
      font-size:30px;
      color:#ccc;
      }
      .rated:not(:checked) > label {
      float:right;
      width:1em;
      overflow:hidden;
      white-space:nowrap;
      cursor:pointer;
      font-size:30px;
      color:#ccc;
      }
      .rate:not(:checked) > label:before {
      content: '★ ';
      }
      .rate > input:checked ~ label {
      color: #ffc700;
      }
      .rate:not(:checked) > label:hover,
      .rate:not(:checked) > label:hover ~ label {
      color: #deb217;
      }
      .rate > input:checked + label:hover,
      .rate > input:checked + label:hover ~ label,
      .rate > input:checked ~ label:hover,
      .rate > input:checked ~ label:hover ~ label,
      .rate > label:hover ~ input:checked ~ label {
      color: #c59b08;
      }
      .star-rating-complete{
         color: #c59b08;
      }
      .rating-container .form-control:hover, .rating-container .form-control:focus{
      background: #fff;
      border: 1px solid #ced4da;
      }
      .rating-container textarea:focus, .rating-container input:focus {
      color: #000;
      }
      .rated {
      float: left;
      height: 46px;
      padding: 0 10px;
      }
      .rated:not(:checked) > input {
      position:absolute;
      display: none;
      }
      .rated:not(:checked) > label {
      float:right;
      width:1em;
      overflow:hidden;
      white-space:nowrap;
      cursor:pointer;
      font-size:30px;
      color:#ffc700;
      }
      .rated:not(:checked) > label:before {
      content: '★ ';
      }
      .rated > input:checked ~ label {
      color: #ffc700;
      }
      .rated:not(:checked) > label:hover,
      .rated:not(:checked) > label:hover ~ label {
      color: #deb217;
      }
      .rated > input:checked + label:hover,
      .rated > input:checked + label:hover ~ label,
      .rated > input:checked ~ label:hover,
      .rated > input:checked ~ label:hover ~ label,
      .rated > label:hover ~ input:checked ~ label {
      color: #c59b08;
      }
</style>
@endsection
@section('content')

 <!-- Hero Section -->
 <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo12@2x.jpg')}}' );">
    <div class="bg-black-75">
      <div class="content content-boxed content-full py-5">
        <div class="row">
          <div class="col-md-8 d-flex align-items-center py-3">
            <div class="w-100 text-center text-md-start">
              <h1 class="h2 text-white mb-2">
                {{$campaign['post_title']}}
              </h1>
              <h2 class="h4 fs-sm text-uppercase fw-semibold text-white-75">
                {{$campaign['campaignType']['name']}}
              </h2>
              <a class="fw-semibold" href="#">
                <i class="fab fa-fw fa-leanpub text-white-50"></i> Freebyz.com.
              </a>
            </div>
          </div>
          <div class="col-md-4 d-flex align-items-center">
            <a class="block block-rounded block-link-shadow block-transparent bg-black-50 text-center mb-0 mx-auto" href="">
              <div class="block-content block-content-full px-5 py-4">
                <div class="fs-2 fw-semibold text-white">
                  @if($campaign['currency'] == 'NGN')
                    &#8358;{{$campaign['campaign_amount']}}<span class="text-white-50"></span>
                    @else
                    ${{$campaign['campaign_amount']}}<span class="text-white-50"></span>
                  @endif
                </div>
                <div class="fs-sm fw-semibold text-uppercase text-white-50 mt-1 push">Per Job</div>
                {{-- <span class="btn btn-hero btn-primary">
                  <i class="fa fa-arrow-right opacity-50 me-1"></i> Apply Now
                </span> --}}
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Hero Section -->

  <!-- Page Content -->
  <div class="content content-boxed">
    <div class="row">
      <div class="col-md-4 order-md-1">
        <!-- Job Summary -->
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Campaign Summary</h3>
          </div>
          <div class="block-content">
            <ul class="fa-ul list-icons">
              {{-- <li>
                <span class="fa-li text-primary">
                  <i class="fa fa-map-marker-alt"></i>
                </span>
                <div class="fw-semibold">Location</div>
                <div class="text-muted">New York</div>
              </li> --}}
              <li>
                <span class="fa-li text-primary">
                  <i class="fa fa-briefcase"></i>
                </span>
                <div class="fw-semibold">Campaign Type</div>
                <div class="text-muted">{{$campaign['campaignType']['name']}}</div>
              </li>
              <li>
                <span class="fa-li text-primary">
                  <i class="fa fa-briefcase"></i>
                </span>
                <div class="fw-semibold">Campaign Category</div>
                <div class="text-muted">{{$campaign['campaignCategory']['name']}}</div>
              </li>
              <li>
                <span class="fa-li text-primary">
                  <i class="fa fa-money-check-alt"></i>
                </span>
                <div class="fw-semibold">Amount per Campaign</div>
                @if($campaign['currency'] == 'NGN')
                <div class="text-muted">&#8358;{{$campaign['campaign_amount']}}</div>
                @else
                <div class="text-muted">${{$campaign['campaign_amount']}}</div>
                @endif
              </li>
              <li>
                <span class="fa-li text-primary">
                  <i class="fa fa-users"></i>
                </span>
                <div class="fw-semibold">Number of Worker</div>
                <div class="text-muted">{{$campaign['number_of_staff']}}</div>
              </li>
              @if($campaign['user_id'] == auth()->user()->id)
                <li>
                  <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $campaign->job_id }}"> <i class="fa fa-plus opacity-50 me-1"></i> Add More Workers</button>
                </li>
              @endif
            </ul>
          </div>
        </div>

        <div class="modal fade" id="modal-default-popout-{{ $campaign['job_id'] }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
          <div class="modal-dialog modal-dialog-popout" role="document">
          <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title"> Add More Worker </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body pb-1">
                Current Number of Workers - {{ $campaign['number_of_staff'] }} <br>
                Current Value per Job  - {{ number_format($campaign['campaign_amount']) }} <br>
                @if($campaign['currency'] == 'NGN')
                Current Value  - &#8358;{{ number_format($campaign['total_amount']) }} <br>
                @else
                Current Value  - ${{ number_format($campaign['total_amount'],2) }} <br>
                @endif
                <hr>
                <form action="{{ route('addmore.workers') }}" method="POST">
                  @csrf
                  <div class="mb-4">
                    <label class="form-label" for="post-files">Number of Worker</small></label>
                        <input class="form-control" name="new_number" type="number" required>
                  </div>
                  <input type="hidden" name="id" value="{{ $campaign['job_id'] }}">
                  <input type="hidden" name="amount" value="{{ $campaign['campaign_amount'] }}">
                  <div class="mb-4">
                    <button class="btn btn-primary" type="submit">Add</button>
                  </div>
                 </form>
              </div>
              
              <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
              {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
              </div>
          </div>
          </div>
      </div>


        <!-- END Job Summary -->
      </div>
      <div class="col-md-8 order-md-0">
        <div class="alert alert-info">
          <li class="fa fa-info"></li> Please note that this job must be approved before payment. 
          We'll automatically approve it if it is not approved by poster after 5days.
        </div>
        @if (session('success'))
          <div class="alert alert-success" role="alert">
              {{ session('success') }}
          </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Job Description -->
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Campaign Description</h3>
          </div>
            <div class="block-content">
              

              @if($campaign->post_link != '')
              Link: <a href="{{ $campaign->post_link }}" target="_blank" class="">{{ $campaign->post_link }}</a>
              <hr>
              @endif
            {!! $campaign->description !!}
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Campaign Instruction</h3>
            </div>
            <div class="block-content">
             {!! $campaign->proof !!}
            </div>
            <br>
          </div>
        <!-- END Job Description -->
          
        <!-- Similar Jobs -->
            <div class="block block-rounded">
              <div class="block-header block-header-default">
                  <h3 class="block-title">Post Proof of Completion</h3>
              </div>    
                      @if($campaign->user_id == auth()->user()->id)
                        <div class="block-content">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    You cannot do this campaign because you created it.
                                </div>
                            </div>
                        </div>
                        </div>
                      @else
                          @if($completed)
                              
                            <div class="block-content">
                              <div class="row">
                                <div class="col-md-12">
                                    
                                    <h4 class="fw-normal text-muted text-center">
                                        Opps!! You have completed this campaign
                                    </h4>
                                    @if(!$is_rated)
                                    <!-- Onboarding Modal -->
                                        <div class="modal fade" id="modal-onboarding" tabindex="-1" role="dialog" aria-labelledby="modal-onboarding" aria-hidden="true">
                                          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content rounded overflow-hidden bg-image bg-image-bottom border-0" style="background-image: url({{asset('src/assets/media/photos/photo23.jpg')}});">
                                              <div class="row">
                                                <div class="col-md-12">
                                                
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                          <h5 class="modal-title">Kindly rate your experience</h5> 
                                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                      
                                                    <div class="modal-body pb-1">
                                            
                                                        <form action="{{ route('job.rating') }}" method="POST">
                                                          @csrf
                                                          <div class="col-md-6">
                                                            <label for="input-1" class="control-label">Select Rating</label>
                                                            <div class="form-group row">
                                                              <div class="col">
                                                                <div class="rate">
                                                                    <input type="radio" id="star5" class="rate" name="rating" value="5"/>
                                                                    <label for="star5" title="text">5 stars</label>
                                                                    <input type="radio" id="star4" class="rate" name="rating" value="4"/>
                                                                    <label for="star4" title="text">4 stars</label>
                                                                    <input type="radio" id="star3" class="rate" name="rating" value="3"/>
                                                                    <label for="star3" title="text">3 stars</label>
                                                                    <input type="radio" id="star2" class="rate" name="rating" value="2">
                                                                    <label for="star2" title="text">2 stars</label>
                                                                    <input type="radio" id="star1" class="rate" name="rating" value="1"/>
                                                                    <label for="star1" title="text">1 star</label>
                                                                </div>
                                                              </div>
                                                          </div>
              
                                                          </div>
                                                          <br>
                                                          <div class="mb-3">
                                                            <label class="form-label" for="post-files">Comment(optional)</small></label>
                                                                <textarea class="form-control" name="comment" id="js-ckeditor5-classic" required> {{ old('comment') }}</textarea>
                                                          </div>
                                                          <input type="hidden" name="campaign_id" value="{{$campaign['id']}}" required>

                                                          <div class="mb-4">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                                                          </div>
                                                        </form>
                                                        
                                                    </div>
                                                    
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                                                    {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
                                                    </div>
                                                </div>

                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    <!-- END Onboarding Modal -->
                                    @endif
                                    {{-- <center>
                                        <a class="btn btn-hero btn-primary mb-4" href="{{url('campaign/my/submitted/'.$campaign->myCompleted->id)}}" data-toggle="click-ripple" >
                                        View Work
                                      </a>
                                    </center> --}}
                                </div>
                              </div>
                            </div>

                          @else
                              <?php 
                              $completed_count = $campaign->completed()->where('status', '!=', 'Denied')->count();
                              ?>
                              @if($completed_count >= $campaign->number_of_staff)
                                <div class="block-content">
                                  <div class="row">
                                    <div class="col-md-12">
                                        
                                        <h4 class="fw-normal text-muted text-center">
                                            This campaign has reached its maximum number of worker.
                                        </h4>
                                      
                                    </div>
                                  </div>
                                </div>
                              @else
                                  <div class="block-content">
                                    <div class="row">
                                      <form action="{{ route('post.campaign.work') }}" method="POST" enctype="multipart/form-data">
                                          @csrf
                                          <div class="col-md-12 mb-3">
                                              <textarea class="form-control" name="comment" id="js-ckeditor5-classic"></textarea>
                                          </div>
                                          <div class="col-md-12 mb-3">
                                            <label class="form-label" for="formFileMultiple" class="form-label">Upload Proof (png,jpeg,gif,jpg)</label>
                                            <input class="form-control" type="file" name="proof" id="example-file-input-multiple" required>
                                          </div>
                                          <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                          <input type="hidden" name="amount" value="{{ $campaign->campaign_amount }}">
                                          <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                                          @if(auth()->user()->is_verified)
                                          <div class="row mb-4 mt-4">
                                            <div class="col-lg-6">
                                            <button type="submit" class="btn btn-alt-primary">
                                                <i class="fa fa-plus opacity-50 me-1"></i> Submit
                                            </button>
                                            </div>
                                        </div>
                                         
                                        @elseif(!auth()->user()->is_verified && $campaign->campaign_amount <= 10)
                                        <div class="row mb-4 mt-4">
                                          <div class="col-lg-6">
                                          <button type="submit" class="btn btn-alt-primary">
                                              <i class="fa fa-plus opacity-50 me-1"></i> Submit
                                          </button>
                                          </div>
                                        </div>
                                        @else
                                        <div class="row mb-4 mt-4">
                                          <div class="col-lg-12">
                                            <p> You are not verified yet. Please click the button below to get Verified!</p>
                                            <a href="{{ url('upgrade') }}" class="btn btn-primary btn-sm"> <li class="fa fa-link"> </li> Get Verified! </a>
                                          {{-- <button type="button" class="btn btn-alt-primary">
                                              <i class="fa fa-plus opacity-50 me-1 disabled"></i> Submit
                                          </button> --}}
                                          </div>
                                        </div>
                                        @endif
                                          
                                      </form>
                                    </div>
                                      

                                  </div>
                                @endif
                          @endif

                      @endif
            </div>

            <a href="{{ url('home') }}" class="btn btn-secondary btn-sm mb-2"><i class="fa fa-backspace"></i> Back Home</a>

      </div>
    </div>
  </div>
  <!-- END Page Content -->
@endsection


@section('script')
 <!-- Page JS Code -->
<script src="{{asset('src/assets/js/pages/be_comp_onboarding.min.js')}}"></script>
<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Page JS Helpers (CKEditor 5 plugins) -->
<script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>

@endsection