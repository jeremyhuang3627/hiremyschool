
$(document).ready(function() {

//scrollilng up
 $.scrollUp({
        scrollName: 'scrollUp', // Element ID
        topDistance: 300, // Distance from top before showing element (px)
        topSpeed: 300, // Speed back to top (ms)
        animation: 'fade', // Fade, slide, none
        animationInSpeed: 200, // Animation in speed (ms)
        animationOutSpeed: 200, // Animation out speed (ms)
        scrollText: 'Scroll to top', // Text for element
        scrollImg: false, // Set true to use image
        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
    }); 

//resizeing images
$('.item-box img').on("load",function() {
    var maxWidth = 200; // Max width for the image
    var maxHeight = 200;    // Max height for the image
    var ratio = 0;  // Used for aspect ratio
    var width = $(this).width();    // Current image width
    var height = $(this).height();  // Current image height
    // Check if the current width is larger than the max
    if(width > maxWidth){
        ratio = maxWidth / width;   // get ratio for scaling image
        $(this).css("width", maxWidth); // Set new width
        $(this).css("height", height * ratio);  // Scale height based on ratio
        height = height * ratio;    // Reset height to match scaled image
    }

    var width = $(this).width();    // Current image width
    var height = $(this).height();  // Current image height

    // Check if current height is larger than max
    if(height > maxHeight){
        ratio = maxHeight / height; // get ratio for scaling image
        $(this).css("height", maxHeight);   // Set new height
        $(this).css("width", width * ratio);    // Scale width based on ratio
        width = width * ratio;    // Reset width to match scaled image
    }

    // end of resizing images
    });

$.validator.addMethod(
    "edu", function(value,element){
       var pattern=/@.*\.edu\.?/;
       return pattern.test(value); 
    },"Please enter your college email address."
    ); 
//signup form ajaxSubit ajaxSubmit({url: 'modify_store_item.php', type: 'post'})
$("#signup-form").validate({
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
               edu:"Your college email address should contain '.edu' or '.edu.' ."
        }
    },
    submitHandler: function(form) {
        /*         form.submit(); */
      $(form).ajaxSubmit({url:'createAccount.php',
                            type:'post',
                            dataType:'json', 
                            success:function(data){
                               if (data.status=="repeat"){
                                    $(".email-repeat").fadeIn(300); 
                               }else if(data.status=="success"){
                                    $('#signup-box').fadeOut(300).siblings('.notification').fadeIn(300);
                               }else if(data.status=="error"){
                                    $('#signup-box').fadeOut(300).siblings('.error-popup').fadeIn(300);
                               };
                            }}); 
    /*  .parents('#signup-box').fadeOut(300).siblings('.notification').fadeIn(300);*/
    }
});

$("#verify-form").validate({
    errorPlacement: function(error, element) {
        error.appendTo(element.parent());
      },
    rules: {
        school:"required",
        first_name:"required", 
        last_name:"required", 
        password: {
            required:true, 
            minlength:5
        }
    },
    messages: {
        school:"Please enter your school", 
        first_name:"please enter your first name", 
        last_name: "Please enter your last name",
        password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    }
    },
    submitHandler: function(form) {
   /*             form.submit();   */
      $(form).ajaxSubmit({url:'activateAccount.php',
                            type:'post',
                            dataType:'json', 
                            success:function(data){
                               if (data.status=="error"){
                                    $('.error-popup').fadeIn(300);
                               }else if(data.status=="success"){
                                    $('.success').fadeIn(300);
                                    $( ".sign_up_field" ).prop( "disabled", true );
                               }
                            }}); 
    /*  .parents('#signup-box').fadeOut(300).siblings('.notification').fadeIn(300);*/
    }
});




var uni;
$.getJSON("js/uni.json",function(data){
    uni=data; 
})

$("#school").autocomplete(
        {
            source:function( request, response )
            {
              var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex(request.term ), "i" );
              response( $.grep(uni, function( item )
              {
                  return matcher.test( item );
              }))
            },appendTo:"#school-field",
        });
//sign up popup box
$('a.menu-item').click(function() {
    
            //Getting the variable's value from a link 
    var loginBox = $(this).attr('href');

    //Fade in the Popup
    $(loginBox).fadeIn(300);
    
    //Set the center alignment padding + border see css style
    var popMargTop = ($(loginBox).height() + 24) / 2; 
    var popMargLeft = ($(loginBox).width() + 24) / 2; 
    
    $(loginBox).css({ 
        'margin-top' : -popMargTop,
        'margin-left' : -popMargLeft
    });
    
    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);

    $("#mask").click(function(){
        $("#mask , .popup").fadeOut(300 , function() {
        $('#mask').remove();  
        })
    }); 
    return false;
});

// remove items in store
$("a.delete-item").on("click", function(e){
    e.preventDefault(); 
    var deleteButton = $(this); 
        $("#confirm-deleteItem-dialog").dialog({
        resizeable:false,
        height:200, 
        modal:true, 
        buttons:{
            "Yes":function(){
            //  alert(user_id);
            //  alert(pic_url);
            deleteButton.next("form").append("<input type='hidden' name='delete' value='"+ deleteButton.attr('id')+"' />").ajaxSubmit({url: 'modify_store_item.php', type: 'post'}).parent("div").remove(); 
            $(this).dialog("close"); 
            }, 
            Cancel: function(){$(this).dialog("close");}
        }
    }); 
    return false;
});

$("a.close-popup").on("click",function(e){
    e.preventDefault(); 
    $(this).parent(".popup").fadeOut(300); 
});

// When clicking on the button close or the mask layer the popup closed
$("a.close").on("click", dismissMask);

});

function dismissMask() { 
      $("#mask, .popup").fadeOut(300 , function() {
        $('#mask').remove();  
    }); 
    return false;
    }

function afterSuccess()  {
            $('#UploadForm').resetForm();  // reset form
            $('#SubmitButton').removeAttr('disabled'); //enable submit button
        }



(function($,W,D)
{
    var JQUERY4U = {};

    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#login-form").validate({
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
                  },
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                },
                messages: {
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    },
                    email: "Please enter a valid email address"
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }
    }

    //when the dom has loaded setup form validation rules
    $(D).ready(function($) {
        JQUERY4U.UTIL.setupFormValidation();
    });

})(jQuery, window, document);

(function($,W,D)
{
    var JQUERY4U = {};

    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#add-item-form").validate({
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
                  },
                rules: {
                    service: "required",
                    price: "required",
                    description:"required"
                },
                messages: {
                    service: "Please enter service item name",
                    price: "Please indicate how you are going to charge",
                    description:"Please enter your service description"
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }
    }

    //when the dom has loaded setup form validation rules
    $(D).ready(function($) {
        JQUERY4U.UTIL.setupFormValidation();
    });

})(jQuery, window, document);


//modifying the browse button for image upload
function getFile(){
        document.getElementById("image_upload").click();
    }

