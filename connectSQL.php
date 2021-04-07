<?php
// Connect to MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hybridauth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


?>