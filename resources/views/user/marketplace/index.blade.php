@extends('layouts.main.master')

@section('content')
<div class="content">
    <h2 class="content-heading">List of Products</h2>
    <div class="row">
      <div class="col-12">
        <div class="alert alert-info">
          Earn up to 70% commission on every Product you sell. Copy the Link and Advertise or Make a post with the Link to get started. Your wallet will be automatically credited once you make sales. Its that simple!
        </div>
      </div>
    </div>
    <div class="row">
        @foreach ($marketPlaceLists as $list)

        <div class="col-md-6 animated fadeIn mb-5">
          <div class="options-container fx-item-zoom-in fx-overlay-zoom-out">
            <img class="img-fluid options-item" src="{{$list->banner}}" alt="">
            <div class="options-overlay bg-black-75">
              <div class="options-overlay-content">
                <h3 class="h4 text-white mb-1"> {{$list->name}}</h3>
                <h4 class="h6 text-white-75 mb-3">Amount: &#8358;{{number_format($list->total_payment)}}<br> 
                  <span> Commission Per. Sale: &#8358;{{ number_format($list->commission_payment) }}</span></h4>
                {{-- <a class="btn btn-sm btn-primary img-lightbox" href="{{$list->banner}}">
                  <i class="fa fa-search-plus opacity-50 me-1"></i> View
                </a> --}}
                {{-- <a class="btn btn-sm btn-secondary" href="javascript:void(0)">
                  <i class="fa fa-pencil-alt opacity-50 me-1"></i> Edit
                </a> --}}
              </div>
            </div>
           
          </div>
          <div class="block-content block-content-full bg-body-light">
                     
            <div class="row g-0 fs-sm">
                <div class="col-12">
                  <strong> {{$list->name}} </strong> <br>
                  Amount: &#8358;{{number_format($list->total_payment)}}<br>
                  Commission Per. Sale: &#8358;{{ number_format($list->commission_payment) }}
                  <hr>
                  <a href="{{ url('marketplace/'.auth()->user()->referral_code.'/'.$list->product_id) }}" target="_blank">{{ url('marketplace/'.auth()->user()->referral_code.'/'.$list->product_id) }}</a>
                    {{-- <a href="{{ url('marketplace/'.auth()->user()->referral_code.'/'.$list->product_id) }}" target="_blank">{{ url('marketplace/'.auth()->user()->referral_code.'/'.$list->product_id) }}</a> --}}
                </div>
  
            </div>
          </div>
  
        </div>


        
      @endforeach
    </div>
  </div>
@endsection
@section('script')
 <!-- Page JS Plugins -->
 {{-- <script src="{{asset('src/assets/js/plugins/magnific-popup/jquery.magnific-popup.min.js')}}"></script> --}}
 <!-- Page JS Helpers (Magnific Popup Plugin) -->
 {{-- <script>Dashmix.helpersOnLoad(['jq-magnific-popup']);</script> --}}


{{-- <script src="{{asset('src/assets/js/dashmix.app.min.js')}}"></script> --}}

<!-- jQuery (required for Magnific Popup plugin) -->
{{-- <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script> --}}

<!-- Page JS Plugins -->
{{-- <script src="{{asset('src/assets/js/plugins/magnific-popup/jquery.magnific-popup.min.js')}}"></script> --}}

<!-- Page JS Helpers (Magnific Popup Plugin) -->
{{-- <script>Dashmix.helpersOnLoad(['jq-magnific-popup']);</script> --}}
@endsection