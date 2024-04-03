@extends('layouts.main.master')
@section('style')


@endsection

@section('content')

 <!-- Hero -->
 <div class="bg-image" style="background-image: url('{{ asset('src/assets/media/photos/photo11@2x.jpg') }}');">
    <div class="bg-black-75">
      <div class="content content-boxed text-center">
        <div class="py-5">
          <h1 class="fs-2 fw-normal text-white mb-2">
            <i class="fa fa-heart me-1"></i> WellaHealth
          </h1>
          {{-- <h2 class="fs-4 fw-normal text-white-75">Subscribe to WellaHealth Insurance Package</h2> --}}
        </div>
      </div>
    </div>
  </div>
  <!-- END Hero -->

   <!-- Pricing Tables -->
   <div class="content content-boxed overflow-hidden">
   
    <div class="row">
      
      <div class="col-12">
        @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif


            {{-- <div class="alert alert-info">
                show up
            </div> --}}
      </div>

      <!-- Special Offer -->
      <div class="bg-body-light">
        <div class="content content-boxed content-full">
          <div class="py-5">
            <h2 class="mb-2 text-center">
              Make Money with Wellahealth
            </h2>
            <h3 class="fw-light text-muted push text-center">
                {{-- How to make money from selling healthcare plans --}}
            </h3>
          </div>
          <div class="row py-3">
            <div class="col-sm-12 col-md-12 mb-5">
              <div class="my-3">
                <i class="fa fa-2x fa-heart text-danger"></i>
              </div>
              <h4 class="h5 mb-2">
                Healthcare Support
              </h4>
              <p class="mb-0 text-muted">
                This is an amazing opportunity for you and your friends to spend less on drugs, medical check ups and hospital bills, ultimately to help you get reliable, affordable, and fast access to healthcare.
                <br>
                To get started, reach out to your friends and family members to share the advantages of health insurance and the healthcare plans available (see your unique link below).
              </p>
            </div>
            <div class="col-sm-12 col-md-12 mb-5">
              <div class="my-3">
                <i class="fa fa-2x fa-money-bill-wave text-xinspire"></i>
              </div>
              <h4 class="h5 mb-2">
                Make Money
              </h4>
              <p class="mb-0 text-muted">
                Wellahealth care is currently accessible in Nigeria however as a Freebyz user outside Nigeria, you can share your Wellahealth (link below) with your friends in Nigeria so that you’ll earn up to 7% when they pay for the healthcare plan.
              </p>
            </div>
            {{-- <div class="col-sm-6 col-md-4 mb-5">
              <div class="my-3">
                <i class="fa fa-2x fa-puzzle-piece text-xinspire"></i>
              </div>
              <h4 class="h5 mb-2">
                Unlimited Integrations
              </h4>
              <p class="mb-0 text-muted">
                Any current or future integrations with third-party services will be available with your plan for unlimited usage.
              </p>
            </div> --}}
          </div>
        </div>
      </div>
      <!-- END Special Offer -->

      <!-- Call to Action -->
      <div class="content content-boxed text-center">
        <div class="py-5">
          <h2 class="mb-3 text-center">
            Start Earning Now!
          </h2>
          <h3 class="h4 fw-light text-muted push text-center">
            Send the link below to your family and friends
          </h3>

          <div  class="h4 fw-light text-muted push text-center">
            {{-- <input type="text" value="{{ auth()->user()->virtualAccount->account_number }}" class="form-control form-control-alt" id="myInput-2" readonly> --}}
            <center>
              <div class="col-md-8">
                <div class="input-group">
                  <input type="text" value="{{url('agent/'.auth()->user()->referral_code).'/wellahealth'}}" class="form-control form-control-alt" id="myInput" readonly>
                  <button type="button" class="btn btn-alt-secondary" onclick="myFunction()" onmouseout="outFunc()">
                      <i class="fa fa-copy"></i>
                  </button>
                </div>
              </div>
            </center>

          </div>
          
        </div>
          
      </div>
      <!-- END Call to Action -->
     
    </div>
  </div>
  <!-- END Pricing Tables -->

@endsection

@section('script')

<script>
  function myFunction() {
      var copyText = document.getElementById("myInput");
      
      copyText.select();
      copyText.setSelectionRange(0, 99999);
  
      console.log(navigator.clipboard.writeText(copyText.value));
      
      // var tooltip = document.getElementById("myTooltip");
      // tooltip.innerHTML = "Copied: " + copyText.value;
    }

</script>
@endsection

