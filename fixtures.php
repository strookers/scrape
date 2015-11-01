<?php
/*
 * Converts CSV to JSON
 * Example uses Google Spreadsheet CSV feed
 * csvToArray function I think I found on php.net
 */
header('Content-type: application/json');
// Set your CSV feed
$feed = 'http://www.football-data.co.uk/fixtures.csv';
// Arrays we'll use later
$keys = array();
$newArray = array();
// Function to convert CSV into associative array
function csvToArray($file, $delimiter) { 
  if (($handle = fopen($file, 'r')) !== FALSE) { 
    $i = 0; 
    while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
      for ($j = 0; $j < count($lineArray); $j++) { 
        $arr[$i][$j] = $lineArray[$j]; 
      } 
      $i++; 
    } 
    fclose($handle); 
  } 
  return $arr; 
} 
// Do it
$data = csvToArray($feed, ',');
// Set number of elements (minus 1 because we shift off the first row)
$count = count($data) - 1;
  
//Use first row for names  
$labels = array_shift($data);  
foreach ($labels as $label) {
  $keys[] = $label;
}
// Add Ids, just in case we want them later
$keys[] = 'id';
for ($i = 0; $i < $count; $i++) {
  $data[$i][] = $i;
}
  
// Bring it all together
for ($j = 0; $j < $count; $j++) {
  $d = array_combine($keys, $data[$j]);
  $newArray[$j] = $d;
}
// Print it out as JSON
//print_r($newArray[0]["HomeTeam"]);

//var_dump($csv[0]);
foreach($newArray as $data) {

  if ($data["Div"] == "E0") {

  echo $data["Date"];
  echo ": ";
  echo $data["HomeTeam"];
  echo " ";
  echo $data["FTHG"];
  echo " - ";
  echo $data["FTAG"];
  echo " ";
  echo $data["AwayTeam"];
  echo "\n";
  }
}

$arrayCount = count($newArray);

for($i = 0; $i < $arrayCount; $i++)
{
  if ($newArray[$i]["Div"] == "E0") {
    if ($newArray[$i]["Date"] != $newArray[$i -1]["Date"]) {
      $newDate = $newArray[$i]["Date"];
      echo $newArray[$i]["Date"].":";
      echo "\n";
        foreach($newArray as $data) {

          if ($data["Div"] == "E0") {
            if ($data["Date"] == "$newDate") {
            echo $data["HomeTeam"];
            echo " ";
            echo $data["FTHG"];
            echo " - ";
            echo $data["FTAG"];
            echo " ";
            echo $data["AwayTeam"];
            echo "\n";
          }
          }
        }
        echo "\n";
    }
  }
}

?>
