<?php
// Build json Pie:
/*
  {
    "cols": [
    {"label":"Topping","type":"string"},
    {"label":"Slices","type":"number"}
    ],
    "rows": [
    {"c":[{"v":"Mushrooms"},{"v":4}]},
    {"c":[{"v":"Olives"},{"v":1}]},
    {"c":[{"v":"Zucchini"},{"v":1}]},
    {"c":[{"v":"Pepperoni"},{"v":2}]}
    ]
  }
*/
function buildjsonpie($con) {
  // Get table info 
  $dbquery = "select * from CallsByWeekday";
  $result = mysqli_query($con, $dbquery);
  if (!$result){
    die("Database query failed: " . mysqli_error($con));
  }

  $i = 0;
  while ($row = mysqli_fetch_array($result)){
    $rows[$i] = $row['CbwNumber'];
    $i = $i + 1;
  }

  $jsonData = "{\"cols\":[{\"label\":\"Weekday\",\"type\":\"string\"}," .
              "{\"label\":\"Total\",\"type\":\"number\"}]," .
              "\"rows\":[{\"c\":[{\"v\":\"Monday\"},{\"v\":{$rows[0]}}]}," .
              "{\"c\":[{\"v\":\"Tuesday\"},{\"v\":{$rows[1]}}]}," .
              "{\"c\":[{\"v\":\"Wednesday\"},{\"v\":{$rows[2]}}]}," .
              "{\"c\":[{\"v\":\"Thursday\"},{\"v\":{$rows[3]}}]}," .
              "{\"c\":[{\"v\":\"Friday\"},{\"v\":{$rows[4]}}]}," .
              "{\"c\":[{\"v\":\"Saturday\"},{\"v\":{$rows[5]}}]}," .
              "{\"c\":[{\"v\":\"Sunday\"},{\"v\":{$rows[6]}}]}]}";

  return $jsonData;
}

// RegEx date pattern
// Determines the MySQL pattern to use for date
  function getdatepattern($date) {
    if (preg_match('/\d{2}\/\d{2}\/\d{4}/',$date)){
      $dtpattern = "'%m/%d/%Y ";
    }
    elseif (preg_match('/\d{2}\/\d{1,2}\/\d{4}/',$date)) {
      $dtpattern = "'%m/%e/%Y ";
    }
    elseif (preg_match('/\d{1,2}\/\d{1,2}\/\d{4}/',$date)) {
      $dtpattern = "'%c/%e/%Y ";
    }
    elseif (preg_match('/\d{2}\/\d{2}\/\d{2}/',$date)){
      $dtpattern = "'%m/%d/%y ";
    }
    elseif (preg_match('/\d{2}\/\d{1,2}\/\d{2}/',$date)) {
      $dtpattern = "'%m/%e/%y ";
    }
    elseif (preg_match('/\d{1,2}\/\d{1,2}\/\d{2}/',$date)) {
      $dtpattern = "'%c/%e/%y ";
    }
    
    return $dtpattern;
  }
  
// RegEx date pattern
// Determines the MySQL pattern to use for date
  function gettimepattern($time) {
    if (preg_match('/\d{1,2}:\d{1,2}:\d{2}[\s]{0,1}[A|P]M/',$time)){
      $tmpattern = "%r'";
    }
    elseif (preg_match('/\d{2}:\d{2}[A|P]M/',$time)) {
      $tmpattern = "%h:%i%p'";
    }
    elseif (preg_match('/\d{2}:\d{2}[\s][A|P]M/',$time)) {
      $tmpattern = "%h:%i %p'";
    }
    else {
      $tmpattern = "%T'";
    }
    
    return $tmpattern;
  }
?>
