<?php require_once("includes/functions.php"); ?>
<?php require_once("includes/dbcon.php"); ?>
<?php define('TITLE','Results'); ?>
<?php include("includes/header.php"); ?>
<?php include("./includes/menu.php"); ?>
<br />
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span2 hidden-phone"></div>
    <div class="span8"><?php 
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
      if ($rows["CfwCost"]) {
        $total_cost = $rows["CfwCost"];
      }
      else {
        $total_cost = 0;
      }

      // Get call count for CPL calculation
      $dbquery = "SELECT COUNT(*) as `total` FROM AllCallsForWeek";
      $result = mysqli_query($con, $dbquery);
      if (!$result){
          die("Database query failed: " . mysqli_error($con));
      }
      $rows = mysqli_fetch_array($result);
      if ($rows["total"]) {
        $total_calls = $rows["total"];
      }
      else {
        $total_calls = 0;
      }
      
      // Get table info 
      $dbquery = "select OutStation, date_format(OutDateBegin,'%m/%d/%Y') as OutDateBegin, " . 
                 "date_format(OutDateEnd,'%m/%d/%Y') as OutDateEnd from OutputInformation";
      $result = mysqli_query($con, $dbquery);
      if (!$result){
          die("Database query failed: " . mysqli_error($con));
      }
      $rows = mysqli_fetch_array($result);
      $station_name = $rows["OutStation"];
      $date_begin = $rows["OutDateBegin"];
      $date_end = $rows["OutDateEnd"];

      echo "<h4>Total Cost: \$" . $total_cost;
      echo "<br />Total Calls: " . $total_calls;

      if ($total_calls === 0) {
        echo "<br />CPL: \$" . $total_cost . "</h4>";
      }
      else {
        echo "<br />CPL: \$" . $total_cost / $total_calls . "</h4>";
      }
      ?>
      <div class="container-fluid">
        <div class="row-fluid">
            <div class="span1 hidden-phone"></div>
            <div class='span8 center'>
              <div id='pie_chart'></div>
          </div>
        </div>
      </div>
      <br />
      <div class="container-fluid">
        <div class="row-fluid">
            <div class="span1 hidden-phone"></div>
            <div class='span8 center'>
              <div id='line_chart'></div>
            </div>
        </div>
      </div><?php
      // Begin left margin
      echo "<br /><h4 style='color:black'>All calls for <b>{$station_name}</b> between " . 
           "<b>{$date_begin}</b> and <b>{$date_end}</b></h4>";

      // Get all calls for week 
      $dbquery = "SELECT DATE_FORMAT(STR_TO_DATE(AcwDate,'%m/%d/%Y'), '%W, %m/%d/%Y') " .
                 "as AcwDate, AcwTime, AcwCallID FROM AllCallsForWeek";
      $result = mysqli_query($con, $dbquery);
      if (!$result){
          die("Database query failed: " . mysqli_error($con));
      }

      echo "
      <table id='all-calls-table' width='100%' class=\"table table-condensed table-striped table-hover\">
        <thead>
        <tr>
          <td width='20%'><b>Date</b></td>
          <td width='30%'><b>Time</b></td>
          <td width='50%'><b>Caller ID</b></td>
        </tr>
        </thead>
        <tbody>";
      while ($row = mysqli_fetch_array($result)){
          echo "<tr>";
          echo "<td width='33%'>{$row["AcwDate"]}</td>";
          echo "<td width='33%'>{$row["AcwTime"]}</td>";
          echo "<td width='34%'>{$row["AcwCallID"]}</td>";
          echo "</tr>";
      }
      echo "</tbody></table>";
  ?></div>
  </div>
  <br /><br />
  <div class="row-fluid">
    <div class="span2 hidden-phone"></div>
    <div class='span8'><?php

        // Begin right margin
        echo "<h4 style='color:black'>Relevant calls for <b>{$station_name}</b> " .
             "between <b>{$date_begin}</b> and <b>{$date_end}</b></h4>";

        // Get relevant calls for week 
        $dbquery = "SELECT DATE_FORMAT(STR_TO_DATE(RcwDate,'%m/%d/%Y'), '%W, %m/%d/%Y') " .
                   "as RcwDate, RcwTime, RcwCallTime, RcwPrice, RcwISCI FROM RelCallsForWeek";
        $result = mysqli_query($con, $dbquery);
        if (!$result){
            die("Database query failed: " . mysqli_error($con));
        }

        echo "
        <table width='100%' id='relevant-table' class=\"table table-condensed table-striped table-hover\">
          <thead>
          <tr>
            <th width='15%'><b>Date</b></th>
            <th width='15%'><b>Air Time</b></th>
            <th width='15%'><b>Call Time</b></th>
            <th width='10%'><b>Cost</b></th>
            <th width='45%'><b>ISCI</b></th>
          </tr>
          </thead>
          <tbody>";
        while ($row = mysqli_fetch_array($result)){
            echo "<tr>";
            echo "<td width='15%'>{$row["RcwDate"]}</td>";
            echo "<td width='15%'>{$row["RcwTime"]}</td>";
            echo "<td width='15%'>{$row["RcwCallTime"]}</td>";
            echo "<td width='10%'>\${$row["RcwPrice"]}</td>";
            echo "<td width='45%'>{$row["RcwISCI"]}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";

        $jsonPieData = buildjsonpie($con);
        $jsonLineData = buildjsonline($con);
    ?></div>
  </div>
  <br /><br />
<br />

<!-- Begin PieChart JS -->
<script type="text/javascript">
  // Load the Visualization API and the piechart package.
  google.load('visualization', '1', {'packages':['corechart']});
  
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawPieChart);
  
  // Function that creates the chart
  function drawPieChart() {      
    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable('<?php echo $jsonPieData; ?>');
    
    // Instantiate chart
    var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
    
    // Define options
    var options = {
      title: '<?php echo "Calls Per Weekday for {$station_name}"; ?>',
      titleTextStyle: {bold: true, fontSize: 20},
      pieSliceText: 'value',
      width: 500,
      height: 400,
      is3D: true,
      legend: {position:'left', textStyle: {color: 'black', fontSize: 18}},
      pieSliceTextStyle: {fontSize: 16},
      chartArea: {left:20,top:60,width:"90%",height:"90%"},
      fontName: 'Source Sans Pro'
    };
    
    // Draw chart with options
    chart.draw(data, options);
  }
</script>
<!-- Begin LineChart JS -->
<script type="text/javascript">
  // Load the Visualization API and the piechart package.
  google.load('visualization', '1', {'packages':['corechart']});
  
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawLineChart);
  
  // Function that creates the chart
  function drawLineChart() {      
    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable('<?php echo $jsonLineData; ?>');
    
    // Instantiate chart
    var chart = new google.visualization.LineChart(document.getElementById('line_chart'));
    
    // Define options
    var options = {
      title: '<?php echo "Calls Per Day for {$station_name}"; ?>',
      titleTextStyle: {bold: true, fontSize: 20},
      pieSliceText: 'value',
      width: 800,
      is3D: true,
      legend: {position:'left', textStyle: {color: 'black', fontSize: 18}},
      pieSliceTextStyle: {fontSize: 16},
      chartArea: {left:20,width:"100%"},
      fontName: 'Source Sans Pro'
    };
    
    // Draw chart with options
    chart.draw(data, options);
  }
</script>
<?php require("includes/footer.php"); ?>