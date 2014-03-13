<?php
	$pattern = '/\\\/'; 
	$test = "I\\\'ve worked for companies ranging from Dell to the official engineering sector of the Taiwanese government (ITRI) so I\\\'m extremely capable and literally trained to edit. Thus, if you have a 50 page essay, I would be willing to completely help with that. Prices are negotiable."; 
	$str = preg_replace($pattern,'',$test); 
	echo $pattern;
	echo 'str is:';
	echo $str; 
?>
