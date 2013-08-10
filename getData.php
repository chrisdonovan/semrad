<?php

    require_once("includes/dbcon.php");
    //require_once("includes/functions.php");
	
    $dnis_id = $_POST['dnis'];
    $wkbegin = $_POST['wkbegin'] . ' 00:00:00';
    $wkend = $_POST['wkend'] . ' 23:59:59';
    $jsonFile = "sampleData.json";

    $dbquery = "CALL CplByDateRange(convert('{$wkbegin}',datetime),convert('{$wkend}',datetime),{$dnis_id})";
    $result = mysqli_query($con, $dbquery);
    if (!$result){
        die("Database query failed: " . mysqli_error($con));
    }
    
    $dbquery = "select * from CallsByWeekday";
    $result = mysqli_query($con, $dbquery);
    if (!$result){
        die("Database query failed: " . mysqli_error($con));
    }
    
    // Clear file contents
    file_put_contents($jsonFile, "");
 
    $output = '{ "cols" : [{"label":"Weekday","type":"string"},{"label":"Total","type":"number"}], "rows" : [';

    while ($row = mysqli_fetch_array($result)){
        //echo $row['CbwDay'];
        $output .= '{"c":[{"v":"' . $row['CbwDay'] . '"},{"v":' . (int) $row['CbwNumber'] . '}]},';
    }
    $output = substr($output,0,strlen($output) - 1);
    $output .= ']}';
    
    //echo $output;
    // Input new file contents into json file
    //file_put_contents($jsonFile, $output);
    //header("/viewCpl.php")
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
     <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
      
    function drawChart() {
      var jsonData = '<?php echo $output; ?>'
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, {width: 400, height: 240});
    }

    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>