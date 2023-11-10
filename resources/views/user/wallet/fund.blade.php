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

            <div class="col-md-12">
             
            </div>
            <div class="mb-4">
              @if(auth()->user()->wallet->base_currency == 'Naira')
              <div class="alert alert-info">
                <li class="fa fa-info"></li> Fund your wallet by making a transfer to the account details below. Your wallet get credited in less than 5mins
              </div>
              
              @if(auth()->user()->virtualAccount)
              Bank Name: {{ auth()->user()->virtualAccount->bank_name }} <br>
         
              Account Number:  {{ auth()->user()->virtualAccount->account_number }}
              <hr>
                  @if(auth()->user()->is_verified == '0')
                        <center>
                          <a href="{{ route('make.payment.wallet') }}" class="btn btn-hero btn-primary mt-1" data-toggle="click-ripple">
                            <i class="fa fa-link opacity-50 me-1"></i> Verify with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}} 
                          </a>
                        </center>
                  @else
                  <center>
                    <a href="#"" class="btn btn-hero btn-primary mt-1 disabled" data-toggle="click-ripple">
                      <i class="fa fa-link opacity-50 me-1"></i> Verify with Wallet Balance &#8358;{{number_format(auth()->user()->wallet->balance)}} 
                    </a>
                  </center>
                  @endif
            @else 

                  <a href="{{  url('bank/information') }}" class="btn btn-secondary"> Click here to Generate a Virtual Account</a>

            @endif

              {{-- <div class="input-group">
                <span class="input-group-text">
                  &#8358;
                </span>
                <input type="number" class="form-control @error('balance') is-invalid @enderror" id="reminder-credential" name="balance" min="500" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                <span class="input-group-text">.00</span>
              </div>

              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">
                  <i class="si si-paper-plane opacity-50 me-1"></i> Fund Wallet
                </button>
              </div> --}}


              @else
              <div class="input-group">
                <span class="input-group-text">
                  $
                </span>
                <input type="number" class="form-control @error('balance') is-invalid @enderror" id="reminder-credential" name="balance" min="2" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                <span class="input-group-text">.00</span>
              </div>

            </div>

            <div class="text-center mb-4">
              <button type="submit" class="btn btn-primary">
                <i class="si si-paper-plane opacity-50 me-1"></i> Fund Wallet
              </button>
            </div>
            @endif
          </div>
          <div class="col-lg-3"></div>
       
      </div>
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


