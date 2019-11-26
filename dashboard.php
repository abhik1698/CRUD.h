<html>
  <head>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      <?php include 'dashboard.css'; include 'index.css'; ?>      
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
        
        if (trim($keywords[0]) === "exit" || trim($keywords[0]) === "logout") {
          echo "<script>window.location = '../index.php';</script>";
        }
      
        //Create Table Syntax <c> <tableName> <columns...>            
        elseif (trim($keywords[0]) === "c"){  
          if($count > 1){
            $keywords[1] = trim($keywords[1]);      
            $sql = "CREATE TABLE `$user`.`$keywords[1]` ( id_id_id int, ";
            for($i = 2; $i < $count; $i++){
              $keywords[$i] = trim($keywords[$i]);
              $sql .= "$keywords[$i] varchar(50), ";
            }        
            $keywords[$i] = trim($keywords[$i]);
            $sql .= "$keywords[$i] varchar(50), primary key($keywords[2]) )";
            
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
          if($count < 2){
            $sql = "DROP DATABASE `$user`";
            $conn->query($sql);          
                                           
            echo "<script>window.location = '../index.php';</script>";                
            $sql = "delete from `miniW`.`tbls` where user='$user'";
            $conn->query($sql);              
            $sql = "delete from `miniW`.`login` where uid='$user'";
            $conn->query($sql);              
          }
        } 
      
        //Drop Table
        elseif((trim($keywords[0]) === "dr")){  
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
      
        //Delete row
        elseif((trim($keywords[0]) === "d")){  
          if($count >= 3){
            $keywords[1] = trim($keywords[1]);      
            $keywords[2] = trim($keywords[2]);      
            $keywords[3] = trim($keywords[3]);      
            $sql = "delete from `$user`.`$keywords[1]` where $keywords[2]='$keywords[3]'";
          
            if($conn->query($sql)){
              echo "Deleted Successfully";                
            } else {
              echo "Couldn't Delete";
            }
          } else {
            echo("Delete Syntax is Wrong"); 
          }                  
        }
      
        //Update row
        elseif((trim($keywords[0]) === "u")){  
          if($count >= 5){
            $keywords[1] = trim($keywords[1]);      
            $keywords[2] = trim($keywords[2]);      
            $keywords[3] = trim($keywords[3]);      
            $sql = "update `$user`.`$keywords[1]` set $keywords[4]='$keywords[5]' where $keywords[2]='$keywords[3]'";
          
            if($conn->query($sql)){
              echo "Updated Successfully";                
            } else {
              echo "Couldn't Update";
            }
          } else {
            echo("Update Syntax is Wrong"); 
          }                  
        }
      }          
      ?>
    <div class="header" style="position: fixed;
      right: 0;
      top: 0;">
      <div class="navbar" >
        <a href="./?user=<?=$user?>">Schema</a>  
        <div class="dropdown">
          <button class="dropbtn">DDL Syntax 
          <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-content">
            <a onclick="updateCMD('c ')">
              <h3>Create Table</h3>
              <p id="c">c tableName Columns...</p>
            </a>
            <a href="#" onclick="updateCMD('dr ')" >
              <h3>Drop Table</h3>
              <p id="dr">dr tableName</p>
            </a>
            <a href="#" onclick="updateCMD('r ')">
              <h3>Drop Schema</h3>
              <p id="r">r</p>
            </a>
          </div>
        </div>
        <div class="dropdown">
          <button class="dropbtn">DML Syntax 
          <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-content">
            <a href="#" onclick="updateCMD('i ')">
              <h3>Insert to Table</h3>
              <p id="i">i tableName values...</p>
            </a>
            <a href="#" onclick="updateCMD('u ')">
              <h3>Update Row</h3>
              <p id="u">u tableName Column1 Value1 Column Value</p>
            </a>
            <a href="#" onclick="updateCMD('d ')">
              <h3>Delete Row</h3>
              <p id="d">d tableName Column1 Value1</p>
            </a>
            <a href="#" onclick="updateCMD('t ')">
              <h3>Truncate Table</h3>
              <p id="t">t tableName</p>
            </a>
          </div>
        </div>
        <a onclick="document.getElementById('about').style.display='block'" type="submit" style="width:auto;">About</a>
        <a href="../index.php">Logout</a>
      </div>
    </div>
    <center>
    <h2 style="color:white;">&lt;CRUD.h&gt;</h2>
      <h1>Schema: <?=$user?></h1>      
    </center>
    <form autocomplete="off" action="../dashboard.php/?user=<?=$user?>" method="post">
      <?php  
        //Retreive Tables per User
        // $conn = new mysqli('127.0.0.1:3306', 'root', '', 'miniW');
        $sql = "select tbl from `miniW`.`tbls` where user='$user'";
        $result = $conn->query($sql);  
        while($row = mysqli_fetch_assoc($result)){
          $tbl = $row['tbl'];
          echo "<div class='tables' style='margin-left:20%;'><h4 class='yellow' onclick='updateCMD(this.innerHTML, 1)'>$tbl</h4>";
          echo "<table style='width:70%;margin-left:0%;' class='container' >";    
          $sql2 = "select * from `$user`.`$tbl`";
          $trows = $conn->query($sql2);
          
          for ($set = array (); $trow = $trows->fetch_assoc(); $set[] = $trow);
            // for($i = 0; $i < count($set); $i++){
              echo "<tr>";
              for($j = 1; $j < count($set[0]); $j++){          
                echo("<th onclick='updateCMD(this.innerHTML, 1)'>" . array_keys($set[0])[$j] . "</th>");   
              }
              echo "</tr>";
            // }                      
          
          for($i = 1; $i < count($set); $i++){
            echo "<tr>";
            for($j = 1; $j < count($set[$i]); $j++){        
              echo("<td onclick='updateCMD(this.innerHTML, 1)'>" .array_values($set[$i])[$j]. "</td>");        
            }
            echo "</tr>";
          }               
          echo "</table></div>";      
        }  
        $conn->close();
        ?>
      </br></br>
      <div class="footer"style="position: fixed;
        left: 0;
        bottom: 0;
        width: 90%;   
        text-align: center;   
        display: flex;
        margin-left: 3px;" >       
        <h3>Query goes here --*></h3>
        <input id="cmd" name="qry" style="width: 100%; margin: 10px; " placeholder="c tableName Column1 Column2..."/>
        <input style="width: 15%;  margin: 10px; font-weight: bold;" type="submit" class='run' value="run" name="SubmitButton"/>   
        <input style="width: 15%;  margin: 10px; font-weight: bold; left: 0;" type="submit" class='clr' value="Clear Query" 
      onclick="document.getElementByID('cmd').value=''"/>       
      </div>
    </form>
    <div id="about" class="modal">
      <form class="modal-content animate">
        <div class="imgcontainer">
          <span onclick="document.getElementById('about').style.display='none'" class="close" title="Close Modal">&times;</span>
          <img src="../src/aboutBG.jpg" alt="About us" class="avatar"/>
        </div>
        <div class="container">
          <center>
            <h1>&lt;CRUD.h&gt;</h1>
          </center>
          <h2>MySQL shorthand tool to manage database like a Journalist.</br></br>
            Follow the specified syntax to Manipulate Data. </br></br>
            <b style="color: #4DC3FA;">Components used </b></br></br> HTML, CSS, JavaScript, PHP, MySQL
          </h2>
        </div>
      </form>
    </div>
    <script>
      document.getElementById('cmd').focus();
      function updateCMD(val, c=0){
        var cmd = document.getElementById("cmd");
        if(c == 0)
          cmd.value = val;
        else
          cmd.value += val + ' ';
      }
      
      // Get the modal
      var modal = document.getElementById('about');
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
  </body>
</html>