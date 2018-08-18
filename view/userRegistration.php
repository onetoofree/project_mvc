<?php 

require '../model/db_connect.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['register']))
	{
		require '../controller/registration.php';
	}

	elseif (isset($_POST['login'])) 
	{
        
        require 'login.php';
        
    }

}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Registration Form</title>
	<script src="http://code.jquery.com/jquery-latest.min.js" charset="utf-8"></script>
	<!-- <?php include 'css/css.html'; ?> -->
</head>


<body>
<h1>User Registration Page</h1>

<form action="userRegistration.php" method="post">
Username: <input type="text" name="username"><br>
Email Address: <input type="text" name="email"><br>
Password: <input type="password" name="password"><br>
<button type="submit" name="register" />Register</button>
<!-- <input type="submit" name="register"> -->
</form>



</body>
</html>
