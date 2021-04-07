<?php
require 'connectSQL.php';



@$password = $_POST['password'];
@$confirm_password = $_POST['confirm_password'];
@$username = $_POST['username'];
@$email = $_POST['email'];

// Check password
if(isset($password) && isset($confirm_password)){
	if(strlen($password)<6 || strlen($password) > 15)
		echo "Password length should be between 6 and 15 characters.";
	else if($password !== $confirm_password && $confirm_password!==''){
		echo "Does not match password!";
	}
	
}

// Check username
if(isset($username)){
	if(strlen($username)<6 || strlen($username) > 20)
		echo "Username length should be between 6 and 20 characters." ;
	else {
		$sql="select id, username from user where username = '".$username."'";
		if ($result = $conn->query($sql)){
				if($result->num_rows != 0){
					echo "This username is not available!";
				}
		} else {
			echo "Check error: " . $conn->error;
		}
	}
}

// Check email
if(isset($email)){
	if(strlen($email) < 3 || strlen($email) > 50 || !filter_var($email, FILTER_VALIDATE_EMAIL))
		echo "Please check your email address.";
	else {
		$sql="select id, email from profile where email = '".$email."'";
		if ($result = $conn->query($sql)){
				if($result->num_rows != 0){
					echo "This email is already registered!";
				}
		} else {
			echo "Check error: " . $conn->error;
		}
	}
}

$conn->close();
?>