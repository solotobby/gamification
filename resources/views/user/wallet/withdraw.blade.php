@extends('layouts.main.master')
@section('content')
  <!-- Page Content -->
  <div class="content">
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Withdraw Funds</h3>
      </div>
    <div class="block-content">
   
      <form class="js-validation-reminder" action="{{ route('store.withdraw') }}" method="POST">
          @csrf
        <!-- Text -->
  
        <div class="mb-2 text-center content-heading mb-4">
          {{-- <p class="text-uppercase fw-bold fs-sm text-muted">Withdraw Funds</p> --}}
          <p class="link-fx fw-bold fs-1">
            @if(auth()->user()->wallet->base_currency == "Naira")
          &#8358;{{ number_format(auth()->user()->wallet->balance,3) }}
          @else
          ${{ number_format(auth()->user()->wallet->usd_balance,3) }}
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
                <div class="input-group">
                  @if(auth()->user()->wallet->base_currency == 'Naira')
                      <span class="input-group-text">
                        &#8358;
                      </span>
                      <input type="number" class="form-control @error('amount') is-invalid @enderror" id="reminder-credential" min="2500" name="balance" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                      <span class="input-group-text">.00</span>
                  @else

                 
                    {{-- <span class="input-group-text">
                      $
                    </span>
                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="reminder-credential" min="5" name="balance" value="{{ old('balance') }}" placeholder="Enter Amount" required>
                    <span class="input-group-text">.00</span>
               --}}

                  @endif
                </div>
              </div>
              @if(auth()->user()->wallet->base_currency== 'Naira')
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
              </div>  --}}


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


             
          @endif

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