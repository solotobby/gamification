
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawVisualization);

function drawVisualization() {
  // Some raw data (not necessarily accurate)
  var data = google.visualization.arrayToDataTable(weeklyRegistrationChannel);

  var options = {
    title : 'Weekly Registration',
    vAxis: {title: 'Registrations'},
    hAxis: {title: 'Days'},
    seriesType: 'bars',
    series: {5: {type: 'line'}}
  };

  var chart = new google.visualization.ComboChart(document.getElementById('chart_div_weekly_rev_channel'));
  chart.draw(data, options);
}
