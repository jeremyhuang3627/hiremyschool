<?php
$test = "dorm cleaning for 50 bucks new york university"; 
$test = mysql_real_escape_string($test);
echo $test;
$array = preg_split("/[\s,]+/", $test);
print_r($array);
?>