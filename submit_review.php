<?php 
include_once "common/base.php"; 
include_once "inc/inc.class.php"; 

if ($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_SESSION['fn'])&&isset($_SESSION['ln'])){
	$user = new cfuser($db); 
	if ($user->postReview($_POST['review_text'],$_POST['rating'],$_POST['review_item_id'],$_POST['review_user_id'])){
		$reviewArray = array(); 
		$reviewArray['review']= $_POST['review_text']; 
		$reviewArray['rating']= $_POST['rating'];
		$reviewArray['fn'] = $_SESSION['fn']; 
		$reviewArray['ln'] = $_SESSION['ln']; 
		echo json_encode($reviewArray); 
	}else{
		echo "failure";
	}
}

?>