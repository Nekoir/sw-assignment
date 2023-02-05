CREATE DATABASE 4256992_tempdb;
USE 4256992_tempdb;

CREATE TABLE sensors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)
);

CREATE TABLE DHT11_data (
  id INT AUTO_INCREMENT PRIMARY KEY,
  temperature FLOAT,
  humidity FLOAT,
  timestamp DATETIME,
  sensor_id INT,
  FOREIGN KEY (sensor_id) REFERENCES sensors(id)
);

CREATE TABLE soil_moisture_data (
  id INT AUTO_INCREMENT PRIMARY KEY,
  moisture FLOAT,
  timestamp DATETIME,
  sensor_id INT,
  FOREIGN KEY (sensor_id) REFERENCES sensors(id)
);
INSERT INTO sensors (name)
VALUES ('DHT11'), ('MH_Sensor_Series');

CREATE TABLE sensor_data_type (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sensor_id INT,
  data_type VARCHAR(100),
  FOREIGN KEY (sensor_id) REFERENCES sensors(id)
);

INSERT INTO sensor_data_type (sensor_id, data_type)
VALUES (1, 'temperature'), (1, 'humidity'), (2, 'moisture');
