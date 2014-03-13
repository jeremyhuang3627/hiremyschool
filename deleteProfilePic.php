<?php
session_start(); 
$user_id = $_POST['user_id']; 
$user_id_session = $_SESSION['user_id'];
//echo $user_id_session;

if((!is_numeric($user_id))||($user_id!=$user_id_session)){
	header("Location: index.php"); 
	exit(); 
}

include_once "common/base.php"; 
include_once "inc/inc.class.php"; 
$user = new cfuser($db); 
if ($user->deleteProfilePic($user_id)){
	echo json_encode(array("status"=>"success")); 
}else{
	echo json_encode(array("status"=>"failure"));
}
?>