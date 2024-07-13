{{-- @extends('layouts.main.master')
@section('content')

 <!-- Hero -->
 <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Fastest Finger</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Fastest Finger</li>
            <li class="breadcrumb-item active" aria-current="page">View All</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Full Table -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title"></h3>
        <div class="block-options">
          <button type="button" class="btn-block-option">
            <i class="si si-settings"></i>
          </button>
        </div>
      </div>
      <div class="block-content">
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
            
       
        <div class="table-responsive">
            <ul class="list-group push">  
               
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Current Badge
                    <span class="nav-main-link-badge badge rounded-pill "><span style="color: black; font-size:15px">gjfghhkm</span> <i class="fa fa-star fa-lg" aria-hidden="true" style="color:white"></i> </span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                   Number of Paid Referrals
                   <span class="badge rounded-pill bg-info">gfkjhgfygh</span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Date 
                    <span class=" rounded-pill ">gyfkhgk</span>
                </li>
                
            </ul>
            <a href="{{ route('redeem.badge') }}" class="btn btn-secondary mb-3">Redeem</a>

          
        </div>
      </div>
    </div>
    <div class="d-flex">
     
    </div>
    <!-- END Full Table -->

  </div>

  @endsection --}}



  @extends('layouts.main.master')
@section('content')
  <!-- Page Content -->
<div class="content">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Fastest Finger</h3>
    </div>
  <div class="block-content">
 
    
      <!-- Text -->

      {{-- <div class="mb-2 text-center content-heading mb-4">
        <p class="text-uppercase fw-bold fs-sm text-muted">Buy Airtime</p>
        <p class="link-fx fw-bold fs-1">
          &#8358;{{ number_format(auth()->user()->wallet->balance) }}
        </p>
        <p>Wallet Balance</p>
      </div> --}}

      <div class="row">
        <div class="col-lg-3"></div>
          <div class="col-lg-6">
            
            <div class="alert alert-info mb-4">
              Share the fun, enjoy daily giveaway from Freebyz. We are giving out FREE Airtime/Data to all qualified Users. To access the freebies, you
              <br>
              - Must have 10 verified referrals in the last 10days<br>
              - Must be a verified user <br>
            </div>
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

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!auth()->user()->is_verified)
                <div class="alert alert-danger">
                  You need to be a verified to partake in the give away! To get verified, <a href="{{ url('upgrade') }}"> Click Here </a>
                </div>
            @else
            
            @if($refCount >= 10)

                @if(!$Info)

                    <div class="alert alert-success">
                      You are eligible for the Fastest Finger Give away! Please fill the foem below to get started
                    </div>
                    
                    <form class="js-validation-reminder" action="{{ route('fastest.finger') }}" method="POST">
                      @csrf
                      <div class="mb-4">
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="fab fa-tiktok"></i>
                          </span>
                          <input type="text" class="form-control @error('tiktok') is-invalid @enderror" id="reminder-credential" name="tiktok" value="{{ old('tiktok') }}" placeholder="Enter Tiktok Handle" required>
                        </div>
                      </div>
                      <div class="mb-4">
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="fa fa-phone"></i>
                          </span>
                          <input type="text" class="form-control @error('phone') is-invalid @enderror" id="reminder-credential" name="phone" value="{{ old('phone') }}" placeholder="Enter phone Number (08054887593)" required>
                        </div>
                      </div>
                      <div class="mb-4">
                        <div class="input-group">
                        <select class="form-control" name="network" required>
                            <option value="">Select Network</option>
                            <option value="MTN">MTN</option>
                            <option value="AIRTEL">AIRTEL</option>
                            <option value="GLO">GLO</option>
                            <option value="9MOBILE">9MOBILE</option>
                        </select>
                        </div>
                      </div>
                      <div class="text-center mb-4">
                        <button type="submit" class="btn btn-primary">
                          <i class="fa fa-fw fa-share opacity-50 me-1"></i> Declare Interest
                        </button>
                      </div>
                    </form>

                @else
                
                  @if(!$checkTodayPool)
                    <div class="alert alert-success">
                      Yaayyyyy...You qualified for our daily recharge card giveaway. Click the button below to enter Pool for todays Giveaway
              
                      </div>
                    <form action="{{ route('enter.pool') }}" method="POST">
                      @csrf

                    <button type="submit" class="btn btn-primary mb-3">Enter Pool for: {{ Carbon\Carbon::today()->format('l M Y') }}</button>

                    </form>
                  @else
                    <div class="alert alert-success">
                    Yes! Pool successfully submitted  for {{ Carbon\Carbon::today()->format('l M Y') }}
              
                    </div>
                      
                  @endif

                @endif

              @else
              
                <div class="alert alert-danger">
                  You have {{ $refCount }} verified referrals in the last 10 days, therefore you do not qualify. 
                </div>

              @endif

            @endif

          </div>
          <div class="col-lg-3"></div>
       
      </div>
      <!-- END Text -->
    
  </div>
</div>

@endsection

@section('script')
 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

 <!-- Page JS Code -->
 <script src="{{ asset('src/assets/js/pages/op_auth_reminder.min.js') }}"></script>
@endsection


