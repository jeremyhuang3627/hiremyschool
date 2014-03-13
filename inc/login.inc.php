<?php 
//include_once "common/base.php";
include_once "common/formvalidator.php"; 
error_reporting (0);

if ($_SERVER["REQUEST_METHOD"]=="POST"){

    $validator = new FormValidator();
    $validator->addValidation("email","email","The input for Email should be a valid email value");
    $validator->addValidation("email","req","Please fill in Email");
    $validator->addValidation("password","minlen=5","Your password should have at least 5 characters");
    //Now, validate the form
    
    if($validator->ValidateForm())
    {
        //Validation success. 
        //Here we can proceed with processing the form 
        //(like sending email, saving to Database etc)
        // In this example, we just display a message
       // include_once "inc/inc.class.php";
        $user = new cfuser($db); 
        $user->accountLogin(); 
    }
    else
    {
        $error_hash = $validator->GetErrors();
        foreach($error_hash as $inpname => $inp_err)
        {
            echo "<p>$inpname : $inp_err</p>\n";
        }        
    }//else
}//if(isset($_POST['Submit']))

?>
