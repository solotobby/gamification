@extends('layouts.main.general')
@section('style')


@endsection

@section('content')
<div class="bg-image" style="background-image: url('{{ asset('src/assets/media/photos/photo11@2x.jpg') }}');">
    <div class="bg-black-75">
      <div class="content content-boxed text-center">
        <div class="py-5">
          <h1 class="fs-2 fw-normal text-white mb-2">
            <i class="fa fa-arrow-up me-1"></i> WellaHealth
          </h1>
          <h2 class="fs-4 fw-normal text-white-75">Subscribe to WellaHealth Insurance Package</h2>
        </div>
      </div>
    </div>
</div>

<div class="content">
   
    <!-- Info -->
    <div class="block block-rounded">
      
      <div class="block-content">
        <div class="row justify-content-center">
            
          <div class="col-md-10 col-lg-8">
                
            <div class="block block-rounded invisible" data-toggle="appear">
                <div class="block-content block-content-full">
                  <div class="py-5 text-center">
                    <div class="item item-2x item-circle bg-success text-white mx-auto">
                      <i class="fa fa-2x fa-check"></i>
                    </div>
                    <div class="fs-4 fw-semibold pt-3 mb-0">Payment Successful</div>
                    <br>
                    Your subscription to wellahealth is completed. To access this produt, contact this phone number 
                    <br> 
                    <a href="{{ url('/') }}" class="btn btn-primary btn-sm"> <li class="fa fa-home"> </li> Home </a>
                  </div>
                </div>
              </div>


          </div>

        </div>
      </div>
    </div>
    <!-- END Info -->
  </div>



@endsection

@section('script')
 <!-- jQuery (required for jQuery Appear plugin) -->
 <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>

 <!-- Page JS Plugins -->
 <script src="{{asset('src/assets/js/plugins/jquery-appear/jquery.appear.min.js')}}"></script>

 <!-- Page JS Helpers (jQuery Appear plugin) -->
 <script>Dashmix.helpersOnLoad(['jq-appear']);</script>
@endsection