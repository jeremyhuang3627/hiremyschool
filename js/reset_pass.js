$(function(){
	$("#reset_pass_btn").on("click",function(e){
		e.preventDefault(); 
		$("#reset-pass-form").ajaxSubmit({
			url:'update_pass.php',
			type:'POST', 
			dataType:'json', 
			success:function(data){
				if (data.status=="success"){
					$(".pass-word-change").fadeIn(300);
				}else if(data.status=="failure"){
					alert("Oops, an error has occurred.");
				}
			}
		}); 
	}); 
}); 