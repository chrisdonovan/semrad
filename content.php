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
                
            </td>
        </tr>
    </table>
<?php require("includes/footer.php"); ?>