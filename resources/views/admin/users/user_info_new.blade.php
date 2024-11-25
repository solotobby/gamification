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
            @if($info->is_verified)
                <i class='fa fa-check text-info me-1'></i>
            @endif
        </h1>
        <p class="text-muted">
          {{-- <i class="fa fa-award text-warning me-1"></i> --}}
          {{$info->email}} &#8226; {{$info->phone}} &#8226; {{$info->wallet->base_currency}}
        </p>
       
      </div>
    </div>
    <div class="block-content bg-body-light text-center">
      <div class="row items-push text-uppercase">
       
        <div class="col-6 col-md-3">
          <div class="fw-semibold text-dark mb-1">Balance</div>
          <a class="link-fx fs-3" href="{{ url('admin/user/transactions/'.$info->id) }}" target="_blank">
            @if($info->wallet->base_currency == 'NGN')
                &#8358;{{number_format($info->wallet->balance,2)}}
            @elseif($info->wallet->base_currency == 'USD')
                ${{number_format($info->wallet->usd_balance,2)}}
            @else
                {{$info->wallet->base_currency}} {{number_format($info->wallet->base_currency_balance,2)}}
            @endif
        
            </a>
        </div>
        <div class="col-6 col-md-3">
          <div class="fw-semibold text-dark mb-1">Campaigns</div>
          <a class="link-fx fs-3" href="{{ url('admin/user/campaigns/'.$info->id) }}" target="_blank"> {{ $info->my_campaigns_count }} </a>
        </div>
        <div class="col-6 col-md-3">
          <div class="fw-semibold text-dark mb-1">Jobs</div>
          <a class="link-fx fs-3" href="{{ url('admin/user/jobs/'.$info->id) }}" target="_blank"> {{ $info->my_jobs_count }} </a>
        </div>
        <div class="col-6 col-md-3">
          <div class="fw-semibold text-dark mb-1">Referral</div>
          <a class="link-fx fs-3" href="{{ url('admin/user/referral/'.$info->id) }}" target="_blank">{{ $info->referees_count }} </a>
        </div>
      </div>
    </div>
  </div>


<div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Further Informations</h3>
    </div>
    <div class="block-content">
      <div class="row">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="col-lg-6">
          <!-- Billing Address -->
          <div class="block block-rounded block-bordered">
            <div class="block-header border-bottom">
              <h3 class="block-title">Freebyz Account</h3>
            </div>
            <div class="block-content">
              <address class="fs-sm">
                &#8226; Naira Verified: {{$info->is_verified == '1' ? 'Verified' : 'Unverified'}} <br>
                &#8226; USD Verified: {{ $info->USD_verified == true ? 'Verified' : 'Unverified'}} <br>
                &#8226; Referral: {{@$referredBy->name}}<br>
                &#8226; Referral Code: {{@$info->referral_code}}<br>
                &#8226; Celebrity Status: {{ @$info->is_blacklisted == true ? 'Yes' : 'No' }}<br>
                &#8226; Country: {{ $info->country }}<br>
                &#8226; Is Blocked: {{ @$info->profile->is_celebrity == true ? 'Yes' : 'No' }}<br>
                &#8226; Phone Number Verified: {{ @$info->profile->phone_verified == true ? 'Yes' : 'No' }}<br>
              </address>
            </div>
           
          </div>
          <!-- END Billing Address -->
        </div>
        <div class="col-lg-6">
          <!-- Shipping Address -->
          <div class="block block-rounded block-bordered">
            <div class="block-header border-bottom">
              <h3 class="block-title">Account Info</h3>
            </div>
            <div class="block-content">
              <div class="fs-5 mb-1">Freebyz Account</div>
              <address class="fs-sm">
                @if($info->virtualAccount)
                Virtual Account Name: {{ @$info->virtualAccount->account_name }} <br>
                Virtual Account Number: {{ @$info->virtualAccount->account_number }}<br>
                @else
                 Virtual Account Number: Not Created<br>
               @endif
              </address>
              <div class="fs-5 mb-1">Bank Account</div>
              <address class="fs-sm">
                Account Name: {{ @$info->accountDetails->name }} <br>
                Bank Name: {{ @$info->accountDetails->bank_name }} <br>
                Account Number:{{ @$info->accountDetails->account_number }}<br>
              </address>
            </div>
          </div>
          <!-- END Shipping Address -->
        </div>
      </div>
    </div>
  </div>


   <!-- Results -->
   <div class="block block-rounded">
    <ul class="nav nav-tabs nav-tabs-block" role="tablist">
     
      <li class="nav-item">
        <button class="nav-link active" id="search-photos-tab" data-bs-toggle="tab" data-bs-target="#search-photos" role="tab" aria-controls="search-photos" aria-selected="false">
          Credit Wallet
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link" id="search-customers-tab" data-bs-toggle="tab" data-bs-target="#search-customers" role="tab" aria-controls="search-customers" aria-selected="false">
          Verification
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link" id="search-projects-tab" data-bs-toggle="tab" data-bs-target="#search-projects" role="tab" aria-controls="search-projects" aria-selected="false">
          Update Bank
        </button>
      </li>
      <li class="nav-item">
          <button class="nav-link" id="search-project-tab-manage" data-bs-toggle="tab" data-bs-target="#search-project-manage" role="tab" aria-controls="search-project-manage" aria-selected="false">
            Update Currency
          </button>
        </li>
     
    </ul>
    <div class="block-content tab-content overflow-hidden">
      
      <!-- Photos -->
      <div class="tab-pane fade show active" id="search-photos" role="tabpanel" aria-labelledby="search-photos-tab">

        <div class="container">
            <h4 class="fw-normal text-muted text-center">
              Manual Wallet TopUp
            </h4>
            <form action="{{ route('admin.wallet.topup') }}" method="POST">
              @csrf
              <div class="form-row align-items-center">
                <div class="col-auto">
                  <div class="input-group mb-4">
                    <div class="input-group-prepend">
                      <div class="input-group-text">*</div>
                    </div>
                    <select name="type" class="form-control" required>
                      <option value="">Select Type</option>
                      <option value="credit">Credit</option>
                      <option value="debit">Debit</option>
                    </select>
                    {{-- <input type="number" class="form-control" name="amount" placeholder="Amount" required> --}}
                  </div>
                  <div class="input-group mb-4">
                    <div class="input-group-prepend">
                      <div class="input-group-text">*</div>
                    </div>
                    <select name="currency" class="form-control" required>
                      <option value="">Select Currency</option>
                      <option value="USD">Dollar</option>
                      <option value="NGN">Naira</option>
                    </select>
                  </div>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">&#8358;/$</div>
                    </div>
                    <input type="text" class="form-control" name="amount" placeholder="Amount" required>
                  </div>
                  <div class=" mb-2">
                  <label>Provide reson</label>
                    <input type="text" class="form-control" name="reason" placeholder="Provide reason" required>
                  </div>
                </div>
                <input type="hidden" name="user_id" value="{{ $info->id }}">
               
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary mb-2">Process...</button>
                </div>
              </div>
            </form>
        </div>


      </div>
      <!-- END Photos -->

      <!-- Customers -->
      <div class="tab-pane fade" id="search-customers" role="tabpanel" aria-labelledby="search-customers-tab">
       
        <div class="container">
            <h3 class="mb-2 text-center">
            Manual Verification
            </h3>
            <h4 class="fw-normal text-muted text-center">
            Please Click the Button below to Upgrade User
            </h4>
            <center>
            @if($info->is_verified == '0')
           
                <a class="btn btn-hero btn-primary mb-4" href="{{url('admin/upgrade/'.$info->id.'/naira')}}" data-toggle="click-ripple">
                  Verify User (Naira) Now!
                </a>
                @else
                <a class="btn btn-hero btn-warning mb-4" href="{{url('admin/upgrade/'.$info->id.'/naira')}}" data-toggle="click-ripple">
                  UnVerify User (Naira) Now!
                </a>
           
            @endif
          </center>

          <center>
            @if(!$info->USD_verified)
            
                <a class="btn btn-hero btn-primary mb-4" href="{{url('admin/upgrade/'.$info->id.'/dollar')}}" data-toggle="click-ripple">
                  Verify User (USD) Now!
                </a>
                @else
                <a class="btn btn-hero btn-warning mb-4" href="{{url('admin/upgrade/'.$info->id.'/dollar')}}" data-toggle="click-ripple">
                  UnVerify User (USD) Now!
                </a>
              
            @endif
          </center>

           
              @if($info->wallet->base_currency == 'NGN')
                <hr>
                <h5 class="fw-normal text-muted text-center">
                  Generate Virtual Account
                  </h5>
                  <center>
                  <a href="{{ url('reactivate/virtual/account/'.$info->id) }}" class="btn btn-hero btn-success mb-4">Activate VA</a>
                  </center>
              @endif
        </div>

      </div>
      <!-- END Customers -->

      <!-- Projects -->
      <div class="tab-pane fade" id="search-projects" role="tabpanel" aria-labelledby="search-projects-tab">
       
     
        <h4 class="fw-normal text-muted text-center">
            Update User Account Information
           </h4>

           Account Number: {{ @$info->accountDetails->account_number }} <br>
           Bank Name: {{ @$info->accountDetails->bank_name }} <br>
           Account Name: {{ @$info->accountDetails->name}} <br><br>

           <form action="{{ route('admin.update.account.details') }}" method="POST">
             @csrf
             <div class="form-row align-items-center">
               <div class="col-auto">
                 <div class="mb-4">
                   <label>Select Bank Name</label>
                   <div class="input-group">
                       
                   <select class="form-control" name="bank_code" required>
                       <option value="">Select Bank</option>
                       @foreach ($bankList as $bank)
                           <option value="{{ $bank['code'] }}"> {{ $bank['name'] }}</option>
                           
                       @endforeach    
                       </select> 
                   </div>
               </div>
               <input type="hidden" name="user_id" value="{{$info->id}}">
               <div class="mb-4">
                   <label>Enter Account Number</label>
                 <div class="input-group">
                   <span class="input-group-text">
                     
                   </span>
                   {{-- <input type="text" class="form-control text-center" id="example-group1-input3" name="example-group1-input3" placeholder="00"> --}}
                   <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="reminder-credential" name="account_number" placeholder="Enter Account Number" required value="{{ old('account_number') }}">
                   {{-- <span class="input-group-text">,00</span> --}}
                 </div>
               </div>
               
               <div class="text-center mb-4">
                 <button type="submit" class="btn btn-primary">
                   <i class="fa fa-fw fa-save opacity-50 me-1"></i> Save & Continue
                 </button>
               </div>
               </div>
             </div>
           </form>
       
      </div>
      <!-- END Projects -->


      <!-- Manage -->
      <div class="tab-pane fade" id="search-project-manage" role="tabpanel" aria-labelledby="search-project-tab-manage">
         
          <div class="container">

            <h5 class="fw-normal text-muted text-center mt-2">
                Switch User
                </h5>
                Current Base Currency : {{ $info->wallet->base_currency }}
                <br>   <br>
                

                <form action="{{ url('admin/switch/wallet') }}" method="POST">
                  @csrf
                  <input type="hidden" name="user_id" value="{{ $info->id }}">

                  <div class="form-group mb-3">
                    <select name="currency" class="form-control @error('method') is-invalid @enderror" required>
                        <option value="">Select Currency</option>
                        @foreach (currencyList($info->wallet->base_currency,true) as $currency)
                          <option value="{{$currency->code}}">{{$currency->code}} - {{$currency->country}}</option>
                        @endforeach
                    </select>
                  </div>

                    <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-fw fa-share opacity-50"></i>Switch Currency</button>
                 
                </form> 


            

                <hr>
                <h4 class="fw-normal text-muted text-center">
                  Turn a User to Celebrity account. (They will not benefit from referral bonuses and there will bw a 10% discount on verification)
                </h4>

                <form action="{{ route('admin.celebrity') }}" method="POST">
                  @csrf
                  <div class="form-row align-items-center">
                    <div class="col-auto">
                      <div class="input-group mb-4">
                        <div class="input-group-prepend">
                          <div class="input-group-text">*</div>
                        </div>
                        <input type="text" class="form-control" name="referral_code" placeholder="Enter Code" required>
                      </div>
                      <input type="hidden" name="user_id" value="{{ $info->id }}">
           
                      <div class="col-auto">
                        <button type="submit" class="btn btn-primary mb-2">Update</button>
                      </div>
                    </div>
                  </div>
                </form>
                <hr>



              <h5 class="fw-normal text-muted text-center mt-2">
                Dead-end for this User!!!!
                </h5>
                @if($info->is_blacklisted == '0')
                <center>
                <a class="btn btn-hero btn-secondary mb-3" href="{{url('admin/blacklist/'.$info->id)}}" data-toggle="click-ripple">
                  Blacklist User
                </a>
                </center>
                @else
                <center>
                <a class="btn btn-hero btn-danger mb-3" href="#" data-toggle="click-ripple">
                  User Blaklisted!!
                </a>
                </center>
                @endif
  
               


          </div>
         
        <!-- END Projects -->
    </div>
  </div>
  <!-- END Results -->
  


</div>
@endsection