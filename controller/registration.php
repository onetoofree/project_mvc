<?php
ob_start();
require '../model/registrationAndLoginQueries.php';

// Escape all $_POST variables to protect against SQL injections
$username = $dbc->escape_string($_POST['username']);
$email = $dbc->escape_string($_POST['email']);
$password = $dbc->escape_string($_POST['password']);
$hash = $dbc->escape_string( md5( rand(0,1000) ) );
$userid = mysqli_insert_id($dbc);
$active = 0;
      
// Check if user with that email already exists
$result = $dbc->query("SELECT * FROM user WHERE email='$email' OR username='$username'") or die($dbc->error());

//check if any fields are empty
if(strlen($username) == 0 || strlen($email) == 0 || strlen($password) == 0)
{
    $_SESSION['message'] = 'Username, Email and Password are mandatory.  Please check if any are missing and try again';
    header("location: error.php");
}

// We know user email exists if the rows returned are more than 0
elseif ( $result->num_rows > 0 ) {
    
    $_SESSION['message'] = 'User with this email or username already exists!';
    header("location: error.php");
    
}
else { // Email doesn't already exist in a database, proceed...
    
    $passwordmd5 = md5($password);
    $sql = $insertNewRegisteredUserQuery;

    // Add user to the database
    if ( $dbc->query($sql) ){
    $_SESSION['logged_in'] = true; // So we know the user has logged in
    header("location: profile.php"); 
    }

}
?>