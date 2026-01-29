@extends('layouts.main.master')

@section('style')
@endsection

@section('content')
    @php
        $user = auth()->user();
        $wallet = $user->wallet;
        $profile = $user->profile;
        $isNGN = $wallet->base_currency == 'NGN';
        $isVerified = $isNGN ? $user->is_verified : $user->USD_verified;
        $virtualAccount = $user->virtualAccount ?? null;

        $currency = baseCurrency();
        $balance = match($currency) {
            'NGN' => $wallet->balance,
            'USD' => $wallet->usd_balance,
            default => $wallet->base_currency_balance
        };
    @endphp

    <!-- Hero -->
    <div class="bg-image"
        style="background-image: url('https://i.natgeofe.com/n/9e7c6381-8205-4a0c-a3a6-e744bf86a751/climbing-8000-meters-first-winter-ascents-everest.jpg');">
        <div class="bg-primary-dark-op">
            <div class="content content-full">
                <div class="row my-3">
                    <div class="col-md-5 d-md-flex align-items-md-center">
                        <div class="py-4 py-md-0 text-center text-md-start">
                            <h1 class="fs-2 text-white mb-2">
                                {{ $user->name }}
                                @if ($isVerified)
                                    <i class="fa fa-{{ $isNGN ? 'check' : 'check-double' }} opacity-75 me-1"></i>
                                @endif
                            </h1>
                            <h2 class="fs-lg fw-normal text-white-75 mb-0">Complete Simple Jobs and Get Paid!</h2>
                        </div>
                    </div>
                    <div class="col-md-7 d-md-flex align-items-md-center">
                        <div class="row w-100 text-center">
                            <div class="col-4 col-xl-4">
                                <p class="fs-3 fw-semibold text-white mb-0">
                                    {{ $currency }} {{ number_format($balance, 2) }}
                                </p>
                                <p class="fs-sm fw-semibold text-white-75 mb-0">
                                    <i class="fa fa-money-bill opacity-75 me-1"></i> Balance
                                </p>
                            </div>
                            <div class="col-4 col-xl-4">
                                <p class="fs-3 fw-semibold text-white mb-0">
                                    {{ $user->referees()->count() }}
                                </p>
                                <p class="fs-sm fw-semibold text-white-75 mb-0">
                                    <i class="fa fa-users opacity-75 me-1"></i> Referrals
                                </p>
                            </div>
                            <div class="col-4 col-xl-4">
                                <p class="fs-3 fw-semibold text-white mb-0">
                                    {{ $user->myAttemptedJobs()->where('status', 'Approved')->count() }}
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
                @if ($isVerified)
                    <div class="alert alert-warning">
                        Your {{ $isNGN ? 'Naira' : 'Dollar' }} account has been <strong>Verified.</strong>
                    </div>
                @endif
            </div>

            <div class="col-12">
                @if ($announcement)
                    <div class="alert alert-info">
                        {!! @$announcement->content !!}
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="col-md-6">
                <h5 class="fw-light mb-2">Referral Link</h5>
                <div class="js-task block block-rounded block-fx-pop block-fx-pop mb-2 animated fadeIn">
                    <table class="table table-borderless table-vcenter mb-0">
                        <div class="input-group">
                            <input type="text" value="{{ url('register/' . $user->referral_code) }}"
                                class="form-control form-control-alt" id="myInput" readonly>
                            <button type="button" class="btn btn-alt-secondary" onclick="copyToClipboard('myInput')">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <h5 class="fw-light mb-2">Your Funding Account (to fund your wallet)</h5>
                <div class="js-task block block-rounded block-fx-pop block-fx-pop mb-2 animated fadeIn">
                    <table class="table table-borderless table-vcenter mb-0">
                        <div class="input-group">
                            @if ($isNGN)
                                @if ($virtualAccount)
                                    @if ($virtualAccount->bank_name == 'Wema Bank')
                                        <span class="form-control form-control-alt">
                                            <a href="{{ route('create.updated.virtual.account') }}"
                                                class="btn btn-success btn-sm">Activate New Freebyz Personal Account</a>
                                        </span>
                                    @else
                                        <span class="form-control form-control-alt">{{ $virtualAccount->bank_name }}</span>
                                        <input type="text" value="{{ $virtualAccount->account_number }}"
                                            class="form-control form-control-alt" id="myInput-2" readonly>
                                        <button type="button" class="btn btn-alt-secondary" onclick="copyToClipboard('myInput-2')">
                                            <i class="fa fa-copy"></i>
                                        </button>
                                    @endif
                                @else
                                    {{-- <span class="form-control form-control-alt">VFD</span> <input type="text" value="4600066074" class="form-control form-control-alt" id="myInput-2" readonly>
                        <button type="button" class="btn btn-alt-secondary" onclick="myFunction2()" onmouseout="outFunc()">
                          <i class="fa fa-copy"></i>
                        </button> --}}
                                    <span class="form-control form-control-alt">
                                        {{-- <a href="{{ route('generate.virtual.account') }}" class="btn btn-success btn-sm">Fund your Wallet</a> --}}
                                        <a href="{{ url('wallet/fund') }}" class="btn btn-success btn-sm">Click Here to Fund Wallet</a>
                                    </span>
                                @endif
                            @else
                                <span class="form-control form-control-alt">
                                    <a href="{{ url('wallet/fund') }}" class="btn btn-success btn-sm">Click Here to Fund Wallet</a>
                                </span>
                                {{-- <span class="form-control form-control-alt">https://flutterwave.com/pay/topuponfreebyz</span>    --}}
                            @endif
                        </div>
                    </table>
                </div>
            </div>
        </div>

        {{-- <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
            <h4 class="fw-light mb-0">Ad</h4><br>
            @if ($ads)
                <a href="{{ url('ad/'.$ads->banner_id.'/view')}}" target="_blank">
                    <img src="{{ asset($ads->banner_url) }}" width="100%" height="300" class="img-responsive">
                </a>
            @else
                <a href="{{ url('banner/create')}}" target="_blank">
                    <img src="https://freebyz.s3.us-east-1.amazonaws.com/ad/1701380428.jpg" width="100%" height="300" class="img-responsive">
                </a>
                <iframe width="1060" height="315" src="https://www.youtube.com/embed/XAjXvFri0Z4?si=XCPZbkHroL7qfh2j" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            @endif
        </div> --}}

        <!-- Available Jobs -->
        <div class="d-flex justify-content-between align-items-center mt-0 mb-3">
            <h4 class="fw-light mb-0">Available Jobs</h4>

            {{-- @csrf
              @if ($wallet->base_currency == 'Naira')
              <input type="hidden" name="currency" value="Dollar">
              <button type="submit" class="btn btn-primary btn-sm btn-primary rounded-pill px-3">
                <i class="fa fa-fw fa-share opacity-50 me-1"></i> Switch to Dollar
              </button>
              @else
              <input type="hidden" name="currency" value="Naira">
              <button type="submit" class="btn btn-primary btn-sm btn-primary rounded-pill px-3">
                <i class="fa fa-fw fa-share opacity-50 me-1"></i> Switch to Naira
              </button>
              @endif
          </form>  --}}
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

        {{-- @if ($isNGN)
          @if ($promotion)
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
      @endif --}}

        @if ($user)
            <a href="https://dashboard.freebyz.com/campaign/EbmFbEm">
            @else
                <a href="https://dashboard.freebyz.com/campaign/public/EbmFbEm" target="_blank">
        @endif

        {{-- <div class="block block-rounded block-fx-pop mb-2">
            <div class="block-content block-content-full border-start border-3 border-dark">
                <div class="d-md-flex justify-content-md-between align-items-md-center">
                    <div class="col-12">
                        <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                        <h3 class="h4 fw-bold mb-1" style="color: #191918">NEW: Learn 2 Earn</h3>
                        <p class="fs-sm text-muted">
                            <i class="fa fa-heart me-1"></i> Get paid to build skills! Complete FREE courses from Propel
                            and earn N500.
                        </p>
                    </div>
                </div>
            </div>
        </div> --}}
        </a>

        <a href="https://payhankey.com/register" target="_blank">
            <div class="block block-rounded block-fx-pop mb-2">
                <div class="block-content block-content-full border-start border-3 border-dark">
                    <div class="d-md-flex justify-content-md-between align-items-md-center">
                        <div class="col-12">
                            <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                            <h3 class="h4 fw-bold mb-1" style="color: #191918">Monetize your Posts on Payhankey</h3>
                            <p class="fs-sm text-muted">
                                <i class="fa fa-heart me-1"></i> Your posts can earn you money. You don't need Followers or Watch hours to earn
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ url('skills') }}" target="_blank">
            <div class="block block-rounded block-fx-pop mb-2">
                <div class="block-content block-content-full border-start border-3 border-dark">
                    <div class="d-md-flex justify-content-md-between align-items-md-center">
                        <div class="col-12">
                            <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                            <h3 class="h4 fw-bold mb-1" style="color: #191918">Hire Skilled and Unskilled Workers</h3>
                            <p class="fs-sm text-muted">
                                <i class="fa fa-heart me-1"></i>Hire the best hands to work with you.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- <a href="{{ route('agent.wellahealth') }}">
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
        </a>  --}}

        <a href="https://famlic.com" target="_blank">
            <div class="block block-rounded block-fx-pop mb-2">
                <div class="block-content block-content-full border-start border-3 border-dark">
                    <div class="d-md-flex justify-content-md-between align-items-md-center">
                        <div class="col-12">
                            <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                            <h3 class="h4 fw-bold mb-1" style="color: #191918">Raise Money for Urgent Needs</h3>
                            <p class="fs-sm text-muted">
                                <i class="fa fa-heart me-1"></i> Create a Crowdfunding link to share with your Friends
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- <a href="https://zireemilsoude.net/4/8562126" target="_blank">
          <div class="block block-rounded block-fx-pop mb-2">
            <div class="block-content block-content-full border-start border-3 border-dark">
              <div class="d-md-flex justify-content-md-between align-items-md-center">
                <div class="col-12">
                  <div class="icon" style="color:#191918"> <i class="fa fa-briefcase"></i> </div>
                  <h3 class="h4 fw-bold mb-1" style="color: #191918">Get Amazing Opportunities from our Partners</h3>
                  <p class="fs-sm text-muted">
                    <i class="fa fa-heart me-1"></i>Play Games and Install Apps to earn more money
                  </p>
                </div>
              </div>
            </div>
          </div>
        </a>  --}}

        <div class="" id="display-jobs"></div>
        <div id="pagination-controls" class="d-flex justify-content-center mt-3"></div>
    </div>

    @include('layouts.resources.pathway')

    <!-- Conditional Modals -->
    @if ($profile->is_welcome == false)
        {{-- Show welcome pop up --}}
        @include('layouts.resources.welcome')
    @elseif($wallet->base_currency == 'USD' && $wallet->base_currency_set == false)
        @include('layouts.resources.validate_currency')
    @elseif($profile->pathway == null)
        @include('layouts.resources.choice')
        {{-- @elseif(!$user->accountDetails)
        @if ($isNGN)
            @include('layouts.resources.account_details')
        @endif --}}

        {{-- @elseif(!$profile->phone_verified)
        @if ($isNGN)
            @include('layouts.resources.account_details')
        @endif  --}}

        {{-- @elseif($isNGN && @$virtualAccount->bank_name == 'Wema Bank')
     @include('layouts.resources.virtualaccount') --}}

        {{-- @include('layouts.resources.unverified') --}}

        {{-- @else
      @include('layouts.resources.xmas') --}}
    @endif
@endsection

@section('script')
    <!-- jQuery (required for Slick Slider plugin) -->
    <script src="{{ asset('src/assets/js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('src/assets/js/plugins/slick-carousel/slick.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('src/assets/js/pages/be_comp_onboarding.min.js') }}"></script>

    <script>
        // Unified copy to clipboard function
        function copyToClipboard(elementId) {
            const copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
        }

        $(document).ready(function() {
            const baseApiUrl = '{{ url('available/jobs') }}';
            const baseUrl = '{{ url('campaign') }}';
            const itemsPerPage = 30;
            let currentPage = 1;

            function loadJobs(apiUrl) {
                fetch(apiUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch jobs: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        renderJobs(data.data);
                        updatePaginationControls(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        $("#display-jobs").html(
                            `<div class="alert alert-warning mt-2">Failed to load jobs. Please refresh your browser.</div>`
                        );
                    });
            }

            function renderJobs(jobs) {
                const container = $("#display-jobs");
                container.empty();

                if (jobs.length === 0) {
                    container.html(`<div class="alert alert-info mt-2">No jobs found for this category</div>`);
                    return;
                }

                // Filter out completed jobs
                const filteredJobs = jobs.filter(job => !job.is_completed);

                filteredJobs.forEach(job => {
                    const url = `${baseUrl}/${job.job_id}`;
                    const jobHtml = `
                        <a href="${url}">
                            <div class="block block-rounded block-fx-pop mb-2">
                                <div class="block-content block-content-full border-start border-3 border-primary">
                                    <div class="d-md-flex justify-content-md-between align-items-md-center">
                                        <div class="col-12">
                                            <h3 class="h4 fw-bold mb-1" style="color: black">${job.post_title}</h3>
                                            <p class="fs-sm text-muted">${job.local_currency} ${job.local_converted_amount}</p>
                                            <div class="mb-0">
                                                <div class="progress mb-1" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                                         role="progressbar"
                                                         style="width: ${job.progress}%;"
                                                         aria-valuenow="${job.percentage_progress}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                                <p class="fs-sm fw-semibold mb-3" style="color: black">
                                                    <span class="fw-bold">${job.completed} completed</span>
                                                    <span class="fw-bold text-muted">out of ${job.number_of_staff} workers</span>
                                                </p>
                                            </div>
                                            <p class="fs-sm text-muted mb-0">${job.type} &bull; Originally posted in ${job.currency}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>`;
                    container.append(jobHtml);
                });
            }

            function updatePaginationControls(data) {
                const controls = $("#pagination-controls");
                controls.empty();

                if (data.total === 0) return;

                const paginationHtml = `
                    ${data.current_page > 1 ? `<button class="btn btn-primary me-2 btn-sm" id="prev-page">Previous</button>` : ''}
                    <span class="align-self-center mx-3">Page ${data.current_page} of ${data.last_page}</span>
                    ${data.current_page < data.last_page ? `<button class="btn btn-primary btn-sm" id="next-page">Next</button>` : ''}
                `;
                controls.html(paginationHtml);

                $("#prev-page").click(() => loadJobs(`${baseApiUrl}/0?page=${data.current_page - 1}`));
                $("#next-page").click(() => loadJobs(`${baseApiUrl}/0?page=${data.current_page + 1}`));
            }

            // Initial load
            loadJobs(`${baseApiUrl}/0?page=${currentPage}`);

            // Category filter
            $('#jobs-categories').on('change', function() {
                const selectedValue = $(this).val();
                currentPage = 1;
                loadJobs(`${baseApiUrl}/${selectedValue}?page=${currentPage}`);
            });
        });

        {{-- Legacy commented code for reference --}}
        //   $(document).ready(function () {
        //     const baseApiUrl = '{{ url('available/jobs') }}';
        //     const baseUrl = '{{ url('campaign') }}';

        //     let currentPage = 1;

        //     function loadJobs(apiUrl) {
        //         fetch(apiUrl)
        //             .then(response => {
        //                 if (!response.ok) {
        //                     throw new Error('Network response was not ok ' + response.statusText);
        //                 }
        //                 return response.json();
        //             })
        //             .then(data => {
        //                 $("#display-jobs").empty(); // Clear previous results

        //                 // Loop through data and render jobs
        //                 $.each(data.data, function (key, value) {
        //                     const url = `${baseUrl}/${value.id}`;
        //                     const jobHtml = `
        //                         <a href="${url}">
        //                             <div class="block block-rounded block-fx-pop mb-2">
        //                                 <div class="block-content block-content-full border-start border-3 border-primary">
        //                                     <div class="d-md-flex justify-content-md-between align-items-md-center">
        //                                         <div class="col-12">
        //                                             <h3 class="h4 fw-bold mb-1" style="color: black">${value.post_title}</h3>
        //                                             <p class="fs-sm text-muted">${value.local_converted_currency_code} ${value.local_converted_amount}</p>
        //                                             <div class="mb-0">
        //                                                 <div class="progress mb-1" style="height: 6px;">
        //                                                     <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: ${value.progress}%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
        //                                                 </div>
        //                                                 <p class="fs-sm fw-semibold mb-3" style="color: black">
        //                                                     <span class="fw-bold">${value.completed} completed</span>
        //                                                     <span class="fw-bold text-muted">out of ${value.number_of_staff} workers</span>
        //                                                 </p>
        //                                             </div>
        //                                             <p class="fs-sm text-muted mb-0">${value.type} &bull; Originally posted in ${value.currency}</p>
        //                                         </div>
        //                                     </div>
        //                                 </div>
        //                             </div>
        //                         </a>`;
        //                     $("#display-jobs").append(jobHtml);
        //                 });

        //                 console.log(data.data);

        //                 // Update pagination controls
        //                 updatePaginationControls(data);
        //             })
        //             .catch(error => {
        //                 console.error('Error:', error);
        //             });
        //     }

        //     function updatePaginationControls(data) {
        //         const paginationControls = $("#pagination-controls");
        //         paginationControls.empty();

        //         // Previous button
        //         if (data.current_page > 1) {
        //             paginationControls.append(`<button id="prev-page" class="btn btn-primary me-2">Previous</button>`);
        //             $("#prev-page").click(() => loadJobs(`${baseApiUrl}/0?page=${data.current_page - 1}`));
        //         }

        //         // Next button
        //         if (data.current_page < data.last_page) {
        //             paginationControls.append(`<button id="next-page" class="btn btn-primary">Next</button>`);
        //             $("#next-page").click(() => loadJobs(`${baseApiUrl}/0?page=${data.current_page + 1}`));
        //         }
        //     }

        //     // Load initial jobs
        //     loadJobs(`${baseApiUrl}/0`);

        //     // Load jobs based on selected category
        //     $('#jobs-categories').on('change', function () {
        //         const selectedValue = $(this).val();
        //         currentPage = 1; // Reset to first page on filter change
        //         const apiUrl = `${baseApiUrl}/${selectedValue}?page=${currentPage}`;
        //         loadJobs(apiUrl);
        //     });
        // });


        // $(document).ready(function() {


        //           const baseApiUrl = '{{ url('available/jobs') }}';
        //           const baseUrl = '{{ url('campaign') }}';

        //           function loadJobs(apiUrl) {
        //               fetch(apiUrl)
        //                   .then(response => {
        //                       if (!response.ok) {
        //                           throw new Error('Network response was not ok ' + response.statusText);
        //                       }
        //                       console.log(response);
        //                       return response.json();
        //                   })
        //                   .then(data => {
        //                       $("#display-jobs").empty(); // Clear previous results
        //                       $.each(data, function(key, value) {
        //                           const url = `${baseUrl}/${value.job_id}`;
        //                           const jobHtml = `
        //                               <a href="${url}">
        //                                   <div class="block block-rounded block-fx-pop mb-2">
        //                                       <div class="block-content block-content-full border-start border-3 border-primary">
        //                                           <div class="d-md-flex justify-content-md-between align-items-md-center">
        //                                               <div class="col-12">
        //                                                   <h3 class="h4 fw-bold mb-1" style="color: black">${value.post_title}</h3>
        //                                                   <p class="fs-sm text-muted">${value.local_converted_currency_code} ${value.local_converted_amount}</p>
        //                                                   <div class="mb-0">
        //                                                       <div class="progress mb-1" style="height: 6px;">
        //                                                           <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: ${value.progress}%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
        //                                                       </div>
        //                                                       <p class="fs-sm fw-semibold mb-3" style="color: black">
        //                                                           <span class="fw-bold">${value.completed} completed</span>
        //                                                           <span class="fw-bold text-muted">out of ${value.number_of_staff} workers</span>
        //                                                       </p>
        //                                                   </div>
        //                                                   <p class="fs-sm text-muted mb-0">${value.type} &bull; Originally posted in ${value.currency}</p>
        //                                               </div>
        //                                           </div>
        //                                       </div>
        //                                   </div>
        //                               </a>`;
        //                           $("#display-jobs").append(jobHtml);
        //                       });

        //                       console.log(data);
        //                   })
        //                   .catch(error => {
        //                       console.error('Error:', error);
        //                   });
        //           }

        //           // Load all jobs on initial page load
        //           loadJobs(`${baseApiUrl}/0`);

        //           // Load jobs based on selected category
        //           $('#jobs-categories').on('change', function() {
        //               const selectedValue = $(this).val();
        //               const apiUrl = `${baseApiUrl}/${selectedValue}`;
        //               loadJobs(apiUrl);
        //           });
        // });
    </script>
@endsection
