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
      <script src="/scripts-styles/js/bootstrap.min.js"></script>
      <script src="http://code.jquery.com/jquery-latest.min.js"></script>
      <script src="/scripts-styles/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="https://www.google.com/jsapi"></script>
      <script src="/scripts-styles/js/main.js"></script>
            
      <!---------- Font Family ---------->
      <link href='http://fonts.googleapis.com/css?family=Share' rel='stylesheet'>
  
      <!---------- FAVICON -------------->
      <link rel="shortcut icon" href="/favicon.ico" />
      
      <script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
      
    function drawChart() {
      var jsonData = $.ajax({
          url: "cpl.php",
          dataType:"json",
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, {width: 400, height: 240});
    }

    </script>
      
  </head>
  <body>
      <div id="main">
