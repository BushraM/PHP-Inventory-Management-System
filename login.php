<?php
/* Subject Code:   INT322A 
   Student Name:   Bushra Mohamed
   Date Submitted: Thursday, April 3, 2014

   Name Bushra Mohamed

   Student ID 018-633-123 
*/
   session_start();

   if( !isset($_SERVER['HTTPS']) )
   { 
      header("Location: https://server.com/assign2.2/login.php");
   }
   
   if( isset($_SESSION["username"]) )  //the user is already logged in. 
   {
      header("Location: view.php");
      die();
   }  
   
   include_once("library.php");
   $loginError = "";
   $found = false;

   if( $_POST && !isset($_POST["Email"]) )
   {
      if( isset($_POST["username"]) && isset($_POST["password"]) ){
         $username = $_POST["username"]; 
         $password = $_POST["password"]; 
        
         //connects to the database. (ConnectDB class implementation can be found at library.php)

         $db = new ConnetDb;
         $link = $db->getlink();

         $username = mysqli_real_escape_string($link,$username);
         $password = mysqli_real_escape_string($link,$password);

         $sql_query = "SELECT * FROM users WHERE username = '$username'";
         $result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
       
         while( $row = mysqli_fetch_assoc($result) )
         {               
            if( $row["username"] == $username )
            {  
               if( crypt($password,$row["password"]) == $row["password"] )
               {    
                 $found = true;
                 $_SESSION["username"] = $username;
                 $_SESSION["role"] = $row["role"];
               }                      
            }  
         } 
  
         if( !$found )
         { 
            $error = true;
            $loginError = "Please enter a valid username and password"; 
         }
    
         if( !$error )
         {
            header("Location: view.php");
         }
         mysqli_close($link); 
      } 
   }


   else if( $_POST && isset($_POST["Email"]) )
   {
      $db = new ConnetDb;
      $link = $db->getlink();

      $usrname = mysqli_real_escape_string($link,$_POST["Email"]);
   
      $sql_query = "SELECT * FROM users WHERE username = '$usrname'";
      $result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
       
      while( $row = mysqli_fetch_assoc($result) )
      {               
         if( $row["username"] == $usrname )
         {  
            $hint = $row["passwordHint"];
            mail(
               "int322@localhost", // E-Mail address
               "Forget Password", // Subject
               "User name: $usrname hint: $hint", // Message
               "From: Admin <int322@localhost>\r\nReply-to: Admin <int322@localthost>"  // Additional Headers
            );                 
         }  
      }  
   }   
   $topheader = new Headers;
?>

<html>
<head>
   <link rel="stylesheet" type="text/css" href="mycss.css">
   <title>Login</title>
</head>

<body>
  <h1>Login</h1>
<?php if( !$_GET && !isset($GET["forget"]) ) //the forget password linked had been clicked.
      {?>
  <form action="login.php" method="POST">
    <table>
      <tr>
        <td>Username:<input type="text" name="username" /></td>
      </tr>
      <tr>
        <td>Password: <input type="password" name="password" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><button type="Submit">Login</button></td>
      </tr>
    </table>
  </form>
   <p class="red"><?php echo $loginError; ?></p>
   <a href="login.php?forget=<?php echo 'y';?>">Forget your password?</a>
<?php }//end of if 
  else{?>
  <form action="login.php" method="POST">
    <table>
      <tr>
        <td>Email:<input type="text" name="Email" /></td>
      </tr>
        <td colspan="2" align="center"><button type="Submit">Submit</button></td>
      </tr>
    </table>
  </form>  
<?php } // end of else ?>   
     
   
   
</body>

</html>


