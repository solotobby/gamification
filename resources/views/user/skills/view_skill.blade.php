@extends('layouts.main.master')

@section('style')

@endsection

@section('content')

 <!-- Hero Section -->
 <div class="bg-image" style="background-image: url('{{asset('src/assets/media/photos/photo12@2x.jpg')}}' );">
    <div class="bg-black-75">
      <div class="content content-boxed content-full py-5">
        <div class="row">
          <div class="col-md-8 d-flex align-items-center py-3">
            <div class="w-100 text-center text-md-start">
              <h1 class="h2 text-white mb-2">
                {{$skill->user->name}}
              </h1>
              <h2 class="h4 fs-sm text-uppercase fw-semibold text-white-75">
                {{$skill->skill->name}}
              </h2>
              <a class="fw-semibold" href="#">
                <i class="fab fa-fw fa-leanpub text-white-50"></i> {{$skill->year_experience}} years
              </a>
            </div>
          </div>
          <div class="col-md-4 d-flex align-items-center">
            @if(auth()->user()->point > 0)
                <a class="block block-rounded block-link-shadow block-transparent bg-black-50 text-center mb-0 mx-auto" href="">
            @else
                <a class="block block-rounded block-link-shadow block-transparent bg-black-50 text-center mb-0 mx-auto" href="">
            @endif
              <div class="block-content block-content-full px-5 py-4">
                <div class="fs-2 fw-semibold text-white">
                1 <span class="text-white-50"></span>
                  {{-- @if($campaign['currency'] == 'NGN')
                    &#8358;{{$campaign['local_converted_amount']}}<span class="text-white-50"></span>
                    @else
                    {{$campaign['local_converted_currency']}} {{$campaign['local_converted_amount']}}<span class="text-white-50"></span>
                  @endif --}}
                </div>
                <div class="fs-sm fw-semibold text-uppercase text-white-50 mt-1 push">Point Required </div>
                @if(auth()->user()->point > 0)
                <span class="btn btn-hero btn-primary">
                  <i class="fa fa-arrow-right opacity-50 me-1"></i> View Professional's Information 
                </span>
                @else
                <span class="btn btn-hero btn-primary">
                    <i class="fa fa-arrow-right opacity-50 me-1"></i> Get Point 
                  </span>
                @endif
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Hero Section -->

  <div class="content content-boxed">
    <div class="alert alert-warning">
     <strong> Note: You need 1 point to view this professional information which is equivalent to {{ baseCurrency() }} {{ currencyConverter('NGN', baseCurrency(), 500); }} </strong>
    </div>
    <div class="col-md-12 order-md-0">
        
        @if (session('success'))
          <div class="alert alert-success" role="alert">
              {{ session('success') }}
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
        <!-- Job Description -->
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Description</h3>
          </div>
            <div class="block-content ">
              
                {!! $skill->description !!}
                <br><br>
            </div>


        </div>


        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Portfolio</h3>
            </div> 

            <div class="block-content">
                <div class="row">
                    <div class="col-md-12">
                        {{-- <div class="alert alert-info">
                            You cannot do this campaign because you created it.
                        </div> --}}

                        <div class="row items-push">
                            @foreach($portfolio as $port)
                                <div class="col-lg-3">
                                <p class="text">
                                    {{$port->title}}
                                </p>
                                </div>

                                <div class="col-lg-9">
                                {{$port->description}}
                                </div>
                            @endforeach

                            @if($checkExist)
                              <div class="col-lg-3">Professional Basic Information</div>
                              <div class="col-lg-9">
                                Email Address:  {{ $skill->user->email }} <br>
                                Phone Number:  {{ $skill->user->phone }} <br>
                              </div>
                            @else

                              <div class="col-lg-3"></div>
                              <div class="col-lg-9">
                                
                                  <div class="mb-4">
                                      <a href="{{ url('view/point/'.$port->id) }}" class="btn btn-alt-primary">
                                          <i class="fa fa-arrow-right opacity-50 me-1"></i> View Professional Information
                                      </a>
                                </div>
                              </div>
                            @endif
                           
                        </div>
                </div>
            </div>


        </div>

     
  </div>
   



  @endsection