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
  <a onclick="document.getElementById('id01').style.display='block'" type="submit" style="width:auto;">Schema</a>
    <div class="dropdown">
      <button class="dropbtn">DDL Syntax 
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content">
      <a ><h3>Create Table</h3><p>c tableName Columns...</p></a>        
        <a href="#" ><h3>Drop Table</h3><p>d tableName</p></a>
        <a href="#"><h3>Drop Schema</h3><p>r</p></a>        
      </div>      
    </div> 
    <div class="dropdown">
      <button class="dropbtn">DML Syntax 
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content">        
        <a href="#"><h3>Insert to Table</h3><p>i tableName values...</p></a>  
        <a href="#"><h3>Update Row</h3><p>u tableName Column1 Value1 Column Value</p></a>    
        <a href="#"><h3>Delete Row</h3><p>d tableName Column1 Value1</p></a>      
        <a href="#"><h3>Truncate Table</h3><p>t tableName</p></a>
      </div>      
    </div> 
    <a href="../About.html">About</a>
    <a onclick="document.getElementById('id01').style.display='block'" type="submit" style="width:auto; float: right;">Login to Schema</a>
  </div>
  </div>
    <center>
      <h1>&lt;CRUD.h&gt;</h1>  
      
    </center>
    
    
    <div id="id01" class="modal">
  
        <form class="modal-content animate" action="schema.php" method="post">
          <div class="imgcontainer">
            <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
            <img src="src/db.jpg" alt="Avatar" class="avatar">
          </div>
      
          <div class="container">      
            <input type="text" placeholder="schemaName" id='uid' name="uid" required>
                  
            <input type="password" placeholder="Password" id='pwd' name="pwd" required>
              
            <button type="submit">Run Schema</button>

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
        function updateLogin(val){
          document.getElementById('id01').style.display='block';
          document.getElementById('uid').value= val;
          document.getElementById('pwd').focus();
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