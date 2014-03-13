<?php
  include_once "common/base.php";
 include_once "inc/inc.class.php"; 
 include_once "inc/login.inc.php";
 include_once "common/header.php"; 
 
  
  $user = new cfuser($db); 
  //$user->loadAllItems(); 
  $total_items = $user->getNumItems();
?>
<link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
    var track_load = 0; 
    var loading = false; 
    var item_per_group = 5;
    var total_items = <?php echo $total_items; ?>;
    var total_groups = total_items / item_per_group; 
    var query = ""; 
 //   var search_type = "";
    // load first group 
    $("#seller").load("autoload_process.php",{'group_no':track_load,'query':query, 
              'item_per_group':item_per_group}, function(){track_load++;
               $("a[rel^='prettyPhoto']").prettyPhoto({
                    social_tools:false
                 });});  
    console.log("window height: " + $(window).height()); 
      console.log("document height: " + $(document).height());
     console.log("scrollTop height: " + $(window).scrollTop());
    
    $(window).scroll(function(){
  
    	if ($(window).scrollTop() + $(window).height() + 10 >= $(document).height())
    	{

          if (track_load <= total_groups && loading == false){
      				loading = true; 
      				$('.loading').fadeIn(500);
    				$.post("autoload_process.php",{'group_no':track_load,'query':query,'item_per_group':item_per_group}, function(data){
    					$("#seller").append(data); 
    					$('.loading').fadeOut(500); 
    					track_load++;
    					loading = false; 
              $("a[rel^='prettyPhoto']").prettyPhoto({
                    social_tools:false
                 });
    				}).fail(function(xhr,ajaxOptions,thrownError){
    					alert(thrownError); 
    					$('.loading').hide(); 
    					loading = false; 
    				});  				
      		}else if(track_load > total_groups){
            $("#footer").show();
          }
      	}
    });

    $("#search_btn").on("click",function(e){
      e.preventDefault(); 
      track_load = 0;
      query = $(this).parents("form").find("#search_field").val()
      //search_type = $('input[name=search_option]:checked').val(); 
      $.ajax({
        url:'autoload_process.php',
        type:'POST', 
        data:{'group_no':track_load,'query':query, 
              'item_per_group':item_per_group},
        success:function(data,status,jqXHR){
          $("#seller").html(data); 
          track_load++; 
          if ($("div.hidden-div").attr("id")==0){
            $("#seller").append("<div id='no-reuslts' style='width:200px; margin-left:auto; margin-right:auto; margin-top:80px;' ><img src='img/cross.png' />No results</div>"); 
          }
          total_groups = ($("div.hidden-div").attr("id"))/item_per_group; 
		  // this is the only way i can think of to get the new total group
        }
      }); 
    }); 
  });
</script>
<style> 
#menu a img{
  position: relative;
  top:5px;
}

a#comment-btn{
font-size: 13px;
text-decoration: none;
text-align: center;
margin-bottom: 30px;
}

.loading{
position: fixed; 
bottom: 0px;
margin-right: auto; 
margin-left: auto;
background: #787878;
padding: 10px;
display: none;
color: #FFFFFF;
}

#search-div{
 margin-left: auto; 
 margin-right: auto;
 width: 800px;
 text-align: center;
 position: relative;
 top: 20px;
}

#search_btn {
  position: relative;
  top: 10px;
}

#search-div img {
  margin-left: 5px;
}
</style> 
<div id="search-div"> 
  <form  method="post" action="search.php"> 
  <input type="text" name="search" id="search_field" class="text-field" placeholder="search a service here" ><img src="img/search_icon.png" />
  <br />
  <button id="search_btn" class="button">Go</button>
  </form> 
</div> 
<div id='seller'>
</div> 
<div class="loading">
  <span style="font-size:12px;">Galvea is trying to load. :D</span>
</div> 
<?php
include_once "common/popup.php";
include_once "common/email_contact_form.php";
include_once "common/footer.php"; ?> 