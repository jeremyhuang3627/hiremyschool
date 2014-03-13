<?php
	//set the error reporting level
	//error_reporting(E_ALL); 
	error_reporting(0);
	ini_set("display_errors",1); 

	//start a php session
	session_start(); 
	include_once "inc/constants.inc.php"; 

	//create a database object
	try{
		$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME; 
		$db = new PDO($dsn,DB_USER, DB_PASS); 
	}catch(PDOException $e){
		echo "Connection failed: ".$e->getMessage(); 
		exit;
	}
?>
