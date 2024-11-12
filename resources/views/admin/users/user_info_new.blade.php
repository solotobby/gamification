@extends('layouts.main.master')
@section('content')
<div class="content">
<div class="block block-rounded">
    <div class="block-content text-center">
      <div class="py-4">
        <div class="mb-3">
          <img class="img-avatar img-avatar96" src="{{asset('src/assets/media/avatars/avatar15.jpg')}}" alt="">
        </div>
        <h1 class="fs-lg mb-0">
            {{$info->name}}
            <i class="fa fa-check text-info me-1"></i>
        </h1>
        <p class="text-muted">
          {{-- <i class="fa fa-award text-warning me-1"></i> --}}
          {{$info->email}} &#8226; {{$info->phone}} &#8226; {{$info->wallet->base_currency}}
        </p>
        <h1 class=" mb-0">{{$info->wallet->balance}}</h1>
      </div>
    </div>
    <div class="block-content bg-body-light text-center">
      <div class="row items-push text-uppercase">
        <div class="col-6 col-md-3">
          <div class="fw-semibold text-dark mb-1">Transactions</div>
          <a class="link-fx fs-3" href="javascript:void(0)">5</a>
        </div>
        <div class="col-6 col-md-3">
          <div class="fw-semibold text-dark mb-1">Campaigns</div>
          <a class="link-fx fs-3" href="javascript:void(0)">$5.680,00</a>
        </div>
        <div class="col-6 col-md-3">
          <div class="fw-semibold text-dark mb-1">Jobs</div>
          <a class="link-fx fs-3" href="javascript:void(0)">4</a>
        </div>
        <div class="col-6 col-md-3">
          <div class="fw-semibold text-dark mb-1">Referral</div>
          <a class="link-fx fs-3" href="javascript:void(0)">3</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection