<?php

session_start();

/* If login POST exec API call to have vars in scope*/
if(isset($_POST['login']))
	require("process_api_call.php");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>	
	<title>IPViking API Login Page</title>	
	<link rel="shortcut icon" href="favicon.ico" />	
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />		
</head>
<body>	
	<div id="corp-header"></div>	
	<div id="logo-header">		
		<h1 id="logo"><a href="https://ipviking.com"><span class="hidden">IPViking</span></a></h1>   			
	</div>		
	<div id="login-box" class="common">		
	
    <span class="fieldError"><?php 
    		if($error)
    			echo $error;
    ?></span>
    
		<form class="common-form" method="POST">	
			<fieldset>
				<label>Username</label>
				<input class="text-input" type="input" name="username" value="" /> 
			</fieldset>			
			<fieldset>
				<label>Password</label>
				<input class="text-input" type="password" name="password" value="" /> 
			</fieldset>								
			<fieldset class="checkbox-row">
				<input type="checkbox" name="remember" value="1" /> <small>Remember me</small>
			</fieldset>
			<input class="submit-button" type="submit" name="submit" value="Login" />
		</form>				
	</div>					
	<h6 id="footer-text"><img src="images/login/lock_icon.png" alt="" /> Secure Login</h6>
</body>
</html>