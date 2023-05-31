
google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
  var data = google.visualization.arrayToDataTable(country);

  var options = {
    title: 'Country Distribution',
    pieHole: 0.3,
  };

  var chart = new google.visualization.PieChart(document.getElementById('country_distribution'));
  chart.draw(data, options);
}