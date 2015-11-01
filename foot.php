<?php

//$data = file_get_contents("http://www.football-data.co.uk/mmz4281/1516/E0.csv");
//$dataArray = str_getcsv($data, '\n');

//var_dump($dataArray);

$file = file_get_contents('convertcsv.json');
$csv = json_decode($file, true);
$aCount = 0;
//var_dump($csv[0]);
foreach($csv as $data) {
	if($data["HomeTeam"] == "Arsenal" || $data["AwayTeam"] == "Arsenal")
	{
		$aCount++;
	}

echo $data["Date"];
echo ": ";
echo $data["HomeTeam"];
echo " ";
echo $data["FTHG"];
echo " - ";
echo $data["FTAG"];
echo " ";
echo $data["AwayTeam"];
echo "<br>";
}

echo "arsenal har spillet: " .$aCount. " kampe.";