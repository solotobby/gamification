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
              You'll earn &#8358;12,500 on the friends you refer and we'll reward you with &#8358;5,000 extra.
            </p>
          </div>
          <div class="col-sm-6 col-md-6 mb-5">
            <div class="my-3">
              <i class="fa fa-2x fa-hand-paper text-xinspire"></i>
            </div>
            <h4 class="h5 mb-2">
              Manual Withdrawals
            </h4>
            <p class="mb-0 text-muted">
              You can do Manual Funding by sending the Fee to our account. We will activate/verify your account from our back end. 
              Kindly attach a receipt of payment to us in <b>Talk to Us</b> panel of your dashboard.
              <b>ACCOUNT DETAILS: 1014763749 - DOMINAHL TECH SERVICES (ZENITH)</b>
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
          Getting verified can help you expand your business reach and acquire much more customers!
        </h3>
        <span class="m-2 d-inline-block">
          @if(auth()->user()->is_verified == '0')
          <a href="{{ route('make.payment') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
            <i class="fa fa-link opacity-50 me-1"></i>Get Verified Using Card 
          </a><br><br>
          {{-- <button type="button" class="btn btn-hero btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default-popout-upgrade"><i class="fa fa-link opacity-50 me-1"></i> Get Verified</button> --}}
          @if(auth()->user()->wallet->balance >= 500)
          <a href="{{ route('make.payment.wallet') }}" class="btn btn-hero btn-primary" data-toggle="click-ripple">
            <i class="fa fa-link opacity-50 me-1"></i> Verify with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}} 
          </a>
          @else
          <a href="#" class="btn btn-hero btn-primary" data-toggle="click-ripple">
            <i class="fa fa-link opacity-50 me-1"></i> Verify with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}} 
          </a>
          @endif

          <div class="modal fade" id="modal-default-popout-upgrade" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Select an upgrade method </h5> 
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pb-1">
                   
                    <hr>
                    <div class="block-content">
                      <ul class="list-group push">
                         @if(auth()->user()->wallet->balance >= 1000)
                          <a href="{{ route('make.payment.wallet') }}"> 
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                             Upgrade with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}}
                              <span class="badge rounded-pill bg-info"><i class="fa fa-wallet opacity-50 me-1"></i></span>
                            </li>
                          </a>
                          @else
                           
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                             Upgrade with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}}
                              <span class="badge rounded-pill bg-info"><i class="fa fa-wallet opacity-50 me-1"></i></span>
                             
                            </li>
                            <code>Wallet Balance too Low for Upgrade</code>
                          @endif
                          <a href="{{ route('make.payment') }}"> 
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            Upgrade with Card
                            <span class="badge rounded-pill bg-info"><i class="fa fa-credit-card opacity-50 me-1"></i></span>
                          </li>
                          </a>
                        
                        
                        
                      </ul>
                    </div>
                    <form action="{{ route('campaign.decision') }}" method="POST">
                      @csrf
                      
                    {{-- <a href="{{ url('campaign/approve/'.$list->id) }}" class="btn btn-alt-primary btn-lg ml-10"><i class="fa fa-check"></i> Approve </a>
                    <a href="{{ url('campaign/deny/'.$list->id) }}" class="btn btn-alt-danger btn-lg"><i class="fa fa-times"></i> Deny</a> --}}
                    </form>
                    <br>
                </div>
                
                <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
                </div>
            </div>
            </div>
        </div>
          @else
          <a class="btn btn-hero btn-primary disabled" href="javascript:void(0)" data-toggle="click-ripple">
            <i class="fa fa-link opacity-50 me-1"></i> Verification Completed
          </a>
         
          @endif

         
        </span>
      </div>
    </div>
  </div>

@endsection