@extends('layouts.main.master')

@section('content')
<div class="content">
    <h2 class="content-heading">List of Product</h2>
    <div class="row">
        @foreach ($marketPlaceLists as $list)
        <div class="col-md-4">
            <div class="block block-rounded block-link-rotate">
                <div class="block-content pb-8 bg-image" style="background-image: url({{$list->banner}})">
                    <span class="badge bg-success fw-bold p-2 text-uppercase">
                    {{$list->commission}}%
                    </span>
                </div>

                <div class="block-content">
                    <p class="fw-semibold text-dark mb-1">
                    {{$list->name}}
                    </p>
                    <p class="fs-sm fw-medium text-muted">
                      &#8358; {{number_format($list->total_payment)}}
                    </p>
                </div>
                <div class="block-content block-content-full bg-body-light">
                    <div class="row g-0 fs-sm text-center">
                        <div class="col-6">
                          <span class="text-muted fw-semibold">
                            <i class="fa fa-fw fa-eye opacity-50 me-1"></i> {{ $list->views }}
                          </span>
                        </div>
                        <div class="col-6">
                          <span class="text-muted fw-semibold">
                            <i class="fa fa-fw fa-heart opacity-50 me-1"></i> {{ $list->sales()->where('is_complete', true)->count() }}
                          </span>
                        </div>
                        
                    </div>
                    <div class="row">
                      <div class="col-6 mt-4">
                        <a href="{{ url('admin/remove/marketplace/product/'.$list->product_id) }}" class="btn btn-danger btn-sm" onclick="javascript:return confirm('Are you sure you want to delete this product?')">REMOVE</a>
                      </div>
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