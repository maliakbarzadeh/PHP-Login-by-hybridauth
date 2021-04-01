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


@$password = $_POST['password'];
@$confirm_password = $_POST['confirm_password'];
@$username = $_POST['username'];
@$email = $_POST['email'];


// Check password
if(isset($password) && isset($confirm_password)){
	if($password !== $confirm_password){
		echo "Does not match password!";
	}
}

// Check username
if(isset($username)){
	$sql="select id, username from user where username = '".$username."'";
	if ($result = $conn->query($sql)){
			if($result->num_rows != 0){
				echo "This username is not available!";
			}
	} else {
		echo "Check error: " . $conn->error;
	}
}

// Check email
if(isset($email) && $email != ''){
	$sql="select id, email from profile where email = '".$email."'";
	if ($result = $conn->query($sql)){
			if($result->num_rows != 0){
				echo "This email is already registered!";
			}
	} else {
		echo "Check error: " . $conn->error;
	}
}

$conn->close();
?>