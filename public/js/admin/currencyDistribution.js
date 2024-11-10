google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
  var data = google.visualization.arrayToDataTable(currency);

  var options = {
    title: 'Currency Distribution',
    pieHole: 0.4,
  };

  var chart = new google.visualization.PieChart(document.getElementById('currency_distribution'));
  chart.draw(data, options);
}