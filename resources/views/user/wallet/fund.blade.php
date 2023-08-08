@extends('layouts.main.master')
@section('content')
<div class="content">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Fund Wallet</h3>
    </div>
  <div class="block-content">
 
    <form class="js-validation-reminder" action="{{ route('store.funds') }}" method="POST">
        @csrf
      <!-- Text -->

      <div class="mb-2 text-center content-heading mb-4">
        {{-- <p class="text-uppercase fw-bold fs-sm text-muted">Fund Wallet</p> --}}
        <p class="link-fx fw-bold fs-1">
          @if(auth()->user()->wallet->base_currency == "Naira")
          &#8358;{{ number_format(auth()->user()->wallet->balance) }}
          @else
          ${{ number_format(auth()->user()->wallet->usd_balance) }}
          @endif
          {{-- &#8358;{{ number_format(auth()->user()->wallet->balance) }} --}}
        </p>
        <p>Wallet Balance</p>
      </div>
      
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
              @if(auth()->user()->wallet->base_currency == 'Naira')
              <div class="input-group">
                <span class="input-group-text">
                  &#8358;
                </span>
                <input type="number" class="form-control @error('balance') is-invalid @enderror" id="reminder-credential" name="balance" min="500" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                <span class="input-group-text">.00</span>
              </div>
              @else
              <div class="input-group">
                <span class="input-group-text">
                  $
                </span>
                <input type="number" class="form-control @error('balance') is-invalid @enderror" id="reminder-credential" name="balance" min="2" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                <span class="input-group-text">.00</span>
              </div>
              @endif

            </div>

            <div class="text-center mb-4">
              <button type="submit" class="btn btn-primary">
                <i class="si si-paper-plane opacity-50 me-1"></i> Fund Wallet
              </button>
            </div>
          </div>
          <div class="col-lg-3"></div>
       
      </div>
      @if(auth()->user()->wallet->base_currency == 'Naira')
        <div class="row mb-3">
          <div class="col-lg-2"></div>
          <div class="col-lg-8" style="color: brown">
            You can now fund your wallet via Card or Bank Transfer. Once wallet top up is successful, click the button below to verify your account.
             Verification fee is N1050 (Naira account) and $5 (Dollar account).
            <center>
                <a href="{{ route('make.payment.wallet') }}" class="btn btn-hero btn-primary mt-3" data-toggle="click-ripple">
                  <i class="fa fa-link opacity-50 me-1"></i> Verify with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}} 
                </a>
            </center>

             {{-- Manual Wallet funding, please credit the account below:
               <b>ACCOUNT DETAILS: 4600066074 
              <br>DOMINAHL TECH SERVICES (VFD Microfinance Bank) 
              <br>
              <i>(Add <b><i>wallet funding</i></b> in the transfer description)</i> --}}
          </div>
          <div class="col-lg-2"></div>
      </div>
      @endif
      <!-- END Text -->
    </form>
  </div>
</div>

@endsection

@section('script')
 <!-- Page JS Plugins -->
 <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
 <!-- Page JS Code -->
 <script src="{{ asset('src/assets/js/pages/op_auth_reminder.min.js') }}"></script>
@endsection


