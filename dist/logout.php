<?php
   session_start();
   
   // Delete all session variables
   if(session_destroy()) {
      // Redirect user to login page
      header("location: login.php");
   }
?>