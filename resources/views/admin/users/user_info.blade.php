@extends('layouts.main.master')
@section('style')
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('src/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css')}}">
@endsection
@section('content')

<div class="content">
    <!-- Search -->
    <div class="p-3 bg-body-extra-light rounded push">
      <form action="be_pages_generic_search.html" method="POST">
        <div class="input-group input-group-lg">
          Name: {{$info->name}} <br>
          Referred By: {{@$referredBy->name}} <br>
          Referral Code: {{@$info->referral_code}} <br>
          Email: {{$info->email}} <br>
          Naira Balance: &#8358;{{number_format(@$info->wallet->balance,2)}} <br>
          USD Balance: ${{number_format(@$info->wallet->usd_balance,3)}} <br>
          Naira Verified: {{$info->is_verified == '1' ? 'Verified' : 'Unverified'}}<br>
          USD Verified: {{ $info->USD_verified == true ? 'Verified' : 'Unverified'}} <br>
          Phone Number: {{ $info->phone }}<br>
          Country: {{ $info->country }}<br>
          Base Currency: {{ $info->wallet->base_currency }} <br>
          GoogleID: {{ @$info->google_id }}<br>
          Account Name: {{ @$info->accountDetails->name }} <br>
          Bank Name: {{ @$info->accountDetails->bank_name }} <br>
          Account Number:{{ @$info->accountDetails->account_number }}<br>
          Blocked: {{ @$info->is_blacklisted == true ? 'Yes' : 'No' }}<br>
          Celebrity Status: {{ @$info->profile->is_celebrity == true ? 'Yes' : 'No' }}<br>
          Phone Number Verified: {{ @$info->profile->phone_verified == true ? 'Yes' : 'No' }}<br>
         @if($info->virtualAccount)
         Virtual Account Name: {{ @$info->virtualAccount->account_name }} <br>
         Virtual Account Number: {{ @$info->virtualAccount->account_number }}<br>
         @else
          Virtual Account Number: Not Created<br>
        @endif
          Date Joined: {{ @$info->created_at }}<br>

        </div>
      </form>
    </div>
    <!-- END Search -->

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Results -->
    <div class="block block-rounded">
      <ul class="nav nav-tabs nav-tabs-block" role="tablist">
        {{-- <li class="nav-item">
          <button class="nav-link active" id="search-classic-tab" data-bs-toggle="tab" data-bs-target="#search-classic" role="tab" aria-controls="search-classic" aria-selected="true">
            Referees({{$info->referees->count()}})
          </button>
        </li> --}}
        <li class="nav-item">
          <button class="nav-link active" id="search-photos-tab" data-bs-toggle="tab" data-bs-target="#search-photos" role="tab" aria-controls="search-photos" aria-selected="false">
            Transactions({{ $info->transactions()->where('status', 'successful')->count() }})
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" id="search-customers-tab" data-bs-toggle="tab" data-bs-target="#search-customers" role="tab" aria-controls="search-customers" aria-selected="false">
            Jobs({{ $info->myJobs->count() }})
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" id="search-projects-tab" data-bs-toggle="tab" data-bs-target="#search-projects" role="tab" aria-controls="search-projects" aria-selected="false">
            Campaigns ({{ $info->myCampaigns->count() }})
          </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="search-project-tab-manage" data-bs-toggle="tab" data-bs-target="#search-project-manage" role="tab" aria-controls="search-project-manage" aria-selected="false">
              User Management 
            </button>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/user/referral/'.$info->id) }}" target="_blank" class="nav-link" >
             Refferee List ({{$info->referees->count()}})
            </a>
          </li>
      </ul>
      <div class="block-content tab-content overflow-hidden">
        <!-- Classic -->
        {{-- <div class="tab-pane fade show active" id="search-classic" role="tabpanel" aria-labelledby="search-classic-tab">
          <div class="fs-3 fw-semibold pt-2 pb-4 mb-4 text-center border-bottom">
            <span class="text-primary fw-bold">{{$info->referees()->where('is_verified', true)->count()}} - {{ $info->referees()->where('is_verified', true)->count() * 250 }}</span> Verified  
            |
            <span class="text-primary fw-bold">{{$info->referees->count()}}</span> Referee 
            <hr>

            Total Ref: {{ $info->transactions()->where('type', 'referer_bonus')->sum('amount') }}
          </div>
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Wallet</th>
                  <th>Status</th>
                  <th>When</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($info->referees as $inf)
                  <tr>
                      <td>
                        <a href="{{ url('user/'.$inf->id.'/info') }}"> {{$inf->name }}</a>
                      </td>
                      <td>
                         {{ $inf->email }}
                      </td>
                      <td>
                          {{ $inf->phone}}
                      </td>
                      <td>
                        @if($inf->wallet->base_currency == 'Naira')
                        &#8358;{{ number_format($inf->wallet->balance,2) }}
                        @else
                        ${{ number_format($inf->wallet->balance,2) }}
                        @endif
                      </td>
                      <td>
                          {{ $inf->is_verified == '1' ? 'Verified' : 'Unverified' }}
                      </td>
                      <td>
                          {{ $inf->created_at }}
                      </td>
                    </tr>
                  @endforeach
                
              </tbody>
            </table>
          </div>

        </div> --}}
        <!-- END Classic -->

       
        <!-- Photos -->
        <div class="tab-pane fade show active" id="search-photos" role="tabpanel" aria-labelledby="search-photos-tab">
          <div class="fs-3 fw-semibold pt-2 pb-4 mb-4 text-center border-bottom">
            &#8358;<span class="text-primary fw-bold">{{ number_format($info->transactions->where('status', 'successful')->sum('amount')) }}</span> Transaction Value
          </div>
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter">
              <thead>
                <tr>
                  <th>Reference</th>
                  <th>Type</th>
                  <th>Amount</th>
                  <th>Currency</th>
                  <th>Status</th>
                  <th>Description</th>
                  <th>When</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($info->transactions->where('status', 'successful') as $list)
                  
                    @if($list->tx_type == 'Credit')
                    <tr style="color: forestgreen">
                  @else
                    <tr style="color: chocolate">
                  @endif
                      <td>
                        {{ $list->reference }}
                      </td>
                      <td>
                        {{ $list->type }}
                      </td>
                      <td>
                        @if($list->currency == 'NGN')
                          &#8358;{{ number_format($list->amount,2) }}
                          @else
                          ${{ number_format($list->amount,2) }}
                          @endif
                      </td>
                      <td>
                          {{ $list->currency }}
                      </td>
                      <td>
                          {{ $list->status }}
                      </td>
                      <td>
                          {{ $list->description }}
                      </td>
                      <td>
                          {{ $list->created_at }}
                      </td>
                    </tr>
                  @endforeach
                
              </tbody>
            </table>
          </div>


        </div>
        <!-- END Photos -->

        <!-- Customers -->
        <div class="tab-pane fade" id="search-customers" role="tabpanel" aria-labelledby="search-customers-tab">
          <div class="fs-3 fw-semibold pt-2 pb-4 mb-4 text-center border-bottom">
            &#8358;<span class="text-primary fw-bold">{{ $info->myJobs->where('status', 'Approved')->sum('amount') }}</span> Approved Value |  &#8358;<span class="text-primary fw-bold">{{ $info->myJobs->where('status', 'Denied')->sum('amount') }}</span> Denied Value
          </div>
          <div class="table-responsive">
         

            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                <thead>
                  <tr>
                    <th>Campaign Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>When Done</th>
                    {{-- <th></th> --}}
                  </tr>
                </thead>
                <tbody>
                    @foreach ($info->myJobs as $job)
                    <tr>
                        <td>
                          {{ @$job->campaign->post_title }}
                        </td>
                        <td>
                          @if(@$job->campaign->currency == 'NGN')
                            &#8358;{{ number_format($job->amount,2) }}
                            @else
                            ${{ number_format($job->amount,2) }}
                            @endif
                        </td>
                        <td>
                            {{ @$job->status }}
                        </td>
                        
                        <td>
                            {{ @$job->created_at }}
                        </td>
                        
                      </tr>
                    @endforeach
                  
                </tbody>
              </table>
          </div>
        </div>
        <!-- END Customers -->

        <!-- Projects -->
        <div class="tab-pane fade" id="search-projects" role="tabpanel" aria-labelledby="search-projects-tab">
          <div class="fs-3 fw-semibold pt-2 pb-4 mb-4 text-center border-bottom">
            <span class="text-primary fw-bold">  &#8358;{{ number_format($info->myCampaigns->sum('total_amount')) }}</span> Campaign Values
          </div>
       
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                <thead>
                  <tr>
                    <th>Campaign Name</th>
                    <th>Unit Amount</th>
                    <th>No. of Worker</th>
                    <th>Total Value</th>
                    <th>When Done</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($info->myCampaigns as $campaign)
                    <tr>
                        <td>
                          {{ @$campaign->post_title }}
                        </td>
                        <td>
                            &#8358;{{ number_format(@$campaign->campaign_amount,2) }}
                        </td>
                        <td>
                            {{ @$campaign->number_of_staff }}
                        </td>
                        <td>
                            &#8358;{{ number_format(@$campaign->total_amount,2) }}
                        </td>
                        
                        <td>
                            {{ @$campaign->created_at }}
                        </td>
                        <td>
                          <a href="{{ url('campaign/activities/'.$campaign->job_id)  }}" class="btn btn-primary btn-sm">View Activities</a>
                      </td>
                      </tr>
                    @endforeach
                  
                </tbody>
              </table>
          </div>
         
        </div>
        <!-- END Projects -->


        <!-- Manage -->
        <div class="tab-pane fade" id="search-project-manage" role="tabpanel" aria-labelledby="search-project-tab-manage">
            <div class="fs-3 fw-semibold pt-2 pb-4 mb-4 text-center border-bottom">
              <span class="text-primary fw-bold">  &#8358;{{ number_format($info->myCampaigns->sum('total_amount')) }}</span> Campaign Values
            </div>
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
                  </div>
                  <input type="hidden" name="user_id" value="{{ $info->id }}">
                 
                  <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-2">Process...</button>
                  </div>
                </div>
              </form>
              <hr>
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
                                      {{--  <input type="hidden" name="bank_name" value="{{ $bank['name'] }}">  --}}
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


            </div>
            <hr>
            <div class="bg-body-dark mb-5">
                <div class="content content-full text-center">
                 
                  <div class="py-3">
                      <h3 class="mb-2 text-center">
                      Manual Verification
                      </h3>
                      <h4 class="fw-normal text-muted text-center">
                      Please Click the Button below to Upgrade User
                      </h4>
                      @if($info->is_verified == '0')
                          <a class="btn btn-hero btn-primary" href="{{url('admin/upgrade/'.$info->id.'/naira')}}" data-toggle="click-ripple">
                            Verify User (Naira) Now!
                          </a>
                          @else
                          <a class="btn btn-hero btn-primary disabled" href="#" data-toggle="click-ripple">
                            Verification Successfull (Naira)
                          </a>
                      @endif

                      @if(!$info->USD_verified)
                          <a class="btn btn-hero btn-primary" href="{{url('admin/upgrade/'.$info->id.'/dollar')}}" data-toggle="click-ripple">
                            Verify User (USD) Now!
                          </a>
                          @else
                          <a class="btn btn-hero btn-primary disabled" href="#" data-toggle="click-ripple">
                            Verification Successfull (USD)
                          </a>
                      @endif

                     

                      <hr>
                      <h5 class="fw-normal text-muted text-center mt-2">
                        Generate Virtual Account
                        </h5>
                        <a href="{{ url('reactivate/virtual/account/'.$info->id) }}" class="btn btn-success btn-sm">Activate VA</a>

                      <hr>
                      
                      <h5 class="fw-normal text-muted text-center mt-2">
                        Dead-end for this User!!!!
                        </h5>
                        @if($info->is_blacklisted == '0')
                        <a class="btn btn-hero btn-secondary" href="{{url('admin/blacklist/'.$info->id)}}" data-toggle="click-ripple">
                          Blacklist User
                        </a>
                        @else
                        <a class="btn btn-hero btn-danger" href="#" data-toggle="click-ripple">
                          User Blaklisted!!
                        </a>
                        @endif

                  </div>
              </div>
           
          </div>
          <!-- END Projects -->
      </div>
    </div>
    <!-- END Results -->
  </div>
@endsection

@section('script')

<!-- jQuery (required for DataTables plugin) -->
<script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script>

<!-- Page JS Plugins -->
<script src="{{asset('src/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-jszip/jszip.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.print.min.js')}}"></script>
<script src="{{asset('src/assets/js/plugins/datatables-buttons/buttons.html5.min.js')}}"></script>

<!-- Page JS Code -->
<script src="{{asset('src/assets/js/pages/be_tables_datatables.min.js')}}"></script>
@endsection