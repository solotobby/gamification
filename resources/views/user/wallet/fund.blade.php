@extends('layouts.main.master')
@section('content')
 <!-- Hero -->
 <div class="bg-body-light">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
      <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Fund Wallet</h1>
      <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item active" aria-current="page">Fund Wallet</li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<!-- END Hero -->

<div class="content">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">.</h3>
    </div>
  <div class="block-content">

    <form class="js-validation-reminder" action="{{ route('store.funds') }}" method="POST">
        @csrf
      <!-- Text -->
      <div class="mb-2 text-center content-heading mb-4">
        <p class="text-uppercase fw-bold fs-sm text-muted">Fund Wallet</p>
        <p class="link-fx fw-bold fs-1">

          @if(auth()->user()->wallet->base_currency == 'NGN')
              &#8358;{{ number_format(auth()->user()->wallet->balance,2) }}
          @elseif(auth()->user()->wallet->base_currency == 'GHS')
              &#8373;{{ number_format(auth()->user()->wallet->base_currency_balance,2) }}

          @elseif(auth()->user()->wallet->base_currency == 'USD')
          ${{ number_format(auth()->user()->wallet->usd_balance,2) }}
         @else   
         {{ baseCurrency() }} {{ number_format(auth()->user()->wallet->usd_balance,2) }}
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

            {{-- <div class="col-md-12">
             <div class="input mb-2">
              <label class="mb-2">Funding Channel</label>
                <span class="input-group-text">
                  Funding Channel
                </span>
                <select name="currency" class="form-control @error('method') is-invalid @enderror" required>
                  <option value="">Select a Funding Channel</option>
                  
                  <option value="va">Virtual Account</option>
                  <option value="koray">Koray Pay</option>
                  <option value="KES">Kenya</option>
                  <option value="USD">Other</option>
                </select>
              </div>
              <small><i>Select other if your country is not listed</i></small> 
            </div> --}}


            <div class="mb-4">
              @if(auth()->user()->wallet->base_currency == 'NGN')

                <div class="input mb-2">
                  <label class="mb-2">Funding Channel</label>
                  <select name="channel" class="form-control @error('method') is-invalid @enderror" required>
                    <option value="">Select a Funding Channel</option>
                    <option value="va">Virtual Account</option>
                    @if(auth()->user()->email == 'solotobby@gmail.com')
                    <option value="kora">Koray Pay</option>
                    @endif
                  </select>
                </div>



                <div id="va">
                  <div class="alert alert-info">
                    <li class="fa fa-info"></li> Fund your wallet by making a transfer to the account details below. Your wallet get credited in less than 1min
                  </div>
              
                      @if(auth()->user()->virtualAccount)
                            Account Name: {{ auth()->user()->virtualAccount->account_name }} <br>
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

                            <a href="{{ route('generate.virtual.account') }}" class="btn btn-secondary"> Click here to Generate a Virtual Account</a>

                      @endif

                </div>
                <div id="kora">
                  <div class=" mb-2">
                  <label class="mb-1">Amount</label>
                      <input type="number" class="form-control @error('balance') is-invalid @enderror" id="reminder-credential" name="balance" min="50" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                  </select>
                  </div>

  
                <div class="mb-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="si si-paper-plane opacity-50 me-1"></i> Fund Wallet
                  </button>
                </div>
              </div>

              {{-- <div class="input-group">
                <span class="input-group-text">
                  &#8358;
                </span>
                <input type="number" class="form-control @error('balance') is-invalid @enderror" id="reminder-credential" name="balance" min="2" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                <span class="input-group-text">.00</span>
              </div>

              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">
                  <i class="si si-paper-plane opacity-50 me-1"></i> Fund Wallet
                </button>
              </div> --}}


              @else

              {{-- <div class="input-group mb-0">
                <span class="input-group-text">
                  Country 
                </span>
                <select name="currency" class="form-control @error('method') is-invalid @enderror" required>
                  <option value="">Select Country</option>
                  
                  <option value="GHS">Ghana</option>
                  <option value="RWF">Rwanda</option>
                  <option value="KES">Kenya</option>
                  <option value="USD">Other</option>
                </select>
              </div>
              <small><i>Select other if your country is not listed</i></small> --}}

              {{-- <div class="input-group mb-4">
                <span class="input-group-text">
                  Payment Method
                </span>
                <select name="method" class="form-control @error('method') is-invalid @enderror" required>
                  <option value="">Select Payment Method</option>
                  <option value="stripe">Stripe</option>
                  <option value="flutterwave">Flutterwave</option>
                </select>
              </div> --}}


             
                <div class="input-group">
                  <span class="input-group-text">

                    @if(auth()->user()->wallet->base_currency == "Naira")
                        &#8358;
                    @elseif(auth()->user()->wallet->base_currency == 'GHS')
                        &#8373;
          
                    @elseif(auth()->user()->wallet->base_currency == 'KES')
                        KES 
                    @elseif(auth()->user()->wallet->base_currency == 'TZS')
                        TZS 
                    @elseif(auth()->user()->wallet->base_currency == 'RWF')
                        RWF 
                    @elseif(auth()->user()->wallet->base_currency == 'MWK')
                        MWK
                        
                    @elseif(auth()->user()->wallet->base_currency == 'ZAR')
                       ZAR
                    @elseif(auth()->user()->wallet->base_currency == 'UGX')
                      UGX
                    @else
                        $
                    @endif

                    

                  </span>
                  <input type="number" class="form-control @error('balance') is-invalid @enderror" id="reminder-credential" name="balance" min="2" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                  <span class="input-group-text">.00</span>
                </div>

              {{-- @else
                <div class="input-group">
                  <span class="input-group-text">
                    $
                  </span>
                  <input type="number" class="form-control @error('balance') is-invalid @enderror" id="reminder-credential" name="balance" min="2" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                  <span class="input-group-text">.00</span>
                </div>
              @endif --}}

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

 <script> 
  window.addEventListener('DOMContentLoaded', function() { 
     

      // Get references to the select element and the divs
    const currencySelect = document.querySelector('select[name="channel"]');
    const vaDiv = document.getElementById('va');
    const koraDiv = document.getElementById('kora');


    // Hide both divs by default
    vaDiv.style.display = 'none';
    koraDiv.style.display = 'none';

    // Add an event listener for changes to the select element
    currencySelect.addEventListener('change', function() {
        const selectedValue = currencySelect.value;

        // Show or hide divs based on the selected value
        if (selectedValue === 'va') {
            vaDiv.style.display = 'block';
            koraDiv.style.display = 'none';
        } else if (selectedValue === 'kora') {
            vaDiv.style.display = 'none';
            koraDiv.style.display = 'block';
        } else {
            vaDiv.style.display = 'none';
            koraDiv.style.display = 'none';
        }
    });



  });
 </script>

 @endsection


