 @extends('layouts.main.master')

@section('title', 'Winner List')

@section('content')
 <!-- Hero Section -->
 <div class="bg-body-extra-light text-center">
    <div class="content content-boxed content-full py-5 py-md-7">
      <div class="row justify-content-center">
        <div class="col-md-10 col-xl-6">
          <h1 class="h2 mb-2">
            Complete simple jobs today and get <span class="text-primary">paid</span>.
          </h1>
          {{-- <p class="fs-lg fw-normal text-muted">
            We offer the most complete job platform to publish your job offers and apply for your dream job.
          </p> --}}
          <p>Earn 250 NGN each time you refer a friend. <br>
            <small style="color: chocolate">Note: Your friend must be a verified user</small></p>
          <span>Your Referral Link</span>  <p class="fs-lg fw-normal text-muted">
            {{url('register/'.auth()->user()->referral_code)}}
          </p>
        </div>
      </div>
      
      <div class="d-flex justify-content-center align-items-center mt-5">
        <div class="px-2 px-sm-5">
          <p class="fs-3 text-dark mb-0">&#8358; {{ number_format(auth()->user()->wallet->balance) }}</p>
          <p class="text-muted mb-0">
            Wallet Balance
          </p>
        </div>
        <div class="px-2 px-sm-5 border-start">
          <p class="fs-3 text-dark mb-0">{{ auth()->user()->referees()->count(); }}</p>
          <p class="text-muted mb-0">
            Referrals
          </p>
        </div>
        <div class="px-2 px-sm-5 border-start">
          <p class="fs-3 text-dark mb-0">980</p>
          <p class="text-muted mb-0">
            Completed Jobs
          </p>
        </div>
      </div>
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
  
            <!-- Jobs -->
            @foreach ($available_jobs as $job)
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                  <div class="d-sm-flex">
                    <div class="ms-sm-2 me-sm-4 py-2 text-center">
                      <a class="item item-rounded bg-body-dark text-dark fs-5 mb-2 mx-auto" href="{{ url('campaign/'.$job->job_id) }}">
                        {{-- <i class="fab fa-fw fa-cloudsmith"></i> --}}
                        &#8358;{{ $job->campaign_amount}}
                      </a>
                      <div class="btn-group btn-group-sm">
                        <a class="btn btn-primary" href="{{ url('campaign/'.$job->job_id) }}">
                         Apply
                        </a>
                      </div>
                    </div>
                    <div class="py-2">
                      <a class="link-fx h4 mb-1 d-inline-block text-dark" href="{{ url('campaign/'.$job->job_id) }}">
                        {!! $job->post_title !!}
                      </a>
                      {{-- <span class="pull-left">1700</span> --}}
                      <div class="fs-sm fw-semibold text-muted mb-2">
                        Number of Worker - {{  $job->completed()->where('status', 'Approved')->count(); }} / {{ $job->number_of_staff }}
                      </div>
                      <p class="text-muted mb-2">
                        {{-- {!! substr($job->description, 0,  350) !!} --}}
                        {!! \Illuminate\Support\Str::words($job->description, 50) !!}
                      </p>
                      <div>
                        <span class="badge bg-primary">{{  $job->campaignType->name }}</span>
                        <span class="badge bg-primary">{{ $job->campaignCategory->name }}</span>
                        {{-- <span class="badge bg-primary">Social</span> --}}
                      </div>
                    </div>
                  </div>
                </div>
            </div>
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
    </div>
          <!-- END Call to Action -->

          
@endsection




