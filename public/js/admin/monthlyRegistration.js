
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
