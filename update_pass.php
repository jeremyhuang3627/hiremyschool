<?php 

include_once "common/base.php"; 
include_once "inc/inc.class.php"; 

$p = MD5($_POST['password']); 
$id = $_POST['user_id']; 

if(!is_numeric($id)){
	exit(); 
}

$user= new cfuser($db); 
if ($user->updatePassword($p,$id)){
	echo json_encode(array('status'=>'success')); 
}else{
	echo json_encode(array('status'=>'failure')); 
}
?>