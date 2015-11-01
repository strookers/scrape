<?php

$file = file_get_contents('convertcsv.json');
$csv = json_decode($file, true);

foreach($csv as $data) {
	echo $data["Date"];
	echo ": ";
	echo $data["HomeTeam"];
	echo " ";
	echo $data["FTHG"];
	echo " - ";
	echo $data["FTAG"];
	echo " ";
	echo $data["AwayTeam"];
}

// For loop
// Find out how many elements is in the array by using the count feature in php
$amountOfElementsInArray = count($csv);

// For loop (Everything is contained in the for() part of the loop, first init iterationsOfArray, and keep running while iterationsOfArray is less than or equals to amountOfElementsInArray, and everytime a loop has run, increment iterationsOfArray
for($iterationsOfArray = 0; $iterationsOfArray <= $amountOfElementsInArray; $iterationsOfArray++);
{
  // Echo out the data, in this case $iterationsOfArray is the correct key ID everytime for the element in question
  // For example $csv[0]["Date"]; on the first loop, [1] on 2nd, [2] on 3rd etc.
  echo $csv[$iterationsOfArray]["Date"];
  echo $csv[$iterationsOfArray]["HomeTeam"];
  echo $csv[$iterationsOfArray]["FTHG"];
  echo $csv[$iterationsOfArray]["FTAG"];
  echo $csv[$iterationsOfArray]["AwayTeam"];
}

// While Loop
// Find out how many elements is in the array by using the count feature in php
$amountOfElementsInArray = count($csv);

// Init Iterations
$iterationsOfArray = 0;

// Run whole iterationsOfArray is less than or equals amountOfElementsInArray
while($iterationsOfArray <= $amountOfElementsInArray)
{
  // Echo out the data, in this case $iterationsOfArray is the correct key ID everytime for the element in question
  // For example $csv[0]["Date"]; on the first loop, [1] on 2nd, [2] on 3rd etc.
  echo $csv[$iterationsOfArray]["Date"];
  echo $csv[$iterationsOfArray]["HomeTeam"];
  echo $csv[$iterationsOfArray]["FTHG"];
  echo $csv[$iterationsOfArray]["FTAG"];
  echo $csv[$iterationsOfArray]["AwayTeam"];

  // Increment iterationsOfArray
  $iterationsOfArray++;
}

// foreach loop
// Foreach loops through all the elements in csv, and binds it to $element which is exposed inside the curly braces
// For example, on first iteration, $elements is the same as $csv[0]["Date"]; in the previous two
// Only difference is you don't have to increment anything, or define anything up front, you just tell it to loop over all elements in the array, till it's done.
foreach($csv as $element)
{
  echo $element["Date"];
  echo $element["HomeTeam"];
  echo $element["FTHG"];
  echo $element["FTAG"];
  echo $element["AwayTeam"];
}

// do while

// Do the same as the for and the while, and continue running while iterationsOfArray is less than or equals elementsInArray
// Do While's are used for long running processes mostly, do { ...stuff... } while($shouldIRun = true); once $shouldIRun is set to false inside the do, it quits.
$elementsInArray = count($csv);
$iterationsOfArray = 0;
do {
  // Echo out the data, in this case $iterationsOfArray is the correct key ID everytime for the element in question
  // For example $csv[0]["Date"]; on the first loop, [1] on 2nd, [2] on 3rd etc.
  echo $csv[$iterationsOfArray]["Date"];
  echo $csv[$iterationsOfArray]["HomeTeam"];
  echo $csv[$iterationsOfArray]["FTHG"];
  echo $csv[$iterationsOfArray]["FTAG"];
  echo $csv[$iterationsOfArray]["AwayTeam"];

  // Increment iterationsOfArray
  $iterationsOfArray++;
} while($iterationsOfArray <= $elementsInArray);

// do while example (echo's out yay till cnt hits 100)
$cnt = 0;
do { echo "yay"; $cnt++; } while ($cnt <= 99);