<?php
/*
Subject Code:   INT322A 
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

   if($_GET)
   {
      include_once 'library.php';
      $deletedStatus = "";
      $switch = "";
      $deleteId = $_GET["deleteId"];
   
      $db = new ConnetDb;
      $link = $db->getlink();

      /*Gets the current status of the 'deleted' row for the specified record*/
      $sql_query = "SELECT * FROM inventory WHERE id = '$deleteId' ";
  
      $result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));

      while($row = mysqli_fetch_assoc($result))
      {   
         $switch = ($row['deleted'] == "y") ? 'n' : 'y';             
      }
   
      /*Update the record with altered value from 'n' to 'y' or vice versa*/

      $sql_query = "UPDATE inventory SET deleted = '$switch' 
                 WHERE id = '$deleteId' ";
      $result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
   }
   header("Location: view.php"); 
?>

