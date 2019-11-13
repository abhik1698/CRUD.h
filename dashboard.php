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
      if ((trim($keywords[0]) === "c")){  
        if($count > 1){
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
        else{
          echo("Create Syntax is Wrong"); 
        }
      }
      //remove schema
      elseif((trim($keywords[0]) === "r")){
        if($count < 1){
          $sql = "DROP DATABASE `$user`";
          $conn->query($sql);          
                                         
          echo "<script>window.location = '../index.html';</script>";                
          $sql = "delete from `miniW`.`tbls` where user='$user'";
          $conn->query($sql);              
          $sql = "delete from `miniW`.`login` where uid='$user'";
          $conn->query($sql);              
        }
      } 

      //Drop Table
      elseif((trim($keywords[0]) === "d")){  
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
        $sql = "delete from `miniW`.`tbls` where user='$user' and tbl='$keywords[1]'";
        $conn->query($sql);        
      }                                  
    }  
    $conn->close();           
?>
  <center>
    <h1>Schema named "<?=$user?>" is successfully Created</h1>
  <h1>
  <form autocomplete="off" action="../dashboard.php/?user=<?=$user?>" method="post"> 
  <input type="text" name="qry" placeholder="Query here"/> 
  <input type="submit" name="SubmitButton"/> 
  </form>
<?php
  $conn = new mysqli('127.0.0.1:3306', 'root', '', 'miniW');
  $sql = "select tbl from `miniW`.`tbls` where user='$user'";
  $result = $conn->query($sql);
  echo "<table><th><td>Your Tables</td></th>";
  while($row = mysqli_fetch_assoc($result)){
    echo ("<tr><td>".$row['tbl']."</tr></td>");
  }
  echo "</table>";
  $conn->close();
  ?>

  <h2>---Syntax---</h2>
  <h3>Create Table</h3><p><b>></b> c &nbsp;&nbsp;&lt;tableName&gt;&nbsp;&nbsp; [&lt;Columns...&gt;]</p>
  <h3>Drop Table</h3><p><b>></b> d &nbsp;&nbsp;&lt;tableName&gt;</p>
  <h3>Drop Schema</h3><p><b>></b> r</p>
  </center>
  </body>
</html>