<?php
//-------------------------------------------------------------------------------------------
require 'config.php';

$db;
        $sql = "SELECT DHT11_data.id, DHT11_data.temperature, DHT11_data.humidity, soil_moisture_data.moisture, DHT11_data.timestamp
                FROM DHT11_data
                JOIN soil_moisture_data
                ON DHT11_data.timestamp = soil_moisture_data.timestamp
                ORDER BY id DESC LIMIT 30";
                
	$result = $db->query($sql);
	if (!$result) {
	  { echo "Error: " . $sql . "<br>" . $db->error; }
	}
        
        $sql = "SELECT s.name, sd.data_type
                FROM sensors s
                JOIN sensor_data_type sd ON s.id = sd.sensor_id";
                
	$data = $db->query($sql);
	if (!$data) {
	  { echo "Error: " . $sql . "<br>" . $db->error; }
	}

//-------------------------------------------------------------------------------------------
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>


<style>
.chart {
  width: 100%; 
  min-height: 450px;
}
.row {
  margin:0 !important;
}

#chart_moisture {
  margin: 0 auto;
  width: 100%;
}
</style>
   
</head>
<body>
  
<div class="container">
  
  <div class="row">
  <div class="col-md-12 text-center">
    <h1>Plant Monitoring Dashboard</h1>
    <p>Group: <a href="#">Hustling Dinos</a></p>
  </div>
  <div class="clearfix"></div>
  
  
  <div class="col-md-6">
    <div id="chart_temperature" class="chart"></div>
  </div>
  
  
  <div class="col-md-6">
    <div id="chart_humidity" class="chart"></div>
  </div>
  
  <div class="col-md-6 text-center">
    <div id="chart_moisture" class="chart"></div>
  </div>

</div>


<div class="row">
  <div class="col-md-12">
    <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Temperature</th>
        <th scope="col">Humidity</th>
        <th scope="col">Moisture</th>
        <th scope="col">Timestamp</th>
      </tr>
    </thead>
    <tbody>
    <?PHP $i = 1; while ($row = mysqli_fetch_assoc($result)) {?>
      <tr>
        <th scope="row"><?php echo $i++;?></th>
        <td><?PHP echo $row['temperature'];?></td>
        <td><?PHP echo $row['humidity'];?></td>
        <td><?PHP echo $row['moisture'];?></td>
        <td><?PHP echo date("Y-m-d h:i: A", strtotime($row['timestamp']));?></td>
      </tr>
    <?PHP } ?>
    </tbody>
  </table>
</div>
</div>
<div style="margin-top: 20px;"></div>
<div class="row">
  <div class="col-md-12">
    <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Sensor Name</th>
        <th scope="col">Data Type</th>
      </tr>
    </thead>
    <tbody>
    <?PHP $i = 1; while ($row = mysqli_fetch_assoc($data)) {?>
      <tr>
        <th scope="row"><?php echo $i++;?></th>
        <td><?PHP echo $row['name'];?></td>
        <td><?PHP echo $row['data_type'];?></td>
      </tr>
    <?PHP } ?>
    </tbody>
  </table>
</div>
</div>
</div>
<!-- ---------------------------------------------------------------------------------------- -->
 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
//$(document).ready(function(){
//-------------------------------------------------------------------------------------------------
google.charts.load('current', {'packages':['gauge']});
google.charts.setOnLoadCallback(drawTemperatureChart);
//-------------------------------------------------------------------------------------------------
function drawTemperatureChart() {
	//guage starting values
	var data = google.visualization.arrayToDataTable([
		['Label', 'Value'],
		['Temperature', 0],
	]);
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	var options = {
		width: 		1600, 
		height: 	480,
		redFrom: 	70, 
		redTo:		100,
		yellowFrom:	40, 
		yellowTo: 	70,
		greenFrom:	00, 
		greenTo: 	40,
		minorTicks: 5
	};
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	var chart = new google.visualization.Gauge(document.getElementById('chart_temperature'));
	chart.draw(data, options);
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN



	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	function refreshData () {
		$.ajax({
			url: 'getdata.php',
			// use value from select element
			data: 'q=' + $("#users").val(),
			dataType: 'json',
			success: function (responseText) {
				//______________________________________________________________
				//console.log(responseText);
				var var_temperature = parseFloat(responseText.temperature).toFixed(2)
				//console.log(var_temperature);
				// use response from php for data table
				//______________________________________________________________
				//guage starting values
				var data = google.visualization.arrayToDataTable([
					['Label', 'Value'],
					['Temperature', eval(var_temperature)],
				]);
				//______________________________________________________________
				//var chart = new google.visualization.Gauge(document.getElementById('chart_temperature'));
				chart.draw(data, options);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(errorThrown + ': ' + textStatus);
			}
		});
    }
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	//refreshData();
	
	setInterval(refreshData, 30000);
}
//-------------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------------
google.charts.load('current', {'packages':['gauge']});
google.charts.setOnLoadCallback(drawHumidityChart);
//-------------------------------------------------------------------------------------------------
function drawHumidityChart() {
	//guage starting values
	var data = google.visualization.arrayToDataTable([
		['Label', 'Value'],
		['Humidity', 0],
	]);
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	var options = {
		width: 		1600, 
		height: 	480,
		redFrom: 	70, 
		redTo:		100,
		yellowFrom:	40, 
		yellowTo: 	70,
		greenFrom:	00, 
		greenTo: 	40,
		minorTicks: 5
	};
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	var chart = new google.visualization.Gauge(document.getElementById('chart_humidity'));
	chart.draw(data, options);
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN



	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	function refreshData () {
		$.ajax({
			url: 'getdata.php',
			// use value from select element
			data: 'q=' + $("#users").val(),
			dataType: 'json',
			success: function (responseText) {
				//______________________________________________________________
				//console.log(responseText);
				var var_humidity = parseFloat(responseText.humidity).toFixed(2)
				//console.log(var_temperature);
				// use response from php for data table
				//______________________________________________________________
				//guage starting values
				var data = google.visualization.arrayToDataTable([
					['Label', 'Value'],
					['Humidity', eval(var_humidity)],
				]);
				//______________________________________________________________
				//var chart = new google.visualization.Gauge(document.getElementById('chart_temperature'));
				chart.draw(data, options);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(errorThrown + ': ' + textStatus);
			}
		});
    }
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	//refreshData();
	
	setInterval(refreshData, 30000);
}
//-------------------------------------------------------------------------------------------------



//-------------------------------------------------------------------------------------------------
google.charts.load('current', {'packages':['gauge']});
google.charts.setOnLoadCallback(drawMoistureChart);
//-------------------------------------------------------------------------------------------------
function drawMoistureChart() {
	//guage starting values
	var data = google.visualization.arrayToDataTable([
		['Label', 'Value'],
		['Moisture', 0],
	]);
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	var options = {
		width: 		1600, 
		height: 	480,
		redFrom: 	70, 
		redTo:		100,
		yellowFrom:	40, 
		yellowTo: 	70,
		greenFrom:	00, 
		greenTo: 	40,
		minorTicks: 5
	};
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	var chart = new google.visualization.Gauge(document.getElementById('chart_moisture'));
	chart.draw(data, options);
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN



	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	function refreshData () {
		$.ajax({
			url: 'getdata.php',
			// use value from select element
			data: 'q=' + $("#users").val(),
			dataType: 'json',
			success: function (responseText) {
				//______________________________________________________________
				//console.log(responseText);
				var var_moisture = parseFloat(responseText.moisture).toFixed(2)
				//console.log(var_temperature);
				// use response from php for data table
				//______________________________________________________________
				//guage starting values
				var data = google.visualization.arrayToDataTable([
					['Label', 'Value'],
					['Moisture', eval(var_moisture)],
				]);
				//______________________________________________________________
				//var chart = new google.visualization.Gauge(document.getElementById('chart_temperature'));
				chart.draw(data, options);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(errorThrown + ': ' + textStatus);
			}
		});
    }
	//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
	//refreshData();
	
	setInterval(refreshData, 30000);
}
//-------------------------------------------------------------------------------------------------

//});




$(window).resize(function(){
  drawTemperatureChart();
  drawHumidityChart();
  drawMoistureChart();
});





</script>
<!-- --------------------------------------------------------------------- -->
</body>
</html>
