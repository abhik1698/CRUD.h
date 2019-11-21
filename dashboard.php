<html>
  <head>
    <title>Dashboard</title>
    <style>
      <?php include 'dashboard.css'; ?>
      ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background-color: #333;
      }

      li {
        float: left;
      }

      li a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
      }

      li a:hover {
        background-color: #111;
      }

      label,
      textarea {
          font-size: .8rem;
          letter-spacing: 1px;
      }
      textarea {
          background-color: black;
          color: green;
          width: 500px;
          height: 50px;
          font-size: 2rem;
          line-height: 1.5;
          border-radius: 5px;
          border: 1px solid #ccc;
          box-shadow: 1px 1px 1px #999;
      }
      label {
          display: block;
          margin-bottom: 10px;
      }

    </style>
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
    // $sql = "select * from tbls where user=$user";
    // $result = $conn->query($sql);
    // if($result) {
    //   while($row = mysqli_fetch_assoc($result)) {
    //     echo $row["tbl"];
    //   }
    // }
    
    if(isset($_POST['SubmitButton'])){ //check if form was submitted
      $qry = $_POST['qry']; //get query    
      $qry = trim($qry);
      unset($keyword);
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
      if (trim($keywords[0]) === "c"){  
        if($count > 1){
          $keywords[1] = trim($keywords[1]);      
          $sql = "CREATE TABLE `$user`.`$keywords[1]` ( id_id_id int, ";
          for($i = 2; $i < $count; $i++){
            $keywords[$i] = trim($keywords[$i]);
            $sql .= "$keywords[$i] varchar(50), ";
          }        
          $keywords[$i] = trim($keywords[$i]);
          $sql .= "$keywords[$i] varchar(50) )";
          
          if($conn->query($sql)){
            
            $sql = "insert into `miniW`.`tbls` values('$user', '$keywords[1]')";
            $conn->query($sql);

            $inNull = "insert into `$user`.`$keywords[1]` values(0, ";
            for($i = 2; $i < $count; $i++){
              $inNull .= "' ', ";
            }        
            $inNull .= "' ' )";
            $conn->query($inNull);
          } else {
            echo "Oops, table already exists..";
          }            
        }        
        else{
          echo("Create Syntax is Wrong"); 
        }
      }

      //Insert to table
      elseif((trim($keywords[0]) === "i")){
        if($count > 1){
          $keywords[1] = trim($keywords[1]);
          $sql = "insert into `$user`.`$keywords[1]` values(1, ";
          
          for($i = 2; $i < $count; $i++){
            $keywords[$i] = trim($keywords[$i]);
            $sql .= "'$keywords[$i]', ";
          }        
          $keywords[$i] = trim($keywords[$i]);
          $sql .= "'$keywords[$i]' )";
          
          $conn->query($sql);         
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

      //Truncate Table truncate `3`.ab;
      elseif((trim($keywords[0]) === "t")){  
        if($count == 1){
          $keywords[1] = trim($keywords[1]);      
          $sql = "delete from `$user`.`$keywords[1]` where id_id_id <> 0";
        
          if($conn->query($sql)){
            echo "Removed all rows Successfully";                
          } else {
            echo "No such table exists..";
          }
        } else {
          echo("Truncate Syntax is Wrong"); 
        }                  
      }
    }  
    $conn->close();           
?>
  <ul>
  <li><a class="active" href="./?user=<?=$user?>">Schema</a></li>
  <li><a href="../syntax.html">Syntax</a></li>
  <li><a href="../about.html">About</a></li>
  <li style="float: right;"><a href="../index.html">Logout</a></li>
</ul>
  <center>
    <h1>Schema: <?=$user?></h1>
  <h1>
  <form autocomplete="off" action="../dashboard.php/?user=<?=$user?>" method="post"> 
  <label for="qry">Query here</label>
  ><textarea id="qry" name="qry" placeholder="c tableName Column1 Column2..."></textarea> 
  <input type="submit" class='button' value="run" name="SubmitButton"/> 

  <?php
  //Retreive Tables per User
  $conn = new mysqli('127.0.0.1:3306', 'root', '', 'miniW');
  $sql = "select tbl from `miniW`.`tbls` where user='$user'";
  $result = $conn->query($sql);  
  while($row = mysqli_fetch_assoc($result)){
    $tbl = $row['tbl'];
    echo "<div class='tables container' ><h4 class='yellow'>$tbl</h4>";
    echo "<table style='margin: 10px; width:50%;' >";    
    $sql2 = "select * from `$user`.`$tbl`";
    $trows = $conn->query($sql2);
    
    for ($set = array (); $trow = $trows->fetch_assoc(); $set[] = $trow);
      // for($i = 0; $i < count($set); $i++){
        echo "<tr>";
        for($j = 1; $j < count($set[0]); $j++){          
          echo("<th >" . array_keys($set[0])[$j] . "</th>");   
        }
        echo "</tr>";
      // }                      
    
    for($i = 1; $i < count($set); $i++){
      echo "<tr>";
      for($j = 1; $j < count($set[$i]); $j++){        
        echo("<td>" .array_values($set[$i])[$j]. "</td>");        
      }
      echo "</tr>";
    }               
    echo "</table></div>". "><textarea id='qry' name='qry' placeholder='c tableName Column1 Column2...'></textarea>";  
    echo "<input type='submit' class='button' value='run' name='SubmitButton' />";
  }  
  $conn->close();
  ?>

  </center>
  </form>
  </body>
</html>