$(function(){
	
	var dropbox = $('.dropbox'),
		message = $('.message', dropbox);
	dropbox.filedrop({
	
		// The name of the $_FILES entry:
		paramname:'pic',
		maxfiles: 1,
    	maxfilesize: 2,
		url: 'post_file.php',

        data: {
 				user_id:dropbox.attr("id") //get user_id
   			 },
		
		uploadFinished:function(i,file,response){
			$.data(file).addClass('done');
			var button_template = "<button class='button' id='pro-pic-delete-btn' >Delete</button>";
			$(button_template).appendTo($(".dropbox2"));
			$("button#pro-pic-delete-btn").on("click",function(e){
				e.preventDefault(); 
				var user_id = $(this).parent().prev().attr("id");
				var pic_url = $(this).prev().find("img").attr("src");
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
								'user_id':user_id,
								'pic_url':pic_url
								},
								dataType:'json',
								success:function(data){
								if (data.status=="success"){
								$(".preview").remove(); 
								$("button#pro-pic-delete-btn").remove();
								$(".dropbox").attr("style","display:block").find("span").attr("style","display:block").end().next().hide();
								}else{
									alert("Error!");
								}
							   }
							}); 
							$(this).dialog("close"); 
						}, 
						Cancel: function(){$(this).dialog("close");}
					}
				}); 
			});
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
					alert(err);
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
			$('.dropbox2').show();
			$('.dropbox').hide();
		},
		
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
		}
    	 
	});


	var dropbox = $('.dropbox2');
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
		
		message.hide();
		preview.appendTo(dropbox);
		
		// Associating a preview container
		// with the file, using jQuery's $.data():
		
		$.data(file,preview);
	}

	function showMessage(msg){
		message.html(msg);
	}

});