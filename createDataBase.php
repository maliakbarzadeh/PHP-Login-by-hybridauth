<?php
// Connect to MySQL
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully <br>";


// Create database
$sql = "CREATE DATABASE IF NOT EXISTS hybridauth";
if ($conn->query($sql) === TRUE) {
  echo "Database created successfully <br>";
} else {
  echo "Error creating database: " . $conn->error;
}

$dbname = "hybridauth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully <br>";


// sql to create table user
if ($result = $conn->query("select 1 from `user` LIMIT 1")) {
    if($result->num_rows !== false) {
        echo "Table user exists <br>";
    }
} else {
    $sql = "CREATE TABLE user (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(30),
	password VARCHAR(30),
	specific_id VARCHAR(100)
	)";

	if ($conn->query($sql) === TRUE) {
	  echo "Table user created successfully <br>";
	} else {
	  echo "Error creating table: " . $conn->error;
	}
}


// sql to create table user_social
if ($result = $conn->query("select 1 from `user_social` LIMIT 1")) {
    if($result->num_rows !== false) {
        echo "Table user_social exists <br>";
    }
} else {
	$sql = "CREATE TABLE user_social (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	id_user INT(6),
	social_name VARCHAR(30),
	social_id VARCHAR(50),
	social_meta VARCHAR(3000)
	)";

	if ($conn->query($sql) === TRUE) {
	  echo "Table user_social created successfully <br>";
	} else {
	  echo "Error creating table: " . $conn->error;
	}
}

// sql to create table profile
if ($result = $conn->query("select 1 from `profile` LIMIT 1")) {
    if($result->num_rows !== false) {
        echo "Table profile exists <br>";
    }
} else {
    $sql = "CREATE TABLE profile (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	id_user INT(6),
	firstname VARCHAR(30),
	lastname VARCHAR(30),
	email VARCHAR(50),
	phone_number VARCHAR(15)
	)";

	if ($conn->query($sql) === TRUE) {
	  echo "Table profile created successfully <br>";
	} else {
	  echo "Error creating table: " . $conn->error;
	}
}

$conn->close();


?>