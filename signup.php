<?php
/**
 * Build a simple HTML page with multiple providers, opening provider authentication in a pop-up.
 */

require 'vendor/autoload.php';
require 'config.php';

use Hybridauth\Hybridauth;

$hybridauth = new Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();
if ($adapters) {
	$url='login.php';
	header( "Location: $url" );
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(isset($_POST['username']) & isset($_POST['password']) & isset($_POST['email'])){
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
		
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		
		$sql="select id, username from user where username = '".$username."'";
		if ($result = $conn->query($sql)){
			if($result->num_rows == 0){
				$sql="select user.id, user.username, user.specific_id from user
				inner join profile
				on user.id = profile.id_user
				where profile.email = '".$email."'";
				
				if ($result = $conn->query($sql)){
					// Check user already registered
					if($result->num_rows != 0){
						$row = $result->fetch_assoc();
						if($row['username']===null){
							// Update user account
							$sql="update user set username = '".$username."', password = '".$password."'
							where id = '".$row['id']."'";
							if ($result = $conn->query($sql)){
								$sql="update profile set firstname = '".$firstname."', lastname = '".$lastname."'
								where id_user = '".$row['id']."'";
								if ($result = $conn->query($sql)){
									// Start the session
									session_start();
									// Set session variables
									$_SESSION["id_user"] = $row['id'];
									$_SESSION["specific_id"] = $row['specific_id'];
									$url="welcome.php";
									header( "Location: $url" );
								}
							}
						}
					} else {
						// New user registration.
						$sp_id=uniqid('', true);
						$sql="insert into user (username, password, specific_id)
						values ('".$username."', '".$password."', '".$sp_id."')";
						if ($result = $conn->query($sql)){
							$sql="select id from user where specific_id = '".$sp_id."'";
							$result = $conn->query($sql);
							$row = $result->fetch_assoc();
							$sql="insert into profile (id_user, firstname, lastname, email) 
							values ('".$row['id']."', '".$firstname."', '".$lastname."', '".$email."')";
							if ($result = $conn->query($sql)){
								echo "Registration done.<br>";
								// Start the session
								session_start();
								// Set session variables
								$_SESSION["id_user"] = $row['id'];
								$_SESSION["specific_id"] = $sp_id;
								$url="welcome.php";
								header( "Location: $url" );
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
		
		
		$conn->close();
	} else {
		$url='index.php';
		header( "Location: $url" );
	} //End check data post
}// End check Post
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Sign up Page</title>
	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
<script>
//<![CDATA[
function Check_form(inputname,divid,inputvalue){
	if(divid == 'message_confirm_password'){
		var formvalue = "password="+document.getElementById('password').value
						+"&confirm_password="+document.getElementById('confirm_password').value; 
	}
	if(divid == 'message_username'){
		var formvalue = "username="+document.getElementById('username').value; 
	}	
	if(divid == 'message_email'){
		var formvalue = "email="+document.getElementById('email').value; 
	}
var loadingmessage = '';    
var xmlhttp;
if (window.XMLHttpRequest){
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
    else{
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState<4){
                document.getElementById(divid).innerHTML=loadingmessage;
                }            
                else if (xmlhttp.readyState==4 && xmlhttp.status==200){
                    document.getElementById(divid).innerHTML=xmlhttp.responseText;
                }
        }
        xmlhttp.open("POST","validate.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send(formvalue);
}
//]]>  
</script>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
			<div class="wrap-login100 p-l-110 p-r-110 p-t-62 p-b-33">
				<form class="login100-form validate-form flex-sb flex-w" action="signup.php" method="post">
					<div class="p-t-31 p-b-9">
						<span class="txt1">
							First name
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "First name is required">
						<input class="input100" type="text" required="required" name="firstname" >
						<span class="focus-input100"></span>
					</div>
					<div class="p-t-31 p-b-9">
						<span class="txt1">
							Last Name
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "Last name is required">
						<input class="input100" type="text" required="required" name="lastname" >
						<span class="focus-input100"></span>
					</div>
					<div class="p-t-31 p-b-9">
						<span class="txt1">
							Username
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "Username is required">
						<input class="input100" type="text" id="username" onkeyup="Check_form('username','message_username','this')" minlength="6" maxlength="20" required="required" name="username" >
						<span class="focus-input100"></span>
					</div>
					<div>
						<span id = "message_username" style="color:red"> </span>
					</div>
					<div class="p-t-13 p-b-9">
						<span class="txt1">
							Password
						</span>
					</div>
					<div class="wrap-input100 validate-input" id="d_password" data-validate = "Password is required">
						<input class="input100" type="password" minlength="6" maxlength="15" id="password" required="required" name="password" >
						<span class="focus-input100"></span>
					</div>
					
					<div class="p-t-13 p-b-9">
						<span class="txt1">
							Password Confirm
						</span>
					</div>
					<div class="wrap-input100 validate-input"  data-validate = "Password Confirm is required">
						<input class="input100" minlength="6" onkeyup="Check_form('confirm_password','message_confirm_password','this')" maxlength="15" type="password" id="confirm_password" required="required" name="confirm_password" >
						<span class="focus-input100"></span>
					</div>
					<div>
						<span id = "message_confirm_password" style="color:red"> </span>
					</div>
					<div class="p-t-31 p-b-9">
						<span class="txt1">
							Email
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "Email is required">
						<input class="input100" id="email" onkeyup="Check_form('email','message_email','this')" placeholder="name@example.com" required="required" type="email" name="email" >
						<span class="focus-input100"></span>
					</div>
					<div>
						<span id = "message_email" style="color:red"> </span>
					</div>
					
					<div class="container-login100-form-btn m-t-17">
						<button class="login100-form-btn" submit="return validateForm()" >
							Sign up
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="dropDownSelect1"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>


</body>
</html>

