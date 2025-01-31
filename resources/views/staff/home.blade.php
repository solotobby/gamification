@extends('layouts.main.master')
@section('style')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  var visitor = <?php echo $visitor; ?>;
  console.log(visitor);
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable(visitor);
    var options = {
      title: 'Daily Activity',
      curveType: 'function',
      legend: { position: 'bottom' }
    };
    var chart = new google.visualization.LineChart(document.getElementById('linechart'));
    chart.draw(data, options);
  }
</script> 



    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      var monthly = <?php echo $monthly; ?>;
      console.log(monthly);
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable(monthly);

        var options = {
          title : 'Monthly Registration',
          vAxis: {title: 'Hits'},
          hAxis: {title: 'Months'},
          seriesType: 'bars',
          series: {5: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div_monthly'));
        chart.draw(data, options);
      }
    </script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      var channel = <?php echo $channel; ?>;
      console.log(channel);
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(channel);

        var options = {
          title: 'Registration Channel',
          // pieHole: 0.3,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>

@endsection
@section('content')
<div class="content">
    <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
      <div>
        <h1 class="h3 mb-1">
          Staff Dashboard
        </h1>
        <p class="fw-medium mb-0 text-muted">
          Welcome, {{ auth()->user()->name }}.
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
      <div class="col-sm-6 col-xl-6">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full flex-grow-1">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-users fa-lg text-primary"></i>
            </div>
            <div class="fs-1 fw-bold">{{ $users->count() }}</div>
            <div class="text-muted mb-3">Registered Users</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-up me-1"></i>
              {{ number_format($users->where('is_verified')->count()) }}
            </div>
          </div>
          <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
            <a class="fw-medium" href="javascript:void(0)">
              View all users
              <i class="fa fa-arrow-right ms-1 opacity-25"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-xl-6">
        <div class="block block-rounded text-center d-flex flex-column h-100 mb-0">
          <div class="block-content block-content-full flex-grow-1">
            <div class="item rounded-3 bg-body mx-auto my-3">
              <i class="fa fa-level-up-alt fa-lg text-primary"></i>
            </div>
            <div class="fs-1 fw-bold">{{ $campaigns->count() }}</div>
            <div class="text-muted mb-3">Total Campaigns</div>
            <div class="d-inline-block px-3 py-1 rounded-pill fs-sm fw-semibold text-success bg-success-light">
              <i class="fa fa-caret-down me-1"></i>
              5
            </div>
          </div>
          <div class="block-content block-content-full block-content-sm bg-body-light fs-sm">
            <a class="fw-medium" href="javascript:void(0)">
              Explore analytics
              <i class="fa fa-arrow-right ms-1 opacity-25"></i>
            </a>
          </div>
        </div>
      </div>
   

     
      
    </div>

    <div class="row">
      <div class="col-xl-12">
        <div id="chart_div_monthly" style="width: 100%; height: 500px;"></div>
        
        
       {{-- <hr><div id="chart_div" style="width: 100%; height: 500px;"></div> --}} 
        
        <hr>

       <div id="linechart" style="width: 100%; height: 500px"></div> 
       <hr>
       <div id="donutchart" style="width: 100%; height: 500px;"></div>
      </div>
    </div>
  </div>

    
@endsection

@section('script')
 {{-- <script src="{{asset('src/assets/js/lib/jquery.min.js')}}"></script> --}}
 <!-- Page JS Plugins -->
 {{-- <script src="{{asset('src/assets/js/plugins/chart.js/chart.min.js')}}"></script> --}}

 <!-- Page JS Code -->
 {{-- <script src="{{asset('src/assets/js/pages/be_pages_dashboard.min.js')}}"></script> --}}

 
 {{-- <script src="{{asset('src/assets/js/dashmix.app.min.js')}}"></script> --}}



@endsection