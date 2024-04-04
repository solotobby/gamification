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
   
    <div class="row items-push">
      <div class="col-6 col-lg-4">
        <a class="block block-rounded block-link-shadow text-center h-100 mb-0" href="javascript:void(0)">
          <div class="block-content py-5">
            <div class="fs-3 fw-semibold mb-1">&#8358;{{ number_format($amount) }}</div>
            <p class="fw-semibold fs-sm text-muted text-uppercase mb-0">
              Price
            </p>
          </div>
        </a>
      </div>
      <div class="col-6 col-lg-4">
        <a class="block block-rounded block-link-shadow text-center h-100 mb-0" href="javascript:void(0)">
          <div class="block-content py-5">
            <div class="fs-3 fw-semibold mb-1">{{ $numberOfPersons }}</div>
            <p class="fw-semibold fs-sm text-muted text-uppercase mb-0">
              Number of Persons
            </p>
          </div>
        </a>
      </div>
      <div class="col-lg-4">
        <a class="block block-rounded block-link-shadow text-center h-100 mb-0" href="javascript:void(0)">
          <div class="block-content py-5">
            <div class="fs-3 fw-semibold mb-1">
             {{ $type }}
            </div>
            <p class="fw-semibold fs-sm text-muted text-uppercase mb-0">
             Subscription Type
            </p>
          </div>
        </a>
      </div>
    </div>
    <!-- END Quick Overview + Actions -->

    <!-- Info -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Kindly Enter Beneficiary information</h3>
      </div>
      <div class="block-content">
        <div class="row justify-content-center">
            
          <div class="col-md-10 col-lg-8">
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
             @endif

             @if (session('success'))
             <div class="alert alert-info" role="alert">
                 {{ session('success') }}
             </div>
          @endif
             
                <div class="mb-4">
                    <label class="form-label" for="dm-ecom-product-id">You were Referred by</label>
                    <input type="text" class="form-control" id="dm-ecom-product-id" name="dm-ecom-product-id" value="{{$referral->name}}" readonly>
                </div>
                <form action="{{ url('agent/store/wellahealth')}}" method="POST">
                  @csrf
                @for($i=1; $i<=$numberOfPersons; $i++)
                    <strong> Enter Beneficiary {{ $i }} </strong>
                    <div class="mb-4">
                        <label class="form-label" for="dm-ecom-product-name">First Name</label>
                        <input type="text" class="form-control" id="dm-ecom-product-name" name="firstName[]" value="" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="dm-ecom-product-name">Last Name</label>
                        <input type="text" class="form-control" id="dm-ecom-product-name" name="lastName[]" value="" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="dm-ecom-product-name">Phone Number</label>
                        <input type="text" class="form-control" id="dm-ecom-product-name" name="phone[]" value="" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="dm-ecom-product-name">Email</label>
                        <input type="email" class="form-control" id="dm-ecom-product-name" name="email[]" value="" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="dm-ecom-product-name">Date of Birth</label>
                        <input type="date" class="form-control" id="dm-ecom-product-name" name="dateOfBirth[]" value="" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" for="dm-ecom-product-category">Gender</label>
                        <select class="js-select2 form-select" id="dm-ecom-product-category" name="gender[]" style="width: 100%;" required>
                        <option value="">Select One</option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        </select>
                    </div>
                    <hr>
                @endfor

                <input type="hidden" name="referral_code" value="{{ $ref }}">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <input type="hidden" name="paymentPlan" value="{{ $type }}">
                <input type="hidden" name="planCode" value="{{ $planCode }}">

                <div class="mb-4">
                    <button type="submit" class="btn btn-alt-primary">Subscribe</button>
                </div>
                </form>
                <hr>
             
          </div>

        </div>
      </div>
    </div>
    <!-- END Info -->
  </div>



@endsection