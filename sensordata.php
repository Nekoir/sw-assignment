<?php 

require 'config.php';

//----------------------------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$api_key = escape_data($_POST["api_key"]);
        //print_r($_POST);
        //echo "<br>PROJECT_API_KEY: ".PROJECT_API_KEY."<br>";
	//MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
	if($api_key == PROJECT_API_KEY) {
		$temperature = escape_data($_POST["temperature"]);
		$humidity = escape_data($_POST["humidity"]);
                $moisture = escape_data($_POST["moisture"]);
                $DHT11 = 1;
                $SM_sensor = 2;
		
		//$sql = "INSERT INTO sensors_data(temperature,humidity,moisture,created_date) 
		//	VALUES('".$temperature."','".$humidity."','".$moisture."','".date("Y-m-d H:i:s")."')";
                $sql = "INSERT INTO DHT11_data(temperature, humidity, timestamp, sensor_id) 
                        VALUES('".$temperature."', '".$humidity."', '".date("Y-m-d H:i:s")."', ".$DHT11.")";
                

		if($db->query($sql) === FALSE)
			{ echo "Error: " . $sql . "<br>" . $db->error; }
                        
                $sql = "INSERT INTO soil_moisture_data(moisture, timestamp, sensor_id) 
                        VALUES('".$moisture."', '".date("Y-m-d H:i:s")."', ".$SM_sensor.")";

                        
                if($db->query($sql) === FALSE)
			{ echo "Error: " . $sql . "<br>" . $db->error; }

		echo "OK. INSERT ID: ";
		echo $db->insert_id;
	}
	//MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
	else
	{
		echo "Wrong API Key";
	}
	//MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
}
//----------------------------------------------------------------------------
else
{
	echo "No HTTP POST request found";
}
//----------------------------------------------------------------------------


function escape_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}