<?php require_once("includes/dbcon.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php define('TITLE','Upload'); ?>
<?php include("includes/header.php"); ?>
<?php include("includes/menu.php"); ?>

<div class="span4"></div>
<div class="span4">
  <h2>Content Area</h2>
  <br />
  <select name="csvtype" form="csvform">
    <option value="calls">Call Log</option>
    <option value="postlog">Post Log</option>
  </select>
  <form action="upload.php" method="post" enctype="multipart/form-data" id="csvform">
    <label for="file">Filename:</label>
    <input type="file" name="file" id="file"><br /><br />
    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
  </form>

  <?php
  // If there was a submit
  if(isset($_POST['submit'])) {
    // Check previous existence of file
    if (file_exists("./src/csv/" . $_FILES["file"]["name"])) {
      echo $_FILES["file"]["name"] . " already exists. <br />";
      $exists = true;
    }
    else {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "./src/csv/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "./src/csv/" . $_FILES["file"]["name"] . "<br />";
      $exists = false;
    }

    if(!$exists){
      // callsCSV reads the CSV file and uploads it to the calls DB
      function readCSV($csvFile){
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle) ) {
          $line_of_text[] = fgetcsv($file_handle, 1024);
        }
        fclose($file_handle);
        return $line_of_text;
      }
      $csvFile = "./src/csv/" . $_FILES["file"]["name"];
      $csv = readCSV($csvFile);

      // post_logs
      if ($_POST['csvtype'] === 'postlog'){
        foreach($csv as $entry){
          $yrpattern = getdatepattern($entry[0]);
          $tmpattern = gettimepattern($entry[1]);
          
          $dbquery = 'CALL InsertPostLog("\'' . $entry[0] . ' ' . $entry[1] .
                     '\'",' . $entry[2] . ',"' . $entry[3] . '","' .
                     $yrpattern . $tmpattern . '")';
          $result = mysqli_query($con, $dbquery);
          if (!$result){
            echo "<em>{$dbquery}</em>";
            die("Database query failed: " . mysqli_error($con));
          }
        }
      }
      
      // call_logs
      else {
        foreach($csv as $entry){
          $dbquery = "CALL InsertCallLog('{$entry[0]} {$entry[1]}','{$entry[2]}','{$entry[3]}','{$entry[4]}','{$entry[5]}')";
          $result = mysqli_query($con, $dbquery);
          if (!$result){
            die("Database query failed: " . mysqli_error($con));
          }
        }
      }
    }
    
    else{
        echo 'Nothing uploaded to the database.';
    }
  }
  ?>
</div>
<?php require("includes/footer.php"); ?>