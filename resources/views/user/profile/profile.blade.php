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
          <h1 class="fw-bold my-2 text-white">{{auth()->user()->name}}  <i class="fa fa-star" aria-hidden="true" style="color: {{$badge['color']}}"></i> </h1>
         
          {{-- <h2 class="h4 fw-bold text-white-75">
            {{auth()->user()->email}}
          </h2> --}}
          <h2 class="h4 fw-bold text-white-75">
            {{$badge['badge']}}
            {{-- @ {{auth()->user()->referral_code}} --}}
          </h2>
          
          
          @if(auth()->user()->wallet->base_currency == 'NGN')
              @if(auth()->user()->is_verified == true)
                <a class="btn btn-alt-info btn-sm" href="#">
                  <i class="fa fa-fw fa-check opacity-50"></i> Verified
                </a>
              @else
              <a class="btn btn-alt-danger btn-sm" href="{{ url('upgrade') }}">
                <i class="fa fa-fw fa-times opacity-50"></i> Unverified
              </a>
              @endif

          @elseif(auth()->user()->wallet->base_currency == 'USD')

                @if(auth()->user()->USD_verified)
                <a class="btn btn-alt-info btn-sm" href="#">
                  <i class="fa fa-fw fa-check opacity-50"></i> Verified
                </a>
              @else
              <a class="btn btn-alt-danger btn-sm" href="{{ url('upgrade') }}">
                <i class="fa fa-fw fa-times opacity-50"></i> Unverified
              </a>
              
              @endif

          @else

            @if(auth()->user()->USD_verified)
                <a class="btn btn-alt-info btn-sm" href="#">
                  <i class="fa fa-fw fa-check opacity-50"></i> Verified
                </a>
              @else
              <a class="btn btn-alt-danger btn-sm" href="{{ url('upgrade') }}">
                <i class="fa fa-fw fa-times opacity-50"></i> Unverified
              </a>
              
              @endif

          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content content-full content-boxed">
    <div class="block block-rounded">
      <div class="block-content">
        {{-- <form action="#" method="POST" enctype="multipart/form-data" onsubmit="return false;"> --}}
          <!-- User Profile -->
          <h2 class="content-heading pt-0">
            <i class="fa fa-fw fa-user-circle text-muted me-1"></i> User Profile 
          </h2>
          <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Your account’s vital info. 
              </p>
            </div>
            <div class="col-lg-8 col-xl-5">
              @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
               @endif

               @if (session('info'))
                <div class="alert alert-info" role="alert">
                    {{ session('info') }}
                </div>
               @endif

              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-username">Email Address</label>
                <p>{{ auth()->user()->email }}</p>
              </div>
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-username">Referral Code</label>
                <p> @ {{auth()->user()->referral_code}}</p>
              </div>
             
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-username">Gender</label>
                <p>{{ auth()->user()->gender }}</p>
                
              </div>
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-name">Age Bracket</label>
                <p>{{ auth()->user()->age_range }}</p>
                
              </div>
              {{-- <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-job-title">Phone Number</label>
                <p>  {{ @auth()->user()->phone }} </p>
                
              </div>
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-job-title">Country</label>
                <p>  {{ @auth()->user()->country }} </p>
               
              </div> --}}
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-email">Base Currency</label>
                <p> {{ auth()->user()->wallet->base_currency }}</p>
              
              </div>
              
              {{--<div class="mb-4">
                <label class="form-label" for="dm-profile-edit-company">Company</label>
                <input type="text" class="form-control" id="dm-profile-edit-company" name="dm-profile-edit-company" value="@ProXdesign" readonly>
              </div>
              <div class="mb-4">
                <label class="form-label">Your Avatar</label>
                <div class="push">
                  <img class="img-avatar" src="assets/media/avatars/avatar10.jpg" alt="">
                </div>
                <label class="form-label" for="dm-profile-edit-avatar">Choose a new avatar</label>
                <input class="form-control" type="file" id="dm-profile-edit-avatar">
              </div> --}}
            </div>
          </div>
          <!-- END User Profile -->

           <!-- Change Password -->
           {{-- <h2 class="content-heading pt-0">
            <i class="fa fa-fw fa-briefcase text-muted me-1"></i> Account Information
          </h2>
          <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Make a transfer to this account to fund your wallet
              </p>
            </div>
            <div class="col-lg-8 col-xl-5">
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-password">Account Name</label>
                <p>{{ @auth()->user()->virtualAccount->account_name }}</p>
                <input type="password" class="form-control" id="d /m-profile-edit-password" name="dm-profile-edit-password">
              </div>
              <div class="row mb-4">
                <div class="col-12">
                  <label class="form-label" for="dm-profile-edit-password-new">Account Number</label>
                  <p>{{ @auth()->user()->virtualAccount->account_number }}</p>
                  <input type="password" class="form-control" id="dm-profile-edit-password-new" name="dm-profile-edit-password-new">
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-12">
                  <label class="form-label" for="dm-profile-edit-password-new-confirm">Bank Name</label>
                  <p>{{ @auth()->user()->virtualAccount->bank_name }}</p>
                  <input type="password" class="form-control" id="dm-profile-edit-password-new-confirm" name="dm-profile-edit-password-new-confirm">
                </div>
              </div>
            </div>
          </div>  --}}
          <!-- END Change Password -->

          <!-- Change Password -->
          @if(!$bankInfo)
              <h2 class="content-heading pt-0">
                <i class="fa fa-fw fa-asterisk text-muted me-1"></i>Add Bank Account
              </h2>
              <div class="row push">
                <div class="col-lg-4">
                  <p class="text-muted">
                    Add the your bank details
                  </p>
                </div>
                <div class="col-lg-8 col-xl-5">
              
                  <form action="{{ url('save/bank/information') }}" method="POST">
                    @csrf
                        <div class="mb-4">
                          <label class="form-label" for="dm-profile-edit-password">Select Bank</label>
                          <select class="form-control" name="bank_code" required>
                            <option value="">Select Bank</option>
                              @foreach ($bankList as $bank)
                                <option value="{{ $bank['code'] }}"> {{ $bank['name'] }}</option> 
                              @endforeach  
                            </select> 
                        </div>
                        <div class="row mb-4">
                          <div class="col-12">
                            <label class="form-label" for="dm-profile-edit-password-new">Enter Account Number</label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror"  name="account_number" placeholder="Enter Account Number" value="{{ old('account_number') }}">
                          </div>
                        </div>
                        <div class="row mb-4">
                          <div class="col-lg-12">
                              <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-save opacity-50 me-1"></i> Save Bank Details
                              </button>
                          </div>
                        </div> 
                  </form>

                
                </div>
              </div>
          @else
              <h2 class="content-heading pt-0">
                <i class="fa fa-fw fa-asterisk text-muted me-1"></i> Bank Details
              </h2>
              <div class="row push">
                <div class="col-lg-4">
                  <p class="text-muted">
                  Your bank details
                  </p>
                </div>
                <div class="col-lg-8 col-xl-5">
              
                  <div class="mb-4">
                    <label class="form-label" for="dm-profile-edit-username">Bank Name</label>
                    <p>{{ $bankInfo->bank_name }}</p>
                  </div>
                  <div class="mb-4">
                    <label class="form-label" for="dm-profile-edit-username">Account Name</label>
                    <p>{{ $bankInfo->name }}</p>
                  </div>
                  <div class="mb-4">
                    <label class="form-label" for="dm-profile-edit-username">Account Number</label>
                    <p>{{ $bankInfo->account_number }}</p>
                  </div>

                
                </div>
              </div>
            @endif 
           {{-- <h2 class="content-heading pt-0">
            <i class="fa fa-fw fa-asterisk text-muted me-1"></i> Change Password
          </h2>
          <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Changing your sign in password is an easy way to keep your account secure.
              </p>
            </div>
            <div class="col-lg-8 col-xl-5">
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-password">Current Password</label>
                <input type="password" class="form-control" id="dm-profile-edit-password" name="dm-profile-edit-password">
              </div>
              <div class="row mb-4">
                <div class="col-12">
                  <label class="form-label" for="dm-profile-edit-password-new">New Password</label>
                  <input type="password" class="form-control" id="dm-profile-edit-password-new" name="dm-profile-edit-password-new">
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-12">
                  <label class="form-label" for="dm-profile-edit-password-new-confirm">Confirm New Password</label>
                  <input type="password" class="form-control" id="dm-profile-edit-password-new-confirm" name="dm-profile-edit-password-new-confirm">
                </div>
              </div>
            </div>
          </div>  --}}
          <!-- END Change Password -->

          <!-- Connections -->
          {{-- <h2 class="content-heading pt-0">
            <i class="fa fa-fw fa-share-alt text-muted me-1"></i> Preferences
          </h2> --}}
          <h2 class="content-heading pt-0">
            <i class="fa fa-fw fa-cog text-muted me-1"></i> Preferences
          </h2>
          <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Your list of interests...
              </p>
            </div>
            <div class="col-lg-8 col-xl-7">
              <div class="row mb-4">
                @foreach (auth()->user()->interests as $interest)
                  <div class="col-sm-12 col-md-4 col-xl-6 mt-1 d-md-flex align-items-md-center fs-sm mb-2">
                    <a class="btn btn-sm btn-alt-secondary rounded-pill" href="javascript:void(0)">
                      <i class="fa fa-fw fa-dharmachakra opacity-50 me-1"></i> 
                      {{$interest->name}}
                    </a>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <!-- END Connections -->

          <!-- Billing Information -->
          {{-- <h2 class="content-heading pt-0">
            <i class="fab fa-fw fa-paypal text-muted me-1"></i> Billing Information
          </h2>
          <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Your billing information is never shown to other users and only used for creating your invoices.
              </p>
            </div>
            <div class="col-lg-8 col-xl-5">
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-company-name">Company Name (Optional)</label>
                <input type="text" class="form-control" id="dm-profile-edit-company-name" name="dm-profile-edit-company-name">
              </div>
              <div class="row mb-4">
                <div class="col-6">
                  <label class="form-label" for="dm-profile-edit-firstname">Firstname</label>
                  <input type="text" class="form-control" id="dm-profile-edit-firstname" name="dm-profile-edit-firstname">
                </div>
                <div class="col-6">
                  <label class="form-label" for="dm-profile-edit-lastname">Lastname</label>
                  <input type="text" class="form-control" id="dm-profile-edit-lastname" name="dm-profile-edit-lastname">
                </div>
              </div>
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-street-1">Street Address 1</label>
                <input type="text" class="form-control" id="dm-profile-edit-street-1" name="dm-profile-edit-street-1">
              </div>
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-street-2">Street Address 2</label>
                <input type="text" class="form-control" id="dm-profile-edit-street-2" name="dm-profile-edit-street-2">
              </div>
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-city">City</label>
                <input type="text" class="form-control" id="dm-profile-edit-city" name="dm-profile-edit-city">
              </div>
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-postal">Postal code</label>
                <input type="text" class="form-control" id="dm-profile-edit-postal" name="dm-profile-edit-postal">
              </div>
              <div class="mb-4">
                <label class="form-label" for="dm-profile-edit-vat">VAT Number</label>
                <input type="text" class="form-control" id="dm-profile-edit-vat" name="dm-profile-edit-vat" value="EA00000000" disabled>
              </div>
            </div>
          </div> --}}
          <!-- END Billing Information -->

          <!-- Submit -->
          {{-- <div class="row push">
            <div class="col-lg-8 col-xl-5 offset-lg-4">
              <div class="mb-4">
                <button type="submit" class="btn btn-alt-primary">
                  <i class="fa fa-check-circle opacity-50 me-1"></i> Update Profile
                </button>
              </div>
            </div>
          </div> --}}
          <!-- END Submit -->
        {{-- </form> --}}
      </div>
    </div>
  </div>
  <!-- END Page Content -->

@endsection