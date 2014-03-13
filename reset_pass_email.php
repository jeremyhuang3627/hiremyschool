<?php
include_once "common/base.php"; 
include_once "common/header.php"; 
include_once "inc/inc.class.php"; 
?>
<style> 
#reset-email-div {
	width: 500px;
	margin-left: auto; 
	margin-right: auto; 
	position: relative;
	top: 100px;
}

#spinning-circle {
  width: 30px;
  margin-left: 45%; 
  display: none;
}

#reset-email-title{
	position: relative;
	bottom: 50px;
	width: 300px;
}

#email-input-field {
	width: 250px;
	height: 20px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	position: relative;
	left: 90px;
}

#reset-pass-btn {
	position: relative;
	left:110px;
	top:100px;
}
</style>
<script> 
$(function(){

  $(document).ajaxStart(function(){
    $("#spinning-circle").show();
  }).ajaxStop(function(){
    $("#spinning-circle").hide();
  });
  
	$("#reset-email-form").validate({
    debug:true,
    errorPlacement: function(error, element) {
        error.appendTo(element.parent());
      },
    rules: {
        email: {
            required: true,
            email: true,
            edu:true,
        }
    },
    messages: {
        email: {
               required: "Please enter a valid email address.",
               email:"Please enter a valid email address.",
           //    edu:"Your college email address should contain '.edu' or '.edu.' ."
        }
    },
    submitHandler: function(form) {
         /*      form.submit();  */
      $(form).ajaxSubmit({url:'send_reset_email.php',
                            type:'post',
                            dataType:'json', 
                            success:function(data){
                             // alert(data);
                              if (data.status=="success"){
                              	$(".reset-div").fadeIn(300); 
                              }else if(data.status=="failure"){
                              	$(".reset-email-noexist-div").fadeIn(300);
                              }
                            }}); 
    /*  .parents('#signup-box').fadeOut(300).siblings('.notification').fadeIn(300);*/   
    }   
});

}); 
</script>
<div id="reset-email-div"> 
<form id="reset-email-form"> 
		<span id="reset-email-title">Please enter the email address you registered with Galvea.</span>
		<input type="text" id="email-input-field" name="email" placeholder="email address" /><br /> 
		<input type="submit" id="reset-pass-btn" class="button" value="Send verification email" />
</form>
</div>
<div id="spinning-circle"> 
  <img src="img/loading.gif" style="height:30px;width:30px;"/>
</div> 
<div class="reset-div popup">
	<a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<img src="img/email.png" id="mail-image"/><br /><br />
	<span style="font-size:18px;">A password reset email has been sent to your email address.<br /><br />Please click the link to reset your password.</span>
</div> 

<div class="reset-email-noexist-div popup">
	<a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<span><img src='img/cross.png' />This email is not registered at Galvea.com</span>
</div> 
<?php 
include_once "common/popup.php";
include_once "common/footer.php"; 
?> 