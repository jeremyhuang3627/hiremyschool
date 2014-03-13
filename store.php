<?php
include_once "common/base.php"; 
include_once "inc/inc.class.php";
include_once "common/header.php"; 
include_once "common/formvalidator.php";
?>
<script type="text/javascript" src="js/store.js"></script> 
<style> 
#store-no-items{
	width:250px;
	margin-left:auto; 
	margin-right:auto;
	margin-top:100px;	
}

#store-no-items img{
	margin-left:auto; 
	margin-right:auto;
}
</style>
<div class="update-success popup">
    <span style="font-size:20px;"><img src="img/tick.png" />Item information successfully saved.</span>
</div> 
<div id="confirm-deleteItem-dialog" hidden> 
    <span>Do you really want to delete this Item?</span> 
</div>
<?
$user_id = $_SESSION['user_id'];
$user = new cfuser($db); 
if ($_SERVER['REQUEST_METHOD']=="POST"){
	$validator = new FormValidator();
	$validator->addValidation("service","req","Please fill in your service name.");
    $validator->addValidation("price","req","Please indicate how you are going to charge.");
    $validator->addValidation("description","req","Please fill in your service description.");

    if($validator->ValidateForm())
    {
        if (!$user->addItem($_POST['service'],$_POST['price'],$_POST['description'],$user_id)){
        	echo "<p>Add item failed.</p>";
        	}
    }
}

if (!$user->loadStore($user_id)){
	echo "<div id='store-no-items'><img src='img/shopping_cart.png' /><br /><span>You have no items in store.</span></div>";
}

include_once "common/popup.php";
include_once "common/footer.php";
?> 