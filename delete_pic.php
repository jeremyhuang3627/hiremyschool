<?php 
include_once "common/base.php"; 
include_once "inc/inc.class.php"; 
if ($_SERVER['REQUEST_METHOD']=="POST"){
	$pic_id = $_POST['id']; // this  is the pic_id
	$user_id = $_SESSION['user_id'];
	$user = new cfuser($db); 
	if ($user->deleteItemPic($pic_id,$user_id)){
		echo "success"; 
	}else{
		echo "failure";
	}
}
?>