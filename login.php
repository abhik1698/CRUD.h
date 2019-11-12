<?php
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
            echo "Credentials mismatch";
        }
    }
    $conn->close();
?>

