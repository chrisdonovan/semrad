<?php require_once("includes/dbcon.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>
    <table id="structure">
        <tr>
            <td id="navigation">
                <ul class="subjects">
                <?php
                    // Perform DB query
                    $result = mysqli_query($con, "SELECT * FROM subjects");
                    if (!$result){
                        die("Database query failed: " . mysqli_error());
                    }
                    
                    // Use returned data
                    while ($row = mysqli_fetch_array($result)){
                        echo "<li>{$row["menu_name"]}</li>";
                    }
                ?>
                </ul>
            </td>
            <td id="page">
                <h2>Content Area</h2>
                
                <select name="csvtype" form="csvform">
                    <option value="calls">Call Log</option>
                    <option value="nashpostlog">Nashville Post Log</option>
                    <option value="atlpostlog">Atlanta Post Log</option>
                </select>
                <form action="uploadpost.php" method="post" enctype="multipart/form-data" id="csvform">
                    <label for="file">Filename:</label>
                    <input type="file" name="file" id="file"><br>
                    <input type="submit" name="submit" value="Submit">
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

        // post_logs(id,date,cost,isci)
        if ($_POST['csvtype'] === 'nashpostlog'){
            foreach($csv as $entry){
                $dbquery = "INSERT INTO post_logs VALUES" .
                "(NULL, str_to_date('{$entry[0]} {$entry[1]}','%c/%e/%y %r'),{$entry[2]},'{$entry[3]}')";
                // Date format: 6/3/2013 8:53AM
                //"(NULL, str_to_date('{$entry[0]} {$entry[1]}','%c/%e/%Y %h:%i%p'),{$entry[2]},'{$entry[3]}')";
                $result = mysqli_query($con, $dbquery);
                if (!$result){
                    die("Database query failed: " . mysqli_error($con));
                }
            }
        }
        elseif ($_POST['csvtype'] === 'atlpostlog'){
            foreach($csv as $entry){
                $dbquery = "INSERT INTO post_logs VALUES" .
                // Date format: 6/3/2013 01:24:42AM
                "(NULL, date_sub(str_to_date('{$entry[0]} {$entry[1]}','%c/%e/%Y %r'), interval 1 hour),{$entry[2]},'{$entry[3]}')";
                // Date format: 06/03/13 8:53:06AM
                //"(NULL, date_sub(str_to_date('{$entry[0]} {$entry[1]}','%m/%d/%y %r'), interval 1 hour),{$entry[2]},'{$entry[3]}')";
                // Date format: 06/03/13 8:53AM
                //"(NULL, date_sub(str_to_date('{$entry[0]} {$entry[1]}','%m/%d/%y %h:%i%p'), interval 1 hour),{$entry[2]},'{$entry[3]}')";
                $result = mysqli_query($con, $dbquery);
                if (!$result){
                    echo "<em>{$dbquery}</em>";
                    die("Database query failed: " . mysqli_error($con));
                }
            }            
        }
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
            </td>
        </tr>
    </table>
<?php require("includes/footer.php"); ?>