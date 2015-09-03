<?php
/*
Subject Code:   INT322A 
Student Name:   Bushra Mohamed
Date Submitted: Thursday, APRIL 3, 2014

Name Bushra Mohamed

Student ID 018-633-123

*/
?>


<?php /*The following class holds the business name as the title and the bike image*/

class Headers{

   private function display()
   {?>
      <h2>Ride A Bike</h2>
      <a href="http://openclipart.org/detail/171123/yellow-speed-bike-by-bianchessi-171123">
      <img src="http://openclipart.org/image/400px/svg_to_png/171123/not-branded_bicycle.png" /></a>
<?php  
   } 

   public function __construct()
   {
      $this->display();
   }

}//end of Header class


/*this class contains the menu information (add/view all links, the search field,user's user name and role, and logout link*/
class Menu{
  private function display()
  { ?>
     <table>
       <tr>
         <td><a href="add.php">Add</a></td>
         <td><a href="view.php?viewall=<?php echo true;?>">View All</a></td>

         <form action="view.php" method="POST"> 
         <td>Search in description:<input type="text" name="description" value="<?php if (isset($_SESSION['description'])){echo $_SESSION['description'];}else echo ' ';?>"/></td>
         <td><button type="submit">Search</button></td>
         </form> 
         <td>User: <?php echo $_SESSION["username"]; ?></td>
         <td>role: <?php echo $_SESSION["role"]; ?></td> 
         <td><a href="logout.php">   logout</a></td>
       <tr>   
    </table> 
  <?php
  } // end of display function

  public function __construct()
  {
     $this->display();
  }   
}//end of Menu class?>



<?php
/*This class contains the footer information (the copy right information)*/
class Footer{

   private function display()
   {?>
       <hr>
      Copyright &copy; Bushra Mohamed 
<?php  
   } 

   public function __construct()
   {
      $this->display();
   }

}//end of footer class?>


<?php
/*The following class holds and tries to connect to the database. If not successful, it will show error.*/ 
class ConnetDb{
   private $_lines;
   private $_uid;
   private $_pw;
   private $_dbserver;
   private $_dbname;
   private $_dbclosed;
   private $_link;

   public function __construct()
   {
      //extracts data from external file.
      $this->_lines = file('/home/int322/secret/topsecret')or die();
      $this->_uid = trim($this->_lines[0]); 
      $this->_pw = trim($this->_lines[1]);
      $this->_dbserver = trim($this->_lines[2]);
      $this->_dbname = trim($this->_lines[3]);

      $this->_link = mysqli_connect($this->_dbserver,$this->_uid,$this->_pw,$this->_dbname) or die('Could not connect');

      if($this->_link) 
         $this->_dbclosed = true;
      else
         $this->_dbclosed = false;    
   }

   public function getlink()
   {
      return $this->_link;
   }

   public function __destruct()
   {
      if( !$this->_dbclosed )
         $this->close();
   }
   public function close()
   { 
      if( !$this->_dbclosed )
         mysqli_close($this->_link);   
   }
} //end of ConnectDb class?>






