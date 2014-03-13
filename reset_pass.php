<?php 
include_once "common/base.php"; 
include_once "inc/inc.class.php"; 
include_once "common/header.php"; 
$user = new cfuser($db); 
$v = $_GET['v'];
$e = $_GET['e'];  
$user->reset_pass_verify_account($v,$e); 
include_once "common/footer.php"; 
include_once "common/popup.php";
?>
<div class="pass-word-change popup">
	<a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<span style="font-size:18px;"><img src="img/tick.png" />Your password has been successfully changed.</span>
</div>

