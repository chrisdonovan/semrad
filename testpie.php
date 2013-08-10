<?php require_once("includes/dbcon.php"); ?>
<?php require_once("includes/functions.php"); ?>
    
<?php

// This is just an example of reading server side data and sending it to the client.
// It reads a json formatted text file and outputs it.

$string = file_get_contents("sampleData.json");
echo $string;/*

// Instead you can query your database and parse into JSON etc etc
// Get call count for CPL calculation
    $dbquery = "SELECT * FROM chart";
    $result = mysqli_query($con, $dbquery);
    if (!$result){
        die("Database query failed: " . mysqli_error($con));
    }
    $rows = 
    $rows = mysqli_fetch_array($result);
    
    echo json_encode($rows);*/
?>