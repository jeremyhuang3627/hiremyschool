<?php

// If you want to ignore the uploaded files, 
// set $demo_mode to true;

$demo_mode = false;
$upload_dir = 'uploads/';
$allowed_ext = array('jpg','jpeg','png','gif');


if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit_status('Error! Wrong HTTP method!');
}



if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 ){
	
	$pic = $_FILES['pic'];

	if(!in_array(get_extension($pic['name']),$allowed_ext)){
		exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
	}	

	if($demo_mode){
		
		// File uploads are ignored. We only log them.
		
		$line = implode('		', array( date('r'), $_SERVER['REMOTE_ADDR'], $pic['size'], $pic['name']));
		file_put_contents('log.txt', $line.PHP_EOL, FILE_APPEND);
		
		exit_status('Uploads are ignored in demo mode.');
	}
	
	// Move the uploaded file from the temporary 
	// directory to the uploads folder:
	if (isset($_POST['user_id'])){
		$user_id = $_POST['user_id']; 
		$upload_dir = $upload_dir."userid".$user_id."_".$pic['name']; 
		if(move_uploaded_file($pic['tmp_name'], $upload_dir)){
			include_once "common/base.php";
			include_once "inc/inc.class.php";
			$user = new cfuser($db); 
	        $result = $user->updateProfilePicDir($upload_dir,$user_id);
			if ($result){
				//resize image here; 
			exit_status('user profile dir was uploaded successfuly!');
			}
		}
	}else if(isset($_POST['item_id'])){
		$randNum = rand(0,99999); 
		$item_id = $_POST['item_id']; 
		$upload_dir = $upload_dir."itemid".$item_id."_".$randNum."_".$pic['name']; 
		if(move_uploaded_file($pic['tmp_name'], $upload_dir)){
			include_once "common/base.php";
			include_once "inc/inc.class.php";
			include_once "resize.php"; 
	        $resizer = new resize($upload_dir); 
	        $thumbdir = $resizer -> resizeImage(54,100); 
	        $user = new cfuser($db); 
	        $result = $user->updateItemPicDir($thumbdir,$upload_dir,$item_id);
			if ($result){
			//echo "working";
			//echo $db->lastInsertId();  	
			exit_status($db->lastInsertId());
			}
		}
	}
	
}

echo $upload_dir." ".$user_id;
exit_status('Something went wrong with your upload!');


// Helper functions

function exit_status($str){
	echo json_encode(array('status'=>$str));
	exit;
}

function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}
?>