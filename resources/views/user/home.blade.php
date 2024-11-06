@extends('layouts.main.master')
@section('style')


@endsection

@section('content')
 <!-- Hero -->
 <div class="bg-image" style="background-image: url('https://i.natgeofe.com/n/9e7c6381-8205-4a0c-a3a6-e744bf86a751/climbing-8000-meters-first-winter-ascents-everest.jpg');">
 
    <div class="bg-primary-dark-op">
      <div class="content content-full">
        <div class="row my-3">
          <div class="col-md-5 d-md-flex align-items-md-center">
            <div class="py-4 py-md-0 text-center text-md-start">
              <h1 class="fs-2 text-white mb-2">{{auth()->user()->name}} 
                @if(auth()->user()->wallet->base_currency == 'Naira')
                
                    @if(auth()->user()->is_verified)
                        <i class="fa fa-check opacity-75 me-1"></i>
                    @endif

                @else
                    @if(auth()->user()->USD_verified)
                        <i class="fa fa-check-double opacity-75 me-1"></i>
                    @endif

                @endif
              
              </h1>
              <h2 class="fs-lg fw-normal text-white-75 mb-0">Complete Simple Jobs and Get Paid!</h2>
            </div>
          </div>
          <div class="col-md-7 d-md-flex align-items-md-center">
            <div class="row w-100 text-center">
              <div class="col-4 col-xl-4">
                    <p class="fs-3 fw-semibold text-white mb-0">

                        {{-- @if(auth()->user()->wallet->base_currency == "Naira")
                            &#8358;{{ number_format(auth()->user()->wallet->balance,2) }}
                        @else
                            ${{ number_format(auth()->user()->wallet->usd_balance,3) }}
                        @endif --}}

                        @if(auth()->user()->wallet->base_currency == "Naira")
                            &#8358;{{ number_format(auth()->user()->wallet->balance,2) }}
                        @elseif(auth()->user()->wallet->base_currency == 'GHS')
                            &#8373;{{ number_format(auth()->user()->wallet->base_currency_balance) }}
                        @elseif(auth()->user()->wallet->base_currency == 'KES')
                            KES {{ number_format(auth()->user()->wallet->base_currency_balance) }}
                        @elseif(auth()->user()->wallet->base_currency == 'TZS')
                            TZS {{ number_format(auth()->user()->wallet->base_currency_balance) }}
                        @elseif(auth()->user()->wallet->base_currency == 'RWF')
                            RWF {{ number_format(auth()->user()->wallet->base_currency_balance) }}
                        @elseif(auth()->user()->wallet->base_currency == 'MWK')
                            MWK {{ number_format(auth()->user()->wallet->base_currency_balance) }}
                        @elseif(auth()->user()->wallet->base_currency == 'UGX')
                            UGX {{ number_format(auth()->user()->wallet->base_currency_balance) }}
                        @elseif(auth()->user()->wallet->base_currency == 'ZAR')
                            ZAR {{ number_format(auth()->user()->wallet->base_currency_balance) }}
                        @else 

                            ${{ number_format(auth()->user()->wallet->usd_balance,2) }}
                        @endif


                     
                    </p>
                    <p class="fs-sm fw-semibold text-white-75 mb-0">

                      <i class="fa fa-money-bill opacity-75 me-1"></i> Balance
                    </p>
              </div>
              <div class="col-4 col-xl-4">
                <p class="fs-3 fw-semibold text-white mb-0">
                    {{ auth()->user()->referees()->count() }}
                </p>
                <p class="fs-sm fw-semibold text-white-75 mb-0">
                  <i class="fa fa-users opacity-75 me-1"></i> Referrals
                </p>
              </div>
              <div class="col-4 col-xl-4">
                <p class="fs-3 fw-semibold text-white mb-0">
                    {{ auth()->user()->myAttemptedJobs()->where('status', 'Approved')->count() }}
                </p>
                <p class="fs-sm fw-semibold text-white-75 mb-0">
                  <i class="fa fa-briefcase opacity-75 me-1"></i> Jobs Done
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Hero -->

   <!-- Page Content -->
   <div class="content content-full">
   <div class="row">
  


      <div class="col-12">
          @if(auth()->user()->wallet->base_currency == 'Naira')

            @if(auth()->user()->is_verified)
                  <div class="alert alert-warning">
                    Your Naira account has been <strong>Verified.</strong>
                </div>
            @endif

          @else

            @if(auth()->user()->USD_verified)
                <div class="alert alert-warning">
                  Your Dollar account has been <strong>Verified.</strong>
              </div>
            @endif

          @endif
      </div>

      <div class="col-12">
        @if($announcement)
          <div class="alert alert-info">
            {!! @$announcement->content !!}
          </div>
        @endif
      </div>

   </div>

<div class="row">

    <div class="col-md-6">
        <h5 class="fw-light mb-2">Referral Link</h5>
        <div class="js-task block block-rounded block-fx-pop block-fx-pop mb-2 animated fadeIn" data-task-id="9" data-task-completed="false" data-task-starred="false">
            <table class="table table-borderless table-vcenter mb-0">
                <div class="input-group">
                    <input type="text" value="{{url('register/'.auth()->user()->referral_code)}}" class="form-control form-control-alt" id="myInput" readonly>
                    <button type="button" class="btn btn-alt-secondary" onclick="myFunction()" onmouseout="outFunc()">
                        <i class="fa fa-copy"></i>
                    </button>
                </div>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <h5 class="fw-light mb-2">Your Funding Account(to fund your wallet)</h5>
        <div class="js-task block block-rounded block-fx-pop block-fx-pop mb-2 animated fadeIn" data-task-id="9" data-task-completed="false" data-task-starred="false">
            <table class="table table-borderless table-vcenter mb-0">
                <div class="input-group">
                  {{-- <span class="form-control form-control-alt">Coming Soon!</span>  --}}
                  @if(auth()->user()->wallet->base_currency == "Naira")
      
                      @if(auth()->user()->virtualAccount)

                        <span class="form-control form-control-alt">{{ auth()->user()->virtualAccount->bank_name }}</span> <input type="text" value="{{ auth()->user()->virtualAccount->account_number }}" class="form-control form-control-alt" id="myInput-2" readonly>
                        <button type="button" class="btn btn-alt-secondary" onclick="myFunction2()" onmouseout="outFunc()">
                          <i class="fa fa-copy"></i>
                        </button>
                        
                      @else
                    
                      {{-- <span class="form-control form-control-alt">VFD</span> <input type="text" value="4600066074" class="form-control form-control-alt" id="myInput-2" readonly>
                        <button type="button" class="btn btn-alt-secondary" onclick="myFunction2()" onmouseout="outFunc()">
                          <i class="fa fa-copy"></i>
                        </button> --}}

                            <span class="form-control form-control-alt">
                              <a href="{{ url('reactivate/virtual/account/'.auth()->user()->id) }}" class="btn btn-success btn-sm">Activate Freebyz Personal Account</a>
                            </span>
                      @endif

                  @else
                  <span class="form-control form-control-alt">
                    <a href="{{ url('wallet/fund') }}" class="btn btn-success btn-sm"> Click Here to Fund Wallet</a>
                  </span>
                        {{-- <span class="form-control form-control-alt">https://flutterwave.com/pay/topuponfreebyz</span>    --}}
                  @endif
                </div>

                </table>
            </div>
        </div>
    </div>


    <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
        {{-- <h4 class="fw-light mb-0">Ad</h4><br> --}}
        @if($ads)
                <a href="{{ url('ad/'.$ads->banner_id.'/view')}}" target="_blank">
                    <img src="{{ url($ads->banner_url)  }}" width="100%" height="300" class="img-responsive">
                </a>
        @else

                <a href="{{ url('banner/create')}}" target="_blank">
                  <img src="https://freebyz.s3.us-east-1.amazonaws.com/ad/1701380428.jpg" width="100%" height="300" class="img-responsive">
                </a>
        
        @endif
    </div>

    
    <!-- VPS -->
    <div class="d-flex justify-content-between align-items-center mt-0 mb-3">

        <h4 class="fw-light mb-0">Available Jobs</h4>

         <form action="{{ url('switch/wallet') }}" method="POST">
            @csrf
              @if(auth()->user()->wallet->base_currency == 'Naira')
              <input type="hidden" name="currency" value="Dollar">
              <button type="submit" class="btn btn-primary btn-sm btn-primary rounded-pill px-3">
                <i class="fa fa-fw fa-share opacity-50 me-1"></i> Switch to Dollar
              </button>
              {{-- <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-fw fa-share opacity-50"></i>Switch Currency to Dollar</button> --}}
              @else
              <input type="hidden" name="currency" value="Naira">
              <button type="submit" class="btn btn-primary btn-sm btn-primary rounded-pill px-3">
                <i class="fa fa-fw fa-share opacity-50 me-1"></i> Switch to Naira
              </button>
              {{-- <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-fw fa-share opacity-50"></i>Switch Currency to Naira</button> --}}
              @endif
          </form> 

    </div>

    <form action="#" method="POST">
      <div class="row items-push">
        {{-- <div class="col-sm-6 col-xl-8">
          <div class="input-group">
            <span class="input-group-text">
              <i class="fa fa-search"></i>
            </span>
            <input type="text" class="form-control border-start-0" id="dm-projects-search" name="dm-projects-search" placeholder="Search Projects..">
          </div>
        </div> --}}
        {{-- <div class="col-sm-6 col-xl-3 offset-xl-6"> --}}
        <div class="col-sm-6 col-xl-3">
          <select class="form-select" id="jobs-categories" name="dm-projects-filter">
            <option value="0">All Categories</option>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}">{{ ucwords($category->name) }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>


     
      {{-- <div class="block block-rounded block-fx-pop mb-2">
        <div class="block-content block-content-full border-start border-3 border-dark">
          <div class="d-md-flex justify-content-md-between align-items-md-center">
            <div class="p-1 p-md-3 w-50">
              <h3 class="h4 fw-bold mb-1">vps158875_ny1</h3>
              <p class="fs-sm text-muted">
                <i class="fa fa-map-pin me-1"></i> New York
              </p>
              <div class="mb-0">
                <div class="progress mb-1" style="height: 6px;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 33%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="fs-sm fw-semibold mb-3">
                  <span class="fw-bold">1GB</span> of <span class="fw-bold">3GB</span> RAM
                </p>
                <div class="progress mb-1" style="height: 6px;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 80%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="fs-sm fw-semibold mb-3">
                  <span class="fw-bold">25GB</span> of <span class="fw-bold">50GB</span> Disk Space
                </p>
              </div>
            </div>
            <div class="p-1 p-md-3 text-md-end">
              <a class="btn btn-sm btn-alt-primary rounded-pill px-3 me-1 my-1" href="javascript:void(0)">
                <i class="fa fa-redo opacity-50 me-1"></i> Restart
              </a>
              <a class="btn btn-sm btn-alt-primary rounded-pill px-3 me-1 my-1" href="javascript:void(0)">
                <i class="fa fa-wrench opacity-50 me-1"></i> Manage
              </a>
              <a class="btn btn-sm btn-alt-danger rounded-pill px-3 my-1" href="javascript:void(0)">
                <i class="fa fa-times opacity-50 me-1"></i> Delete
              </a>
            </div>
          </div>
        </div>
      </div>  --}}

      @if(auth()->user()->wallet->base_currency == 'Naira')
          @if($promotion)
              <a href="{{ url('m/'.$promotion->business_link) }}" target="_blank">
                <div class="block block-rounded block-fx-pop mb-2">
                  <div class="block-content block-content-full border-start border-3 border-dark">
                    <div class="d-md-flex justify-content-md-between align-items-md-center">
                      <div class="col-12">
                        <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> <small><i style="color: goldenrod">Freebyz Business Promotion</i></small></div>
                        <h3 class="h4 fw-bold mb-1" style="color: #191918">{{ $promotion->business_name}}</h3>
                        <p class="fs-sm text-muted">
                          
                            {!! $promotion->description !!}
                        </p>
                      </div>
                      
                    </div>
                  </div>
                </div> 
              </a> 
          @endif
      @endif

    
        <a href="https://payhankey.com" target="_blank">
          <div class="block block-rounded block-fx-pop mb-2">
            <div class="block-content block-content-full border-start border-3 border-dark">
              <div class="d-md-flex justify-content-md-between align-items-md-center">
                <div class="col-12">
                  <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                  <h3 class="h4 fw-bold mb-1" style="color: #191918">Payhankey</h3>
                  <p class="fs-sm text-muted">
                    <i class="fa fa-heart me-1"></i>Monetize your Posts on Payhankey
                          Earn up to $500 daily. Monetize every comment, likes and views.
                  </p>
                </div>
                
              </div>
            </div>
          </div> 
        </a> 

        <a href="{{ route('agent.wellahealth') }}">
          <div class="block block-rounded block-fx-pop mb-2">
            <div class="block-content block-content-full border-start border-3 border-dark">
              <div class="d-md-flex justify-content-md-between align-items-md-center">
                <div class="col-12">
                  <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                  <h3 class="h4 fw-bold mb-1" style="color: #191918">WellaHealth</h3>
                  <p class="fs-sm text-muted">
                    <i class="fa fa-heart me-1"></i>Earn up to &#8358;2,800 daily by Selling Wellahealth Insurance
                  </p>
                  
                </div>
                
              </div>
            </div>
          </div> 
        </a> 

        <div class="" id="display-jobs">
        </div>

   </div>



   <!-- END Call to Action -->
    @if(auth()->user()->profile->is_welcome == 0)
    
      {{-- Show welcome pop up --}}
      @include('layouts.resources.welcome')

    @elseif(auth()->user()->wallet->base_currency == 'Dollar' || auth()->user()->wallet->base_currency == 'USD' && auth()->user()->wallet->base_currency_set  == 0)
       
        @include('layouts.resources.validate_currency')  

    {{-- @elseif(!auth()->user()->accountDetails)
        @if(auth()->user()->wallet->base_currency == "Naira")
            @include('layouts.resources.account_details') 
        @endif --}}

    {{-- @elseif(!auth()->user()->profile->phone_verified)

        @if(auth()->user()->wallet->base_currency == "Naira")
            @include('layouts.resources.account_details')      
        @endif  --}}



    @elseif(auth()->user()->is_verified == 0)
    
      {{-- @include('layouts.resources.unverified') --}}

    {{-- @else
    
      @include('layouts.resources.xmas') --}}

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

    console.log(navigator.clipboard.writeText(copyText.value));
    
    // var tooltip = document.getElementById("myTooltip");
    // tooltip.innerHTML = "Copied: " + copyText.value;
  }

  function myFunction2(){
    var copyText = document.getElementById("myInput-2");
    
    copyText.select();
    copyText.setSelectionRange(0, 99999);

    console.log(navigator.clipboard.writeText(copyText.value));
  }

  $(document).ready(function() {
            const baseApiUrl = '{{ url("available/jobs") }}';
            const baseUrl = '{{ url("campaign") }}';

            function loadJobs(apiUrl) {
                fetch(apiUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        $("#display-jobs").empty(); // Clear previous results
                        $.each(data, function(key, value) {
                            const url = `${baseUrl}/${value.job_id}`;
                            const jobHtml = `
                                <a href="${url}">
                                    <div class="block block-rounded block-fx-pop mb-2">
                                        <div class="block-content block-content-full border-start border-3 border-primary">
                                            <div class="d-md-flex justify-content-md-between align-items-md-center">
                                                <div class="col-12">
                                                    <h3 class="h4 fw-bold mb-1" style="color: black">${value.post_title}</h3>
                                                    <p class="fs-sm text-muted">${value.local_converted_currency_code} ${value.local_converted_amount}</p>
                                                    <div class="mb-0">
                                                        <div class="progress mb-1" style="height: 6px;">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: ${value.progress}%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <p class="fs-sm fw-semibold mb-3" style="color: black">
                                                            <span class="fw-bold">${value.completed} completed</span>
                                                            <span class="fw-bold text-muted">out of ${value.number_of_staff} workers</span>
                                                        </p>
                                                    </div>
                                                    <p class="fs-sm text-muted mb-0">${value.type}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>`;
                            $("#display-jobs").append(jobHtml);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            // Load all jobs on initial page load
            loadJobs(`${baseApiUrl}/0`);

            // Load jobs based on selected category
            $('#jobs-categories').on('change', function() {
                const selectedValue = $(this).val();
                const apiUrl = `${baseApiUrl}/${selectedValue}`;
                loadJobs(apiUrl);
            });
        });
    


  


  

  
  




</script>


@endsection