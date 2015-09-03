<?php
/* Subject Code:   INT322A 
   Student Name:   Bushra Mohamed
   Date Submitted: Thursday, April 3 2014

   Name Bushra Mohamed

   Student ID 018-633-123 
*/
   session_start();

   if(!isset($_SESSION["username"]))  //the user is not logged in..redirecting to login. 
   {
      header("Location: login.php");
      die();
   }

   unset($_SESSION["username"]);
   unset($_SESSION["role"]);
   unset($_SESSION["description"]);

   session_destroy();
   header("Location: login.php");

?>
