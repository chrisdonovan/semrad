<?php
//    // callsCSV reads the CSV file and uploads it to the calls DB
//    function callsCSV($csvName){
//        $csvFile = "./src/csv/{$csvName}.csv";
//        $csv = readCSV($csvFile);
//
//        foreach($csv as $entry){
//            $dbquery = "INSERT INTO calls (date,duration,caller_number,target_number,dnis) VALUES " .
//                       "('{$entry[0]} {$entry[1]}','{$entry[2]}','{$entry[3]}','{$entry[4]}','{$entry[5]}')";
//            $result = mysqli_query($con, $dbquery);
//            if (!$result){
//                die("Database query failed: " . mysqli_error());
//            }
//        }
//    }

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

?>
