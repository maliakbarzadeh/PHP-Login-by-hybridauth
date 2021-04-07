// Login social networks
function auth_popup(provider) {
    // replace 'path/to/hybridauth' with the real path to this script
    var authWindow = window.open('callback.php?provider=' + provider, 'authWindow', 'width=600,height=400,scrollbars=yes');
		window.closeAuthWindow = function () {
			authWindow.close();
			$.ajax({
				type: 'POST',
				url: 'login.php',
				error: function (response){
					alert(response);
				}
			});
			location.reload();
		};
		return false;
    }
			
//check registrtion form
function Check_form(inputname,divid,inputvalue){
	if(divid === 'confirm_passwordHelp'){
		var formvalue = "password="+document.getElementById('password').value
			+"&confirm_password="+document.getElementById('confirm_password').value; 
	}
	if(divid === 'usernameHelp'){
		var formvalue = "username="+document.getElementById('username').value; 
	}	
	if(divid === 'emailHelp'){
		var formvalue = "email="+document.getElementById('email').value; 
	}
	if(divid === 'confirm_passwordSocialHelp'){
		var formvalue = "password="+document.getElementById('passwordSocial').value
			+"&confirm_password="+document.getElementById('confirm_passwordSocial').value; 
	}
	if(divid === 'usernameSocialHelp'){
		var formvalue = "username="+document.getElementById('usernameSocial').value; 
	}	
	if(divid === 'emailSocialHelp'){
		var formvalue = "email="+document.getElementById('emailSocial').value; 
	}
	var loadingmessage = '';    
	var xmlhttp;
	if (window.XMLHttpRequest){
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState<4){
				document.getElementById(divid).innerHTML=loadingmessage;
			}            
			else if (xmlhttp.readyState===4 && xmlhttp.status===200){
				document.getElementById(divid).innerHTML=xmlhttp.responseText;
			}
		};
	xmlhttp.open("POST","validate.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(formvalue);
}

// Login by username and password
function Loginform(form){
	var dataString = form.serialize();
	$.ajax({
		type: 'POST',
		url: 'login.php',
		data: dataString,
		success: function (response) {
			if(response === 'success')
				alert('Welcome');
			else
				alert(response);
		},
		error: function (response){
			alert(response);
		}
	});
}


// Registration with username and password
function Regform(form){
	var dataString = form.serialize();
	$.ajax({
		type: 'POST',
		url: 'signup.php',
		data: dataString,
		success: function (response) {
			if(response === 'success')
				alert('Welcome');
			else
				alert(response);
		},
		error: function (response){
			alert(response);
		}
	});
}


// Complete registration
function RegSocialform(form){
	var dataString = form.serialize();
	$.ajax({
		type: 'POST',
		url: 'signup.php',
		data: dataString,
		success: function (response) {
			if(response === 'success')
				alert('Thank you, registration is complete.');
			else
				alert(response);
		},
		error: function (response){
			alert(response);
		}
	});
}
			  
	
// Check Registration Form	
function checkReg(){
	if (document.getElementById('confirm_passwordHelp').innerHTML === '' && 
		document.getElementById('usernameHelp').innerHTML === '' &&
		document.getElementById('emailHelp').innerHTML === '')
		document.getElementById('btn-signup').removeAttribute("disabled");
	else
		document.getElementById('btn-signup').disabled="disabled";
}


// Check Complete Registration
function checkRegSocial(){
	if (document.getElementById('confirm_passwordSocialHelp').innerHTML === '' && 
		document.getElementById('usernameSocialHelp').innerHTML === '' &&
		document.getElementById('emailSocialHelp').innerHTML === '')
		document.getElementById('btn-signupSocial').removeAttribute("disabled");
	else
		document.getElementById('btn-signupSocial').disabled="disabled";
}