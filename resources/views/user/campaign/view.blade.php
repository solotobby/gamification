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

      /* Share button styles */
      .share-btn-group {
        display: flex;
        gap: 10px;
        margin-top: 15px;
      }
      .share-btn {
        padding: 8px 16px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
      }
      .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      }
      .share-btn i {
        margin-right: 5px;
      }
      .copy-link-btn {
        background: #6c757d;
        color: white;
      }
      .copy-link-btn:hover {
        background: #5a6268;
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
                  {{$campaign['local_converted_currency']}} {{$campaign['local_converted_amount']}}<span class="text-white-50"></span>
                </div>
                <div class="fs-sm fw-semibold text-uppercase text-white-50 mt-1 push">Per Job</div>
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
                <div class="text-muted"> {{$campaign['local_converted_currency']}} {{$campaign['local_converted_amount']}}</div>
              </li>
              <li>
                <span class="fa-li text-primary">
                  <i class="fa fa-users"></i>
                </span>
                <div class="fw-semibold">Number of Worker</div>
                <div class="text-muted">{{$campaign['number_of_staff']}}</div>
              </li>
            </ul>

            <!-- Share Campaign Section -->
            {{-- <div class="mt-4 pt-3 border-top">
              <div class="fw-semibold mb-2">
                <i class="fa fa-share-alt text-primary"></i> Share Campaign
              </div>
              <div class="share-btn-group flex-wrap">
                <button class="share-btn copy-link-btn" onclick="copyShareLink()" title="Copy Link">
                  <i class="fa fa-link"></i> Copy Link
                </button>
                <a href="https://wa.me/?text=Complete this {{ $campaign['post_title'] }} task on Freebyz to earn {{ urlencode(url('campaign/public/'.$campaign['job_id'])) }}" target="_blank" class="share-btn" style="background: #25D366; color: white;">
                  <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="https://x.com/intent/post?text=Complete this {{ $campaign['post_title'] }} task on Freebyz to earn {{ urlencode(url('campaign/public/'.$campaign['job_id'])) }}" target="_blank" class="share-btn" style="background: #1DA1F2; color: white;">
                  <i class="fab fa-x"></i> X
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('campaign/public/'.$campaign['job_id'])) }}" target="_blank" class="share-btn" style="background: #1877F2; color: white;">
                  <i class="fab fa-facebook-f"></i> Facebook
                </a>
              </div>
              <input type="hidden" id="shareLink" value="{{ url('campaign/public/'.$campaign['job_id']) }}">
              <small class="text-muted d-block mt-2" id="copyMessage" style="display:none;"></small>
            </div> --}}

            <div class="mt-4 pt-3 border-top">
                <div class="fw-semibold mb-2">
                    <i class="fa fa-share-alt text-primary"></i> Share Campaign
                </div>

                @php
                    $campaignUrl = url('tasks/' . $campaign->job_id);
                    $shareText = "Complete this {$campaign->post_title} task on Freebyz to earn {$campaignUrl}";
                @endphp

                <div class="share-btn-group flex-wrap">
                    <!-- Copy Link -->
                    <button
                    type="button"
                    class="share-btn copy-link-btn"
                    onclick="copyShareLink()"
                    title="Copy Link"
                    >
                    <i class="fa fa-link"></i> Copy Link
                    </button>

                    <!-- WhatsApp -->
                    <a
                    href="https://wa.me/?text={{ rawurlencode($shareText) }}"
                    target="_blank"
                    class="share-btn"
                    style="background:#25D366;color:#fff;"
                    >
                    <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>

                    <!-- X -->
                    <a
                    href="https://x.com/intent/tweet?text={{ rawurlencode($shareText) }}"
                    target="_blank"
                    class="share-btn"
                    style="background:#1DA1F2;color:#fff;"
                    >
                    <i class="fab fa-x"></i> X
                    </a>

                    <!-- Facebook -->
                    <a
                    href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($campaignUrl) }}"
                    target="_blank"
                    class="share-btn"
                    style="background:#1877F2;color:#fff;"
                    >
                    <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                </div>

                <!-- Hidden input for copy -->
                <input type="hidden" id="shareLink" value="{{ $campaignUrl }}">

                <small
                    class="text-muted d-block mt-2"
                    id="copyMessage"
                    style="display:none;"
                ></small>
                </div>

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
                Current Value per Job  - {{ $campaign['local_converted_amount'] }} <br>
                <hr>
                <form action="{{ route('addmore.workers') }}" method="POST">
                  @csrf
                  <div class="mb-4">
                    <label class="form-label" for="post-files">Number of Worker</small></label>
                        <input class="form-control" name="new_number" type="number" required>
                  </div>
                  <input type="hidden" name="id" value="{{ $campaign['job_id'] }}">
                  <input type="hidden" name="amount" value="{{$campaign['local_converted_amount']}}">
                  <div class="mb-4">
                    <button class="btn btn-primary" type="submit">Add</button>
                  </div>
                 </form>
              </div>

              <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
              </div>
          </div>
          </div>
      </div>


        <!-- END Job Summary -->
      </div>
      <div class="col-md-8 order-md-0">
        <div class="alert alert-info">
          <li class="fa fa-info"></li> Please note that this job must be approved before payment.
          We'll automatically approve it if it is not approved by poster after 24hours.
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
                       @if($can_resubmit)
                            <div class="alert alert-warning">
                                Your submission was denied. You have {{ 3 - $denied_count }} attempts remaining.
                            </div>
                        @endif
                          @if($completed)

                            <div class="block-content">
                              <div class="row">
                                <div class="col-md-12">

                                    <h4 class="fw-normal text-muted text-center">
                                        Opps!! You have completed this campaign
                                    </h4>
                                    @if(@!$is_rated)
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
                                                    </div>
                                                </div>

                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    <!-- END Onboarding Modal -->
                                    @endif
                                </div>
                              </div>
                            </div>

                          @else
                                  <div class="block-content">
                                    <div class="row">
                                      <form action="{{ route('post.campaign.work') }}" method="POST" enctype="multipart/form-data">
                                          @csrf
                                          <div class="col-md-12 mb-3">
                                              <textarea class="form-control" name="comment" id="js-ckeditor5-classic" ></textarea>
                                          </div>
                                          @if($campaign->allow_upload == true)
                                          <div class="col-md-12 mb-3">
                                            <label class="form-label" for="formFileMultiple" class="form-label">Upload Proof (png,jpeg,gif,jpg) - Optional</label>
                                            <input class="form-control" type="file" name="proof" id="example-file-input-multiple">
                                          </div>
                                          @endif
                                          <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                          <input type="hidden" name="amount" value="{{$campaign['local_converted_amount']}}">
                                          <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                                          <div class="mb-2">
                                            <input type="checkbox" name="validate" required class="">
                                            <span><small> I agree that I will wait for a maximum of 24hrs for this tasks to be approved by the advertiser. </small></span>
                                        </div>
                                         @if(auth()->user()->is_business)
                {{-- Business Account Restriction --}}
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                <strong>Business Account Restriction:</strong> Business accounts cannot perform tasks or jobs.
                                Please contact support if you need to change your account type.
                            </div>
                        </div>
                    </div>
                </div>
                @else

                <div class="row mb-4 mt-4">
                  <div class="col-lg-6">
                  <button type="submit" class="btn btn-alt-primary">
                      <i class="fa fa-plus opacity-50 me-1"></i> Submit
                  </button>
                  </div>
              </div>
                @endif

                                      </form>
                                    </div>


                                  </div>

                          @endif

                      @endif
            </div>

            <a href="{{ url('home') }}" class="btn btn-secondary btn-sm mb-2"><i class="fa fa-backspace"></i> Back Home</a>
    </div>
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

<script>
function copyShareLink() {
    const shareLink = document.getElementById('shareLink').value;
    const copyMessage = document.getElementById('copyMessage');

    // Use the Clipboard API
    navigator.clipboard.writeText(shareLink).then(function() {
        copyMessage.textContent = 'Link copied to clipboard!';
        copyMessage.style.display = 'block';
        copyMessage.style.color = '#28a745';

        setTimeout(function() {
            copyMessage.style.display = 'none';
        }, 3000);
    }, function(err) {
        copyMessage.textContent = 'Failed to copy link';
        copyMessage.style.display = 'block';
        copyMessage.style.color = '#dc3545';
    });
}
</script>

@endsection
