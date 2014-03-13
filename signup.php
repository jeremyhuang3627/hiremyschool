<?php 
// testurl : http://localhost/campus_freelancer/signup.php?v=2147483647&e=f3a9be45553e61cc45614941bea4869558fdab09
include_once "common/base.php";
include_once "common/header.php"; 
include_once "common/formvalidator.php"; 

if ($_SERVER["REQUEST_METHOD"]=="GET"){
      include_once "inc/inc.class.php";
      $user = new cfuser($db); 
      $result = $user->verifyAccount(); 
      if ($result[0]==0){    
?>
<script src="js/upload_script.js"></script>
<div style="margin-top:30px;"> 
    <center> 
        <span>Enter your account details below to complete activation :D</span>
    </center>
</div> 

<div id="verify-div" > 
    <div id="profile">
        <div class="dropbox" id="<?php echo $_SESSION['user_id'];  ?>" >
            <span class="message">Drop images here to upload.</span>            
        </div>
            <div class="dropbox2" hidden></div>
        <center> 
            <br />
            <span>Profile Image</span>
        </center>
    </div> 
    <div id="verify-form-div"> 
        <form action="activateAccount.php" method="post" id="verify-form"> 
            <div class="form-field">
                <label for="school">School:</label><br />
                <input type="text" name="school" id="school" class="text_box sign_up_field" autocomplete="on"/>
                <br />
            </div> 
            <div class="form-field">
                <label for="first_name">First Name:</label><br />
                <input type="text" name="first_name" id="first_name" class="text_box sign_up_field"/> 
                <br />
            </div> 
            <div class="form-field">
                <label for="last_name">Last Name:</label><br />
                <input type="text" name="last_name" id="last_name" class="text_box sign_up_field"/> 
                <br />
            </div> 
            <div class="form-field">
                <label for="password">Password:</label><br />
                <input type="password" name="password" id="password" class="text_box sign_up_field"/> 
                <br />
            </div>
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];  ?>" />
            <input type="submit" class="button verify-form-submit" value="Submit" />
        </form> 
    </div>
</div>

<?php 

include_once "common/popup.php"; 
include_once "common/footer.php";
 }else{
        echo $result[1];
       }
 }
 ?>