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
            <i class="fa fa-arrow-up me-1"></i> WellaHealth
          </h1>
          <h2 class="fs-4 fw-normal text-white-75">Subscribe to WellaHealth Insurance Package</h2>
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
              Special Offer
            </h2>
            <h3 class="fw-light text-muted push text-center">
              If you upgrade today you will also get all the following at no extra cost.
            </h3>
          </div>
          <div class="row py-3">
            <div class="col-sm-6 col-md-4 mb-5">
              <div class="my-3">
                <i class="fa fa-2x fa-phone text-xeco"></i>
              </div>
              <h4 class="h5 mb-2">
                Lifetime Support
              </h4>
              <p class="mb-0 text-muted">
                Our high quality and award winning phone support will be available 24/7 and for as long as you are using our service.
              </p>
            </div>
            <div class="col-sm-6 col-md-4 mb-5">
              <div class="my-3">
                <i class="fa fa-2x fa-arrows-alt-v text-danger"></i>
              </div>
              <h4 class="h5 mb-2">
                Unlimited Bandwidth
              </h4>
              <p class="mb-0 text-muted">
                We will offer unlimited bandwidth for all your incoming and outcoming connections. You won’t have to worry about it any more.
              </p>
            </div>
            <div class="col-sm-6 col-md-4 mb-5">
              <div class="my-3">
                <i class="fa fa-2x fa-puzzle-piece text-xinspire"></i>
              </div>
              <h4 class="h5 mb-2">
                Unlimited Integrations
              </h4>
              <p class="mb-0 text-muted">
                Any current or future integrations with third-party services will be available with your plan for unlimited usage.
              </p>
            </div>
          </div>
        </div>
      </div>
      <!-- END Special Offer -->

      <!-- Call to Action -->
      <div class="content content-boxed text-center">
        <div class="py-5">
          <h2 class="mb-3 text-center">
            Why Upgrade?
          </h2>
          <h3 class="h4 fw-light text-muted push text-center">
            Upgrading can help you expand your business and acquire much more customers!
          </h3>

          <div  class="h4 fw-light text-muted push text-center">
            {{-- <input type="text" value="{{ auth()->user()->virtualAccount->account_number }}" class="form-control form-control-alt" id="myInput-2" readonly> --}}
            <div class="input-group">
                <input type="text" value="{{url('agent/'.auth()->user()->referral_code).'/wellahealth'}}" class="form-control form-control-alt" id="myInput" readonly>
                <button type="button" class="btn btn-alt-secondary" onclick="myFunction()" onmouseout="outFunc()">
                    <i class="fa fa-copy"></i>
                </button>
            </div>

          </div>
          
        </div>
        {{-- <span class="col-12 d-inline-block">
            <span class="form-control form-control-alt">{{ auth()->user()->virtualAccount->bank_name }}</span> <input type="text" value="{{ auth()->user()->virtualAccount->account_number }}" class="form-control form-control-alt" id="myInput-2" readonly>
            <button type="button" class="btn btn-alt-secondary" onclick="myFunction2()" onmouseout="outFunc()">
              <i class="fa fa-copy"></i>
            </button>

            {{-- <a class="btn btn-hero btn-primary" href="javascript:void(0)" data-toggle="click-ripple">
              <i class="fa fa-link opacity-50 me-1"></i> Learn How..
            </a> -
          </span> --}}

          
      </div>
      <!-- END Call to Action -->



      {{-- @foreach ($subscriptions as $subscription) --}}
            {{-- <div class="col-md-6 col-xl-3">
               
                <div class="block block-link-pop block-rounded text-center">

                <div class="block-header">
                    <h3 class="block-title">{{$subscription['data']['productType']}} {{ $subscription['is_subscribed'] }}</h3>
                </div>
                <div class="block-content bg-body-light">
                    <div class="py-2">
                    <p class="h1 fw-bold mb-2">&#8358;{{number_format($subscription['data']['price'])}}</p>
                    <p class="h6 text-muted">{{$subscription['data']['planType']}}</p>
                    </div>
                </div>
                <div class="block-content">
                    <div class="py-2">
                        <p>
                            <strong>{{$subscription['data']['numberOfPersons']}}</strong>  {{ ($subscription['data']['numberOfPersons'] > 1) ? 'Persons' : 'Person' }}
                        </p>
                        <hr>
                        <strong>Benefits</strong>
                    @foreach ($subscription['data']['planBenefits'] as $benefit)
                        <p>
                            <i class="fa fa-arrow-right me-1"></i> {{$benefit}}
                        </p>
                    @endforeach
                    </div>
                </div>
                @if($subscription['is_subscribed'] == true)
                    <div class="block-content block-content-full bg-body-light">
                        <span class="btn btn-hero btn-secondary disabled px-4">
                        <i class="fa fa-check opacity-50 me-1"></i> Active Plan
                        </span>
                    </div>
                @else
                    <div class="block-content block-content-full bg-body-light">
                        <button class="btn btn-hero btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modal-default-popout-{{ $subscription['data']['planCode'] }}">
                            <i class="fa fa-arrow-up opacity-50 me-1"></i> Upgrade
                        </button>
                    </div>
                @endif
               
            </div>
               
            </div>

            
             <div class="modal fade" id="modal-default-popout-{{ $subscription['data']['planCode'] }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-popout" aria-hidden="true">
                <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                      <h5 class="modal-title">Enter Other information </h5> 
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>

                      <div class="modal-body pb-1">
                  
                          <form action="{{ route('agent.create.wellahealth') }}" method="POST">
                            @csrf
                           
                            <div class="mb-4">
                                <label class="form-label" for="post-files">Referral Code of Affiliate(optional)</small></label>
                                  <input class="form-control" name="referral" id="js-ckeditor5-classic" value="{{ old('reason') }}"> 
                              </div>
                           
                            <div class="mb-4">
                               
                                    <input type="hidden" name="paymentPlan" value="{{ $subscription['data']['planType'] }}">  
                                    <input type="hidden" name="amount" value="{{ $subscription['data']['price'] }}">
                                    <input type="hidden" name="planCode" value="{{ $subscription['data']['planCode'] }}">
                                    <button class="btn btn-hero btn-primary px-4">
                                        <i class="fa fa-arrow-up opacity-50 me-1"></i> Upgrade
                                    </button>
                            </div>
                          </form>
                          <br>
                      </div>
                      
                      <div class="modal-footer">
                      <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                  </div>
                </div>
            </div> --}}
      {{-- @endforeach --}}
      

     
    </div>
  </div>
  <!-- END Pricing Tables -->

@endsection

