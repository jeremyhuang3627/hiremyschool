<?php
include_once "common/base.php"; 
include_once "inc/inc.class.php"; 
$item_per_group = $_POST['item_per_group']; 
$group_number = filter_var($_POST["group_no"], FILTER_SANITIZE_NUMBER_INT,FILTER_FLAG_STRIP_HIGH); 

if (!is_numeric($group_number)){
	exit(); 
}

$query = $_POST['query']; 
$user = new cfuser($db);
$position = ($group_number*$item_per_group); 
if(strlen($query)==0){
	//echo "position is ".$position;
	$user->loadItems($position,$item_per_group); 
}else{
	include_once "inc/constants.inc.php"; 
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
/* check connection */
	if (mysqli_connect_errno()) {
   	 	printf("Connect failed: %s\n", mysqli_connect_error());
   		 exit();
	}
	$query = $mysqli->real_escape_string($query);
	$user->search($query,$position,$item_per_group);
}
?>