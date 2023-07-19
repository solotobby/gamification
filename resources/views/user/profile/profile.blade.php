@extends('layouts.main.master')

@section('content')
<!-- Hero -->
<div class="bg-image" style="background-image: url({{ asset('src/assets/media/photos/photo17@2x.jpg') }});">
    <div class="bg-black-75">
      <div class="content content-full">
        <div class="py-5 text-center">
          <a class="img-link" href="{{url('profile')}}">
            <img class="img-avatar img-avatar96 img-avatar-thumb" src="{{ asset('src/assets/media/avatars/avatar10.jpg')}}" alt="">
          </a>
          <h1 class="fw-bold my-2 text-white">{{auth()->user()->name}}</h1>
          <h2 class="h4 fw-bold text-white-75">
            {{auth()->user()->email}}
          </h2>
          <a class="btn btn-secondary" href="#">
            <i class="fa fa-fw fa-arrow-left opacity-50"></i> Back to Profile
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- END Hero -->

@endsection