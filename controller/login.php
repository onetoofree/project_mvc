<?php
ob_start();
/* User login process, checks if user exists and password is correct */
require '../model/registrationAndLoginQueries.php';


// Escape username to protect against SQL injections
$username = $dbc->escape_string($_POST['username']);
$result = $dbc->query($checkLoginDetailsQuery);

if ( $result->num_rows == 0 ){ // User doesn't exist
    $_SESSION['message'] = "User with that username doesn't exist!";
    header("location: error.php");
}
else { // User exists
    $user = $result->fetch_assoc();
    
    if(md5($_POST['password']) == $user['password'])
    {
    
        $_SESSION['username'] = $user['username'];
        
        // user is logged in
        $_SESSION['logged_in'] = true;

        header("location: profile.php");
        echo "Login success";
    }
    else {
        $_SESSION['message'] = "You have entered wrong password, try again! ";
        
        header("location: error.php");
    }
}
?>
