@extends('layouts.main.master')
@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Get Verified</h1>
        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active" aria-current="page">Verification</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <div class="content">
     <!-- Special Offer -->
     <div class="bg-body-light">
      <div class="content content-boxed content-full">
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <div class="py-5">
          <h2 class="mb-2 text-center">
           Get Verified...
          </h2>
          <h3 class="fw-light text-muted push text-center">
            If you get verified today you will get all the following at no extra cost.
          </h3>
        </div>
        <div class="row py-3">
          <div class="col-sm-6 col-md-6 mb-5">
            <div class="my-3">
              <i class="fa fa-2x fa-phone text-xeco"></i>
            </div>
            <h4 class="h5 mb-2">
              Lifetime access to jobs
            </h4>
            <p class="mb-0 text-muted">
              You will get unlimited access to available jobs including premium ones.  
            </p>
          </div>
          <div class="col-sm-6 col-md-6 mb-5">
            <div class="my-3">
              <i class="fa fa-2x fa-users text-danger"></i>
            </div>
            <h4 class="h5 mb-2">
              Hire Workers
            </h4>
            <p class="mb-0 text-muted">
              You will have unlimited access to hire workers to promote jobs.
            </p>
          </div>
        </div>
        <div class="row py-3">
          <div class="col-sm-6 col-md-6 mb-5">
            <div class="my-3">
              <i class="fa fa-2x fa-share text-xinspire"></i>
            </div>
            <h4 class="h5 mb-2">
              Unlimited Withdrawals
            </h4>
            <p class="mb-0 text-muted">
              Access to withdraw all your earnings and other juicy offers coming up shortly. 
              You'll earn &#8358;25,000 on the friends you refer and we'll reward you with &#8358;5,000 extra.
            </p>
          </div>
          <div class="col-sm-6 col-md-6 mb-5">
            <div class="my-3">
              <i class="fa fa-2x fa-hand-paper text-xinspire"></i>
            </div>
            <h4 class="h5 mb-2">
             Bank Transfer
            </h4>
            <p class="mb-0 text-muted">
              You can fund your wallet and verify your account with your wallet balance. 
              Click <a href="{{ url('wallet/fund')}}"> Here </a> to fund your wallet. 
              {{-- You can do Manual Funding by sending the Fee to our account. We will activate/verify your account from our back end. 
              Kindly attach a receipt of payment to us in <b>Talk to Us</b> panel of your dashboard. --}}
              <br>
              <b>
                {{-- Account Details: 1014763749 - DOMINAHL TECH SERVICES (Zenith Bank) --}}
                {{-- <i>(Please add your Freebyz name, email address and date of transaction in the description while sending payment proof)</i> --}}
              </b>
              {{-- <b>ACCOUNT DETAILS: 4600066074 - DOMINAHL TECH SERVICES (VFD Microfinance Bank) 
                <i>(Please add your persnal name example: Verification from Samuel Mark)</i>
              </b> --}}
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- END Special Offer -->

    <!-- Call to Action -->
    <div class="content content-boxed text-center">
      <div class="py-5">
        
        <h2 class="mb-3 text-center">
          Why Upgrade?
        </h2>

        <h3 class="h4 fw-light text-muted push text-center">
          Getting verified can help you make more money!
        </h3>

        <span class="m-2 d-inline-block">
          @if(auth()->user()->wallet->base_currency == 'Naira')
              @if(auth()->user()->is_verified == '0')

                  {{-- <a href="{{ route('make.payment') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                    <i class="fa fa-link opacity-50 me-1"></i>Get Verified Using Card 
                  </a>
                  
                  <br><br> --}}

                  {{-- <a href="{{ route('make.payment.wallet') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                    <i class="fa fa-link opacity-50 me-1"></i> Verify 
                  </a> --}}
                
                @if(auth()->user()->wallet->balance >= 1050)
                <a href="{{ route('make.payment.wallet') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                  <i class="fa fa-link opacity-50 me-1"></i> Verify with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}} 
                </a>
                @else
                <a href="#" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                  <i class="fa fa-link opacity-50 me-1"></i> Verify with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}} 
                </a>
                @endif
                  

              {{-- <hr>
              <h3 class="h4 fw-light text-muted push text-center mt-3">Can't pay in Naira, Click the Link Button Below</h3>

              <a href="https://flutterwave.com/pay/payfreebyz" target="_blank" class="btn btn-hero btn-secondary" data-toggle="click-ripple">
                <i class="fa fa-link opacity-50 me-1"></i> Verify Using USD 
              </a> --}}

              @else
              <a class="btn btn-hero btn-primary disabled" href="javascript:void(0)" data-toggle="click-ripple">
                <i class="fa fa-link opacity-50 me-1"></i> Verification Completed
              </a>
              @endif
          @else
          
              @if(!auth()->user()->USD_verified)
                <a href="{{ route('make.payment') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                  <i class="fa fa-link opacity-50 me-1"></i>Get Verified Using Card 
                </a>
              @else
                  <a class="btn btn-hero btn-primary disabled" href="javascript:void(0)" data-toggle="click-ripple">
                    <i class="fa fa-link opacity-50 me-1"></i> Verification Completed
                  </a>
              @endif

          @endif
        </span>
      </div>
    </div>
  </div>

@endsection