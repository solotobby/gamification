
@extends('layouts.main.master')

@section('title', 'Winner List')

@section('content')

 <!-- Hero Section -->
 <div class="bg-image" style="background-image: url('src/assets/media/photos/photo12@2x.jpg');">
    <div class="bg-black-75">
      <div class="content content-boxed content-full py-5">
        <div class="row">
          <div class="col-md-8 d-flex align-items-center py-3">
            <div class="w-100 text-center text-md-start">
              <h1 class="h2 text-white mb-2">
                {{$campaign->post_title}}
              </h1>
              <h2 class="h4 fs-sm text-uppercase fw-semibold text-white-75">
                {{$campaign->campaignType->name}}
              </h2>
              <a class="fw-semibold" href="#">
                <i class="fab fa-fw fa-leanpub text-white-50"></i> Freebyz.com.
              </a>
            </div>
          </div>
          <div class="col-md-4 d-flex align-items-center">
            <a class="block block-rounded block-link-shadow block-transparent bg-black-50 text-center mb-0 mx-auto" href="be_pages_jobs_apply.html">
              <div class="block-content block-content-full px-5 py-4">
                <div class="fs-2 fw-semibold text-white">
                    &#8358; {{$campaign->campaign_amount}}<span class="text-white-50"></span>
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
                <div class="text-muted">{{$campaign->campaignType->name}}</div>
              </li>
              <li>
                <span class="fa-li text-primary">
                  <i class="fa fa-briefcase"></i>
                </span>
                <div class="fw-semibold">Campaign Category</div>
                <div class="text-muted">{{$campaign->campaignCategory->name}}</div>
              </li>
              <li>
                <span class="fa-li text-primary">
                  <i class="fa fa-money-check-alt"></i>
                </span>
                <div class="fw-semibold">Amount per Campaign</div>
                <div class="text-muted">&#8358; {{$campaign->campaign_amount}}</div>
              </li>
              <li>
                <span class="fa-li text-primary">
                  <i class="fa fa-clock"></i>
                </span>
                <div class="fw-semibold">Number of Worker</div>
                <div class="text-muted">{{$campaign->number_of_staff}}</div>
              </li>
            </ul>
          </div>
        </div>
        <!-- END Job Summary -->
      </div>
      <div class="col-md-8 order-md-0">
        <!-- Job Description -->
        <div class="block block-rounded">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

          <div class="block-header block-header-default">
            <h3 class="block-title">Campaign Description</h3>
          </div>
          <div class="block-content">
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
                        <div class="block-content">
                            <div class="row">
                            <form action="{{ route('post.campaign.work') }}" method="POST">
                                @csrf
                                <div class="col-md-12">
                                    <textarea class="form-control" name="comment" id="js-ckeditor5-classic"></textarea>
                                </div>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="amount" value="{{ $campaign->campaign_amount }}">
                                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                                <div class="row mb-4 mt-4">
                                    <div class="col-lg-6">
                                    <button type="submit" class="btn btn-alt-primary">
                                        <i class="fa fa-plus opacity-50 me-1"></i> Submit
                                    </button>
                                    </div>
                                </div>
                            </form>
                            </div>
                            

                        </div>
                @endif
            </div>
        

       
      </div>
    </div>
  </div>
  <!-- END Page Content -->
@endsection


@section('script')

<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Page JS Helpers (CKEditor 5 plugins) -->
<script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>

@endsection