<?php require_once("includes/functions.php"); ?>
<?php require_once("includes/dbcon.php"); ?>
<?php define('TITLE','Results'); ?>
<?php include("includes/header.php"); ?>
<?php include("./includes/menu.php"); ?>

<div class="container-fluid">
  <div class="row-fluid">
    <div class="span1 hidden-phone"></div>
    <div class="span5"><?php 
      // If there was a submit
      //if(isset($_POST['submit'])) {
      /*
      while ($row = mysqli_fetch_array($result)){
          echo "<li>{$row["menu_name"]}</li>";
      }
      */
      $dnis_id = $_POST['dnis'];
      $wkbegin = $_POST['wkbegin'] . ' 00:00:00';
      $wkend = $_POST['wkend'] . ' 23:59:59';

      // Run the stored procedure
      $dbquery = "CALL CplByDateRange(convert('{$wkbegin}',datetime),convert('{$wkend}',datetime),{$dnis_id})";
      $result = mysqli_query($con, $dbquery);
      if (!$result){
          die("Database query failed: " . mysqli_error($con));
      }

      // Gather information from tables created
      // TABLES: CostForWeek(CfwCost), AllCallsForWeek(AcwDate,AcwTime,AcwCallID), RelCallsForWeek(RcwDate,RcwTime,RcwCallTime,RcwPrice,RcwISCI)
      $dbquery = "SELECT * FROM CostForWeek";
      $result = mysqli_query($con, $dbquery);
      if (!$result){
          die("Database query failed: " . mysqli_error($con));
      }
      $rows = mysqli_fetch_array($result);
      $total_cost = $rows["CfwCost"];

      // Get call count for CPL calculation
      $dbquery = "SELECT COUNT(*) as `total` FROM AllCallsForWeek";
      $result = mysqli_query($con, $dbquery);
      if (!$result){
          die("Database query failed: " . mysqli_error($con));
      }
      $rows = mysqli_fetch_array($result);
      $total_calls = $rows["total"];

      // Get table info 
      $dbquery = "select OutStation, date_format(OutDateBegin,'%m/%d/%Y') as OutDateBegin, date_format(OutDateEnd,'%m/%d/%Y') as OutDateEnd from OutputInformation";
      $result = mysqli_query($con, $dbquery);
      if (!$result){
          die("Database query failed: " . mysqli_error($con));
      }
      $rows = mysqli_fetch_array($result);
      $station_name = $rows["OutStation"];
      $date_begin = $rows["OutDateBegin"];
      $date_end = $rows["OutDateEnd"];

      echo "<h4>Total Cost: \${$total_cost}";
      echo "<br />Total Calls: {$total_calls}";

      $cpl = $total_cost / $total_calls;
      echo "<br />CPL: \${$cpl}" . "</h4>";

      // Begin left margin
      echo "<br /><h4 style='color:black'>All calls for <b>{$station_name}</b> between <b>{$date_begin}</b> and <b>{$date_end}</b></h4>";

      // Get all calls for week 
      $dbquery = "SELECT * FROM AllCallsForWeek";
      $result = mysqli_query($con, $dbquery);
      if (!$result){
          die("Database query failed: " . mysqli_error($con));
      }

      echo "<table width='100%' class=\"table table-condensed\"><tr><td width='20%'><b>Date</b></td><td width='30%'><b>Time</b></td><td width='50%'><b>Caller ID</b></td></tr>";
      while ($row = mysqli_fetch_array($result)){
          echo "<tr>";
          echo "<td width='33%' bgcolor='gray'>{$row["AcwDate"]}</td>";
          echo "<td width='33%'>{$row["AcwTime"]}</td>";
          echo "<td width='34%' bgcolor='gray'>{$row["AcwCallID"]}</td>";
          echo "</tr>";
      }
      echo "</table>";
  ?></div>
    <div class='span5'>
        <div id='pie_chart_div'></div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span1 hidden-phone"></div>
    <div class='span5'><?php

        // Begin right margin
        echo "<h4 style='color:black'>Relevant calls for <b>{$station_name}</b> between <b>{$date_begin}</b> and <b>{$date_end}</b></h4>";

        // Get relevant calls for week 
        $dbquery = "SELECT * FROM RelCallsForWeek";
        $result = mysqli_query($con, $dbquery);
        if (!$result){
            die("Database query failed: " . mysqli_error($con));
        }

        echo "<table width='100%'><tr><td width='15%'><b>Date</b></td><td width='15%'><b>Air Time</b></td><td width='15%'><b>Call Time</b></td><td width='10%'><b>Cost</b></td><td width='45%'><b>ISCI</b></td></tr>";
        while ($row = mysqli_fetch_array($result)){
            echo "<tr>";
            echo "<td width='15%' bgcolor='gray'>{$row["RcwDate"]}</td>";
            echo "<td width='15%'>{$row["RcwTime"]}</td>";
            echo "<td width='15%' bgcolor='gray'>{$row["RcwCallTime"]}</td>";
            echo "<td width='10%'>\${$row["RcwPrice"]}</td>";
            echo "<td width='45%' bgcolor='gray'>{$row["RcwISCI"]}</td>";
            echo "</tr>";
        }
        echo "</table>";

        $jsonData = buildjsonpie($con);

        //}
    ?></div>
  </div>
<br />

<!--- Begin PieChart JS --->
<script type="text/javascript">
  // Load the Visualization API and the piechart package.
  google.load('visualization', '1', {'packages':['corechart']});
  
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawChart);
  
  // Function that creates the chart
  function drawChart() {      
    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable('<?php echo $jsonData; ?>');
    
    // Instantiate chart
    var chart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
    
    // Define options
    var options = {
      title: '<?php echo "Calls Per Weekday for {$station_name}"; ?>',
      titleTextStyle: {bold: true, fontSize: 20},
      pieSliceText: 'value',
      width: 800,
      height: 480,
      is3D: true,
      backgroundColor: '#BFAA8F',
      legend: {position:'left', textStyle: {color: 'black', fontSize: 18}},
      pieSliceTextStyle: {fontSize: 16}
    };
    
    // Draw chart with options
    chart.draw(data, options);
  }
</script>
<?php require("includes/footer.php"); ?>