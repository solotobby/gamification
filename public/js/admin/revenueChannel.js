google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
  var data = google.visualization.arrayToDataTable(revenue);

  var options = {
    title: 'Revenue Channel',
    pieHole: 0.3,
  };

  var chart = new google.visualization.PieChart(document.getElementById('donutchart_revenue'));
  chart.draw(data, options);
}