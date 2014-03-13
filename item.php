<?php 
if ($_SERVER['REQUEST_METHOD']=="GET" && is_numeric($_GET['id'])){
	include "common/base.php"; 
	include "common/header.php"; 
	include "inc/inc.class.php";

	$user = new cfuser($db); 
	$user->loadItem($_GET['id']);
	$user->loadReview($_GET['id']); 
?>
<link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<script> 
$(function(){

$("#fake-button").on("click",function(){
	$(".login-review").fadeIn(300);
});

$(".review-div").each(function(index,element){
	var numStars = $(this).find(".stars-fixed").attr("id"); 
	for(var i=1;i<=numStars;i++){
		(i%2==1) ? $(this).find(".star-fixed-"+i).attr("style","background-position: 0 -92px;") : $(this).find(".star-fixed-"+i).attr("style","background-position: 0 -115px;");
	}
}); 

$("#review-submit-button").on("click", function(e){
	e.preventDefault(); 
	var radioOk = false;
	var textAreaOk =false; 

	if  (radioButtonValidate()){
		radioOk = true; 
	}else{
		$(".rating-missing").fadeIn(300); 
	}

	if ($("#text-area-review").val() != ""){
		textAreaOk = true; 
	}else{
		$(".text-area-empty").fadeIn(300);
	}

	var markup = "<div class='review-div review-box'>"+
							"<div class='stars-fixed temp' >"+
								"<div class='f-half star-fixed-1'>"+
								"</div>"+

								"<div class='s-half star-fixed-2' >"+
								"</div>"+

								"<div class='f-half star-fixed-3' >"+ 
								"</div>"+ 

								"<div class='s-half star-fixed-4' >"+
								"</div>"+ 

								"<div class='f-half star-fixed-5' >"+ 
								"</div>"+ 

								"<div class='s-half star-fixed-6' >"+
								"</div>"+

								"<div class='f-half star-fixed-7' >"+
								"</div>"+ 

								"<div class='s-half star-fixed-8' >"+ 
								"</div>"+ 
							"</div>"+ 
							"<div class='comments' >"+ 
								"<div class='reviewer-name'><span></span></div><br />"+ 
								"<div class='review'></div>"+
							"</div>"+
						  "</div>"; 

	if (radioOk&&textAreaOk){
		$("#review-form").ajaxSubmit({
			url:"submit_review.php",  
			type:"post", 
			dataType:"json", 
			success: function(data){
				var preview = $(markup);
				var div = $(".stars-fixed",preview); 
				div.attr("id", data.rating); 
				$(".reviewer-name",preview).text(data.fn + " "+data.ln+" says: ");
				$(".review",preview).text(data.review); 
			//	alert(preview);
				preview.appendTo(".review-wrapper").each(function(index,element){
						var numStars = $(this).find(".stars-fixed").attr("id"); 
						for(var i=1;i<=numStars;i++){
							(i%2==1) ? $(this).find(".star-fixed-"+i).attr("style","background-position: 0 -92px;") : $(this).find(".star-fixed-"+i).attr("style","background-position: 0 -115px;");
						}
					});
			}
		}); 
	}

})


$(".stars div").on("mouseover",function(){
	var id = $(this).attr("id"); 
	var numHalfStar = id.charAt(5);
	//$(this).attr("style","background-image: url(star_sprite.png);background-position: 0 -92px;");
	for(var i=1;i<=numHalfStar;i++){
		(i%2==1) ? $("#star-"+i).attr("style","background-position: 0 -92px;") : $("#star-"+i).attr("style","background-position: 0 -115px;")
	}

	for(var j=8;j>numHalfStar; j--){
		(j%2==1) ? $("#star-"+j).attr("style","background-position: 0 0px;") : $("#star-"+j).attr("style","background-position: 0 -23px;")
	}

	$("#rating-"+numHalfStar).click();
}); 

	$("a[rel^='prettyPhoto']").prettyPhoto({
    	social_tools:false
    });

    $("#fake-button").click(function(){
    	$(".login-review").fadeIn(300); 
    }); 

}); 

function radioButtonValidate(){
	var radios=document.getElementsByName("rating"); 
	for (var i=0;i<radios.length;i++){
		if (radios[i].checked){
			return true; 
		}
	}
	return false; 
}
</script> 
<style>
#review-form .review-form-btn{
 position: relative;
 left: 150px;
}

.reviewer-name img{
	position: relative;
	left: 30px;
}

.reviewer-name, .review{
	padding: 5px;
}

.review{
	margin-left: auto; 
	margin-right: auto; 
	width:300px;
	height: 200px;
	clear: both; 
	position: absolute;
	top: 30px;
	right: 20px;
	overflow: auto;
	float: right;
	background-color: #f8f8f8;
}
.review-box{
	width:500px;
	min-height:300px;
	margin-left: auto; 
	margin-right: auto;  
	position: relative;
	margin-top: 10px;
	overflow: auto;
}

.reviewer-name{
	width:120px;
	float:left;
}

.radio-btn {
	display: none;
}

.stars .f-half,.stars-fixed .f-half{
	display: inline-block; 
	width: 12px;
	height: 23px;
	margin-right: 0px; 
	background-image: url(img/star_sprite.png);
	background-position: 0 0px;
	position: relative;
	left:4px;
}

.stars-fixed .f-half{
	position: relative;
	left: 3px; 
}

.temp .f-half{
	position: relative;
	left:0px;
}

.stars .s-half, .stars-fixed .s-half{
	display: inline-block; 
	width: 12px;
	height: 23px;
	margin-left: 0px;
	margin-right: 0px;  
	background-image: url(img/star_sprite.png);
	background-position: 0-23px;
}

.stars, .stars-fixed {
	margin-top: 20px; 
	margin-left: 5px; 
	cursor:default;
}

.rating-missing{
	position: fixed;
	left: 200px; 
	top:200px;
}

</style>
<div class="review-form-div"> 
	<form id="review-form"> 
	<div class="radio-btn">
		<label><input id="rating-1" class="rating" name="rating" type="radio" value="1"/>1 Star</label>
		<label><input id="rating-2" class="rating" name="rating" type="radio" value="2"/>2 Stars</label>
		<label><input id="rating-3" class="rating" name="rating" type="radio" value="3"/>3 Stars</label>  
		<label><input id="rating-4" class="rating" name="rating" type="radio" value="4"/>4 Stars</label> 
		<label><input id="rating-5" class="rating" name="rating" type="radio" value="5"/>5 Star</label>
		<label><input id="rating-6" class="rating" name="rating" type="radio" value="6"/>6 Stars</label>
		<label><input id="rating-7" class="rating" name="rating" type="radio" value="7"/>7 Stars</label>  
		<label><input id="rating-8" class="rating" name="rating" type="radio" value="8"/>8 Stars</label> 
	</div>
	<div class="stars">
		<div class="f-half" id="star-1"> 
		</div> 

		<div class="s-half" id="star-2"> 
		</div> 

		<div class="f-half" id="star-3" > 
		</div> 

		<div class="s-half"  id="star-4"> 
		</div> 

		<div class="f-half"  id="star-5"> 
		</div> 

		<div class="s-half"  id="star-6"> 
		</div> 

		<div class="f-half"  id="star-7"> 
		</div> 

		<div class="s-half"  id="star-8"> 
		</div> 
	</div> 
		<textarea class="text_box" id="text-area-review" name="review_text" required placeholder="Tell others about what you think of this service!"></textarea>
		<input type="hidden" name="review_item_id" value="<?php echo $_GET['id'];  ?>" />
		<?php if (isset($_SESSION['user_id'])){ ?>
		<input type="hidden" name="review_user_id" value="<?php echo $_SESSION['user_id']; ?>" />
		<?php } ?>
		<br /> 
		<br />
		<?php if (isset($_SESSION["user_id"])){ ?>
		<input type="submit" class="button review-form-btn" id="review-submit-button" value="Submit">
		<?php }else{ ?>
		<button class="button review-form-btn" id="fake-button">Submit</button>
		<?php } ?> 
	</form> 
</div>
	
<div class="rating-missing popup">
	<a href="#" class="close-popup"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<span style="font-size:12px;">You forgot to rating this servie! </span>
</div> 

<div class="text-area-empty popup">
	<a href="#" class="close-popup"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<span style="font-size:12px;">Comments cannot be empty!</span>
</div> 

<div class="login-review popup">
	<a href="#" class="close-popup"><img src="img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
	<span style="font-size:12px;">You need to login before you can post a review!</span>
</div> 

<?php 
include_once "common/popup.php"; 
include_once "common/footer.php";
}else{
	header("Location: index.php"); 
}
?> 