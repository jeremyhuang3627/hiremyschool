<?php
class cfuser
{
	private $_db; 
	    /**
     * Checks for a database object and creates one if none is found
     *
     * @param object $db
     * @return void
     */ 
	public function __construct($db=NULL)
	{
		if (is_object($db))
		{
			$this->_db=$db;
		}else{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn,DB_USER,DB_PASS); 
		}
	}

	public function createAccount()
	{
		//0 means ok; 
		
		$e = trim($_POST['email']); 
		// verification code
		$v=sha1(time()); 
	/*	
		$pass=md5($_POST['password']); 
		$school=$_POST['school'];
		$fn = $_POST['first_name'];
		$ln=$_POST['last_name']; */

		$sql = "SELECT COUNT(email) AS theCount FROM users WHERE email=:email"; 

		 
		try{
			$stmt = $this->_db->prepare($sql);
			$stmt -> bindParam(":email",$e,PDO::PARAM_STR);
			$stmt -> execute(); 
			$row = $stmt->fetch(); 
			if ($row['theCount']!=0){

		/*		echo "<h2>Error</h2> "."<p>Sorry,that email is already in use. "
				."Please try again.</p>"; */

				$error_type = 1; 
				return $error_type;
			}

			if(!$this ->sendVerificationEmail($e,$v)){
				echo "verification email failed"; 
				echo "There is an error sending verification email."; 
				$error_type= 2; 
				return $error_type;
				}
			$stmt -> closeCursor(); 
	

			$sql ="INSERT INTO users (email,ver_code,date_registered) VALUES (:email,:ver,NOW())"; 
			    $stmt = $this->_db->prepare($sql);
				$stmt -> bindParam(":email",$e,PDO::PARAM_STR);
				$stmt -> bindParam(":ver",$v, PDO::PARAM_STR); 
			if ($stmt -> execute()){ 
				$stmt -> closeCursor();
				$error_type = 0;
		/*		echo "<p> Successs!</p> ";  */
				return $error_type;

			}else{
		/*		echo "<h2> Error </h2><p> Couldn't insert the "
	                . "user information into the database. </p>"; 
	           echo $e." ".$$v;      */
                    $error_type = 3; 
	             return $error_type;
	            }
	     }catch(PDOException $e){
		/*	echo "<h1>Error!</h1><p>".$e->getMessage()."</p>";    */
			$error_type = 4; 
			return $error_type; 
		}
	}

	public function sendResetEmail($email){
		//first check whether this email has been registed or not
		$sql = "SELECT ver_code FROM users WHERE email = :email"; 
		try{
			$stmt = $this->_db->prepare($sql); 
			$stmt->bindParam(":email",$email,PDO::PARAM_STR);
			$stmt->execute(); 
			$result = $stmt->fetchAll();
		//	print_r($result);
			//echo $sql;
			if ($stmt->rowCount() == 1){
				//send email
				$v = $result[0][0];
				$stmt->closeCursor();
				include_once "lib/swift_required.php"; 	
				$e = sha1($email); 
				$to = trim($email);

				$transport = Swift_SmtpTransport::newInstance('relay-hosting.secureserver.net');
				$mailer = Swift_Mailer::newInstance($transport);	
				$msg  ="A password reset email from Galvea.com 
						 
						Your email: $email
						 
						Click on this link to reset your password: http://galvea.com/reset_pass.php?v=$v&e=$e	 
						--
						Thanks!
						 
						Jeremy
						www.galvea.com";

				$subject = "Galvea - Password Reset"; 
				$message = Swift_Message::newInstance($subject)
							  ->setFrom("info@galvea.com")
							  ->setTo($to)
							  ->setBody($msg)
							  ;
			    $result = $mailer->send($message);
			  //    echo "reset email send success.";
			      return $result; 

			}else{
			//	echo $sql;
			//	echo "This email doesn't exist"; 
				return FALSE; 
			}
		}catch(PDOException $e){
				echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
						return FALSE; 
		}
	}

	public function sendVerificationEmail($email,$ver){
		include_once "lib/swift_required.php"; 	
		$e = sha1($email); 
		$to = trim($email);

		$transport = Swift_SmtpTransport::newInstance('relay-hosting.secureserver.net');
		$mailer = Swift_Mailer::newInstance($transport);
		
		$msg  ="You have a new account at Galvea!
				 
				To get started, please activate your account and choose a
				password by following the link below.
				 
				Your email: $email
				 
				Activate your account: http://galvea.com/signup.php?v=$ver&e=$e	 
				--
				Thanks!
				 
				Jeremy
				www.galvea.com";

		$subject = "Galvea - Please Verify Your Account"; 

		$message = Swift_Message::newInstance($subject)
					  ->setFrom("info@galvea.com")
					  ->setTo($to)
					  ->setBody($msg)
					  ;
	    $result = $mailer->send($message);
	      return $result; 
	}

	public function reset_pass_verify_account($v,$e){
		$sql ="SELECT user_id FROM users WHERE ver_code = :v AND SHA1(email) = :e"; 
		try{
		    $stmt=$this->_db->prepare($sql); 
			$stmt->bindParam(":v",$v,PDO::PARAM_STR); 
			$stmt->bindParam(":e",$e,PDO::PARAM_STR);
		    $stmt->execute(); 
		    if ($stmt->rowCount() == 1){
		    	$result = $stmt->fetchAll(); 
		   	 echo "
		   	 	<script type='text/javascript' src='js/reset_pass.js'></script>
		    	<div id='reset-pass-div'> 
		    		<span>Enter a new password</span>
			    	<form id='reset-pass-form'> 
			    		<input type='hidden' name='user_id' value='".$result[0]['user_id']."' />
			    		<input type='password' name='password' id='password' placeholder='Enter a new password here'/>
			    		<input type='submit' id='reset_pass_btn' class='button' value='Reset Password' />
			    	</form>
		    	</div>"; 
		    	return TRUE; 
		    }else{
		    	echo "<div class='error'>Oops, this is embarrasing. I have encountered a technical error.</div>"; 
		    	return FALSE;
		    }
		}catch(PDOException $e){
				echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
				return FALSE; 
		}
	}

	public function updatePassword($p,$id){
		$sql="UPDATE users SET password = :pass WHERE user_id = :id"; 

		try{
			$stmt = $this->_db->prepare($sql); 
			$stmt->bindParam(':pass',$p,PDO::PARAM_STR); 
			$stmt->bindParam(':id',$id,PDO::PARAM_INT); 
			if ($stmt->execute()){
				$stmt->closeCursor(); 
				return TRUE; 
			}else{
				$stmt->closeCursor(); 
				return FALSE; 
			}
		}catch(PDOException $e){
				echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
				return FALSE; 
		}
	}

	public function verifyAccount()
	{
		$sql = "SELECT user_id FROM users WHERE ver_code =:ver AND SHA1(email)=:user AND ver_status = 0";
		if ($stmt=$this->_db->prepare($sql))
		{
			$stmt->bindParam(':ver',$_GET['v'],PDO::PARAM_STR); 
			$stmt->bindParam(':user',$_GET['e'],PDO::PARAM_STR); 
			$stmt->execute(); 
			$row = $stmt->fetch();
			if (isset($row['user_id']))
			{	
				$_SESSION['user_id']= $row['user_id']; 
				$_SESSION['loggedIn']= 1; 
			} 
			else{
				return array(4, "<h2>Verification Error</h2>n"
                    . "<p>This account has already been verified. "
                    . "Did you <a href='/password.php'>forget "
                    . "your password?</a>".$_GET['v']." ".$_GET['e']);
			}
			$stmt-> closeCursor(); 
			return array(0,NULL); 
		}else
		{
			return array(2,"<h2>Error</h2>n<p>Database error.</p>");
		}
	}

	public function activateAccount(){
		$sql = "UPDATE users SET school = :school,first_name=:fn,last_name=:ln,password=md5(:pass),ver_status=1 WHERE user_id = :id LIMIT 1"; 
		try{
			$stmt = $this->_db->prepare($sql); 
			$stmt->bindParam(":school",$_POST['school'],PDO::PARAM_STR);
			$stmt->bindParam(":fn",$_POST['first_name'],PDO::PARAM_STR); 
			$stmt->bindParam(":ln",$_POST['last_name'],PDO::PARAM_STR); 
			$stmt->bindParam(":pass",$_POST['password'],PDO::PARAM_STR);  
			$stmt->bindParam(":id",$_POST['user_id'],PDO::PARAM_INT); 
			if ($stmt->execute()){
			$stmt->closeCursor(); 
			return TRUE; 
			}else{
			$stmt->closeCursor(); 	
			return FALSE; 
			}
		}catch(PDOException $e){
				echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
						return FALSE; 
		}
	}

	public function accountLogin()
	{
		$sql = "SELECT user_id,email,first_name,last_name FROM users WHERE email=:email AND password=md5(:pass) LIMIT 1"; 
		try {
			$stmt=$this->_db->prepare($sql); 
			$stmt->bindParam(':email',$_POST['email'],PDO::PARAM_STR); 
			$stmt->bindParam(':pass',$_POST['password'],PDO::PARAM_STR); 
			$stmt->execute();
			if($stmt->rowCount()==1)
			{
				$result = $stmt->fetchAll();
				$_SESSION['username']=htmlentities($_POST['email'],ENT_QUOTES); 
				$_SESSION['loggedIn']=1; 
				$_SESSION['fn']=$result[0]['first_name']; 
				$_SESSION['ln']=$result[0]['last_name'];
				$_SESSION['user_id'] = $result[0]['user_id'];
				echo "<div id='login-success'><img src='img/tick_big.png' />Yeah! You have successfully logged in !</div>";
			//	echo 'Session is: '.$_SESSION['user_id']; 
				return TRUE; 
			}else{
			//	echo $stmt->rowCount(); 
				echo "<div id='login-failure'><img src='img/cross.png' />Oops, your credentials are incorrect.Please try again.</div>"; 
				return FALSE; 
			}
		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		}
	}

	public function loadStore($user_id)
	{
		//$sql ="SELECT * FROM items WHERE owner_id = $user_id";
		$sql = "SELECT items.item_id, 
					   items.price,
					   items.description, 
					   items.service, 
					   item_pics.pic_dir, 
					   item_pics.pic_id
					   FROM items INNER JOIN users on users.user_id = items.owner_id
					   LEFT JOIN item_pics ON items.item_id = item_pics.pic_item_id
					   WHERE user_id = $user_id; 
		 			";

		try{
			$stmt = $this->_db->prepare($sql); 
			$stmt->execute();
		/*	if ($stmt->execute()){
				echo "execution sucess!"; 
			}else{
				echo "execution failure!";
			}; */
			$result=$stmt->fetchAll();
			$pattern ='/\\\/';
			if(sizeof($result)>=1)
			{
				$itemPicArray = array(); 
				$picIdArray = array(); 
				echo "<div id='items-wrapper'>";
				for($i=0;$i<$stmt->rowCount();$i++)
				{	
					if (($i+1)<sizeof($result)){
			    		$identical = $result[$i]['item_id']==$result[$i+1]['item_id']; 
			    	}else{
			    		$identical = FALSE; 
			    	}	

					if (!$identical){
					/*	$service = $result[$i]['service']; 
						$price = $result[$i]['price']; 
						$description = $result[$i]['description']; */
						$item_id = $result[$i]['item_id']; 	
					    $service = preg_replace($pattern,'',$result[$i]['service']); 
				        $price = preg_replace($pattern,'',$result[$i]['price']); 
					    $description = preg_replace($pattern,'', $result[$i]['description']); 
						
						if (strlen($result[$i]['pic_dir'])>0){
							array_push($itemPicArray, $result[$i]['pic_dir']);
							array_push($picIdArray, $result[$i]['pic_id']);
						}
						
						echo "
							 <div class='item-box'> 
							 	 <a href='#' class='delete-item' id='".$item_id."'><img src='img/close_pop.png' class='btn_close' title='Close Window' alt='Close' /></a>	
									 <form class='item-form' method='post' action='modify_store_item.php'> 
									 	<div class='field-div'>
									 		<div>
									 			<center> 
										 		<input type='text' name='service' class='service-field' value='".$service."' />
										 		</center>
										 	</div>  

										    <div>
										    	<center> 
										 		<input type='text' name='price' class='price-field' value='".$price."' />
												</center>
											</div>

											<div>
												<center> 
										 		<textarea name='description' class='des-field'>".$description."</textarea><br />
										 		</center>
										 	</div> 
										 	<input type='hidden' name='item_id' value='".$item_id."' >
										 	<input type='button' value='Save Change' class='button save-change-btn'>
										 </div> 
									 </form> 
									 <div id='dropbox-wrapper'>
									 <table> 
									 	<tr> ";

									$count = 0;
									for ($k=0;$k<sizeof($itemPicArray);$k++){
									echo "<td> 
											 	<div id='dropbox-wrapper-small-".$k."'> 
											 		 <div class='dropbox' id='dbxa".$k."-".$item_id."' style='display:none'>
											 			 <span class='message'>Drop images here to upload.</span>
											 		 </div>
											          <div class='dropbox2' id='dbxb".$k."-".$item_id."' style='display: block;'>
											   			<div class='preview done'>
															<span class='imageHolder'>
																	<img src = '".$itemPicArray[$k]."' class ='uploaded_item_pic'/>
															<span class='uploaded'></span>
															</span>
															<div class='progressHolder'>
															<div class='progress' style='width: 100px;'></div>
															</div>
														</div>
														<a href='#' id='".$picIdArray[$k]."' class='button'>Delete</a> 
													</div>
											    </div> 
											</td>"; 

										$count ++;
										if ($count==2){
											echo "</tr>
												  <tr>"; 
										}
									}

									for ($l=0;$l<(4-sizeof($itemPicArray));$l++){
									$j = $l + sizeof($itemPicArray); // adjust back the id number; 
									echo "	<td> 
											 	<div id='dropbox-wrapper-small-".$j."'> 
											        <div class='dropbox' id='dbxa".$j."-".$item_id."' >
											            <span class='message'>Drop images here to upload.</span>            
											        </div>
											            <div class='dropbox2' id='dbxb".$j."-".$item_id."' hidden></div>
											    </div> 
											</td> "; 
											$count ++; 
											if ($count==2){
											echo "</tr>
												  <tr>"; 
										}
									}	

							echo "		 
										</tr> 
									</table>
								    </div> 
							 </div>
							  "; 
						if (sizeof($itemPicArray)>0){
						$itemPicArray = array();  
						$picIdArray = array(); 
						}
					}else{
						array_push($itemPicArray, $result[$i]['pic_dir']);
						array_push($picIdArray, $result[$i]['pic_id']);
					}

					}

					echo "</div>";
					$stmt->closeCursor();
					return TRUE; 
				}
			return FALSE; 
		   }catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		   }
	}

	public function addItem($service,$price,$description,$user_id)
	{
		$sql ="INSERT INTO items (service,price,description,owner_id) VALUES(:service,:price,:description,:owner_id)";

		try{
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(":service",$service,PDO::PARAM_STR); 
			$stmt->bindParam(":price",$price,PDO::PARAM_STR); 
			$stmt->bindParam(":description",$description,PDO::PARAM_STR); 
			$stmt->bindParam(":owner_id",$user_id,PDO::PARAM_INT); 
			if ($stmt->execute()){
			$stmt->closeCursor(); 
			//echo $service." ".$price." ".$user_id;
			return TRUE; 
			}else{
				echo $service." ".$price." ".$user_id;
				$stmt->closeCursor();
				return FALSE; 
			}
		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		   }
	}

	public function deleteItem($item_id, $user_id){
		try{
			$sql = "SELECT items.item_id FROM users INNER JOIN items ON items.owner_id = users.user_id WHERE users.user_id = :user_id";
			$stmt = $this->_db->prepare($sql); 
			$stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
			$stmt->execute(); 
			$result = $stmt->fetchAll(); 
			$id_exist = FALSE; 
			for($i = 0;$i<sizeof($result);$i++){
				if ($result[$i]['item_id']==$item_id){
					$id_exist = TRUE; 
				}
			}
			
			if($id_exist){
				$sql = "SELECT pic_dir,thumb_dir FROM item_pics WHERE pic_item_id = $item_id"; 
				$stmt=$this->_db->prepare($sql);
				$stmt->execute(); 
				$result = $stmt->fetchAll(); 
				
				for($i=0;$i<sizeof($result);$i++){
					unlink($result[$i]['pic_dir']); 
					unlink($result[$i]['thumb_dir']); 
				}
		
				$sql = "DELETE FROM items WHERE item_id = :id LIMIT 1"; 
				$stmt=$this->_db->prepare($sql); 
				$stmt->bindParam(":id",$item_id,PDO::PARAM_INT); 
				if ($stmt->execute()&&$stmt->rowCount()==1){
					echo "<p>deletion success</p>"; 
					return TRUE; 
				}else{
					echo $item_id;
					echo $stmt->rowCount();
					print_r($stmt->execute()); 
					echo "<p>deletion failure!</p>"; 
					return FALSE;
				}
			}else{
				return FALSE; 	
			}

		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		   }
	}

	public function updateProfilePicDir($dir,$user_id){
		$sql ="UPDATE users SET pic_url = :dir WHERE user_id = :user_id LIMIT 1"; 
		try{
			$stmt=$this->_db->prepare($sql); 
			$stmt->bindParam(':dir',$dir,PDO::PARAM_STR); 
			$stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT); 
			if ($stmt->execute()){
				$stmt->closeCursor(); 
				return TRUE; 
			}else{
				echo $sql." ".$dir." ".$user_id;
				$stmt->closeCursor(); 
				return FALSE; 
			}
		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		   }
	}

	public function updateItemPicDir($thumb_dir,$dir,$item_id){
		$sql = "INSERT INTO item_pics (pic_dir,thumb_dir,pic_item_id) VALUES (:dir,:thumb_dir,:item_id)"; 

		try{
			$stmt=$this->_db->prepare($sql); 
			$stmt->bindParam(':dir',$dir,PDO::PARAM_STR); 
			$stmt->bindParam(':thumb_dir',$thumb_dir,PDO::PARAM_STR);
			$stmt->bindParam(':item_id',$item_id,PDO::PARAM_INT); 
			if ($stmt->execute()){
				$stmt->closeCursor(); 
				return TRUE; 
			}else{
				echo $sql." ".$dir." ".$item_id;
				$stmt->closeCursor(); 
				return FALSE; 
			}
		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		   }
	}

	//helper function
	function insertItem($result,$ifBtn){
		//print_r($result);
		echo "<div id='seller'>";
			    $itemPicArray = array(); 
			    $itemThumbArray = array(); 
				$pattern = "/\\\/";
			    for($i=0;$i<sizeof($result);$i++){

			    	if (($i+1)<sizeof($result)){
			    		$identical = $result[$i]['item_id']==$result[$i+1]['item_id']; 
			    	}else{
			    		$identical = FALSE; 
			    	}	 
				    if (!$identical){
					    	$item_id = $result[$i]['item_id'];
							$service = preg_replace($pattern,'',$result[$i]['service']); 
				   			$price = preg_replace($pattern,'',$result[$i]['price']); 
							$description = preg_replace($pattern,'', $result[$i]['description']);  
						/*    $service = $result[$i]['service']; 
						    $price = $result[$i]['price']; 
						    $description = $result[$i]['description'];  */
						    $email = $result[$i]['email']; 
						    $fn = $result[$i]['first_name']; 
						    $ln = $result[$i]['last_name']; 
						    $school = $result[$i]['school'];
						    $profile_url = $result[$i]['pic_url'];

					    	array_push($itemPicArray,$result[$i]['pic_dir']); 
						    array_push($itemThumbArray,$result[$i]['thumb_dir']); // some phantom character here hense the if statement
					    	

						    echo "<div class='item-box' id='".$item_id."'> 
						    		<div class='seller-info'>"; 
						    			if ($profile_url!=NULL){
						    			echo "<div class='user-pic'><img class='image profile-img' src='".$profile_url."' /></div>";
						    			}else{
						    			echo "<div class='user-pic'><img class='image profile-img' src='img/user.png' /></div>";
						    			}
						    echo	   "<div class='text-field'><span>".$fn." ".$ln."</span></div> 
						    			<div class='text-field'><span>".$school."</span></div> 
						    			<div class='text-field'><a href='mailto:".$email."' class='button'>Send email</a></div>
						    		</div> 
						    		<div class='service-info'> 
						    			<div class='text-field'><img src='img/tag.png' /><br /><span>".$service."</span></div>  
						    			<div class='text-field'><img src='img/coins_small.png' /><br /><span>".$price."</span></div>
						    			<div class='text-field item-des'><img src='img/speech_bubble.png' /><br /><span>".$description."</span></div> 
						    			<div class='review-btn'>"; 
						   		if ($ifBtn==TRUE){
						   		echo "<a href='item.php?id=".$item_id."' id='comment-btn'class='button'>View comments</a>"; 
						   			}
						   		echo "
						    			</div> 
						    		</div>
						    		<div class='service-images' >";

						    for($j=0;$j<sizeof($itemPicArray);$j++){
						    	if ($itemPicArray[$j]!=NULL){
						    	echo "<a href='".$itemPicArray[$j]."' class='image-link' rel='prettyPhoto[item-pic]'><img class='image' src='".$itemThumbArray[$j]."' /></a>"; 
						    		}

				 			}
						    	echo "			
						    		</div>
						    	 </div> 
						    		";
						    $itemPicArray = array(); 
						    $itemThumbArray = array();
					    }else{
					    	array_push($itemPicArray,$result[$i]['pic_dir']);
					    //	echo "loop ".$i.$result[$i]['thumb_dir'];
					    	array_push($itemThumbArray,$result[$i]['thumb_dir']);
					    	
					    }
			    }

			    echo "</div>";
	}

	public function getNumItems(){
		$sql = "SELECT * FROM items INNER JOIN users ON users.user_id = items.owner_id LEFT JOIN item_pics ON items.item_id = item_pics.pic_item_id";
		  try{
			    $stmt= $this->_db->prepare($sql); 
			    $stmt->execute(); 
			    $result=$stmt->fetchAll();
			    $item_count = 0; 

			    for ($i=0;$i<sizeof($result);$i++){
			    	if (($i+1)<sizeof($result)){
			    		($result[$i]['item_id']==$result[$i+1]['item_id']) ? : $item_count ++ ;  
			    	}else{
			    		$item_count++; 
			    	}	
			    }

			    return $item_count;
		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		   }
	}

	//load $length items from $position ;
	public function loadItems($position, $length){
		$sql = "SELECT  items.item_id
						,items.price
						,items.description
						,users.email
						,users.first_name
						,users.last_name
						,users.school
						,users.pic_url
						FROM items INNER JOIN users ON users.user_id = items.owner_id ORDER BY items.item_id LIMIT $position, $length
									";  
									//LEFT JOIN item_pics ON items.item_id = item_pics.pic_item_id; 
	    try{
			    $stmt= $this->_db->prepare($sql); 
			    $stmt->execute();
			    $result=$stmt->fetchAll();
			//    echo $sql;
				$pattern = "/\\\/"; 
				$rowCount = $stmt->rowCount();
				echo "<div id='".$rowCount."' class='hidden-div' hidden></div> ";
			    for($i=0;$i<sizeof($result);$i++){
			    	$item_id = $result[$i]['item_id']; 
				  //  $service = preg_replace($pattern,'',$result[$i]['service']); 
				    $price = preg_replace($pattern,'',$result[$i]['price']); 
					$description = preg_replace($pattern,'', $result[$i]['description']); 
				    $email = $result[$i]['email']; 
				    $fn = $result[$i]['first_name']; 
				    $ln = $result[$i]['last_name']; 
				    $school = $result[$i]['school'];
				    $profile_url = $result[$i]['pic_url'];
				    echo "<div class='item-box' id='".$item_id."'> 
				    		<div class='seller-info'>"; 
				    			if ($profile_url!=NULL){
				    			echo "<div class='user-pic'><img class='image profile-img' src='".$profile_url."' height:50 /></div>";
				    			}else{
				    			echo "<div class='user-pic'><img class='image' src='img/user.png' /></div>";
				    			}
				    echo	   "<div class='text-field'><span>".$fn." ".$ln."</span></div> 
				    			<div class='text-field'><span>".$school."</span></div> 
				    			<div class='text-field'><a href='mailto:".$email."' class='button'>Send email</a></div>
				    		</div> 
				    		<div class='service-info'> 
				    			<div class='text-field item-info'><img src='img/coins_small.png' /><br /><span>".$price."</span></div>
				    			<div class='text-field item-des item-info'><img src='img/speech_bubble.png' /><br /><span>".$description."</span></div>
				    			<div class='review-btn'>
				 
				   			<a href='item.php?id=".$item_id."' id='comment-btn'class='button'>View comments</a> 
				    			</div> 
				    		</div>
				    		<div class='service-images' >";

			    	$item_id = $result[$i]['item_id']; 
			    	$sql = "SELECT pic_dir, thumb_dir FROM item_pics WHERE pic_item_id = $item_id";
			    	$stmt = $this->_db->prepare($sql); 
			    	$stmt->execute(); 
			    	$pic_result = $stmt->fetchAll(); 
			    	for ($j=0;$j<sizeof($pic_result);$j++)
			    	{
			    		echo "<a href='".$pic_result[$j]['pic_dir']."' class='image-link' rel='prettyPhoto[item-pic]'><img class='image' src='".$pic_result[$j]['thumb_dir']."' /></a>"; 	    	
			    	}

			    	echo "			
						       </div>
						   </div> 
						    		";

			    } 

		  	$stmt->closeCursor();
		  	return TRUE;
		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		   }
	}

	public function loadItem($item_id){
		$sql = "SELECT  items.item_id
						,items.service
						,items.price
						,items.description
						,users.email
						,users.first_name
						,users.last_name
						,users.school
						,users.pic_url
						,item_pics.pic_dir
						,item_pics.thumb_dir
						FROM items INNER JOIN users ON users.user_id = items.owner_id
						LEFT JOIN item_pics ON items.item_id = item_pics.pic_item_id
						WHERE items.item_id = :item_id 
								";  
			try{
				$stmt = $this->_db->prepare($sql); 
				$stmt->bindParam(":item_id",$item_id,PDO::PARAM_INT); 
				if ($stmt->execute()){
					$result = $stmt->fetchAll(); 
					$this->insertItem($result,FALSE);
					$stmt->closeCursor(); 
					return TRUE;
				}else{
					echo $item_id; 
					$stmt->closeCursor();
					return FALSE; 
				}

			}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		   }
	}

	public function loadReview($item_id){
		$sql = "SELECT review.review
					  ,review.rating
					  ,users.first_name
					  ,users.last_name
					  FROM review INNER JOIN users ON users.user_id = review.reviewer_id WHERE review_item_id = :item_id";
		try{
				$stmt = $this->_db->prepare($sql); 
				$stmt->bindParam(":item_id",$item_id,PDO::PARAM_INT); 
				if ($stmt->execute()){
					$result = $stmt->fetchAll(); 
					/* html here*/
				  echo "<div class='review-wrapper'> ";
		     		for ($i=0;$i<sizeof($result);$i++){
						$review=$result[$i]['review']; 
						$rating = $result[$i]['rating']; 
						$fn = $result[$i]['first_name']; 
						$ln = $result[$i]['last_name']; 

					echo "<div class='review-div review-box'>
							<div class='stars-fixed' id=".$rating.">
								<div class='f-half star-fixed-1'> 
								</div>

								<div class='s-half star-fixed-2' > 
								</div> 

								<div class='f-half star-fixed-3' > 
								</div> 

								<div class='s-half star-fixed-4' > 
								</div> 

								<div class='f-half star-fixed-5' > 
								</div> 

								<div class='s-half star-fixed-6' > 
								</div> 

								<div class='f-half star-fixed-7' > 
								</div> 

								<div class='s-half star-fixed-8' > 
								</div> 
							</div> 
							<div class='comments' > 
								<div class='reviewer-name'>
								<img src='img/user.png' /><br />
								<span>".$fn." ".$ln." says: </span></div><br /> 
								<div class='review'>".$review."</div>
							</div>
						  </div> "; 

					} 
				echo "</div>";
				$stmt->closeCursor();
				 return TRUE; 
				}else{
					echo $item_id; 
					$stmt->closeCursor();
					return FALSE; 
				}
			}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		   	}
	}
	
	public function postReview($review,$rating,$item_id,$user_id){
		$sql = "INSERT INTO review (review,rating,review_item_id,reviewer_id) VALUES (:review,:rating,:item_id,:user_id)"; 
		try{
			$stmt=$this->_db->prepare($sql); 
			$stmt->bindParam(":review",$review,PDO::PARAM_STR); 
			$stmt->bindParam(":rating",$rating,PDO::PARAM_INT); 
			$stmt->bindParam(":item_id",$item_id,PDO::PARAM_INT); 
			$stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
			if ($stmt->execute()){
				$stmt->closeCursor();
				return true; 
			}else{
				echo $review; 
				echo $item_id; 
				return false; 	
			}
		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		}
	}

	public function deleteProfilePic($userid){
		$sql = "SELECT pic_url FROM users WHERE user_id = $userid";
		
		try{
		$stmt = $this->_db->prepare($sql); 
		$stmt->execute(); 
		$result = $stmt->fetchAll(); 
		$stmt->closeCursor();
		$pic_url = $result[0]['pic_url'];
		unlink($pic_url);

		$sql = "UPDATE users SET pic_url = NULL WHERE user_id = $userid"; 
		$stmt = $this->_db->prepare($sql); 
		$stmt->execute(); 
		//$result = $stmt->fetchAll(); 
		$stmt->closeCursor();
		return TRUE;
		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		}
	}

	public function deleteItemPic($pic_id,$user_id){
		// check if the pic_id belongs to this user
		try{
		$sql = "SELECT item_pics.pic_id FROM items INNER JOIN users ON users.user_id = items.owner_id RIGHT JOIN item_pics ON items.item_id = item_pics.pic_item_id WHERE users.user_id = :user_id"; 
		
		$stmt = $this->_db->prepare($sql); 
		$stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
		$stmt->execute(); 
		$result = $stmt->fetchAll(); 
		$id_exist = FALSE; 
		for($i=0;$i<sizeof($result);$i++){
			if ($result[$i]['pic_id'] == $pic_id){
				$id_exist = TRUE; 	
			}
		}
		if ($id_exist){
			$sql = "SELECT pic_dir,thumb_dir FROM item_pics WHERE pic_id = :pic_id";
			$stmt = $this->_db->prepare($sql); 
			$stmt->bindParam(":pic_id",$pic_id,PDO::PARAM_INT); 
			if ($stmt->execute()){
				$result=$stmt->fetchAll(); 
				$stmt->closeCursor();
				if (sizeof($result)>0){
					print_r($result);
					unlink($result[0]['pic_dir']); 
					unlink($result[0]['thumb_dir']);
					$sql = "DELETE FROM item_pics WHERE pic_id = :pic_id LIMIT 1";
					$stmt = $this->_db->prepare($sql); 
					$stmt->bindParam(":pic_id",$pic_id,PDO::PARAM_INT);
					if ($stmt->execute()){
						$stmt->closeCursor();
						return TRUE;
					}
					return FALSE;
				}
				return FALSE;
			}else{
				return FALSE; 
			}		
		}else{
			return FALSE;	
		}
		}catch(PDOException $e){
			echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
			return FALSE; 
		}
	}

	public function search($query,$position,$item_per_group){
		$search_query = $query; 
		$keywordArray = preg_split("/[\s,]+/", $search_query);
	//	print_r($keywordArray);
		
		$sql = "SELECT items.item_id
				,items.service
				,items.price
				,items.description
				,users.email
				,users.first_name
				,users.last_name
				,users.school
				,users.pic_url
				FROM items INNER JOIN users ON users.user_id = items.owner_id WHERE users.school LIKE '%".$search_query."%' OR "; 

		for($i =0;$i<sizeof($keywordArray)-1;$i++){
			$sql = $sql."users.school LIKE '%".$keywordArray[$i]."%' OR "; 
		}

		$sql = $sql."users.school LIKE '%".$keywordArray[$i]."%' OR users.first_name LIKE '%".$search_query."%' OR "; 

		for($i =0;$i<sizeof($keywordArray)-1;$i++){
			$sql = $sql."users.first_name LIKE '%".$keywordArray[$i]."%' OR "; 
		}

		$sql = $sql."users.first_name LIKE '%".$keywordArray[$i]."%' OR users.last_name LIKE '%".$search_query."%' OR "; 

		for($i =0;$i<sizeof($keywordArray)-1;$i++){
			$sql = $sql."users.last_name LIKE '%".$keywordArray[$i]."%' OR "; 
		}

		$sql = $sql."users.last_name LIKE '%".$keywordArray[$i]."%' OR items.service LIKE '%".$search_query."%' OR "; 

		for($i =0;$i<sizeof($keywordArray)-1;$i++){
			$sql = $sql."items.service LIKE '%".$keywordArray[$i]."%' OR "; 
		}

		$sql = $sql."items.service LIKE '%".$keywordArray[$i]."%' OR items.description LIKE '%".$search_query."%' OR "; 


		for($i =0;$i<sizeof($keywordArray)-1;$i++){
			$sql = $sql."items.description LIKE '%".$keywordArray[$i]."%' OR "; 
		}

		$sql = $sql."items.description LIKE '%".$keywordArray[$i]."%' "; 

		try{
			if ($position == 0){
				$stmt=$this->_db->prepare($sql); 
				$stmt->execute(); 
				$rowCount = $stmt->rowCount(); 
				$stmt->closeCursor();
				echo "<div id='".$rowCount."' class='hidden-div' hidden></div> ";
			}
			$sql=$sql."LIMIT $position,$item_per_group";
			$stmt = $this->_db->prepare($sql); 
			$stmt->execute(); 
			$result=$stmt->fetchAll();
			$stmt->closeCursor();
			//echo $sql;
		    for($i=0;$i<sizeof($result);$i++){
		    	$item_id = $result[$i]['item_id']; 
			    $service = $result[$i]['service']; 
			    $price = $result[$i]['price']; 
			    $description = $result[$i]['description']; 
			    $email = $result[$i]['email']; 
			    $fn = $result[$i]['first_name']; 
			    $ln = $result[$i]['last_name']; 
			    $school = $result[$i]['school'];
			    $profile_url = $result[$i]['pic_url'];

			    echo "<div class='item-box' id='".$item_id."'> 
			    		<div class='seller-info'>"; 
			    			if ($profile_url!=NULL){
			    			echo "<div class='user-pic'><img class='image profile-img' src='".$profile_url."' /></div>";
			    			}else{
			    			echo "<div class='user-pic'><img class='image' src='img/user.png' /></div>";
			    			}
			    echo	   "<div class='text-field'><span>".$fn." ".$ln."</span></div> 
			    			<div class='text-field'><span>".$school."</span></div> 
			    			<div class='text-field'><a href='mailto:".$email."' class='button'>Send email</a></div>
			    		</div> 
			    		<div class='service-info'> 
			    			<div class='text-field'><img src='img/tag.png' /><br /><span>".$service."</span></div>  
			    			<div class='text-field'><img src='img/coins_small.png' /><br /><span>".$price."</span></div>
			    			<div class='text-field item-des'><img src='img/speech_bubble.png' /><br /><span>".$description."</span></div> 
			    			<div class='review-btn'>
			 
			   			<a href='item.php?id=".$item_id."' id='comment-btn'class='button'>View comments</a> 
			    			</div> 
			    		</div>
			    		<div class='service-images' >";

		    	$item_id = $result[$i]['item_id']; 
		    	$sql = "SELECT pic_dir, thumb_dir FROM item_pics WHERE pic_item_id = $item_id";
		    	$stmt = $this->_db->prepare($sql); 
		    	$stmt->execute(); 
		    	$pic_result = $stmt->fetchAll(); 
		    	$stmt->closeCursor();
		    	for ($j=0;$j<sizeof($pic_result);$j++)
		    	{
		    		echo "<a href='".$pic_result[$j]['pic_dir']."' class='image-link' rel='prettyPhoto[item-pic]'><img class='image' src='".$pic_result[$j]['thumb_dir']."' /></a>"; 	    	
		    	}
		    	echo "			
					       </div>
					   </div> 
					    		";
				}
			}catch(PDOException $e){
					echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
					return FALSE; 
			}
	}

	public function loadAccountInfo($userid){
		$sql = "SELECT user_id ,email , password, first_name ,last_name ,school ,pic_url FROM users WHERE user_id = $userid LIMIT 1";
		try{
			$stmt = $this->_db->prepare($sql); 
			$stmt->execute(); 
			$result = $stmt->fetchAll(); 
			if ($stmt->rowCount()==1){
				$userid = $result[0]['user_id']; 
				$email = $result[0]['email']; 
				$fn = $result[0]['first_name']; 
				$ln = $result[0]['last_name']; 
				$school = $result[0]['school'];
				$pass = $result[0]['password'];
			echo '
			<div style="margin-top:30px;"> 
			    <center> 
			        <span>Your account information</span>
			    </center>
			</div> 
			<div id="verify-div" > 
			    <div id="profile">
			     <span style="position:relative;left:45px;">Profile Image</span>
			    	';
			    if ($result[0]['pic_url']){
			echo    '<div class="dropbox" id="'.$userid.'" hidden> 
			            <span class="message">Drop images here to upload.</span>            
			        </div>
			        <div class="dropbox2">
			            <div class="preview done" style="height:130px;margin-top:20px;"> 
							<span class="imageHolder"> 
								 <img src="'.$result[0]['pic_url'].'"/>
							</span>
						</div> 
						<button class="button" id="pro-pic-delete-btn" >Delete</button>
					</div>';
				}else{
			echo '<div class="dropbox" id="'.$userid.'" > 
			            <span class="message">Drop images here to upload.</span>            
			        </div>
			      <div class="dropbox2" hidden></div>
					';
				}
			echo  ' <br />
			    </div> 
			    <div id="update-form-div"> 
			        <form method="post" id="update-form"> 
			            <div class="form-field">
			                <label for="school">School:</label><br />
			                <input type="text" name="school" id="school" class="text_box sign_up_field" autocomplete="on" value = "'.$school.'"/>
			                <br />
			            </div> 
			            <div class="form-field">
			                <label for="first_name">First Name:</label><br />
			                <input type="text" name="first_name" id="first_name" class="text_box sign_up_field" value ="'.$fn.'"/> 
			                <br />
			            </div> 
			            <div class="form-field">
			                <label for="last_name">Last Name:</label><br />
			                <input type="text" name="last_name" id="last_name" class="text_box sign_up_field" value ="'.$ln.'"/> 
			                <br />
			            </div> 
			             <div class="form-field">
			                <label for="email">Email:</label><br />
			                <input type="text" name="email" id="email" class="text_box sign_up_field" value = "'.$email.'"/>
			                <br />
			            </div> 
			            <input type="hidden" name="user_id" value="'.$userid.'" />
			            <div style="clear:both;height:60px;" > 
			        		<input type="submit" class="update-btn button" value="Update Info" />
			    		</div> 
			        </form> 
			    </div>
			</div>
							'; 
				$stmt->closeCursor();
				return TRUE;
			}else{
				echo "<h1>Multiple records existed.</h1>"; 
				echo $sql;
				return FALSE; 
			}		
		}catch(PDOException $e){
					echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
					return FALSE; 
		}
	}

	public function updateAccountInfo($email, $school, $fn, $ln, $user_id){
		$sql = "UPDATE users SET email = :email, school = :school, first_name = :fn, last_name = :ln WHERE user_id = :user_id LIMIT 1"; 
		try{
			$stmt = $this->_db->prepare($sql); 
			$stmt->bindParam(':email',$email,PDO::PARAM_STR); 
			$stmt->bindParam(':school',$school,PDO::PARAM_STR); 
			$stmt->bindParam(':fn',$fn,PDO::PARAM_STR); 
			$stmt->bindParam(':ln',$ln,PDO::PARAM_STR);
			$stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
			if ($stmt->execute()){
				$stmt->closeCursor(); 
				return TRUE;
			}else{
				$stmt->closeCursor(); 
				return FALSE;
			}
		}catch(PDOException $e){
					echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
					return FALSE; 
		}
	}

	public function updateItemInfo($item_id, $service, $price, $description){
		$sql= "UPDATE items SET service=:service, price =:price, description = :description WHERE item_id = :item_id"; 	
		try{
			$stmt = $this->_db->prepare($sql); 
			$stmt->bindParam(':service',$service,PDO::PARAM_STR); 
			$stmt->bindParam(':price',$price,PDO::PARAM_STR); 
			$stmt->bindParam(':description',$description,PDO::PARAM_STR); 
			$stmt->bindParam(':item_id',$item_id,PDO::PARAM_INT);
			if ($stmt->execute()){
				$stmt->closeCursor(); 
				return TRUE;
			}else{
				$stmt->closeCursor(); 
				return FALSE;
			}
		}catch(PDOException $e){
					echo "<h1>Error!</h1><p>".$e->getMessage()."</p>"; 
					return FALSE; 
		}
	}

}
?>