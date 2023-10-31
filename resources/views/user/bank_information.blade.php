@extends('layouts.main.master')

@section('content')

<div class="bg-body-light">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
      <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Account Setup</h1>
      <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb">
          {{-- <li class="breadcrumb-item">Bank Details</li> --}}
          {{-- <li class="breadcrumb-item active" aria-current="page">View All</li> --}}
        </ol>
      </nav>
    </div>
  </div>
</div>
<!-- END Hero -->

<div class="content">
  
  @if(auth()->user()->profile->phone_verified == true)
        @if(!$bankInfo)  

          <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Enter Bank Details</h3>
            </div>
            <!-- Hero -->
            <div class="block-content">

              <form id="contact-form" action="{{ route('save.bank.information') }}" method="post">
                  @csrf
                <!-- Text -->
                <div class="row">
                  <div class="col-lg-3"></div>
                    <div class="col-lg-6">
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
                      <div class="mb-4">
                          <label>Select Bank Name</label>
                          <div class="input-group">
                              
                          <select class="form-control" name="bank_code" required>
                              <option value="">Select Bank</option>
                              @foreach ($bankList as $bank)
                                  <option value="{{ $bank['code'] }}"> {{ $bank['name'] }}</option>
                                  {{--  <input type="hidden" name="bank_name" value="{{ $bank['name'] }}">  --}}
                              @endforeach    
                              </select> 
                          </div>
                      </div>

                      <div class="mb-4">
                          <label>Enter Account Number</label>
                        <div class="input-group">
                          <span class="input-group-text">
                            
                          </span>
                          {{-- <input type="text" class="form-control text-center" id="example-group1-input3" name="example-group1-input3" placeholder="00"> --}}
                          <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="reminder-credential" name="account_number" placeholder="Enter Account Number" required value="{{ old('account_number') }}">
                          {{-- <span class="input-group-text">,00</span> --}}
                        </div>
                      </div>
                      
                      <div class="text-center mb-4">
                        <button type="submit" class="btn btn-primary">
                          <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save & Continue
                        </button>
                      </div>
                    </div>
                    <div class="col-lg-3"></div>
                
                </div>
                <!-- END Text -->
              </form>

          </div>

          @else
          
          <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Account Setup Completed</h3>
            </div>
            <!-- Hero -->
            <div class="block-content">
              <div class="row">
    
                <div class="col-sm-12">
                  <div class="block block-rounded invisible" data-toggle="appear">
                    <div class="block-content block-content-full">
                      <div class="py-5 text-center">
                        <div class="item item-2x item-circle bg-success text-white mx-auto">
                          <i class="fa fa-2x fa-check"></i>
                        </div>
                        <div class="fs-4 fw-semibold pt-3 mb-0">Account Setup Successfully Completed</div>
                        <br>
                        <a href="{{ url('home') }}" class="btn btn-primary"> <li class="fa fa-home"> </li> Home </a>
                      </div>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          
        @endif

  @else

  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Verify Phone Number</h3>
    </div>
    <!-- Hero -->
    <div class="block-content">
      @if(!$otp)
        <form id="contact-form" action="{{ route('send.phone.otp') }}" method="post">
            @csrf
          <!-- Text -->
          <div class="row">
            <div class="col-lg-3"></div>
              <div class="col-lg-6">
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
                
                <div class="mb-4">
                    <label>Enter Phone Number</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <li class="fa fa-phone"></li>
                    </span>
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="reminder-credential" name="phone_number" placeholder="e.g 080xxxx" required value="{{ old('phone_number') }}">
                  </div>
                </div>
                
                <div class="text-center mb-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-fw fa-save opacity-50 me-1"></i> Send OTP
                  </button>
                </div>
              </div>
              <div class="col-lg-3"></div>
          
          </div>
          <!-- END Text -->
        </form>
      @else

      <form id="contact-form" action="{{ route('verify.phone.otp') }}" method="post">
        @csrf
      <!-- Text -->
      <div class="row">
        <div class="col-lg-3"></div>
          <div class="col-lg-6">
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
            
            <div class="mb-4">
                <label>Enter OTP</label>
              <div class="input-group">
                <span class="input-group-text">
                  <li class="fa fa-phone"></li>
                </span>
                <input type="text" class="form-control @error('otp') is-invalid @enderror" id="reminder-credential" name="otp" required value="{{ old('otp') }}">
              </div>
            </div>
            
            <div class="text-center mb-4">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-fw fa-save opacity-50 me-1"></i> Send OTP
              </button>
            </div>
          </div>
          <div class="col-lg-3"></div>
      
      </div>
      <!-- END Text -->
    </form>
      @endif

  </div>

  @endif

</div>

@endsection

@section('script')
 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

 <!-- Page JS Code -->
 <script src="{{ asset('src/assets/js/pages/op_auth_reminder.min.js') }}"></script>
  <!-- jQuery (required for jQuery Appear plugin) -->
  <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>

  <!-- Page JS Plugins -->
  <script src="{{asset('src/assets/js/plugins/jquery-appear/jquery.appear.min.js')}}"></script>
 
  <!-- Page JS Helpers (jQuery Appear plugin) -->
  <script>Dashmix.helpersOnLoad(['jq-appear']);</script>
@endsection


