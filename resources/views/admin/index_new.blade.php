@extends('layouts.main.master')

@section('content')

<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
      <div>
        <h1 class="h3 mb-1">
          Admin Dashboard
        </h1>
        <p class="fw-medium mb-0 text-muted">
          {{-- Wallet Balance - <a class="fw-medium" href="javascript:void(0)">&#8358;{{number_format($wallet[0]->total_balance,2)}}</a> --}}
            {{-- <br>Withdrawable Balance - <a class="fw-medium" href="javascript:void(0)">&#8358;{{number_format($wallet[0]->balance_gt_200,2)}} </a> --}}
            {{-- <br> Dollar Wallet - <a class="fw-medium" href="javascript:void(0)">${{number_format($wallet[0]->total_usd_balance,2)}}</a> --}}
            {{-- <br> Total Payout - <a class="fw-medium" href="javascript:void(0)">&#8358;{{ number_format($totalPayout,2) }}</a>

            {{-- <br> Total Transaction - <a class="fw-medium" href="javascript:void(0)"> &#8358;{{number_format($transactions[0]->total_successful_transactions,2)}}</a> --}}
            {{-- <br> Jobs Available - <a class="fw-medium" href="javascript:void(0)">{{ $av_count }}</a> --}}
            {{-- <br> Period Payment (Last {{ $period }} days) - <a class="fw-medium" href="javascript:void(0)"> &#8358;{{number_format($periodPayment,2)}}</a> --}}

            {{-- <br> Active Virtual Account -<a class="fw-medium" href="javascript:void(0)"> {{ totalVirtualAccount() }} </a> --}}

            {{-- <br> Total Pending Payout - <a class="fw-medium" href="javascript:void(0)">&#8358;{{ number_format($totalPendingPayout,2) }}</a> --}} 

            @if(env('APP_ENV') == 'production')
                <br> Location - <a class="fw-medium" href="javascript:void(0)">{{ currentLocation() }}</a>
            @endif
        </p>
      </div>
      <div class="mt-4 mt-md-0">
        <a class="btn btn-sm btn-alt-primary" href="javascript:void(0)">
          <i class="fa fa-cog"></i>
        </a>
        <div class="dropdown d-inline-block">
          <button type="button" class="btn btn-sm btn-alt-primary px-3" id="dropdown-analytics-overview" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span id="selected-option">Last {{ $period }} days</span> <i class="fa fa-fw fa-angle-down"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-analytics-overview">
            <a class="dropdown-item period-filter" data-period="7">Last 7 days</a>
            <a class="dropdown-item period-filter" data-period="14">Last 14 days</a>
            <a class="dropdown-item period-filter" data-period="30">Last 30 days</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <!-- Overview -->
    <div class="row items-push">
      <div class="col-sm-6 col-xl-3">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full flex-grow-1">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-users fa-lg text-primary"></i>
            </div>
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="">
              <span id="totalUsers"></span></div>
            <div class="text-muted mb-3">Registered Users</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-up me-1"></i>
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
      <div class="col-sm-6 col-xl-3">
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
      <div class="col-sm-6 col-xl-3">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full flex-grow-1">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-chart-line fa-lg text-primary"></i>
            </div>
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="">
              &#8358;<span id="campaignValue"></span>
              </div>
            <div class="text-muted mb-3"> Campaigns Value</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-up me-1"></i>
              &#8358; <span id="campaignWorker"></span>
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

      <div class="col-sm-6 col-xl-3">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full flex-grow-1">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-chart-line fa-lg text-primary"></i>
            </div>
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="">
              <span id="activeReg"></span>
              </div>
            <div class="text-muted mb-3">Active Users</div>
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

    <!-- View Analytics Button -->
    <div class="row mt-4">
      <div class="col-12 text-center">
        <a href="{{ url('admin/home/analytics') }}?period={{ $period }}" class="btn btn-lg btn-primary">
        {{-- <a href="" class="btn btn-lg btn-primary"> --}}
          <i class="fa fa-chart-bar me-2"></i>
          View Detailed Analytics
        </a>
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
    // Period filter click handler
    $('.period-filter').click(function(e) {
        e.preventDefault();
        var period = $(this).data('period');
        var text = $(this).text();

        $('#selected-option').text(text);

        // Show loading overlay (if you uncomment it in the view)
        // $('#loading-overlay').css('display', 'flex');

        // Reload page with period parameter
        window.location.href = '{{ url("admin/home") }}?period=' + period;
    });

    // Load initial stats via API
    function loadDashboardStats(period) {
        // Show loading (if you uncomment the overlay)
        // $('#loading-overlay').css('display', 'flex');

        $.ajax({
            url: '{{ url("admin/dashboard/api/default") }}',
            method: 'GET',
            data: { period: period },
            success: function(response) {
                // Parse response data
                var totalUsers = parseInt(response.registeredUser) || 0;
                var verifiedUsers = parseInt(response.verifiedUser) || 0;
                var campaigns = parseInt(response.campaigns) || 0;
                var campaignValue = parseFloat(response.campaignValue) || 0;
                var campaignWorker = parseFloat(response.campaignWorker) || 0;
                var activeReg = parseInt(response.activeUsers) || 0;

                // Update DOM elements
                document.getElementById("totalUsers").innerHTML = Intl.NumberFormat('en-US').format(totalUsers);
                document.getElementById("verifiedUsers").innerHTML = Intl.NumberFormat('en-US').format(verifiedUsers);
                document.getElementById("campaigns").innerHTML = Intl.NumberFormat('en-US').format(campaigns);
                document.getElementById("campaignValue").innerHTML = Intl.NumberFormat('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(campaignValue);
                document.getElementById("campaignWorker").innerHTML = Intl.NumberFormat('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(campaignWorker);
                document.getElementById("activeReg").innerHTML = Intl.NumberFormat('en-US').format(activeReg);

                // Hide loading after data loads
                // $('#loading-overlay').fadeOut(300);
            },
            error: function(xhr, status, error) {
                console.error('Error loading dashboard stats:', error);
                console.error('Response:', xhr.responseText);

                // Set default values on error
                document.getElementById("totalUsers").innerHTML = '0';
                document.getElementById("verifiedUsers").innerHTML = '0';
                document.getElementById("campaigns").innerHTML = '0';
                document.getElementById("campaignValue").innerHTML = '0.00';
                document.getElementById("campaignWorker").innerHTML = '0.00';
                document.getElementById("activeReg").innerHTML = '0';

                // Hide loading on error
                // $('#loading-overlay').fadeOut(300);
            }
        });
    }

    // Load stats on page load
    var currentPeriod = {{ $period }};
    loadDashboardStats(currentPeriod);
});
</script>
@endsection
