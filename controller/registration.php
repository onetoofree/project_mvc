<?php
ob_start();
require '../model/registrationAndLoginQueries.php';

/* Registration process, inserts user info into the database 
   and sends account confirmation email message
 */

// Set session variables to be used on profile.php page
$_SESSION['email'] = $_POST['email'];
$_SESSION['username'] = $_POST['username'];
$_SESSION['password'] = $_POST['password'];

// Escape all $_POST variables to protect against SQL injections
$username = $dbc->escape_string($_POST['username']);
$email = $dbc->escape_string($_POST['email']);
//$password = $dbc->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$password = $dbc->escape_string($_POST['password']);
$hash = $dbc->escape_string( md5( rand(0,1000) ) );
$userid = mysqli_insert_id($dbc);
$active = 0;
      
// Check if user with that email already exists
$result = $dbc->query("SELECT * FROM user WHERE email='$email'") or die($dbc->error());

// We know user email exists if the rows returned are more than 0
if ( $result->num_rows > 0 ) {
    
    $_SESSION['message'] = 'User with this email already exists!';
    //header("location: error.php");
    
}
else { // Email doesn't already exist in a database, proceed...

    // active is 0 by DEFAULT (no need to include it here)
    //$sql = "INSERT INTO users (first_name, last_name, email, password, hash) " 
    //        . "VALUES ('$first_name','$last_name','$email','$password', '$hash')";

    // $sql = "INSERT INTO user (userid, username, email, password, active) " 
    //         . "VALUES ($userid,'$username','$email','$password', '$active')";
    
    $passwordmd5 = md5($password);
    // $sql = "INSERT INTO user (userid, username, email, password, active) " 
    //         . "VALUES ($userid,'$username','$email','$passwordmd5', '$active')";

            $sql = $insertNewRegisteredUserQuery;

            //echo "New record has id: " . $userid;
            header("location: ../view/home.php");
            //mkdir('../../../../projectUsers/userid/', 0777, true);
            //mkdir('/Users/peds/projectUsers', 0777, true);

    // Add user to the database
    if ( $dbc->query($sql) ){

        $_SESSION['active'] = 0; //0 until user activates their account with verify.php
        $_SESSION['logged_in'] = true; // So we know the user has logged in
        $_SESSION['message'] =
                
                 "Confirmation link has been sent to $email, please verify
                 your account by clicking on the link in the message!";

        // Send registration confirmation link (verify.php)
        /* $to      = $email;
        $subject = 'Account Verification ( clevertechie.com )'; */
        /* $message_body = '
        Hello '.$first_name.',

        Thank you for signing up!

        Please click this link to activate your account:

        http://localhost/login-system/verify.php?email='.$email.'&hash='.$hash;   */

        //mail( $to, $subject, $message_body );

        //header("location: profile.php"); 

    }

    /* else {
        $_SESSION['message'] = 'Registration failed!';
        header("location: error.php");
    } */

}
?>