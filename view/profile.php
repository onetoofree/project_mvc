<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  header("location: error.php");    
}
else {
    // Makes it easier to read
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $active = $_SESSION['active'];
}
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Welcome <?= $email ?></title>
  <?php include 'css/css.html'; ?>
</head>

<body>
  <div class="form">

          <h1 name="welcomeMessage">Welcome</h1>
          
          <p>
          <?php 
     
          // Display message about account verification link only once
        //   if ( isset($_SESSION['message']) )
        //   {
        //       echo $_SESSION['message'];
              
        //       // Don't annoy the user with more messages upon page refresh
        //       unset( $_SESSION['message'] );
        //   }
          
          ?>
          </p>
          
          <?php
          
          // Keep reminding the user this account is not active, until they activate
          /* if ( !$active ){
              echo
              '<div class="info">
              Account is unverified, please confirm your email by clicking
              on the email link!
              </div>';
          } */
          
          ?>
          
          <h2 name="userGreeting"><?php echo $username; ?></h2>
          <p><?= $email ?></p>
          
          <!-- <a href="upload.php"><button class="button button-block" name="upload"/>Upload</button></a> -->
          <a href="uploadImages.php"><button class="button button-block" name="upload"/>Upload</button></a>
          <a href="searchImages.php"><button class="button button-block" name="search"/>Search</button></a>
          <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>

    </div>
    
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
