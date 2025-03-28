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
        <div class="py-5">
          <h2 class="mb-2 text-center">
           Get Verified...
          </h2>
          <h3 class="fw-light text-muted push text-center">
            If you get verified today, you will get all the following at no extra cost.
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
              You will get unlimited access to jobs above 20 Naira (for Naira wallet) and can earn in dollars (when you verify your dollar wallet). Dollar verification fee is $6 (to access dollar jobs)
            </p>
          </div>
          <div class="col-sm-6 col-md-6 mb-5">
            <div class="my-3">
              <i class="fa fa-2x fa-users text-danger"></i>
            </div>
            <h4 class="h5 mb-2">
             Earn Referral Bonus
            </h4>
            <p class="mb-0 text-muted">
              You can invite your friends using your referral link and earn N1000 on each verified friend. Some users have cashed out more than N100,000 at a time by just referring friends only with extra income on tasks completed.
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
              You get access to unlimited withdrawals paid to your local or paypal account, every Friday. 
              We put smiles on ou users' faces every friday. You'll earn &#8358;25,000 on the friends you refer and we'll reward you with &#8358;5,000 extra.
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
             
            </p>
          </div>
        </div>

        <p>
          Apart from  N1000 bonus you get on each friend you refer, We have special referral bonuses in 3 levels:
          <br> 1. When you get 50+ verified users in a month, you earn over N25k plus extra N5000 from Freebyz
          <br> 2. When you get 20+ verified users in a month, you earn over N10k plus extra N1500 from Freebyz
          <br> 3. When you get 10+ verified users in a month, you earn over earn N5k plus extra N500 from Freebyz
        </p>
      </div>
    </div>
    <!-- END Special Offer -->

    <!-- Call to Action -->
    <div class="content content-boxed text-center">
      <div class="py-5">
        
        <h2 class="mb-3 text-center">
          How to get Verified
        </h2>


        @if(auth()->user()->wallet->base_currency == 'NGN')
          <h3 class="h4 fw-light text-muted push text-center">
            Credit your account below with &#8358;3000 for automatic verification of Naira Wallet
          </h3>
          @if(auth()->user()->virtualAccount)
              <p>
                Account Name: {{ auth()->user()->virtualAccount->account_name }} | Bank Name: {{ auth()->user()->virtualAccount->bank_name }} <br> Account Number: {{ auth()->user()->virtualAccount->account_number }}
              </p>
              
              <p> For alternative manual verification, pay to <b>6667335193 (Moniepoint BANK- Freebyz Technologies LTD) </b> </p>
      
          @else
          <br>
              <p> For alternative manual verification, pay to <b>6667335193 (Moniepoint BANK- Freebyz Technologies LTD) </b> </p>
      
              <a href="{{ route('generate.virtual.account') }}" class="btn btn-success btn-sm">Activate Your Freebyz Personal Account</a>
          @endif
          <p> <i>Note: After successful payment to your virtual wallet, your account will be automatically verified. <br>You can then proceed <a href="{{ url('home') }}">here</a> to start earning.</i></p>
        @endif

        <span class="m-2 d-inline-block">
          @if(auth()->user()->wallet->base_currency == 'NGN')
              @if(auth()->user()->is_verified == '0')

                  {{-- <a href="{{ route('make.payment') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                    <i class="fa fa-link opacity-50 me-1"></i>Get Verified Using Card 
                  </a>
                  
                  <br><br>

                  @if(auth()->user()->wallet->balance >= 1050)
                    <a href="{{ route('make.payment.wallet') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                        <i class="fa fa-link opacity-50 me-1"></i> Verify with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}}
                      </a>
                  @else
                    <a href="#" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                      <i class="fa fa-link opacity-50 me-1"></i> Verify with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}} 
                    </a>
                  @endif --}}

                
                @if(auth()->user()->wallet->balance >= 3000)
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

          @elseif( auth()->user()->wallet->base_currency == 'USD')
          
              @if(!auth()->user()->USD_verified)
                <a href="{{ route('make.payment') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                  <i class="fa fa-link opacity-50 me-1"></i>Get Verified Using Wallet Balance - $5
                </a>
              @else
                  <a class="btn btn-hero btn-primary disabled" href="javascript:void(0)" data-toggle="click-ripple">
                    <i class="fa fa-link opacity-50 me-1"></i> Verification Completed
                  </a>
              @endif

              @else

              @if(!auth()->user()->USD_verified)
                <a href="{{ route('make.payment.foreign') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
                  <i class="fa fa-link opacity-50 me-1"></i>Get Verified Using Wallet Balance - {{ baseCurrency() }} {{ currencyParameter( baseCurrency() )->upgrade_fee }}
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