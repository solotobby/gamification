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
  var monthly = <?php echo $monthly; ?>;
</script>

<script>
  var revenue = <?php echo $revenue; ?>
</script>

<script>
  var country = <?php echo $country; ?>
</script>

<script>
  var age = <?php echo $age; ?>
</script>

<script>
  var currency = <?php echo $currency; ?>
</script>

<script>
  var monthlyRevenue = <?php echo $monthlyRevenue; ?>
</script>

<script>
  var weeklyRegistrationChannel = <?php echo $weeklyRegistrationChannel; ?>
</script>

<script>
  var weeklyVerificationChannel = <?php echo $weeklyVerificationChannel; ?>
</script>

<script src="{{ asset('js/admin/monthlyRegistration.js')}}"></script>
<script src="{{ asset('js/admin/dailyVisitor.js')}}"></script>
<script src="{{ asset('js/admin/dailyActivities.js')}}"></script>
<script src="{{ asset('js/admin/revenueChannel.js')}}"></script>
<script src="{{ asset('js/admin/countryDistribution.js')}}"></script>
<script src="{{ asset('js/admin/ageDistribution.js')}}"></script>
<script src="{{ asset('js/admin/currencyDistribution.js')}}"></script>
<script src="{{ asset('js/admin/monthlyRevenue.js')}}"></script>
<script src="{{ asset('js/admin/weeklyRegistrationChannel.js')}}"></script>
<script src="{{ asset('js/admin/weeklyVerificationChannel.js')}}"></script>

<style>
#loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}
</style>
@endsection

@section('content')

<!-- Loading Overlay -->
<div id="loading-overlay">
    <div style="text-align: center; color: white;">
        <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Loading analytics data...</p>
    </div>
</div>

<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
      <div>
        <h1 class="h3 mb-1">
          Analytics Dashboard
        </h1>
        <p class="fw-medium mb-0 text-muted">
          Detailed charts and data visualizations
        </p>
      </div>
      <div class="mt-4 mt-md-0">
        <a class="btn btn-sm btn-alt-secondary me-2" href="{{ url('admin/home') }}?period={{ $period }}">
          <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
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
    <div class="row">
      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Monthly Registration</h3>
          </div>
          <div class="block-content">
            <div id="chart_div_monthly" style="width: 100%; height: 500px;"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Monthly Revenue</h3>
          </div>
          <div class="block-content">
            <div id="chart_div_monthly_rev" style="width: 100%; height: 500px;"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Daily Visitor Statistics</h3>
          </div>
          <div class="block-content">
            <div id="chart_div" style="width: 100%; height: 500px;"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Daily Activities</h3>
          </div>
          <div class="block-content">
            <div id="linechart" style="width: 100%; height: 500px"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Weekly Registration Channel</h3>
          </div>
          <div class="block-content">
            <div id="chart_div_weekly_rev_channel" style="width: 100%; height: 500px"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Weekly Verification Channel</h3>
          </div>
          <div class="block-content">
            <div id="chart_div_weekly_verification_channel" style="width: 100%; height: 500px"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Revenue by Channel</h3>
          </div>
          <div class="block-content">
            <div id="donutchart_revenue" style="width: 100%; height: 500px;"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Country Distribution</h3>
          </div>
          <div class="block-content">
            <div id="country_distribution" style="width: 100%; height: 500px;"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Age Distribution</h3>
          </div>
          <div class="block-content">
            <div id="age_distribution" style="width: 100%; height: 500px;"></div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Currency Distribution</h3>
          </div>
          <div class="block-content">
            <div id="currency_distribution" style="width: 100%; height: 500px;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
<script>
$(document).ready(function(){
    // Period filter click handler
    $('.period-filter').click(function(e) {
        e.preventDefault();
        var period = $(this).data('period');
        var text = $(this).text();

        $('#selected-option').text(text);

        // Show loading overlay
        $('#loading-overlay').css('display', 'flex');

        // Reload page with period parameter
        window.location.href = '{{ url("admin/analytics") }}?period=' + period;
    });

    // Hide loading overlay after page loads
    $(window).on('load', function() {
        $('#loading-overlay').fadeOut(300);
    });
});
</script>
@endsection
