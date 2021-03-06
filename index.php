<html>
  <head>
    <title>&lt;CRUD.h&gt;</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="dashboard.css">
  </head>
  <body style="background-image: url('./src/bg.jpg');background-repeat: no-repeat; background-size: 100% 100%;">
    <div class="header">
      <div class="navbar" >
        <a onclick="document.getElementById('id01').style.display='block'; updateLogin();" type="submit" style="width:auto;">Schema</a>
        <div class="dropdown">
          <button class="dropbtn">DDL Syntax 
          <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-content"> 
            <a href="./syntax.html?id=create" target="_blank" >
              <h3>Create Table</h3>
              <p>c tableName Columns...</p>
            </a>
            <a href="./syntax.html?id=tdrop" target="_blank">
              <h3>Drop Table</h3>
              <p>d tableName</p>
            </a>
            <a href="./syntax.html?id=sdrop" target="_blank">
              <h3>Drop Schema</h3>
              <p>r</p>
            </a>
          </div>
        </div>
        <div class="dropdown">
          <button class="dropbtn">DML Syntax 
          <i class="fa fa-caret-down"></i>
          </button>
          <div class="dropdown-content">
            <a href="./syntax.html?id=insert" target="_blank">
              <h3>Insert to Table</h3>
              <p>i tableName values...</p>
            </a>
            <a href="./syntax.html?id=update" target="_blank">
              <h3>Update Row</h3>
              <p>u tableName Column1 Value1 Column Value</p>
            </a>
            <a href="./syntax.html?id=delete" target="_blank">
              <h3>Delete Row</h3>
              <p>d tableName Column1 Value1</p>
            </a>
            <a href="./syntax.html?id=truncate" target="_blank">
              <h3>Truncate Table</h3>
              <p>t tableName</p>
            </a>
          </div>
        </div>
        <a onclick="document.getElementById('about').style.display='block';" type="submit" style="width:auto;">About</a>
        <a type="submit" href="syntax.html" style="width:auto;">Blog</a>
        <a onclick="document.getElementById('id01').style.display='block'; updateLogin();" type="submit" style="width:auto; float: right;">Login to Schema</a>
      </div>
    </div>
    <center>
    <h1>MySQL Shorthand Database Management<h1>
    <h2 style="color:white;">&lt;CRUD.h&gt;</h2>
    </center>
    <div id="id01" class="modal">
      <form class="modal-content animate"  method="post">
        <div class="imgcontainer">
          <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
          <h1>Login / Sign Up</h1>
          <img src="src/loginBG.png" style="border-radius: 100%;" alt="Avatar" class="avatar">
        </div>
        <div class="container">      
          <input type="text" placeholder="schemaName" id='uid' name="uid" required>
          <input type="password" placeholder="Password" id='pwd' name="pwd" pattern=".{4,}" title="Minimum 4 characters are required" required>
          <button type="submit" name="login">Run Schema</button>
        </div>
      </form>
      <?php //Authentication
      if(isset($_POST['login'])) {        
        // Create connection
        $conn = new mysqli('127.0.0.1:3306', 'root', '', 'miniW');        
        // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }        
        $username =  $_POST['uid'];
        $password =  $_POST['pwd'];
        //Check user exists for usernmae & password given
        $sql = "select uid from login where uid='$username'";
        $result = $conn->query($sql);
        
        if ($result->num_rows < 1) {
            $sql = "insert into login(uid, pwd) values('$username', '$password')";
            $conn->query($sql);
            $sql = "CREATE SCHEMA `$username`";
            $conn->query($sql);
            echo "<script>window.location = 'dashboard.php/?user=$username';</script>"; //Reg success
            // echo "Hi newbie";
        } else {
            $sql = "select uid from login where uid='$username' and pwd='$password'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {        
                echo "<script>window.location = 'dashboard.php/?user=$username';</script>"; //Reg success
                // echo "Hi oldie";
            } else {
                echo "<script>alert('Schema Exits, Credentials Mismatch!');</script>";
            }
        } 
        $conn->close();  
      }?>

    </div>
    <div id="about"  class="modal" onclick="document.getElementById('about').style.display='none';">
      <form style="background-color:#FFFFFF;" class="modal-content animate">
        <div class="imgcontainer">
          <span onclick="document.getElementById('about').style.display='none'" class="close" title="Close Modal">&times;</span>
          <img src="src/aboutBG.jpg" alt="About us" class="avatar"/>
        </div>
        <div class="container">
          <center>
            <h1>&lt;CRUD.h&gt;</h1>
          </center>
          <h2 style="color: black;">MySQL shorthand tool to manage database like a Journalist.</br></br>Follow 
            the specified syntax to Manipulate Data.</br></br>
            <b style="color: #4DC3FA;">Components used</b></br></br> HTML, CSS, JavaScript, PHP, MySQL
          </h2>
        </div>
      </form>
    </div>
    <?php  
      // Create connection
      $conn = new mysqli('127.0.0.1:3306', 'root', '', 'miniW');
      // Check connection 
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      
      echo "<h4 class='yellow' style='text-align: center;'>Registered Databases</h4>";
      $sql = "select uid from login";
      $result = $conn->query($sql);
      if($result) {
        echo "<table id='table' style=' width:30%; height: 30%;' class='container'>";
        while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";        
          echo("<td onclick='updateLogin(this.innerHTML)'>" . $row["uid"] . "</td>");   
          echo "</tr>";
        }        
        }      
        ?>
    <script>
      function updateLogin(val=""){
        document.getElementById('id01').style.display='block';
        document.getElementById('uid').value= val;
        if(val != "")
          document.getElementById('pwd').focus();
        else
          document.getElementById('uid').focus();
      }        
      // Get the modal
      var modal = document.getElementById('id01');
      
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";            
        }
      }
      
    </script>
  </body>
</html>