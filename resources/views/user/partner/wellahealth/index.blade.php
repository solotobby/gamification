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
      {{-- <div class="col-md-6 col-xl-3">
        <div class="block block-rounded block-themed text-center">
          <div class="block-header bg-muted">
            <h3 class="block-title">Freelancer</h3>
          </div>
          <div class="block-content bg-body-light">
            <div class="py-2">
              <p class="h1 fw-bold mb-2">$29</p>
              <p class="h6 text-muted">per month</p>
            </div>
          </div>
          <div class="block-content">
            <div class="py-2">
              <p>
                <strong>3</strong> Projects
              </p>
              <p>
                <strong>1GB</strong> Storage
              </p>
              <p>
                <strong>1</strong> Monthly Backup
              </p>
              <p>
                <strong>10</strong> Clients
              </p>
              <p>
                <strong>Email</strong> Support
              </p>
            </div>
          </div>
          <div class="block-content block-content-full bg-body-light">
            <span class="btn btn-hero btn-secondary disabled px-4">
              <i class="fa fa-check opacity-50 me-1"></i> Active Plan
            </span>
          </div>
        </div>
      </div> --}}
      
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
      @foreach ($subscriptions as $subscription)
            <div class="col-md-6 col-xl-3">
                <!-- Startup Plan -->
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
                <!-- END Startup Plan -->
            </div>

             <!-- Pop Out Default Modal -->
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
                            {{-- <div class="mb-4">
                              <label class="form-label" for="post-files">Reason</small></label>
                                <textarea class="form-control" name="reason" id="js-ckeditor5-classic" required> {{ old('reason') }}</textarea>
                            </div> --}}
                            <div class="mb-4">
                                <label class="form-label" for="post-files">Referral Code of Affiliate(optional)</small></label>
                                  <input class="form-control" name="referral" id="js-ckeditor5-classic" value="{{ old('reason') }}"> 
                              </div>
                           
                            <div class="mb-4">
                                {{-- Monthly, quarterly ... --}}
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
                      {{-- <button type="submit" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Done</button> --}}
                      </div>
                  </div>
                </div>
            </div>
      @endforeach
      

     
    </div>
  </div>
  <!-- END Pricing Tables -->

@endsection

