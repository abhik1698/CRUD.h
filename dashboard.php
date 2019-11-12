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
    
    while($row = mysqli_fetch_assoc($result)) {
      echo $row["tbl"];
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
  flush();
    $conn->close();           
?>
    
  <form action="../dashboard.php/?user=<?=$user?>" method="post"> 
  <input type="text" name="qry"/> 
  <input type="submit" name="SubmitButton"/> 
  </form>
  
  </body>
</html>