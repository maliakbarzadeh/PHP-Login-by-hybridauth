<?php
/**
 * Build a simple HTML page with multiple providers, opening provider authentication in a pop-up.
 */

require 'vendor/autoload.php';
require 'config.php';

use Hybridauth\Hybridauth;

$hybridauth = new Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();
$firstname='';
$lastname='';
$email='';
$username='';
if (isset($_SESSION['id_user']) && isset($_SESSION['specific_id'])) {
        
require 'connectSQL.php';		
		$sql="select user.id, user.username, profile.firstname, profile.lastname, profile.email from user
		inner join profile on user.id = profile.id_user
		where user.id = '".$_SESSION['id_user']."' and user.specific_id = '".$_SESSION['specific_id']."'";
		if ($result = $conn->query($sql)){
			if($result->num_rows != 0){
				$row = $result->fetch_assoc();
				$firstname = $row['firstname'];
				$lastname = $row['lastname'];
                                $email = $row['email'];
                                $username = $row['username'];
			} else {
				echo "User not found.";
			}
		} else {
			echo "User found error: " . $conn->error;
		}

$conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login Page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--===============================================================================================-->	
        <link rel="stylesheet" type="text/css" href="./vendor/bootstrap/css/bootstrap.css">
        <!--===============================================================================================-->	
        <link rel="stylesheet" type="text/css" href="./css/all.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="./vendor/bootstrap/css/bootstrap-grid.css">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="main.css">
        

    </head>
    <body>
        <div class="container">
            <nav class="navbar navbar-expand-md bg-dark navbar-dark">
                <!-- Brand -->
                <a class="navbar-brand" href="#">Logo</a>

                <!-- Links -->
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link 1</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="#">Link 2</a>
                  </li>
                </ul>
                <ul id="signBar" name="signBar" class="navbar-nav ml-auto">
                    <?php if(!isset($_SESSION['id_user']) && !isset($_SESSION['specific_id'])) : ?>
                    <li  class="nav-item">
                        <a  class="nav-link" href="#">
                            <!-- Button to Open the login -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-login">
                              Sign in \ Sing up
                            </button>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['id_user']) && isset($_SESSION['specific_id'])) : ?>
                    <li id="loginBar" name="loginBar" class="nav-item"  >
                        <strong class="text-light"><?php echo $firstname." ".$lastname; ?></strong>
                            <a class="btn btn-primary" href="logout.php">Log Out</a>
                            <?php if($username === null): ?>
                            <a class="btn btn-danger" data-toggle="modal" data-target="#modal-login" >Complete the profile</a>
                            <?php endif; ?>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <!-- The Modal login-->
            <div class="modal" id="modal-login">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Sign in \ Sign up</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <?php if(!isset($_SESSION['id_user']) && !isset($_SESSION['specific_id'])) : ?>
                                    <!-- Login -->
                                    <div id="loginbox"  style="margin: 5px; " class="card card-img col-sm">
                                        <div style="margin-top: 10px;" class="card-header">
                                            <div class="card-title row">
                                                <span class="col-sm" > 
                                                    <strong> Sign In With </strong>
                                                </span>
                                                <span class="col-sm text-right">
                                                Not a member? ->
                                                <button type="button" onclick="$('#loginbox').hide(); $('#signupbox').show();"  class="btn btn-info">
                                                    <i class="icon-hand-right" ></i> &nbsp Sign Up
                                                </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div style="margin-top:10px;" class="col-sm-6 col-sm-offset-3">
                                                <!-- Login by username and password -->
                                                <div style="padding-top:15px" class="card-body" >                            
                                                    <form id="loginform" name="loginform" class="form-horizontal" role="form" method="POST">
                                                        <label for="loginUsername" class="control-label">Username</label>
                                                        <div style="margin-bottom: 25px" class=" form-group input-icons">
                                                            <i class="fa fa-user icon"></i>
                                                            <input id="loginUsername"  type="text" class="form-control input-field" name="loginUsername" required placeholder="Username">                                        
                                                            <small id="loginUsernameHelp" style="color:red" class="form-text"></small>
                                                        </div>
                                                        <label for="loginPassword" class="control-label">Password</label>
                                                        <div style="margin-bottom: 25px" class="form- input-icons">
                                                            <i class="fa fa-key icon"></i>
                                                            <input id="loginPassword" type="password" class="form-control input-field" name="loginPassword" required placeholder="Password">
                                                        </div>
                                                        <div class="input-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                                                                </label>
                                                            </div>
                                                        </div>
                                                            <div style="margin-top:10px" class="form-group">
                                                                <!-- Button -->
                                                                <div class="col-sm-12 controls">
                                                                    <button id="btn-login" type="submit" onclick="Loginform($('#loginform'))" class="btn btn-success btn-block">Login  </button>
                                                                </div>
                                                            </div>
                                                    </form>     
                                                </div>                     
                                            </div> 
                                            <!-- End login username and password -->
                                            <!-- Login by social network's -->
                                            <div id="loginsocialbox" style="margin-top:20px;" class="col-sm-6 col-sm-offset-3">
                                            <?php foreach ($hybridauth->getProviders() as $name) : ?>
                                                <?php if (!isset($adapters[$name]) & $name=='Facebook') : ?>
                                                <div class="btn  btn-block">
                                                    <a href="#" class="btn btn-primary btn-block" onclick="javascript:auth_popup('<?php print $name ?>');">
                                                        <img class="img-thumbnail float-left" src="images/icons/icon-facebook.png" alt="FACEBOOK">
                                                            Login with Facebook
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!isset($adapters[$name]) & $name=='Google') : ?>
                                                <div class="btn btn-block">
                                                    <a href="#" class="btn btn-success btn-block" onclick="javascript:auth_popup('<?php print $name ?>');">
                                                        <img class="img-thumbnail float-left" src="images/icons/icon-google.png" alt="GOOGLE">
                                                            Login with Google
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!isset($adapters[$name]) & $name=='Discord') : ?>
                                                <div class="btn btn-block">                                                    
                                                    <a href="#" class="btn btn-info btn-block" onclick="javascript:auth_popup('<?php print $name ?>');">
                                                        <img class="img-thumbnail float-left" src="images/icons/icon-discord.png" alt="DISCORD">
                                                            Login with Discord
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!isset($adapters[$name]) & $name=='Reddit') : ?>
                                                <div class="btn btn-block">
                                                    <a href="#" class="btn btn-danger btn-block" onclick="javascript:auth_popup('<?php print $name ?>');">
                                                        <img class="img-thumbnail float-left" src="images/icons/icon-reddit.png" alt="Reddit">
                                                            Login with Reddit
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            </div>
                                            <!-- End login social network's -->
                                        </div>
                                    </div>
                                    <!-- End login  -->
                                    <!-- Sign up new user -->
                                    <div id="signupbox" style="margin: 5px; display: none;" class="card card-img col-sm">
                                        <div style="margin-top:10px">
                                            <div class="card-header">
                                                <div class="card-title row">
                                                    <span class="col-sm" > 
                                                        <strong> Sign Up </strong>
                                                    </span>
                                                    <span class="col-sm text-right">
                                                    Are you a member? ->
                                                    <button type="button" onclick="$('#signupbox').hide(); $('#loginbox').show();"  class="btn btn-info">
                                                        <i class="icon-hand-right" ></i> &nbsp Login
                                                    </button>
                                                    </span>
                                                </div>
                                            </div>  
                                            <div class="card-body" >
                                                <form id="signupform" class="form-horizontal" role="form" method="POST">
                                                    <label for="firstname" class="control-label">First Name</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-user-tie icon"></i>
                                                        <input type="text" class="form-control input-field" id="firsname" name="firstname" required placeholder="First Name">
                                                    </div>
                                                    <label for="lastname" class="control-label">Last Name</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-user-circle icon"></i>
                                                        <input type="text" class="form-control input-field" id="lastname" name="lastname" required placeholder="Last Name">
                                                    </div>
                                                    <label for="username" class="control-label">Username</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-user icon"></i>
                                                        <input type="text" class="form-control input-field" onkeyup="Check_form('username','usernameHelp','this'); checkReg();" id="username" required name="username" placeholder="Username">
                                                        <small id="usernameHelp" style="color:red" class="form-text"></small>
                                                    </div>
                                                    <label for="password" class="control-label">Password</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-key icon"></i>
                                                        <input type="password" class="form-control input-field" id="password" onkeyup="Check_form('confirm_password','confirm_passwordHelp','this'); checkReg();" required name="password" placeholder="Password">
                                                    </div>
                                                    <label for="confirm_password" class="control-label">Confirm Password</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-key icon"></i>
                                                        <input type="password" class="form-control input-field" id="confirm_password" onkeyup="Check_form('confirm_password','confirm_passwordHelp','this'); checkReg();" required name="confirm_password" placeholder="Confirm Password">
                                                        <small id="confirm_passwordHelp" style="color:red" class="form-text"></small>
                                                    </div>
                                                    <label for="email" class="control-label">Email</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-envelope icon"></i>
                                                        <input type="email" class="form-control input-field" onkeyup="Check_form('email','emailHelp','this'); checkReg();" required  id="email" name="email" placeholder="Email">
                                                        <small id="emailHelp" style="color:red" class="form-text"></small>
                                                    </div>
                                                    <div class="form-group">
                                                        <!-- Button -->                                        
                                                        <div class="text-center" >
                                                            <button id="btn-signup" onclick="Regform($('#signupform'))" onkeyup="checkReg()" onmousemove="checkReg()" on type="submit" class="btn btn-info btn-block"><i class="icon-hand-right"></i> &nbsp Sign Up</button>
                                                        </div>
                                                    </div>
                                                    <div style="border-top: 1px solid #999; padding-top:20px"  class="form-group">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End sign up new user -->
                                    <?php endif; ?>
                                    <?php if(isset($_SESSION['id_user']) && isset($_SESSION['specific_id'])) : ?>
                                    <!-- Continue sing up social network's -->
                                    <div id="signupSocialbox" style="margin: 5px;" class="card card-img col-sm">
                                        <div style="margin-top:10px">
                                            <div class="card-header">
                                                <div class="card-title row">
                                                    <span class="col-sm" > 
                                                        <strong> You must fill out the form below to complete the registration.  </strong>
                                                    </span>
                                                </div>
                                            </div>  
                                            <div class="card-body" >
                                                <form id="signupSocialform" class="form-horizontal" role="form" method="POST">
                                                    <label for="firstname" class="control-label" >First Name</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-user-tie icon"></i>
                                                        <input type="text" class="form-control input-field" id="firsnameSocial" name="firstnameSocial" value="<?php echo $firstname; ?>" required placeholder="First Name">
                                                    </div>
                                                    <label for="lastname" class="control-label">Last Name</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-user-circle icon"></i>
                                                        <input type="text" class="form-control input-field" id="lastnameSocial" name="lastnameSocial" value="<?php echo $lastname; ?>" required placeholder="Last Name">
                                                    </div>
                                                    <label for="username" class="control-label">Username</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-user icon"></i>
                                                        <input type="text" class="form-control input-field" onkeyup="Check_form('usernameSocial','usernameSocialHelp','this'); checkRegSocial();" id="usernameSocial" required name="usernameSocial" placeholder="Username">
                                                        <small id="usernameSocialHelp" style="color:red" class="form-text"></small>
                                                    </div>
                                                    <label for="password" class="control-label">Password</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-key icon"></i>
                                                        <input type="password" class="form-control input-field" id="passwordSocial" onkeyup="Check_form('confirm_passwordSocial','confirm_passwordSocialHelp','this'); checkRegSocial();" required name="passwordSocial" placeholder="Password">
                                                    </div>
                                                    <label for="confirm_password" class="control-label">Confirm Password</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-key icon"></i>
                                                        <input type="password" class="form-control input-field" id="confirm_passwordSocial" onkeyup="Check_form('confirm_passwordSocial','confirm_passwordSocialHelp','this'); checkRegSocial();" required name="confirm_passwordSocial" placeholder="Confirm Password">
                                                        <small id="confirm_passwordSocialHelp" style="color:red" class="form-text"></small>
                                                    </div>
                                                    <label for="email" class="control-label">Email</label>
                                                    <div class="form-group input-icons">
                                                        <i class="fa fa-envelope icon"></i>
                                                        <input type="email" class="form-control input-field" value="<?php echo $email; ?>" <?php if($email !== '') {echo "disabled";} ?>  onkeyup="Check_form('emailSocial','emailSocialHelp','this'); checkRegSocial();" required  id="emailSocial" name="emailSocial" placeholder="Email">
                                                        <small id="emailSocialHelp" style="color:red" class="form-text"></small>
                                                    </div>
                                                    <div class="form-group">
                                                        <!-- Button -->                                        
                                                        <div class="text-center" >
                                                            <button id="btn-signupSocial" onclick="RegSocialform($('#signupSocialform'))" onkeyup="checkRegSocial()" onmousemove="checkRegSocial()" on type="submit" class="btn btn-info btn-block"><i class="icon-hand-right"></i> &nbsp Register</button>
                                                        </div>
                                                    </div>
                                                    <div style="border-top: 1px solid #999; padding-top:20px"  class="form-group">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End continue sing up social network's -->
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--===============================================================================================-->
        <script type="text/javascript" src="./vendor/jquery/jquery-3.6.0.js"></script>
        <!--===============================================================================================-->
        <script type="text/javascript" src="./vendor/jquery/jquery-3.6.0.min.js"></script>
         <!--===============================================================================================-->
         <script type="text/javascript" src="./vendor/jquery/jquery-3.6.0.slim.js"></script>
        <!--===============================================================================================-->
        <script type="text/javascript" src="./vendor/jquery/jquery-3.6.0.slim.min.js"></script>
        <!--===============================================================================================-->
        <script src="./vendor/bootstrap/js/bootstrap.js"></script>
        <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
        <!--===============================================================================================-->
        <script src="main.js"></script>
    </body>
</html>

