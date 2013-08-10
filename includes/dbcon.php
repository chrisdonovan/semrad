<?php
    // Create a database connection
    require("constants.php");
    $con = mysqli_connect(DB_SERV,DB_USR,DB_PASS,DB_NAME);
    if(!$con){
        die("Database connection failed: " . mysqli_error());
    }
?>