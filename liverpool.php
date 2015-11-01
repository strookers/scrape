<?php
/*
 * Converts CSV to JSON
 * Example uses Google Spreadsheet CSV feed
 * csvToArray function I think I found on php.net
 */
header('Content-type: application/json');
// Set your CSV feed
$feed = 'http://www.football-data.co.uk/mmz4281/1516/E0.csv';
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

$aCount = 0;
//var_dump($csv[0]);
foreach($newArray as $data) {
	if($data["HomeTeam"] == "Liverpool" || $data["AwayTeam"] == "Liverpool")
	{
		$aCount++;
	


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
echo "\nLiverpool har spillet: " .$aCount. " kampe.";

?>
