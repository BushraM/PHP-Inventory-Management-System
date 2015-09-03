<?php
/*
Subject Code:   INT322A 
Student Name:   Bushra Mohamed
Date Submitted: Thursday, April 3 2014

Name Bushra Mohamed

Student ID 018-633-123

*/

   if( !isset($_SERVER['HTTPS']) )
   { 
      header("Location: https://server.com/assign2.2/view.php");
      die();
   }

   session_start();

   if( !isset($_SESSION["username"]) )  //the user is not logged in..redirecting to login. 
   {
      header("Location: login.php");
      die();
   }
      
   $id_num = "id"; //default sort by id column
   if( $_GET && isset($_GET["sort"]) ) // the user clicked on one of the column names to sort.
   {                                   //this if statement uses recursion to set the sort.
      setcookie("sortby",$_GET["sort"],time()+60*60*24*30);     
      unset($_GET["sort"]);
      header("Location: view.php"); 
      
   }
   else  if( isset($_COOKIE["sortby"]) )  //The last sort by used 
   { 
      $order_by = $_COOKIE["sortby"];
   }
   else //first time visitor
   {
      setcookie("sortby",$id_num,time()+60*60*24*30);
      $order_by = $id_num;
   }

   if( isset($_POST["description"]) ) //Session for search criteria
   {
      $_POST["description"] = htmlentities($_POST["description"]);
      $_SESSION["description"] = trim($_POST["description"]);  
   }
   else if( isset($_SESSION["description"]) )
   {
      $search = htmlentities($_SESSION["description"]);
   }
   else // first time visitor
   {
      $_SESSION["description"] = "";  
   }

   if( $_GET && isset($_GET["viewall"]) ) //user clicked on 'View All' 
   { 
      $_SESSION["description"] = "";
   }
    
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="mycss.css">
<title>View Inventory</title>
</head>

<body>
<?php include_once 'library.php'; //common classes are held in this source library 
   $topheader = new Headers; //printing initial headers. Classes available in 'library.php for more info'
   $menuNav = new Menu;
?>
   <table>
      <tr>
         <th><a href="view.php?sort=<?php echo 1//ID; ?>">ID</a></th>
         <th><a href="view.php?sort=<?php echo 2//Name; ?>">Item <br/> Name</a></th>
         <th><a href="view.php?sort=<?php echo 3//description; ?>">Description</a></th>
         <th><a href="view.php?sort=<?php echo 4//SupplierCode; ?>">Supplier</a></th>
         <th><a href="view.php?sort=<?php echo 5//cost; ?>">Cost</a></th>
         <th><a href="view.php?sort=<?php echo 6//price; ?>">Price</a></th>
         <th><a href="view.php?sort=<?php echo 7//on_hand; ?>">Number on Hand</a></th>
         <th><a href="view.php?sort=<?php echo 8//reorder_level; ?>">Reorder Level</a></th>
         <th><a href="view.php?sort=<?php echo 9//back_order; ?>">On Back order</a></th>
         <th><a href="view.php?sort=<?php echo 10//delete; ?>">Delete/Restore</a></th>
      </tr>  

<?php
   if(isset($_POST["description"]))
   {
      $_POST["description"] = trim($_POST["description"]);
   }

   if( isset($_SESSION["description"]) ) //if the user has requested a search description
   {
      $db = new ConnetDb;
      $link = $db->getlink();

      $search = mysqli_escape_string($link,$_SESSION["description"]);

      $sql_query = "SELECT * FROM inventory WHERE description like '%$search%' ORDER BY $order_by ";  
      $result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));
      $numofrows = mysqli_num_rows($result);
     
      if($numofrows)
      {
         while($row = mysqli_fetch_assoc($result))
         {      
?>          <tr>
               <td><a href="add.php?modify=<?php echo $row['id'];?>"><?php echo $row['id']; ?></a></td>
               <td><?php echo $row['itemName']; ?></td>
               <td><?php echo $row['description']; ?></td>
               <td><?php echo $row['supplierCode']; ?></td>
               <td><?php echo $row['cost']; ?></td>
               <td><?php echo $row['price']; ?></td>
               <td><?php echo $row['onHand']; ?></td>
               <td><?php echo $row['reorderPoint']; ?></td>
               <td><?php echo $row['backOrder']; ?></td>
               <td><a href="delete.php?deleteId=<?php echo $row['id']; ?>"><?php if( $row['deleted'] == "y" ){ echo "Restore";}else{ echo "Delete";}?></a></td>
            </tr>     
<?php   
         }
      } //end of if $numofrows
      else
      {?>
         <tr>
            <td>No Records Found.</td>
         </tr>
<?php      
      }
   }  
?>
   </table>

<?php
   $footer = new Footer();
?>   
</body>

</html>
