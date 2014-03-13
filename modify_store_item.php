<?php 
include_once "common/base.php";
?>
<noscript>
<div align="center"><a href="index.php">Go Back To Upload Form</a></div><!-- If javascript is disabled -->
</noscript>
<?php
include_once "inc/inc.class.php";

if ($_SERVER['REQUEST_METHOD']=='POST'){
	if (isset($_POST['delete']))
	{
		$user_id = $_SESSION['user_id']; 
		$item_id = $_POST['delete']; 
		$user = new cfuser($db); 
		if ($user->deleteItem($item_id, $user_id)){
			echo $item_id." has been deleted"; 
		}else{
			echo "deletion failure"; 
		//	echo "item_id".$item_id;
		//	echo "user_id".$user_id; 	
		};
	}else{
		echo "oops";
		echo "Post is ".$_POST['delete'];
	}
}else{
	echo "what is going on?";
}
?> 