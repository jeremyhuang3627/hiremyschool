<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="css/style.css">
<link href='http://fonts.googleapis.com/css?family=Metamorphous' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/upload_primary.css" />
<link rel="stylesheet" href="css/upload_secondary.css" />
<link rel="stylesheet" href="css/tab.css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>
<script src="js/upload_jquery.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/jquery.filedrop.js"></script>
<script type="text/javascript" src="js/jquery.scrollUp.min.js"></script>
<script type="text/javascript" src="js/home.js"></script>
<title>Home</title>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="menu">
    <?php if (isset($_GET['type'])&&($_GET['type']==1)){ ?>
    <a href="#add-item-box" class="add-item menu-item">
        <span>Add Item</span>
        <img src="img/add_item.png" />
    </a>
	<?php }
    if(empty($_SESSION['loggedIn'])&&empty($_SESSION['username'])){ ?> 
    <a href="#login-box" class="login menu-item">
    	<span>Log In</span>
        <img src="img/log_in.png" />
    </a>
    <a href="#signup-box" class="signup menu-item">
    	<span>Sign Up</span>
        <img src="img/signup.png" />
    </a>
    <?php }else{  ?>
    <a href="logout.php" class="logout menu-item-no-mask">
        <span>Log Out</span>
        <img src="img/logout.png" />
    </a>
    <a href="account.php" class="logout menu-item-no-mask">
        <span>My Account</span>
        <img src="img/my_account.png" />
    </a>
    <a href="store.php?type=1" class="store menu-item-no-mask">
        <span>My Store</span>
        <img src="img/my_store.png" />
    </a>
    <?php } ?>
    <a href="index.php" class="home menu-item-no-mask">
    	<span>Home</span>
        <img src="img/home.png" />
    </a>
    <a href="#helpbox" class="help menu-item">
        <span>About</span>
        <img src="img/help.png" />
    </a>
     <a href="mailto:jh3627@stern.nyu.edu" class="contact menu-item">
        <span>Contact</span>
        <img src="img/contact_mail.png" />
    </a>
</div> 
<div id="header"> 
    <div id="title"> 
        <span id="name">Galvea.com</span><br /> 
        <span id="small_name"> - a student labor market on the web </span>
        <?php if(!empty($_SESSION['LoggedIn'])&&!empty($_SESSION['Username'])){
            echo "<span id='greeting'>Hi, ".$_SESSION['fn']."!</span>";
        }?> 
    </div>
    <div id="process"> 
        <div class="sign_up_step">
            <img src="img/step_0.png" /><br />
            <span class="subtitle">Sign Up</span> 
        </div> 
         <div class="arrow">
            <img src="img/arrow.png" /> 
        </div> 

        <div class="step">
            <img src="img/step_1.png" /><br />
            <span class="subtitle">Find something <br />you are good at</span> 
        </div> 

        <div class="arrow">
            <img src="img/arrow.png" /> 
        </div> 

        <div class="step">
            <img src="img/step_2.png" /><br />
            <span class="subtitle">Sell it to <br />your school mates</span> 
        </div>

        <div class="arrow">
            <img src="img/arrow.png" /> 
        </div> 

        <div class="step">
            <img src="img/step_3.png" /><br />
            <span class="subtitle">And make cash!</span> 
        </div>  
		<div class="fb-like" data-href="http://www.bidinsanity.com" data-send="true" data-width="450" data-show-faces="true"></div>
    </div>
</div>
<div id="wrapper"> 

	
