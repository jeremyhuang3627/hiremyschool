<?php
include_once "common/base.php";
include_once "common/formvalidator.php"; 

error_reporting(0);

if ($_SERVER["REQUEST_METHOD"]=="POST"){ 
		$validator = new FormValidator();
	    $validator->addValidation("email","email","The input for Email should be a valid email value");
   		$validator->addValidation("email","req","Please fill in Email");	

   		if($validator->ValidateForm())
    	{
        //Validation success. 
        //Here we can proceed with processing the form 
        //(like sending email, saving to Database etc)
        // In this example, we just display a message
        include_once "inc/inc.class.php";
        $user = new cfuser($db); 
        $type = $user->createAccount(); 
        $response = array("status"=>"success");  
        if($type==1){
        	$response["status"] = "repeat"; //email was already registered; 
        }else if(!$type==0){
        	$response["status"] = "error"; //other errors
        }
        echo json_encode($response); 
  } 
}
?>