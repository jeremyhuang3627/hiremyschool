<?php 
	include_once "lib/swift_required.php"; 	
		$email="jh3627@stern.nyu.edu"; 

		$e = sha1($email); 
		$to = trim($email);

		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 25,'tls')
		  ->setUsername('wecleanyourdorm@gmail.com')
		  ->setPassword('wecleanyourdorm0426'); 

		$mailer = Swift_Mailer::newInstance($transport);
		
		$msg  ="You have a new account at Galvea!
				 
				To get started, please activate your account and choose a
				password by following the link below.
				 
				Your email: $email
				 
				Activate your account: http://bidinsanity.com/signup.php?v=$ver&e=$e	 
				--
				Thanks!
				 
				Jeremy
				www.galvea.com";

		$subject = "Galvea - Please Verify Your Account"; 

		$message = Swift_Message::newInstance($subject)
					  ->setFrom("wecleanyourdorm@gmail.com")
					  ->setTo($to)
					  ->setBody($msg)
					  ;
	    $result = $mailer->send($message);
	      
	      if ($result){
	      	echo "success!"; 
	      } else{
	      	echo "failure!"; 
	      }

?>