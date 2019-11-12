<html>
  <head>
    <title>Dashboard</title>
  </head>
  <body>
  <?php  

    // Create connection
    $conn = new mysqli('127.0.0.1:3306', 'root', '', 'miniW');
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    $user = $_GET["user"];
    $sql = "select * from tbls where user=$user";
    $result = $conn->query($sql);
    if($result) {
      while($row = mysqli_fetch_assoc($result)) {
        echo $row["tbl"];
      }
    }
    
    if(isset($_POST['SubmitButton'])){ //check if form was submitted
      $qry = $_POST['qry']; //get query    
      $qry = trim($qry);
      unset($keyword); unset($keywords);
      $count = 0; 
      $keyword = "";
      $keywords = [];
      for($i=0; $i<strlen($qry); $i++){
        $keyword .= $qry[$i];
        
        if ($qry[$i] == ' '){
          $keywords[$count] = $keyword;          
          $keyword = "";
          $count++;          
        }
      }
      $keywords[$count] = $keyword;
      
      //Create Table Syntax <c> <tableName> <columns...>
      
      if($count > 1) {
        if (strcmp($keywords[0], 'c')){  
          $keywords[1] = trim($keywords[1]);      
          $sql = "CREATE TABLE `$user`.`$keywords[1]` ( ";
          for($i = 2; $i < $count; $i++){
            $keywords[$i] = trim($keywords[$i]);
            $sql .= "$keywords[$i] varchar(50), ";
          }        
          $keywords[$i] = trim($keywords[$i]);
          $sql .= "$keywords[$i] varchar(50) )";
          
          if($conn->query($sql)){
            echo "Table Created Successfully";
            $sql = "insert into `miniW`.`tbls` values('$user', '$keywords[1]')";
            $conn->query($sql);
          } else {
            echo "Oops, table already exists..";
          }
        }
        
    } 
    // else ($count < 2){
    //   echo("Create Syntax is Wrong"); 
    // }                             
      //Delete Table Syntax <DROP TABLE `5`.`v`;          (strcmp($keywords[0], 'd'))
      else{  
        $keywords[1] = trim($keywords[1]);      
        $sql = "DROP TABLE `$user`.`$keywords[1]`";             
        if($count != 1){
          echo("DROP Syntax is Wrong"); 
        }
        if($conn->query($sql)){
          echo "Table Dropped Successfully";
        } else {
          echo "No such table exists..";
        }        
      }                                  
    }  
    // unset($keywords); 
      // ob_implicit_flush(true);
  // flush();
    $conn->close();           
?>
  <center>
    <h1>Schema named "<?=$user?>" is successfully Created</h1>
  <h1>
  <form action="../dashboard.php/?user=<?=$user?>" method="post"> 
  <input type="text" name="qry" placeholder="Query here"/> 
  <input type="submit" name="SubmitButton"/> 
  </form>
<?php
  // $conn = new mysqli('127.0.0.1:3306', 'root', '', 'miniW');
  //   $sql = "select * from `miniW`.`tbls` where user='$user'";
  //   $result = $conn->query($sql);

  //   while($row = mysqli_fetch_assoc($result)) {
  //     // print_r (array_values($row)[1]);
  //     $tbl = array_values($row)[1];
  //     $sql2 = "select * from `$user`.`$tbl`";
  //     $result2 = $conn->query($sql);
  //     while($row2 = mysqli_fetch_assoc($result2)) {
  //       print_r (array_keys($row2));
  //       for($i = 0; $i < count(array_keys($row2)); $i++){
  //         print_r (array_values($row2)[$i]);
  //       }
  //       echo "<br/>";
  //     }
      
  // }
    // $sql = "select tbl from `miniW`.`tbls` where user=$user";
  ?>

  <h2>---Syntax---</h2>
  <br>
  <h3>Create: <br>c &nbsp;&nbsp;&lt;tablename&gt;&nbsp;&nbsp; [&lt;Columns...&gt;]</h3>
  <h3>Drop: <br>d &nbsp;&nbsp;&lt;tablename&gt;</h3>
  </center>
  </body>
</html>