<?php
include_once "common/base.php"; 
if (!isset($_SESSION['user_id'])){
	header("Location: index.php"); 
	exit(); 
}

$userid = $_SESSION['user_id']; 
if(!is_numeric($userid)){
	header("Location: index.php"); 
	exit(); 
}

include_once "inc/inc.class.php"; 
include_once "common/header.php"; 
?>
<script src="js/upload_script.js"></script>
<script>
$(function(){
/*
$("button.update-btn").on("click",function(e){
	e.preventDefault(); 
	$("form#update-form").ajaxSubmit({
		url:"updateAccount.php",
		type:"POST",
		dataType:'json', 
		success:function(data){
			
		}
	});
});
*/

$("#update-form").validate({
	    errorPlacement: function(error, element) {
	        error.appendTo(element.parent());
	      },
	    rules: {
	        school:"required",
	        first_name:"required", 
	        last_name:"required", 
	    },
	    messages: {
	        school:"Please enter your school", 
	        first_name:"please enter your first name", 
	        last_name: "Please enter your last name"
	    },
	    submitHandler: function(form){
	   /*             form.submit();   */
	      $(form).ajaxSubmit({url:'updateAccount.php',
	                            type:'post',
	                            dataType:'json', 
	                            success:function(data){
								//	alert(data);
	                              if (data.status=="success"){
	                              	$(".account-update-success").fadeIn(1000).fadeOut(3500);
	                              }else{
	                              	alert("Oops an error has occured.");
	                              }
	                            }
	                        }); 
	    /*  .parents('#signup-box').fadeOut(300).siblings('.notification').fadeIn(300);*/
	    }
	});

$("button#pro-pic-delete-btn").on("click",function(e){
	e.preventDefault(); 
	var user_id = $(this).parent().prev().attr("id");
	$("#confirm-dialog").dialog({
		resizeable:false,
		height:200, 
		modal:true, 
		buttons:{
			"Yes":function(){
			//	alert(user_id);
			//	alert(pic_url);
				$.ajax({
					url:'deleteProfilePic.php',
					type:'POST', 
					data:{
					'user_id':user_id
					},
					dataType:'json',
					success:function(data){
					//	alert(data);
					if (data.status=="success"){
					$(".preview").remove(); 
					$("button#pro-pic-delete-btn").remove();
					$(".dropbox").attr("style","display:block");
					$(".dropbox2").attr("style","display:none");
					}else{
						alert("error!");
					}
				   }
				}); 
				$(this).dialog("close"); 
			}, 
			Cancel: function(){$(this).dialog("close");}
		}
	}); 
});

}); 
</script>
<div class="account-update-success popup">
    <span style="font-size:20px;"><img src="img/tick.png" />Account information successfully saved.</span>
</div> 
<?php
$user = new cfuser($db); 
$user->loadAccountInfo($userid); 
include_once "common/popup.php"; 
include_once "common/footer.php"; 
?> 