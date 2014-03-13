<?php
include_once "common/base.php";

if ($_SERVER['REQUEST_METHOD']=="POST"){
	  include_once "inc/inc.class.php";
	  $user = new cfuser($db); 
	  $response = array("status"=>"success"); 
      if(!$user->activateAccount()){
      		$response['status']="error"; 
      }
      echo json_encode($response); 
}

?>