<?php
/**
 * Build a simple HTML page with multiple providers, opening provider authentication in a pop-up.
 */

require 'vendor/autoload.php';
require 'config.php';

use Hybridauth\Hybridauth;

$hybridauth = new Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Welcome</title>
	<meta charset="UTF-8">
</head>
<body>
	<?php if (isset($_SESSION)) : ?>
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
		$firstname='';
		$lastname='';
		
		$sql="select user.id, profile.firstname, profile.lastname from user
		inner join profile on user.id = profile.id_user
		where user.id = '".$_SESSION['id_user']."' and user.specific_id = '".$_SESSION['specific_id']."'";
		if ($result = $conn->query($sql)){
			if($result->num_rows != 0){
				$row = $result->fetch_assoc();
				$firstname = $row['firstname'];
				$lastname = $row['lastname'];
			} else {
				echo "User not found <br>";
			}
		} else {
			echo "User found error: " . $conn->error;
		}
	?>
		<h1>Welcome</h1>
		<ul>
				<li>
					<strong><?php echo $firstname." ".$lastname; ?></strong>
					<span>(<a href="logout.php">Log Out</a>)</span>
				</li>
		</ul>
	<?php endif; ?>
	<?php $conn->close(); ?>
</body>

