@extends('layouts.main.master')


@section('content')

<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
      <div>
        <h1 class="h3 mb-1">
          Admin Dashboard
        </h1>
        <p class="fw-medium mb-0 text-muted">
             Wallet Balance - <a class="fw-medium" href="javascript:void(0)">&#8358;{{number_format($wallet[0]->total_balance,2)}}</a>
            <br>Withdrawable Balance - <a class="fw-medium" href="javascript:void(0)">&#8358;{{number_format($wallet[0]->balance_gt_200,2)}} </a>
            <br> Dollar Wallet - <a class="fw-medium" href="javascript:void(0)">${{number_format($wallet[0]->total_usd_balance,2)}}</a>
            {{-- <br> Total Payout - <a class="fw-medium" href="javascript:void(0)">&#8358;{{ number_format($totalPayout,2) }}</a>   --}}
           
            <br> Total Transaction - <a class="fw-medium" href="javascript:void(0)"> &#8358;{{number_format($transactions[0]->total_successful_transactions,2)}}</a>
            <br> Jobs Available - <a class="fw-medium" href="javascript:void(0)">{{ $av_count }}</a>
            {{-- <br> This Week Payment - <a class="fw-medium" href="javascript:void(0)"> &#8358;{{number_format($weekPayment,2)}}</a>
             --}}
             {{-- <br> Active Virtual Account -<a class="fw-medium" href="javascript:void(0)"> {{ totalVirtualAccount() }} </a> --}}
             
              @if(env('APP_ENV') == 'production')
                  <br> Location - <a class="fw-medium" href="javascript:void(0)">{{ currentLocation() }}</a>
              @endif 
            
        </p>
      </div>
      {{--Wallet Balance - &#8358;{{ number_format($wallet) }}  <span id="monthly"></span>--}}
      <div class="mt-4 mt-md-0">
        <a class="btn btn-sm btn-alt-primary" href="javascript:void(0)">
          <i class="fa fa-cog"></i>
        </a>
        <div class="dropdown d-inline-block">
          <button type="button" class="btn btn-sm btn-alt-primary px-3" id="dropdown-analytics-overview" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span id="selected-option">Last 30 days</span> <i class="fa fa-fw fa-angle-down"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-analytics-overview">
            <a class="dropdown-item" id="7" >Last 7 days</a>
            <a class="dropdown-item" id="14" >Last 14 days</a>
          </div>
        </div>
      </div>
      
      
    </div>
  </div>
  
  <div class="content">

    <form action="{{ url('users') }}" method="GET">
          <div class="mb-4">
              <label>Select Date Range</label>
              <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
              <input type="date" class="form-control" id="start" name="start_date" placeholder="From" data-week-start="1" data-autoclose="true" data-today-highlight="true">
              <span class="input-group-text fw-semibold">
                  <i class="fa fa-fw fa-arrow-right"></i>
              </span>
              <input type="date" class="form-control" id="end" name="end_date" placeholder="To" data-week-start="1" data-autoclose="true" data-today-highlight="true">
              </div>
          </div>
        </form>

    <!-- Overview -->
    <div class="row items-push">
      <div class="col-sm-6 col-xl-4">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full flex-grow-1">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-users fa-lg text-primary"></i>
            </div>
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="">
             {{-- {{ App\Helpers\SystemActivities::numberFormat($users->count()) }}  --}} 
              <span id="totalUsers"></span></div>
            <div class="text-muted mb-3">Registered Users</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-up me-1"></i>
              {{-- {{ number_format($users->where('is_verified')->count()) }} -  --}} 
              <span id="verifiedUsers"></span>
            </div>
          </div>
          <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
            <a class="fw-medium" href="{{ url('users') }}">
              View all users
              <i class="fa fa-arrow-right ms-1 opacity-25"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-4">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full flex-grow-1">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-level-up-alt fa-lg text-primary"></i>
            </div>
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title=""><span id="campaigns"></span></div>
            <div class="text-muted mb-3">Total Campaigns</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-down me-1"></i>
              
            </div>
          </div>
          <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
            <a class="fw-medium" href="{{ url('campaigns') }}">
              View Campaigns
              <i class="fa fa-arrow-right ms-1 opacity-25"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-4">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full flex-grow-1">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-chart-line fa-lg text-primary"></i>
            </div>
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title=""> 
              {{-- {{ App\Helpers\SystemActivities::numberFormat($campaigns->sum('total_amount')) }} --}}
              &#8358;<span id="campaignValue"></span>
              </div>
            <div class="text-muted mb-3"> Campaigns Value</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-up me-1"></i>
              &#8358; <span id="campaignWorker"></span>
              
              {{-- {{ App\Helpers\SystemActivities::numberFormat($workers) }} --}}
            </div>
          </div>
          <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
            <a class="fw-medium" href="{{url('campaigns')}}">
              View all sales
              <i class="fa fa-arrow-right ms-1 opacity-25"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

@endsection


@section('script')
<script>
  $(function () {
      $('[data-toggle="tooltip"]').tooltip()
  })
  </script>

  <script>
    $(document).ready(function(){
      
          // Get the dropdown items
        const dropdownItems = document.querySelectorAll('.dropdown-item');

        // Add click event listener to each dropdown item
        dropdownItems.forEach(item => {
          item.addEventListener('click', function() {
            // Get the text content of the clicked dropdown item
            const selectedOption = this.textContent;

            // Update the active option in the button
            document.getElementById('selected-option').textContent = selectedOption;
          });
        });


      function handleClick() {
          var id = this.id;
         
          $.ajax({
              url: '{{ url("admin/dashboard/api/default") }}',
              method: 'GET',
              data: {
                  period: id,
                  // end_date: endDate,
              },
              success: function(response) {
                // console.log(response);
                var totalUsers = response.registeredUser;
                var verifiedUsers = response.verifiedUser;
                var campaigns = response.campaigns;
                var campaignValue = response.campaignValue;
                var campaignWorker = response.campaignWorker;
                // var loginPoints = response.loginPoints;
                // var loginPointsValue = response.loginPointsValue;
               
                //  var amount = length*2;

                document.getElementById("totalUsers").innerHTML = totalUsers.toFixed(2);
                document.getElementById("verifiedUsers").innerHTML = verifiedUsers.toFixed(2);
                document.getElementById("campaigns").innerHTML = campaigns.toFixed(2);
                document.getElementById("campaignValue").innerHTML = Intl.NumberFormat('en-US').format(campaignValue.toFixed(1));
                document.getElementById("campaignWorker").innerHTML = Intl.NumberFormat('en-US').format(campaignWorker.toFixed(1));
                // document.getElementById("loginPoints").innerHTML = loginPoints.toFixed(2);
                // document.getElementById("loginPointsValue").innerHTML = loginPointsValue.toFixed(2);

              },
              error: function(xhr, status, error) {
                  console.error(status);
              }
        });

      }

      // var day_1 = document.getElementById('1');
      var day_7 = document.getElementById('7');
      var day_14 = document.getElementById('14');
      
      // day_1.addEventListener('click', handleClick);
      day_7.addEventListener('click', handleClick);
      day_14.addEventListener('click', handleClick);

      //on load, it gets data for the last 30 days
      $.ajax({
              url: '{{ url("admin/dashboard/api/default") }}',
              method: 'GET',
              data: {
                  period: 30,  //number of days to filter
              },
              success: function(response) {
                // console.log(response);
                var totalUsers = response.registeredUser;
                var verifiedUsers = response.verifiedUser;
                var campaigns = response.campaigns;
                var campaignValue = response.campaignValue;
                var campaignWorker = response.campaignWorker;
                // var loginPoints = response.loginPoints;
                // var loginPointsValue = response.loginPointsValue;
                // var monthly = response.monthlyVisits;
                //  var amount = length*2;

                document.getElementById("totalUsers").innerHTML = totalUsers.toFixed(2);
                document.getElementById("verifiedUsers").innerHTML = verifiedUsers.toFixed(2);
                document.getElementById("campaigns").innerHTML = campaigns.toFixed(2);
                document.getElementById("campaignValue").innerHTML = Intl.NumberFormat('en-US').format(campaignValue.toFixed(1));
                document.getElementById("campaignWorker").innerHTML = Intl.NumberFormat('en-US').format(campaignWorker.toFixed(1));
                // document.getElementById("loginPoints").innerHTML = loginPoints.toFixed(2);
                // document.getElementById("loginPointsValue").innerHTML = loginPointsValue.toFixed(2);
                // document.getElementById("monthly").innerHTML = monthly.toFixed(2);
              },
              error: function(xhr, status, error) {
                  console.error(status);
              }
        });

       

        // $('#post-type').change(function(){
        //     console.log('load');
        // });


        $('#end').change(function(){
          var startDate = document.getElementById("start").value;
          var endDate = document.getElementById("end").value;

              $.ajax({
                        url: '{{ url("admin/dashboard/api") }}',
                        method: 'GET',
                        data: {
                            start_date: startDate,
                            end_date: endDate,
                        },
                        success: function(response) {
                          // console.log(response);
                          var totalUsers = response.registeredUser;
                          var verifiedUsers = response.verifiedUser;
                          var campaigns = response.campaigns;
                          var campaignValue = response.campaignValue;
                          var campaignWorker = response.campaignWorker;
                          // var loginPoints = response.loginPoints;
                          // var loginPointsValue = response.loginPointsValue;
                        

                          document.getElementById("totalUsers").innerHTML = totalUsers.toFixed(2);
                          document.getElementById("verifiedUsers").innerHTML = verifiedUsers.toFixed(2);
                          document.getElementById("campaigns").innerHTML = campaigns.toFixed(2);
                          document.getElementById("campaignValue").innerHTML = Intl.NumberFormat('en-US').format(campaignValue.toFixed(1));
                          document.getElementById("campaignWorker").innerHTML = Intl.NumberFormat('en-US').format(campaignWorker.toFixed(1));
                          // document.getElementById("loginPoints").innerHTML = loginPoints.toFixed(2);
                          // document.getElementById("loginPointsValue").innerHTML = loginPointsValue.toFixed(2);
                         
                        },
                        error: function(xhr, status, error) {
                            console.error(status);
                        }
              });
        });

    });
    </script>
@endsection

