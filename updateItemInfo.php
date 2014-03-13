<?php

if (!(isset($_POST['service'])&&isset($_POST['price'])&&isset($_POST['description'])&&isset($_POST['item_id']))){
	exit(); 
}

$service =$_POST['service']; 
$price = $_POST['price']; 
$description = $_POST['description']; 
$item_id = $_POST['item_id']; 

if(!is_numeric($item_id)){
	exit();
}

include_once "common/base.php"; 
include_once "inc/inc.class.php"; 

$user = new cfuser($db); 
if ($user -> updateItemInfo($item_id,$service,$price,$description)){
	echo json_encode(array('status'=>'success')); 
}else{
	echo json_encode(array('status'=>'failure'));
}
?>