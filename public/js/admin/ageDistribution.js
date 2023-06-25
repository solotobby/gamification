
google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
  var data = google.visualization.arrayToDataTable(age);

  var options = {
    title: 'Age Distribution',
    pieHole: 0.3,
  };

  var chart = new google.visualization.PieChart(document.getElementById('age_distribution'));
  chart.draw(data, options);
}