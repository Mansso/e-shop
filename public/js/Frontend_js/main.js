/*price range*/

 $('#sl2').slider();

	var RGBChange = function() {
	  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
	};	
		
/*scroll to top*/

$(document).ready(function(){
	$(function () {
		$.scrollUp({
	        scrollName: 'scrollUp', // Element ID
	        scrollDistance: 300, // Distance from top/bottom before showing element (px)
	        scrollFrom: 'top', // 'top' or 'bottom'
	        scrollSpeed: 300, // Speed back to top (ms)
	        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
	        animation: 'fade', // Fade, slide, none
	        animationSpeed: 200, // Animation in speed (ms)
	        scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
					//scrollTarget: false, // Set a custom target element for scrolling to the top
	        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
	        scrollTitle: false, // Set a custom <a> title if required.
	        scrollImg: false, // Set true to use image
	        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	        zIndex: 2147483647 // Z-Index for the overlay
		});
	});
});


//changing price
$(document).ready(function(){
	$("#selSize").change(function(){
		var idSize = $(this).val();
		if(idSize == ""){
			return false;
		}
		$.ajax({
			type:'get',
			url:'/get-product-price',
			data:{idSize:idSize},
			success:function(resp){
				var arr = resp.split('#');
				$("#getPrice").html("US $ "+arr[0]);
				$("#price").val(arr[0]);
				if(arr[1]==0){
					$("#cartButton").hide();
					$("#Availability").text("Out of Stock");
				}else{
					$("#cartButton").show();
					$("#Availability").text("In Stock");
				}
			},error:function(){
				alert("Error");
			}
		});
	});
});



//changing images
$(document).ready(function () {
 	$(".changeImage").click(function () {
		 var image = $(this).attr('src');
		 $(".mainImage").attr("src",image);
 	});
});

// Instantiate EasyZoom instances
var $easyzoom = $('.easyzoom').easyZoom();

// Setup thumbnails example
var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

$('.thumbnails').on('click', 'a', function (e) {
	var $this = $(this);

	e.preventDefault();

	// Use EasyZoom's `swap` method
	api1.swap($this.data('standard'), $this.attr('href'));
});

// Setup toggles example
var api2 = $easyzoom.filter('.easyzoom--with-toggle').data('easyZoom');

$('.toggle').on('click', function () {
	var $this = $(this);

	if ($this.data("active") === true) {
		$this.text("Switch on").data("active", false);
		api2.teardown();
	} else {
		$this.text("Switch off").data("active", true);
		api2._init();
	}
});

$().ready(function(){
	//Validate register form on keyup and submit
	$("#registerForm").validate({
		rules:{
			name:{
				required:true,
				minlength:2,
				accept: "[a-zA-Z]+"
			},
			password:{
				required:true,
				minlength:6
			},
			email:{
				required:true,
				email:true,
				remote: "/check-email"
			}
		},
		messages:{
			name: {
				required:"Please enter a Username",
				lettersonly: "The name must contain letters only !",
				accept : "Letters only !"
			},
			password:{
				required: "PLease enter a password",
				minlength: "The password is too short"
			},
			email:{
				required: "Please enter you email",
				email: "email invalid !",
				remote: "Email already exists !"
			}
		}
	});

	//Update form on keyup and submit
	$("#accountForm").validate({
		rules: {
			name: {
				required: true,
				minlength: 2,
				accept: "[a-zA-Z]+"
			},
			adresse: {
				required: true,
				minlength: 6
			},
			city: {
				required: true,
				minlength: 2
			},
			state: {
				required: true,
			},
			country: {
				required: true
			}
		},
		messages: {
			name: {
				required: "Please enter your name",
				lettersonly: "The name must contain letters only !",
				accept: "Letters only !"
			},
			adresse: {
				required: "PLease enter a valid adresse",
				minlength: "Adress too short"
			},
			city: {
				required: "Please enter you city",
				minlength: "Adress too short"
			},
			state: {
				required: "Please enter you state",
				minlength: "Adress too short"
			},
			country: {
				required: "Please select you country",
				minlength: "Adress too short"
			}

		}
	});

	//Validate login form on keyup and submit
	$("#loginForm").validate({
		rules: {
			email: {
				required: true,
				email: true
			},
			password: {
				required: true
			},
			
		},
		messages: {
			email: {
				required: "Please enter you email",
				email: "email invalid !"
			},
			password: {
				required: "PLease enter a password"
			}
		}
	});

	$("#passwordForm").validate({
	    rules: {
	        current_pwd: {
	            required: true,
	            minlength: 6,
	            maxlength: 20
	        },
	        new_pwd: {
	            required: true,
	            minlength: 6,
	            maxlength: 20,
	        },
	        confirm_pwd: {
	            required: true,
	            minlength: 6,
	            maxlength: 20,
	            equalTo: "#new_pwd"
	        }
	    },
	    errorClass: "help-inline",
	    errorElement: "span",
	    highlight: function (element, errorClass, validClass) {
	        $(element).parents('.control-group').addClass('error');
	    },
	    unhighlight: function (element, errorClass, validClass) {
	        $(element).parents('.control-group').removeClass('error');
	        $(element).parents('.control-group').addClass('success');
	    }
	});

	//Check Current user password
	$('#current_pwd').keyup(function(){
		var current_pwd = $(this).vacl();
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name = "csrf-token"]').attr('content')
			},
			type: 'post',
			url: '/check-user-pwd',
			data:{current_pwd:current_pwd},
			success:function(resp){
				if(resp == "false"){
					$("#chkPwd").html("<font color='red'>Current Password is incorrect</font>");
				}else if(resp == "true"){
					$("#chkPwd").html("<font color='green'>Current Password is correct</font>");
				}
			},error:function(){
				alert("Error");
			}
		});
	});

	//Password strength Script
	$("#myPassword").passtrength({
		minChars: 4,
		passwordToggle: true,
		tooltip: true,
		eyeImg: "/images/Frontend_images/eye.svg"
	});





	//Billing adress same as shipping adress
	$(document).ready(function () {
		$("#billtoship").click(function () {
			if(this.checked){
				$('#shipping_name').val($('#billing_name').val());
				$('#shipping_adress').val($('#billing_adress').val());
				$('#shipping_city').val($('#billing_city').val());
				$('#shipping_state').val($('#billing_state').val());
				$('#shipping_country').val($('#billing_country').val());
				$('#shipping_pincode').val($('#billing_pincode').val());
				$('#shipping_mobile').val($('#billing_mobile').val());
			}else{
				$('#shipping_name').val('');
				$('#shipping_adress').val('');
				$('#shipping_city').val('');
				$('#shipping_state').val('');
				$('#shipping_country').val('');
				$('#shipping_pincode').val('');
				$('#shipping_mobile').val('');
			}
		});
	});




	// $('#billtoship').onclick(function(){
	// 	alert("test");
	// });
});

function selectPaymentMethod(){
	if($('#Paypal').is(':checked') || $('#COD').is(':checked')){
		alert("checked");
	}else{
		alert("Please select a payment method first");
		return false;
	}
}