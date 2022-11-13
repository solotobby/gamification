@extends('layouts.main.master')

@section('content')
<div class="content">
    <h2 class="content-heading">List of Products</h2>
    <div class="row">
        @foreach ($marketPlaceLists as $list)
        <div class="col-md-6">
            <div class="block block-rounded block-link-rotate">
                <div class="block-content pb-8 bg-image" style="background-image: url({{$list->banner}});">
                    {{-- <span class="badge bg-success fw-bold p-2 text-uppercase">
                    Comm. per Sale %{{$list->commission}}
                    </span> --}}
                </div>
                <div class="block-content">
                    <p class="fw-semibold text-dark mb-1">
                    {{$list->name}}
                    </p>
                    <p class="fs-sm fw-medium text-muted">
                        Amount: &#8358;{{number_format($list->total_payment)}}<br> 
                        <span> Commission Per. Sale: &#8358;{{ number_format($list->commission_payment) }}</span>
                    </p>
                </div>
                <div class="block-content block-content-full bg-body-light">
                   
                    <div class="row g-0 fs-sm">
                        <div class="col-12">
                            <a href="{{ url('marketplace/'.auth()->user()->referral_code.'/'.$list->product_id) }}" target="_blank">{{ url('marketplace/'.auth()->user()->referral_code.'/'.$list->product_id) }}</a>
                        </div>
                        <div class="col-6">
                            {{-- <a href="" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"> View </i>
                            </a> --}}
                          {{-- <span class="text-muted fw-semibold">
                            <i class="fa fa-fw fa-eye opacity-50 me-1"></i> 600
                          </span> --}}
                        </div>
                        {{-- <div class="col-6">
                            <a href="" class="btn btn-danger btn-sm">
                                <i class="fa fa-edit"> View </i>
                            </a>
                          {{-- <span class="text-muted fw-semibold">
                            <i class="fa fa-fw fa-heart opacity-50 me-1"></i> 87
                          </span> --
                        </div> --}}
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
 <script src="{{asset('src/assets/js/plugins/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
 <!-- Page JS Helpers (Magnific Popup Plugin) -->
 <script>Dashmix.helpersOnLoad(['jq-magnific-popup']);</script>
@endsection