<?php 
	session_start();
	if(isset($_SESSION['loggedIn'])&&isset($_SESSION['username'])){
	unset($_SESSION['username']);
	unset($_SESSION['loggedIn']); 
	unset($_SESSION['fn']);
	unset($_SESSION['ln']);
	unset($_SESSION['user_id']);
	header("Location: index.php");
	} 
?>