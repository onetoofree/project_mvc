<?php 

require '../model/db_connect.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (isset($_POST['login'])) 
	{
        
    require '../controller/login.php';
        
  }
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Login Form</title>
	<script src="http://code.jquery.com/jquery-latest.min.js" charset="utf-8"></script>
	<?php include 'css/css.html'; ?>
</head>
<body>
<div class="search-text">
<div class="form">

         <div id="login">   
          <h1>Login</h1>
          
          <form action="userLogin.php" method="post" autocomplete="off">
          
            <div class="field-wrap">
            <label>
			<span class="req"></span>
            </label>
            Username
            <input type="text" placeholder = "Username *" autocomplete="off" name="username"/>
          </div>
          
          <div class="field-wrap">
            <label>
              <span class="req"></span>
            </label>
            Password
            <input type="password" placeholder = "Password *" autocomplete="off" name="password"/>
          </div>
          
          <button class="button button-block" name="login" />Log In</button>
          
          </form>
        </div>
      </div>      
</div>
</div>
</body>
</html>
