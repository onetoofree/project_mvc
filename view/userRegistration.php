<?php 

require '../model/db_connect.php';
session_start();

if(isset($_POST['register']))
	{
		require '../controller/registration.php';
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Registration Form</title>
	<script src="http://code.jquery.com/jquery-latest.min.js" charset="utf-8"></script>
	<?php include 'css/css.html'; ?>
</head>


<body>
<div class="form">
<h1>User Registration Page</h1>

<form action="userRegistration.php" method="post">
<div class="field-wrap">	
<input type="text" placeholder = "Username *" name="username">
</div>
<div class="field-wrap">
<input type="text" placeholder = "Email Address *" name="email">
</div>
<div class="field-wrap">
<input type="password" placeholder = "Password *" name="password">
</div>
<button type="submit" class="button button-block" name="register" />Register</button>
</form>


</div>
</body>
</html>
