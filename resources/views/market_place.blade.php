@extends('layouts.master')
@section('title', $product->name)

@section('content')

	<!-- basic-breadcrumb start -->
    <div class="basic-breadcrumb-area gray-bg ptb-70">
        <div class="container">
            <div class="basic-breadcrumb text-center">
                <h3 class="">{{$product->name}}</h3>
                <ol class="breadcrumb text-xs">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">{{$product->name}}</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- basic-breadcrumb end -->

    <!-- features-area start -->
    <div id="about" class="features-area pt-90 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="about-me mb-30">
                        <img src="{{ $product->banner }}" alt="">
                    </div>
                </div>
                <div class="col-xl-7 col-lg-7 col-md-7">
                    <div class="about-me-info mt-30 mb-30 pl-30">
                        <h2>{{ $product->name }} </h2>
                        <p>
                            {{ $product->description }}
                        </p>
                        <div class="info-me mb-30 mt-30">
                            <!-- PROGRESS BAR -->
                            <h6 class="progress-title">
                                &#8358;{{number_format($product->total_payment)}} 
                                {{-- <span class="pull-right"><span>80</span>%</span> --}}
                            </h6>
                            <div class="progress">
                                <div class="progress-bar" aria-valuenow="80" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                            </div>
                            <!-- END PROGRESS BAR -->

                            <!-- PROGRESS BAR -->
                            {{-- <h6 class="progress-title">CSS <span class="pull-right"><span>70</span>%</span></h6>
                            <div class="progress">
                                <div class="progress-bar" aria-valuenow="60" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 70%;"></div>
                            </div> --}}
                            <!-- END PROGRESS BAR -->

                            <!-- PROGRESS BAR -->
                            {{-- <h6 class="progress-title">PHP <span class="pull-right"><span>75</span>%</span></h6>
                            <div class="progress">
                                <div class="progress-bar" aria-valuenow="75" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"></div>
                            </div> --}}
                            <!-- END PROGRESS BAR -->
                        </div>

                        {{-- <a href="{{ url('marketplace/payment/'.$user->referral_code.'/'.$product->product_id) }}" class="btn smoth-scroll">Buy Now!</a> --}}

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                            Buy Now
                        </button>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ url('marketplace/proccess/payment') }}">
                                        @csrf
                                        <div class="form-group">
                                          <label for="recipient-name" class="col-form-label">Name:</label>
                                          <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="col-form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                        </div>
                                        <input type="hidden" name="referral_code" value="{{ $user->referral_code }}">
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">  
                                        <input type="hidden" name="amount" value="{{ $product->total_payment }}">  
                                                                           
                                        <button type="submit" class="btn btn-primary">Continue</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                                </div>
                            </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection