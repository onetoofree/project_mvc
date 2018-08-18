<?php
// require '/Library/WebServer/Documents/project/pages/registration.php';
// $_SESSION['email'] = $_POST['email'];
// $_SESSION['username'] = $_POST['username'];
// $_SESSION['password'] = $_POST['password'];
// Set session variables to be used on profile.php page
$_SESSION['email'] = $_POST['email'];
$_SESSION['username'] = $_POST['username'];
$_SESSION['password'] = $_POST['password'];

// Escape all $_POST variables to protect against SQL injections
//$username = $dbc->escape_string($_POST['username']);
$email = $dbc->escape_string($_POST['email']);
//$password = $dbc->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$password = $dbc->escape_string($_POST['password']);
$hash = $dbc->escape_string( md5( rand(0,1000) ) );
$userid = mysqli_insert_id($dbc);
$active = 0;

$username = $dbc->escape_string($_POST['username']);
$passwordmd5 = md5($password);

$checkLoginDetailsQuery = "SELECT * FROM user WHERE username='$username'";

$insertNewRegisteredUserQuery = "INSERT INTO user 
(userid, username, email, password, active) 
VALUES ($userid,'$username','$email','$passwordmd5', '$active')";


?>