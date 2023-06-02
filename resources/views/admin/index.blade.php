@extends('layouts.main.master')
@section('style')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
  var visitor = <?php echo $visitor ?>
</script>
<script>
  var daily = <?php echo $daily; ?>
</script>
<script>
  var monthly = <?php echo $monthly; ?>
</script>
<script>
  var channel = <?php echo $channel; ?>
</script>
<script>
  var revenue = <?php echo $revenue; ?>
</script>
<script>
  var country = <?php echo $country; ?>
</script>

<script src="{{ asset('js/admin/monthlyRegistration.js')}}"></script>
<script src="{{ asset('js/admin/dailyVisitor.js')}}"></script>
<script src="{{ asset('js/admin/registrationChannel.js')}}"></script>
<script src="{{ asset('js/admin/dailyActivities.js')}}"></script>
<script src="{{ asset('js/admin/revenueChannel.js')}}"></script>
<script src="{{ asset('js/admin/countryDistribution.js')}}"></script>
@endsection

@section('content')
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
      <div>
        <h1 class="h3 mb-1">
          Admin Dashboard
        </h1>
        <p class="fw-medium mb-0 text-muted">
          Welcome, {{ auth()->user()->name }}! You have <a class="fw-medium" href="javascript:void(0)">8 new notifications</a>.
        </p>
      </div>
      <div class="mt-4 mt-md-0">
        <a class="btn btn-sm btn-alt-primary" href="javascript:void(0)">
          <i class="fa fa-cog"></i>
        </a>
        <div class="dropdown d-inline-block">
          <button type="button" class="btn btn-sm btn-alt-primary px-3" id="dropdown-analytics-overview" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Last 30 days <i class="fa fa-fw fa-angle-down"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-analytics-overview">
            <a class="dropdown-item" href="javascript:void(0)">This Week</a>
            <a class="dropdown-item" href="javascript:void(0)">Previous Week</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="javascript:void(0)">This Month</a>
            <a class="dropdown-item" href="javascript:void(0)">Previous Month</a>
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
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="{{number_format($users->count())}}">{{ App\Helpers\PaystackHelpers::numberFormat($users->count()) }}</div>
            <div class="text-muted mb-3">Registered Users</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-up me-1"></i>
              {{ number_format($users->where('is_verified')->count()) }}
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
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="{{number_format($campaigns->count())}}">{{ $campaigns->count() }}</div>
            <div class="text-muted mb-3">Total Campaigns</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-down me-1"></i>
              5
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
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="{{number_format($campaigns->sum('total_amount'))}}"> &#8358;{{ App\Helpers\PaystackHelpers::numberFormat($campaigns->sum('total_amount')) }}</div>
            <div class="text-muted mb-3"> Campaigns Value</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-up me-1"></i>
              &#8358;{{ App\Helpers\PaystackHelpers::numberFormat($workers) }}
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
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="{{(number_format($loginPoints->sum('point')))}}">{{ App\Helpers\PaystackHelpers::numberFormat($loginPoints->sum('point')) }}</div>
            <div class="text-muted mb-3">Login Points</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-up me-1"></i>
              &#8358;{{$loginPoints->sum('point') / 2.5 }}
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
      

      {{--<div class="col-sm-6 col-xl-3">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-wallet fa-lg text-primary"></i>
            </div>
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="{{$tx->where('type', 'referer_bonus')->sum('amount')}}"> &#8358;{{ App\Helpers\PaystackHelpers::numberFormat($tx->where('type', 'referer_bonus')->sum('amount')) }}</div>
            <div class="text-muted mb-3">Referral Revenue</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-down me-1"></i>
             
              {{ $tx->where('type', 'referer_bonus')->count() }}
              
            </div>
          </div>
          <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
            <a class="fw-medium" href="javascript:void(0)">
              Withdrawal options
              <i class="fa fa-arrow-right ms-1 opacity-25"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-wallet fa-lg text-primary"></i>
            </div>
           
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="{{$tx->where('type', 'direct_referer_bonus')->sum('amount')}}"> &#8358;{{ App\Helpers\PaystackHelpers::numberFormat($tx->where('type', 'direct_referer_bonus')->sum('amount')) }}</div>
            <div class="text-muted mb-3">Direct Reg. Revenue</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-down me-1"></i>
             
              {{ $tx->where('type', 'direct_referer_bonus')->count() }}
              
            </div>
          </div>
          <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
            <a class="fw-medium" href="javascript:void(0)">
              Withdrawal options
              <i class="fa fa-arrow-right ms-1 opacity-25"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-3">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-wallet fa-lg text-primary"></i>
            </div>
           
            <div class="fs-1 fw-bold" data-toggle="tooltip" data-placement="top" title="{{number_format($wal->balance)}}"> &#8358;{{ App\Helpers\PaystackHelpers::numberFormat($wal->balance) }}</div>
            <div class="text-muted mb-3">Wallet Balance</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-down me-1"></i>
             
            </div>
          </div>
          <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
            <a class="fw-medium" href="javascript:void(0)">
              Withdrawal options
              <i class="fa fa-arrow-right ms-1 opacity-25"></i>
            </a>
          </div>
        </div>
      </div>--}}
    </div>

    <div class="row">
      <div class="col-xl-12">
        <div id="chart_div_monthly" style="width: 100%; height: 500px;"></div>
      </div>
      <hr>
      <div class="col-xl-12">
        <div id="chart_div" style="width: 100%; height: 500px;"></div>
      </div>
      <hr>
      <div class="col-xl-12">
       <div id="linechart" style="width: 100%; height: 500px"></div> 
      </div>
      <hr>
       <div class="col-xl-12">
          <div id="donutchart" style="width: 100%; height: 500px;"></div>
      </div>
      
      <hr>
       <div class="col-xl-12 mb-3">
          <div id="donutchart_revenue" style="width: 100%; height: 500px;"></div>
      </div>
      <hr>
       <div class="col-xl-12 mb-3">
          <div id="country_distribution" style="width: 100%; height: 500px;"></div>
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
@endsection