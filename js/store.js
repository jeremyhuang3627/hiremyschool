// this script loop through all items div and assign drag drop functionality to each

$(function(){
  $("div.item-box").each(function()
  		{
	  		  var _item_id = $(this).find("a.delete-item").attr("id");
	  		  $(this).find("div.dropbox").each(function(j)
	  		     {
	  			//	var dropbox = $("#dbxa"+i+"-"+_item_id), 
	  			//	    message = $('.message', dropbox);
	  			/*	$("#dbxa"+j+"-"+_item_id).on("click",function(){
	  					alert($(this).attr("id"));
	  				}); */
	  				//dropbox.attr("id",i);
	  				$("#dbxa"+j+"-"+_item_id).filedrop({
					  	// The name of the $_FILES entry:
						paramname:'pic',
						maxfiles: 1,
				    	maxfilesize: 2,
						url: 'post_file.php',

				        data: {
				 				item_id:_item_id //get user_id
				   			 }, 

						uploadFinished:function(i,file,response){
							var pic_id = response.status;
							$.data(file).addClass('done');
							var button_template = "<a href='#' id='"+pic_id + "' class='button'>Delete</a>"
							$(button_template).appendTo($("#dbxb"+j+"-"+_item_id));
							$("a#"+pic_id).on("click",function(e){
								e.preventDefault();
								//delete preview
								$(this).parent().find("div.preview").remove().end().attr("style","display:none").prev().attr("style", "display:block").end().find("a.button").remove();
								var id = $(this).attr("id"); 
								// send ajax request to delete picture;
								$.ajax({
									url: "delete_pic.php", 
									type:"POST", 
									data:{'id':id}, 
							/*		success:function(data,status,jqXHR){
										alert(data);
									} */
								}); 

							})

							//parent.window.location = "photos.html";
							// response is the JSON object that post_file.php returns
						},

							error: function(err, file) {
							switch(err) {
								case 'BrowserNotSupported':
									showMessage('Your browser does not support HTML5 file uploads!');
									break;
								case 'TooManyFiles':
									alert('Too many files! Please select 5 at most! (configurable)');
									break;
								case 'FileTooLarge':
									alert(file.name+' is too large! Please upload files up to 2mb (configurable).');
									break;
								default:
									break;
							}
						},
						
						// Called before each upload is started
						beforeEach: function(file){
							if(!file.type.match(/^image\//)){
								alert('Only images are allowed!');
								// Returning false will cause the
								// file to be rejected
								return false; 
							}
						},
						
						uploadStarted:function(i, file, len){
							createImage(file);
							$("#dbxb"+j+"-"+_item_id).show();
							$("#dbxa"+j+"-"+_item_id).hide();
						},
						
						progressUpdated: function(i, file, progress) {
							$.data(file).find('.progress').width(progress);	
						}

					}); 
					
					var template = '<div class="preview">'+
										'<span class="imageHolder">'+
											'<img />'+
											'<span class="uploaded"></span>'+
										'</span>'+
										'<div class="progressHolder">'+
											'<div class="progress"></div>'+
										'</div>'+
									'</div>'; 

					function createImage(file){

						var preview = $(template), 
							image = $('img', preview);
							
						var reader = new FileReader();
						
						image.width = 100;
						image.height = 100;
						
						reader.onload = function(e){
							
							// e.target.result holds the DataURL which
							// can be used as a source of the image:
							
							image.attr('src',e.target.result);
						};
						
						// Reading the file as a DataURL. When finished,
						// this will trigger the onload function above:
						reader.readAsDataURL(file);
						
						$("#dbxa"+j+"-"+_item_id+".message").hide();
						preview.appendTo($("#dbxb"+j+"-"+_item_id));			
						// Associating a preview container
						// with the file, using jQuery's $.data():
						
						$.data(file,preview);
					}

					function showMessage(msg){
						message.html(msg);
					}
	  		     }
	 	     )
  	    }
  	)
	
	$(".save-change-btn").on("click",function(e){
		e.preventDefault(); 
		$(this).parents(".item-form").ajaxSubmit({
			url:'updateItemInfo.php', 
			type:'POST', 
			dataType:'json', 
			success:function(data){
			//	alert(data);
				if (data.status=="success"){
					$(".update-success").fadeIn(1000).fadeOut(3500);
				}else{	
					alert("Oops an error has occured."); 
				}
			}
		}); 
	}); 

	$("a.button").on("click",function(e){
		e.preventDefault();
		//delete preview
		$(this).parent().find("div.preview").remove().end().attr("style","display:none").prev().attr("style", "display:block").end().find("a.button").remove();
		var id = $(this).attr("id"); 
		// send ajax request to delete picture;
		$.ajax({
			url: "delete_pic.php", 
			type:"POST", 
			data:{'id':id}, 
		/*	success:function(data,status,jqXHR){
				//alert(data);
			} */
		}); 

	})

}); 