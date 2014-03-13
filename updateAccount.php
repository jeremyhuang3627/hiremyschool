<?php
if (!(isset($_POST['email'])&&isset($_POST['school'])&&isset($_POST['first_name'])&&isset($_POST['last_name']))){
	exit(); 
}

include_once "common/base.php"; 
include_once "inc/inc.class.php"; 

$email = htmlspecialchars($_POST['email']); 
$school = htmlspecialchars($_POST['school']);
$fn = htmlspecialchars($_POST['first_name']);
$ln = htmlspecialchars($_POST['last_name']);
$user_id = htmlspecialchars($_POST['user_id']);
//$pass = $_POST['password']; 

if (!is_numeric($user_id)){
	exit(); 
}

$user = new cfuser($db); 
if ($user->updateAccountInfo($email,$school,$fn,$ln,$user_id)){
	echo json_encode(array('status'=>'success')); 
} else {
	echo json_encode(array('status'=>'failure')); 
}
?>