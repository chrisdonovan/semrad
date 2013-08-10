<?php require_once("includes/dbcon.php"); ?>
<?php require_once("includes/functions.php"); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <meta name="viewport" content="width-device-width, initial-scale=1.0">
  
      <!----------   Title    ----------->
	  <title>
	  <?php 
	  	if (defined ('TITLE')) {
			echo 'Chris Donovan - ' . TITLE;
		} else {
			echo 'Chris Donovan';
		}
	  ?>
      </title>
      <!---------- CSS Files ------------>
      <link href="/scripts-styles/css/bootstrap.min.css" rel="stylesheet" media="screen" />
      <link href="/scripts-styles/css/main.css" rel="stylesheet" media="screen" />
      <link href="/scripts-styles/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen" />
      
      <!---------- Script Files --------->
      <script src="http://code.jquery.com/jquery-latest.min.js"></script>
      <script src="/scripts-styles/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="https://www.google.com/jsapi"></script>
      <script src="/scripts-styles/js/main.js"></script>
            
      <!---------- Font Family ---------->
      <link href='http://fonts.googleapis.com/css?family=Share' rel='stylesheet'>
  
      <!---------- FAVICON -------------->
      <link rel="shortcut icon" href="/favicon.ico" />
      

      
  </head>
    <body>
        <div id="main">
        <?php include("./includes/menu.php"); ?>
        <?php 

// If there was a submit
//if(isset($_POST['submit'])) {
/*
 * while ($row = mysqli_fetch_array($result)){
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
    echo "<div class='row'>";
    echo "<div class='span4'>";
    echo "<br /><h4 style='color:black'>All calls for <b>{$station_name}</b> between <b>{$date_begin}</b> and <b>{$date_end}</b></h4>";
    
    // Get all calls for week 
    $dbquery = "SELECT * FROM AllCallsForWeek";
    $result = mysqli_query($con, $dbquery);
    if (!$result){
        die("Database query failed: " . mysqli_error($con));
    }

    echo "<table width='100%'><tr><td width='20%'><b>Date</b></td><td width='30%'><b>Time</b></td><td width='50%'><b>Caller ID</b></td><tr>";
    while ($row = mysqli_fetch_array($result)){
        echo "<tr>";
        echo "<td width='33%' bgcolor='gray'>{$row["AcwDate"]}</td>";
        echo "<td width='33%'>{$row["AcwTime"]}</td>";
        echo "<td width='34%' bgcolor='gray'>{$row["AcwCallID"]}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Begin right margin
    echo "</div><div class='span6'><div id='chart_div'></div></div></div>";
    echo "<br /><div class='row'>";
    echo "<div class='span6'><h4 style='color:black'>Relevant calls for <b>{$station_name}</b> between <b>{$date_begin}</b> and <b>{$date_end}</b></h4>";
    
    // Get relevant calls for week 
    $dbquery = "SELECT * FROM RelCallsForWeek";
    $result = mysqli_query($con, $dbquery);
    if (!$result){
        die("Database query failed: " . mysqli_error($con));
    }
        
    echo "<table width='100%'><tr><td width='15%'><b>Date</b></td><td width='15%'><b>Air Time</b></td><td width='15%'><b>Call Time</b></td><td width='10%'><b>Cost</b></td><td width='45%'><b>ISCI</b></td><tr>";
    while ($row = mysqli_fetch_array($result)){
        echo "<tr>";
        echo "<td width='15%' bgcolor='gray'>{$row["RcwDate"]}</td>";
        echo "<td width='15%'>{$row["RcwTime"]}</td>";
        echo "<td width='15%' bgcolor='gray'>{$row["RcwCallTime"]}</td>";
        echo "<td width='10%'>\${$row["RcwPrice"]}</td>";
        echo "<td width='45%' bgcolor='gray'>{$row["RcwISCI"]}</td>";
        echo "</tr>";
    }
    echo "</table></div></div><br>";
    
    $jsonData = buildjsonpie($con);
    echo $jsonData;
    
//}
?>
</div>
        <br /><br /><br />

<?php require("includes/footer.php"); ?>