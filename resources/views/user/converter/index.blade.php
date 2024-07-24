@extends('layouts.main.master')
@section('content')
  <!-- Page Content -->
<div class="content">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Currency Converter</h3>
    </div>
  <div class="block-content">
 
    <form class="js-validation-reminder" action="{{ route('make.conversion') }}" method="POST">
        @csrf
      <!-- Text -->

      <div class="mb-2 text-center content-heading mb-4">
        {{-- <p class="text-uppercase fw-bold fs-sm text-muted">Buy Databundle</p> --}}
        <p class="link-fx fw-bold fs-1">
            @if(auth()->user()->wallet->base_currency == "Naira")
            &#8358;{{ number_format(auth()->user()->wallet->balance,2) }}
            @else
            ${{ number_format(auth()->user()->wallet->usd_balance,2) }}
            @endif
          {{-- &#8358;{{ number_format(auth()->user()->wallet->balance) }} --}}
        </p>
        <p>Wallet Balance</p>

        &#8358;{{ number_format(auth()->user()->wallet->balance,2) }}
        -
        ${{ number_format(auth()->user()->wallet->usd_balance,2) }}

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
            <div class="mb-2">
                {{-- <label>Select Currency</label> --}}
              <div class="input-group">
                @if(auth()->user()->wallet->base_currency == "Naira")
                        <span class="input-group-text">
                            &#8358;
                        </span>
                        <input type="text" class="form-control @error('amount') is-invalid @enderror" id="naira" min="2000" name="amount" value="{{ old('amount') }}"  placeholder="Enter amount to convert" required>
                @else
                        <span class="input-group-text">
                        $
                        </span>
                        <input type="text" class="form-control @error('usd') is-invalid @enderror" id="usd" name="usd" min="2" value="{{ old('usd') }}" placeholder="Enter amount to convert" required>
                @endif

                {{-- <select class="form-control" required name="network" id="network">
                    @if(auth()->user()->wallet->base_currency == "Naira")
                        <option value=""> USD </option>
                    @else
                        <option value=""> NGN </option>
                    @endif
                </select> --}}
              </div>
            </div>
            <div class="mb-2">
                @if(auth()->user()->wallet->base_currency == "Naira")
                    1NGN = {{  naira_dollar() }} USD
                @else
                    1USD = {{  dollar_naira() }} NGN
                @endif
                {{-- Rate: 1NGN = 0.0013 --}}
            </div>
            {{-- <div class="mb-4">
              <div class="input-group">
                <select class="form-control" required name="code" id="bundle">
                  <option value="">Select Bundle</option>
                </select>
              </div>
            </div> --}}
            <div class="mb-4">
              <div class="input-group">
                @if(auth()->user()->wallet->base_currency == "Naira")
                    <span class="input-group-text">
                        $
                    </span>
                    <input type="number" class="form-control @error('usd') is-invalid @enderror" id="usd_amount" name="usd" value="{{ old('usd') }}"  required readonly>
                    <input type="hidden" name="label" value="naira">
               @else
                    <span class="input-group-text">
                        &#8358;
                    </span>
                    <input type="number" class="form-control @error('naira') is-invalid @enderror" id="naira_amount" name="naira" value="{{ old('naira') }}"  required readonly>
                    <input type="hidden" name="label" value="usd">
               @endif
              </div>
            </div>
            <div class="text-center mb-4">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-fw fa-reply opacity-50 me-1"></i> Convert
              </button>

            </div>
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
 {{-- <script src="{{ asset('src/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script> --}}
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <!-- Page JS Code -->
 {{-- <script src="{{ asset('src/assets/js/pages/op_auth_reminder.min.js') }}"></script> --}}

<script>
$(document).ready(function(){
  $('#naira').keyup(function(){
    var naira_amount = this.value;
    // console.log(naira_amount);
      $.ajax({
        url: '{{ url("naira/dollar") }}',
        type: "GET",
        dataType: 'json',
        success: function(result){
          var dollar_amount = result*naira_amount;

         // console.log(dollar_amount);

          document.getElementById("usd_amount").value = dollar_amount;
        }
      });
    });

    $('#usd').keyup(function(){
        var dollar_amount = this.value;
        console.log(dollar_amount);
        $.ajax({
          url: '{{ url("dollar/naira") }}',
          type: "GET",
          dataType: 'json',
          success: function(result){
            var naira_amount = result*dollar_amount;

            console.log(naira_amount);

            document.getElementById("naira_amount").value = naira_amount;
          }
      });
    });

});
    // $('#naira').onkeyup(function(){
      
        // var network = this.value + 'DATA';
        //alert(network)
        // $("#bundle").html('');

        // $.ajax({
        //     url: '{{ url("load/network") }}/' + encodeURI(network),
        //     type: "GET",
        //     data: {
               
        //         _token: '{{csrf_token()}}'
        //     },
        //     dataType: 'json',
        //     success: function(result) {
              
        //       $('#bundle').html('<option value="">Select Bundle</option>');
        //       $.each(result, function(key, value) {
        //             if(network == 'MTNDATA'){
        //               $("#bundle").append('<option value="'+value.code+':'+value.amount+'">' + value.duration + ' @ ' + value.amount+'</option>');
        //             }else{
        //               $("#bundle").append('<option value="'+value.code+':'+value.amount+'">' + value.value + ' for ' +value.duration+ ' @ ' + value.amount+'</option>');
        //             }
                      
        //       });
        //     }
        //   });
        
    // });
 
</script>
@endsection