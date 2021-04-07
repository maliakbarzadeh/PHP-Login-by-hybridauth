<?php
/**
 * Build a simple HTML page with multiple providers, opening provider authentication in a pop-up.
 */
require 'connectSQL.php';


require 'vendor/autoload.php';
require 'config.php';

use Hybridauth\Hybridauth;

$hybridauth = new Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();


if($_SERVER["REQUEST_METHOD"] == "POST"){
	// Complete the profile
	if(isset($_SESSION['id_user']) && isset($_SESSION['specific_id'])
		&& isset($_POST['usernameSocial']) && isset($_POST['passwordSocial']) && isset($_POST['confirm_passwordSocial'])
        && isset($_POST['firstnameSocial']) && isset($_POST['lastnameSocial'])) {
		$firstname='';
		$lastname='';
		$email='';
		$username='';
		$password='';
		$sql="select user.id, user.specific_id, profile.email from user
		inner join profile on user.id = profile.id_user
		where user.id = '".$_SESSION['id_user']."' and user.specific_id = '".$_SESSION['specific_id']."'";
		if ($result = $conn->query($sql)){
			if($result->num_rows != 0){
				$row = $result->fetch_assoc();
				$sp_id = $row['specific_id'];
				$user_id = $row['id'];
				$firstname = $_POST['firstnameSocial'];
				$lastname = $_POST['lastnameSocial'];
				$username = $_POST['usernameSocial'];
				$password = $_POST['passwordSocial'];
				$confirm_password = $_POST['confirm_passwordSocial'];
				if(isset($_POST['emailSocial']))
					$email = $_POST['emailSocial'];
				else
					$email = $row['email'];
				if(strlen($username) >= 6 && strlen($username) <= 20 
					&& strlen($password) >= 6 && strlen($password) <= 15 && $password === $confirm_password
					&& strlen($email) > 3 && strlen($email) <= 50 && filter_var($email, FILTER_VALIDATE_EMAIL)
					&& strlen($firstname) >= 1 && strlen($firstname) <= 50
					&& strlen($lastname) >= 1 && strlen($lastname) <= 50){
					$sql = "update user set username = '".$username."', password = '".crypt($password, $sp_id)."' 
					where id = '".$user_id."'";
					if ($result = $conn->query($sql)){
						$sql = "update profile set firstname = '".$firstname."', lastname = '".$lastname."', email = '".$email."'  
						where id_user = '".$user_id."'";
						if ($result = $conn->query($sql))
							echo "success";
						else
							echo "Registration error: " . $conn->error;
					} else 
						echo "Registration error: " . $conn->error;
				} else {
					echo "Check all fields.";
				}
			} else {
				echo "User not found.";
			}
		} else {
			echo "User found error: " . $conn->error;
		} 
	} //End Complete the profile
	if(!isset($_SESSION['id_user']) && !isset($_SESSION['specific_id'])){
		if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['email'])
					&& isset($_POST['firstname']) && isset($_POST['lastname'])){
			
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$confirm_password = $_POST['confirm_password'];
			$email = $_POST['email'];
			if(strlen($username) >= 6 && strlen($username) <= 20 
				&& strlen($password) >= 6 && strlen($password) <= 15 && $password === $confirm_password
				&& strlen($email) > 3 && strlen($email) <= 50 && filter_var($email, FILTER_VALIDATE_EMAIL)
				&& strlen($firstname) >= 1 && strlen($firstname) <= 50
				&& strlen($lastname) >= 1 && strlen($lastname) <= 50){
				$sql="select id, username from user where username = '".$username."'";
				if ($result = $conn->query($sql)){
					if($result->num_rows == 0){
						$sql="select user.id, user.username, user.specific_id from user
						inner join profile
						on user.id = profile.id_user
						where profile.email = '".$email."'";
						
						if ($result = $conn->query($sql)){
							// Check user already registered
							if($result->num_rows === 0){
								// New user registration.
								$sp_id=uniqid('', true);
								do{
									$sp_id=uniqid('', true);
									$sql="select id from user where specific_id='".$sp_id."'";
									if(!($result = $conn->query($sql))){
										echo "Registration error: " . $conn->error;
									}
									$row = $result->fetch_assoc();
								}while($result->num_rows != 0);
								$sql="insert into user (username, password, specific_id)
								values ('".$username."', '".crypt($password, $sp_id)."', '".$sp_id."')";
								if ($result = $conn->query($sql)){
									$sql="select id from user where specific_id = '".$sp_id."'";
									$result = $conn->query($sql);
									$row = $result->fetch_assoc();
									$sql="insert into profile (id_user, firstname, lastname, email) 
									values ('".$row['id']."', '".$firstname."', '".$lastname."', '".$email."')";
									if ($result = $conn->query($sql)){
										// Start the session
										if(!isset($_SESSION))
											session_start();
										// Set session variables
										$_SESSION["id_user"] = $row['id'];
										$_SESSION["specific_id"] = $sp_id;
										echo 'success';
										//$url="welcome.php";
										//header( "Location: $url" );
									}
								} else {
									echo "User registration error: " . $conn->error;
								}					
							}// End new user
						} else {
							echo "User found error: " . $conn->error;
						} //End check connection
					}
					} else {
						echo "Check error: " . $conn->error;
					} // End check username
				} else {
					echo "Check all fields.";
				}
		} else {
			echo "Check all fields.";
		} //End check data post
	}
} // End check Post
$conn->close();
?>