<?php
/*
Subject Code:   INT322A 
Student Name:   Bushra Mohamed
Date Submitted: Thursday, April 3 2014

Name: Bushra Mohamed

*/

session_start();

if( !isset($_SERVER['HTTPS']) )
{ 
   header("Location: https://server.com/assign2.2/add.php");
   die();
}

if(!isset($_SESSION["username"]))  //the user is not logged in..redirecting to login. 
{
   header("Location: login.php");
   die();
}

include_once 'library.php'; //common classes and their functions are held in this source library 

$ERRORS = false;  //if true, the user has entered invalid data in at lest one field  

//Error Identifiers for each field 
$itemname_error = "";
$desc_error= "";
$supplier_error ="";
$cost_error = "";
$price_error = "";
$onhand_error = "";
$reorderpoint_error = "";

if( $_POST )
{
   $_POST["itemName"] = trim($_POST["itemName"]);
   $_POST["description"] = trim($_POST["description"]);
   $_POST["supplierCode"] = trim($_POST["supplierCode"]);
   $_POST["cost"] = trim($_POST["cost"]);   
   $_POST["price"] = trim($_POST["price"]);
   $_POST["onHand"] = trim($_POST["onHand"]); 
   $_POST["reorderPoint"] = trim($_POST["reorderPoint"]);
   $backOrder = "n";
   $modify = false;

   //The following variables are used for storing into database..
   $itemName = $_POST["itemName"]; 
   $description= $_POST["description"];
   $supplierCode = $_POST["supplierCode"];
   $cost = $_POST["cost"];   
   $price = $_POST["price"];
   $onHand = $_POST["onHand"]; 
   $reorderPoint = $_POST["reorderPoint"];
  

   //Checking if the checkbox backOrder is checked
   if( isset($_POST["backOrder"]) )
   {
      $backOrder = "y";
   }
  
   //String variables that hold regular expressions for validating the fields
   $itemname_pattern = "/^[a-z0-9\s-',;:]+$/i";
   $desc_pattern = "/^[a-z0-9\s.,'-]+$/im";
   $supplier_pattern = "/^[a-z0-9\s-]+$/i";  
   $monetary_pattern = "/^\d+\.\d\d$/";  // for price and cost
   $digitsonly_pattern = "/^\d+$/"; // for onHand and reorderPoint
   
  
   //checks if the fields have value and validates the data using regular expression. if error found sets 'ERRORS' to true 
   if( !strlen($_POST["itemName"]) )
   {
      $itemname_error = "Error - Please enter an item name";
      $ERRORS = true;
   }
   else if ( !preg_match($itemname_pattern,$_POST["itemName"]) )
   {
      $itemname_error = "Error - Please make sure you enter a valid item name";
      $ERRORS = true;
   }

   if( !strlen($_POST["description"]) )
   {
      $desc_error = "Error - Please enter a description";
      $ERRORS = true;
   }
   else if ( !preg_match($desc_pattern,nl2br($_POST["description"])) )
   {
      $desc_error = "Error - Please make sure you enter a valid description";
      $ERRORS = true;
   }

   if( !strlen($_POST["supplierCode"]) )
   {
      $supplier_error = "Error - Please enter a supplier Code";
      $ERRORS = true;
   }
   else if ( !preg_match($supplier_pattern,$_POST["supplierCode"]) )
   {
      $supplier_error = "Error - Please make sure you enter a valid supplier Code";
      $ERRORS = true;
   }
   
   if( !strlen($_POST["cost"]) )
   {
      $cost_error = "Error - Please enter a cost";
      $ERRORS = true;
   }
   else if ( !preg_match($monetary_pattern,$_POST["cost"]) )
   {
      $cost_error = "Error - Please make sure you enter a valid cost";
      $ERRORS = true;
   }
   
   if( !strlen($_POST["price"]) )
   {
      $price_error = "Error - Please enter a price";
      $ERRORS = true;
   }
   else if ( !preg_match($monetary_pattern,$_POST["price"]) )
   {
      $price_error = "Error - Please make sure you enter a valid price";
      $ERRORS = true;
   }

   if( !strlen($_POST["onHand"]) )
   {
      $onhand_error = "Error - Please enter the amount on hand";
      $ERRORS = true;
   }
   else if ( !preg_match($digitsonly_pattern,$_POST["onHand"]) )
   {
      $onhand_error = "Error - Please make sure you enter a valid on hand amount";
      $ERRORS = true;
   }

   if( !strlen($_POST["reorderPoint"]) )
   {
      $reorderpoint_error = "Error - Please enter the reorder point amount";
      $ERRORS = true;
   }
   else if ( !preg_match($digitsonly_pattern,$_POST["reorderPoint"]) )
   {
      $reorderpoint_error = "Error - Please make sure you enter a valid reorder point amount";
      $ERRORS = true;
   }
   
   //Passed all validation
   if( !$ERRORS )
   { 
      //connects to the database. (ConnectDB class implementation can be found at library.php) 
      $db = new ConnetDb;
      $link = $db->getlink();

      $itemName = mysqli_real_escape_string($link,$itemName); 
      $description = mysqli_real_escape_string($link,$description);

      /*Gets the current status of the 'deleted' row for the specified record*/
      $sql_query = "SELECT * FROM inventory";
  
      $result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));

      while($row = mysqli_fetch_assoc($result))
      {   
         if( $row['id'] == $_POST['itemId'] )
         {
            $modify = true;
         }            
      }


      if( !$modify )
      {
         $sql_query = "INSERT INTO inventory SET itemName = '$itemName', description = '$description', 
                                           supplierCode = '$supplierCode', cost = '$cost', price = '$price',
                                           onHand = '$onHand', reorderPoint = '$reorderPoint', backOrder = '$backOrder', deleted = 'n' ";
      }
      else
      {
         $id = mysqli_real_escape_string($link,$_POST["itemId"]); 

         $sql_query = "UPDATE inventory SET   itemName = '$itemName', description = '$description', 
                                           supplierCode = '$supplierCode', cost = '$cost', price = '$price',
                                           onHand = '$onHand', reorderPoint = '$reorderPoint', backOrder = '$backOrder', deleted = 'n' 
                       WHERE id = '$id'";
      }

      $result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));  
      $db->close();

      header("Location: view.php");
      die();
   }
} 

if( $ERRORS || !$_POST || isset($_GET["modify"]) )
{ 
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="mycss.css">
<title>Add Inventory</title>
</head>

<body>
   <?php   
         $topheader = new Headers;
         $menuNav = new Menu;

  
         $itemName;
         $Desct;
         $sp_code;
         $Cost;
         $S_price;
         $Num_hand;
         $reod_point;
         $backOrder;?>
          
   <form method="post" action="add.php">

      <table>
    <?php if( isset($_POST['itemId']) || isset($_GET['modify']) ){

             $db = new ConnetDb;
             $link = $db->getlink();

             $repopulate = mysqli_real_escape_string($link,$_GET['modify']);

            /*Gets the current status of the 'deleted' row for the specified record*/
             $sql_query = "SELECT * FROM inventory WHERE id = '$repopulate' ";
  
             $result = mysqli_query($link, $sql_query) or die('query failed'. mysqli_error($link));

             while($row = mysqli_fetch_assoc($result))
            {   
       
               $itemName = $row['itemName'];
               $Desct = $row['description'];
               $sp_code = $row['supplierCode'];
               $Cost = $row['cost'];
               $S_price = $row['price'];
               $Num_hand = $row['onHand'];
               $reod_point = $row['reorderPoint'];
               $backOrder = $row['backOrder'];
           } ?>


        <tr>
           <td>Item Id:</td>
           <td><input name="itemId" type="text" readonly="readonly" value="<?php if(isset($_POST['itemId'])) echo $_POST['itemId']; else if(isset($_GET['modify'])) echo $_GET['modify']; ?>"></td>
        </tr>
       <?php } ?>

        <tr>
           <td>Item name:</td>
           <td><input name="itemName" type="text" value="<?php if(isset($_POST['itemName']) ) echo $_POST['itemName']; else if( isset($_GET['modify']) ) echo $itemName;  ?>"></td>
           <td class="red"><?php echo $itemname_error; ?></td>
        </tr>

        <tr>
           <td>Description: </td>
           <td><textarea name="description"><?php if( isset($_POST['description'])) echo $_POST['description']; else if( isset($_GET['modify']) ) echo $Desct;?> </textarea></td>
           <td class="red"><?php echo $desc_error; ?></td>
        </tr>

        <tr>
           <td>Supplier Code:</td>
           <td><input name="supplierCode" type="text" value="<?php if( isset($_POST['supplierCode']) ) echo $_POST['supplierCode']; else if( isset($_GET['modify']) ) echo $sp_code; ?>"></td>
            <td class="red"><?php echo $supplier_error; ?></td>
         </tr>

         <tr>
            <td>Cost:</td>
            <td><input name="cost" type="text" value="<?php if( isset($_POST['cost']) ) echo $_POST['cost']; else if( isset($_GET['modify']) ) echo $Cost;   ?>"></td>
            <td class="red"><?php echo $cost_error; ?></td>
         </tr>

         <tr>
            <td>Selling price:</td>
            <td><input name="price" type="text" value="<?php if( isset($_POST['price']) ) echo $_POST['price']; else if( isset($_GET['modify']) ) echo $S_price; ?>"></td>
            <td class="red"><?php echo $price_error; ?></td>
         </tr>

         <tr>
            <td>Number on hand:</td>
            <td><input name="onHand" type="text" value="<?php if( isset($_POST['onHand']) ) echo $_POST['onHand']; if( isset($_GET['modify']) ) echo $Num_hand;  ?>"></td>
            <td class="red"><?php echo $onhand_error; ?></td>
         </tr>  

         <tr>
            <td>Reorder Point:</td>
            <td><input name="reorderPoint" type="text" value="<?php if( isset($_POST['reorderPoint']) ) echo $_POST['reorderPoint'];  if( isset($_GET['modify']) ) echo $reod_point;  ?>"></td>
            <td class="red"><?php echo $reorderpoint_error; ?></td>
         </tr>

         <tr>
            <td>On Back Order:</td>
            <td><input name="backOrder" type="checkbox" <?php if ( isset($_POST['backOrder']) ) echo "CHECKED"; else if( isset($_GET['modify']) && $backOrder == 'y'  ) echo "CHECKED"; ?>   ></td>
         </tr>     
         <tr>
            <td colspan = "2" class="middle" ><input name="submit" type="submit"></td>
         </tr> 

      </table>

   </form>
<?php 
}// end of $ERRORS || !$_POST if statment
  $footer = new Footer; 
?>   
</body>

</html>



