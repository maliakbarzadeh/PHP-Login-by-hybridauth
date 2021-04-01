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


require 'vendor/autoload.php';
require 'config.php';

use Hybridauth\Hybridauth;

$hybridauth = new Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();

if (isset($adapters)){
	foreach ($adapters as $name => $adapter) {
		$user_profile = $adapter -> getUserProfile();
		// Check new user or registered
		$sql="select user.id from user
		inner join user_social 
		on user.id = user_social.id_user
		where user_social.social_id='".$user_profile-> identifier."'";
		
		if ($result = $conn->query($sql)){
			if($result->num_rows != 0){
				// If already registered.
				$row = $result->fetch_assoc();
			} else {
				// Check that the email has already been registered.
				$sql="select user.id from user
				inner join profile 
				on user.id = profile.id_user
				where profile.email='".$user_profile-> email."'";
				
				if($result = $conn->query($sql)){
					if($result->num_rows != 0){
						// If the email has already been registered but the new account has not been registered.
						$row = $result->fetch_assoc();
						$meta=print_r($user_profile,1);
						$sql="insert into user_social (id_user, social_name, social_id, social_meta)
						values ('".$row['id']."', '".$name."', '".$user_profile->identifier."', '".$meta."')";
						if(!($result = $conn->query($sql))){
							echo "Registration error: " . $conn->error;
						}
					} else {
						// New user registration.
						$sp_id=uniqid('', true);
						$sql="INSERT INTO `user`(specific_id) VALUES ('".$sp_id."')";
						if($result = $conn->query($sql)){
							$sql="select id from user where specific_id='".$sp_id."'";
							$result = $conn->query($sql);
							$row = $result->fetch_assoc();
							$meta=print_r($user_profile,1);
							$sql="insert into user_social (id_user, social_name, social_id, social_meta)
							values ('".$row['id']."', '".$name."', '".$user_profile->identifier."', '".$meta."')";
							if($result = $conn->query($sql)){
								$sql="insert into profile (id_user, firstname, lastname, email, phone_number)
								values ('".$row['id']."', '".$user_profile->firstName."', '".$user_profile->lastName."', 
								'".$user_profile->email."', '".$user_profile->phone."')";
								if(!($result = $conn->query($sql))){
									echo "Registration error: " . $conn->error;
								}
							} else {
								echo "Registration error: " . $conn->error;
							}
						} else {
							echo "Registration error: " . $conn->error;
						}
					}
				} else {
					echo "Check error: " . $conn->error;
				}
			}
		}
		$sql="select user.id, user.specific_id from user
		inner join user_social 
		on user.id = user_social.id_user
		where user_social.social_id='".$user_profile-> identifier."'";
		if($result = $conn->query($sql)){
			if($result->num_rows != 0){
				// If the email has already been registered but the new account has not been registered.
				$row = $result->fetch_assoc();
				// Start the session
				session_start();
				// Set session variables
				$_SESSION["id_user"] = $row['id'];
				$_SESSION["specific_id"] = $row['specific_id'];
				$url="welcome.php";
				header( "Location: $url" );
			}
		} else {
			echo "User found error: " . $conn->error;
		}
	} // End foreach
} // End if check set user social
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['username']) & isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$sql="select id, specific_id from user where username = '".$username."' and  password = '".$password."'";
		if($result = $conn->query($sql)){
			if($result->num_rows != 0){
				$row = $result->fetch_assoc();
				$sp_id = $row['specific_id'];
				$sql="select * from profile where id_user = '".$row['id']."'";
				if($result = $conn->query($sql)){
					$row = $result->fetch_assoc();
					// Start the session
					session_start();
					// Set session variables
					$_SESSION["id_user"] = $row['id'];
					$_SESSION["specific_id"] = $sp_id;
					$url="welcome.php";
					header( "Location: $url" );
				} else {
					echo "User found error: " . $conn->error;
				}
			} else {
				echo "User not found!";
			}
		} else {
			echo "Login error: " . $conn->error;
		}
	} else {
		echo "Error receiving data.<br>";
	}
}

$conn->close();

?>
