<?php
require 'config.php';

//$sql = "SELECT * FROM sensors_data WHERE 1 ORDER BY id DESC";
$sql = "SELECT DHT11_data.id, DHT11_data.temperature, DHT11_data.humidity, soil_moisture_data.moisture, DHT11_data.timestamp
        FROM DHT11_data
        JOIN soil_moisture_data
        ON DHT11_data.timestamp = soil_moisture_data.timestamp
        ORDER BY id DESC LIMIT 30";
$result = $db->query($sql);
if (!$result) {
  { echo "Error: " . $sql . "<br>" . $db->error; }
}

$row = $result->fetch_assoc();

//$rows = $result -> fetch_all(MYSQLI_ASSOC);
//print_r($row);

//header('Content-Type: application/json');
 
//$table=array(0=>array('Label','Value'),1=>array('Temperature',$row));


echo json_encode($row);


?>