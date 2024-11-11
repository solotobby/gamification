@extends('layouts.main.master')
@section('content')
 <!-- Hero -->
 <div class="bg-body-light">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
      <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Withdraw Funds</h1>
      <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item active" aria-current="page">Withdraw</li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<!-- END Hero -->
  <!-- Page Content -->
  <div class="content">
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">.</h3>
      </div>
    <div class="block-content">
   
      <form class="js-validation-reminder" action="{{ route('store/withdraw') }}" method="POST">
          @csrf
        <!-- Text -->
  
        <div class="mb-2 text-center content-heading mb-4">
          {{-- <p class="text-uppercase fw-bold fs-sm text-muted">Withdraw Funds</p> --}}
          <p class="link-fx fw-bold fs-1">
           

            @if(baseCurrency() == 'NGN')
                &#8358;{{ number_format(auth()->user()->wallet->balance,2) }}
            @elseif(baseCurrency() == 'GHS')
                &#8373;{{ number_format(auth()->user()->wallet->base_currency_balance,2) }}
            @elseif(baseCurrency() == 'USD')
                $ {{ number_format(auth()->user()->wallet->usd_balance,2) }}
            @else
                {{ baseCurrency() }} {{ number_format(auth()->user()->wallet->base_currency_balance,2) }}
            @endif
          
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

              @if(baseCurrency() == 'NGN')

              <div class="mb-4">
                <div class="input-group">
                
                      <span class="input-group-text">
                        &#8358;
                      </span>
                      <input type="number" class="form-control @error('amount') is-invalid @enderror" id="reminder-credential" min="2500" name="balance" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                      <span class="input-group-text">.00</span>
                </div>
              </div>

              <div class="mb-4">
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fa fa-grip-vertical"></i>
                  </span>
                
                  <select name="type" class="form-control" required>
                    <option value="">Select An Option</option>
                    <option value="local_withdrawal">Local Withdrawal</option>
                    <option value="paypal_withdrawal">Paypal Withdrawal</option>
                  </select>
                  
                </div>
              </div>

              @elseif(baseCurrency() == 'USD')

                <div class="mb-4">
                  <div class="input-group">
                    <span class="input-group-text">
                      <i class="fa fa-envelope"></i>
                    </span>
                    <input type="email" class="form-control @error('paypal_email') is-invalid @enderror" id="reminder-credential" name="paypal_email" value="{{ old('paypal_email') }}" placeholder="Enter Paypal Email Address" required>
                  </div>
                </div>
                

              @else

                  <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">

                          @if(baseCurrency() == 'NGN')
                              &#8358;
                          @elseif(baseCurrency() == 'GHS')
                              &#8373;
                          @elseif(baseCurrency() == 'USD')
                              $ 
                          @else
                              {{ baseCurrency() }}
                          @endif

                        </span>
                        <input type="text" class="form-control @error('amount') is-invalid @enderror" id="reminder-credential" min="0" name="balance" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                        <span class="input-group-text">.00</span>
                    </div>
                  </div> 

                  @if(baseCurrency() == 'GHS')
                      <div class="mb-4">
                        <div class="input-group">
                          <span class="input-group-text">
                            <i class="fa fa-grip-vertical"></i>
                          </span>
                        
                          <select name="account_bank" class="form-control" required>
                            <option value="">Select Bank</option>
                            <option value="AIRTEL">AIRTEL</option>
                            <option value="TIGO">TIGO</option>
                            <option value="MTN">MTN</option>
                            <option value="VODAFONE">VODAFONE</option>
                          
                          </select>
                          
                        </div>
                      </div>

                      <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text">
                              <i class="fa fa-grip-vertical"></i>
                            </span>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="reminder-credential" name="account_number" value="{{ old('account_number') }}" placeholder="Enter Account Number" required>
                        </div>
                      </div>

                      <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text">
                              <i class="fa fa-grip-vertical"></i>
                            </span>
                            <input type="text" class="form-control @error('beneficiary_name') is-invalid @enderror" id="reminder-credential" name="beneficiary_name" value="{{ old('beneficiary_name') }}" placeholder="Enter Beneficiary Name" required>
                        </div>
                      </div>

                  @else
                      <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text">
                              <i class="fa fa-grip-vertical"></i>
                            </span>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="reminder-credential" name="account_number" value="{{ old('account_number') }}" placeholder="Enter Account Number" required>
                        </div>
                      </div>

                      <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text">
                              <i class="fa fa-grip-vertical"></i>
                            </span>
                            <input type="text" class="form-control @error('beneficiary_name') is-invalid @enderror" id="reminder-credential" name="beneficiary_name" value="{{ old('beneficiary_name') }}" placeholder="Enter Beneficiary Name" required>
                        </div>
                      </div>
                  @endif
                 
                    {{-- <span class="input-group-text">
                      $
                    </span>
                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="reminder-credential" min="5" name="balance" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                    <span class="input-group-text">.00</span>
               --}}

                  @endif
               
              {{-- @if(auth()->user()->wallet->base_currency== 'Naira')
                  <div class="mb-4">
                    <div class="input-group">
                      <span class="input-group-text">
                        <i class="fa fa-grip-vertical"></i>
                      </span>
                    
                      <select name="type" class="form-control" required>
                        <option value="">Select An Option</option>
                        <option value="local_withdrawal">Local Withdrawal</option>
                        <option value="paypal_withdrawal">Paypal Withdrawal</option>
                      </select>
                      
                    </div>
                  </div>
              @else
              {{-- <div class="mb-4">
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fa fa-grip-vertical"></i>
                  </span>
                
                  <select name="country" id="country-list" class="form-control" required>
                    <option value="">Select Receipient Country</option>
                    <option value="GH">Ghana</option>
                    <option value="KE">Kenya</option>
                    <option value="UG">Uganda</option>
                    <option value="TZ">Tanzania</option>
                    <option value="RW">Rwanda</option>
                  </select>
                  
                </div>
              </div>  --}}

              {{-- <div class="mb-4">
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fa fa-grip-vertical"></i>
                  </span>
                
                  <select name="bank" id="bank-list" class="form-control" required>
                      <option value="">Select Bank</option>
                  </select>
                  
                </div>
              </div>  --}}

              {{-- <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text">
                      <i class="fa fa-phone"></i>
                    </span>
                    <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="reminder-credential"  name="mobile" value="{{ old('phone') }}" placeholder="Enter Mobile number" required>
                </div>
              </div>  --}


              <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text">
                      $
                    </span>
                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="reminder-credential" min="5" name="balance" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                    <span class="input-group-text">.00</span>
                </div>
              </div> 

              <div class="mb-4">
                <div class="input-group">
              <span class="input-group-text">
                <i class="fa fa-envelope"></i>
              </span>
              <input type="email" class="form-control @error('paypal_email') is-invalid @enderror" id="reminder-credential" name="paypal_email" value="{{ old('paypal_email') }}" placeholder="Enter Paypal Email Address" required>
                </div>
              </div>


             
          @endif --}}

              <div class="text-center mb-4">
               
                @if(auth()->user()->is_verified == true)
                  <button type="submit" class="btn btn-primary">
                    <i class="si si-paper-plane opacity-50 me-1"></i> Withdraw Fund
                  </button>
                @else
                  <button type="button" class="btn btn-primary disabled">
                    <i class="si si-paper-plane opacity-50 me-1"></i> Withdrawal Not Activated
                  </button>
                @endif
  
              </div>
            </div>
            <center><p style="color: brown"><i>Note: Withdrawals are made every friday of the week. Only verified users can withdraw</i> </p></center>
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
  $(document).ready(function(){ 
    

    $('#country-list').change(function(){
      var countryCode = this.value;
     
      // api/brail/rates
      $.ajax({
            url: '{{ url("api/flutterwave/list/banks/") }}/' + encodeURI(countryCode),
            type: "GET",
            data: {
                //  country_id: country_id,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
               
                // console.log(result.data)

                $('#bank-list').html('<option value="">Select Bank</option>');
                $.each(result.data, function(key, value) {
                    $("#bank-list").append('<option value="' + value.code + '">' + value.name + '</option>');  
                });
            }
      });

     });
  });
  </script>

@endsection