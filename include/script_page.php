<script>	
	// user name validation
	$("#name").blur(function() {
		var r = /^[a-zA-Z]+ [a-zA-Z]+$/;
		$p = $(this).val();
		if ($p.length == 0) {
			//document.getElementById("name").focus();
			$("#erruserspan").html("Please Enter Your Full Name");
			$("#erruserspan").css("color","red");
		} else if (r.test($p) == false) {
			//document.getElementById("name").focus();
			$(this).focus();
			$("#erruserspan").html("Please enter full name and remove numeric,symbols and digits if any");
			$("#erruserspan").css("color","red");
			
			$("#erruser").html("").removeClass("ok").addClass("not-ok");
			$('#btn_signup').attr({
				disabled: true
			});
		} else {
			$("#erruserspan").html("");
			$("#erruser").html("").removeClass("not-ok").addClass("ok");
			$('#btn_signup').attr({
				disabled: false
			});
		}
	});
</script>
<script>
// Email Id Validation
$(document).on('blur', '#email_id', function() {

	var userinput = $(this).val();
	var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
	
	if(!pattern.test(userinput))
	{
	  $(this).val("");
	// document.getElementById("email_id").focus();
		
		$(".err_email").css("color","red");
		$(".err_email").html("Please input valid email!!!");
		$('#btn_signup').attr({
				disabled: true
			});
	}
	else
	{
		$(".err_email").empty();
		$('#btn_signup').attr({
				disabled: false
			});
	}
		
});
</script>

<script>
// Mobile Number Validation
 $("#mobile").bind("blur", function() {
		 var mob = $("#mobile").val();
		 var r = /^[6-9]{1}[0-9]{9}$/;
			$(".mobile_err").html("");
		 var res = r.test(mob);
		 if (res == false) {
		 // alert(res);
			 $(".mobile_err").html("Please enter valid Mobile No.");
			 $(".mobile_err").css("color","red");
			 $("#mobile").val("");
			// document.getElementById("mobile").focus();
			 $('#btn_signup').attr("disabled","true");
		 } else
			 {
			 $(".mobile_err").html("");
		 $('#btn_signup').attr({
			 disabled: false
		 });
		}
	 });
</script>
<script>

</script>


<script>
$("#btn_signup").click(function(){
	//For Name
    // alert();
	var rn = /^[a-zA-Z]+ [a-zA-Z]+$/;
		$n = $("#name").val();
		if ($n.length == 0) {
			Swal.fire({
//					   title: "Welcome to kodehash!!!",
					   text: "Please Enter Your Full Name!!!",
					   // type: "warning",
						allowOutsideClick: false,
						icon:'error',
					   // showCancelButton: true,
					   confirmButtonColor: "red",
					   confirmButtonText: "OK.",
					});
		} else if (rn.test($n) == false) {
			Swal.fire({
//					   title: "Welcome to kodehash!!!",
					   text: "Please enter full name and remove numeric,symbols and digits if any!!!",
					   // type: "warning",
						allowOutsideClick: false,
						icon:'error',
					   // showCancelButton: true,
					   confirmButtonColor: "red",
					   confirmButtonText: "OK.",
					});
			return false;
			} else {
				var check_name='1';
			$('input[type=submit]',$("#logfrm")).removeAttr('disabled');
		}
		
		//For Eamil
		var userinput = $("#email_id").val();
	var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
	if(!pattern.test(userinput))
	{
	  $("#email_id").focus();
		Swal.fire({
                    text: "Please enter correct email id!!!",
                    type: "warning",
                    allowOutsideClick: false,
                    icon:'error',
                    confirmButtonColor: "red",
                    confirmButtonText: "OK.",
				});
			return false;
	}
	else
	{
		var check_email='1';
		$('input[type=submit]',$("#logfrm")).removeAttr('disabled');
	}
		
		//For Mobile
		var mob = $("#mobile").val();
		 var rm = /^[6-9]{1}[0-9]{9}$/;
			$(".mobile_err").html("");
		 var res = rm.test(mob);
		 if (res == false) {
		 // alert(res);
		 $('#mobile').focus();
		Swal.fire({
//					   title: "Welcome to kodehash!!!",
					   text: "Please input correct Mobile no!!!",
						allowOutsideClick: false,
						icon:'error',
					   confirmButtonColor: "red",
					   confirmButtonText: "OK.",
					});	 
			 return false;
		 } else
		 {
			 var check_mobile='1';
			$('input[type=submit]',$("#logfrm")).removeAttr('disabled');
		 }
		
		if(check_name=='1'  && check_mobile=='1' && check_email=='1')
		{
//		alert(jegy);
			var user =$("#name").val();
			var mobile=$("#mobile").val();
			var email=$("#email_id").val();
			var signup=$("#btn_signup").val();
			var des=$("#desc").val();
            var country=$("#country").val();
      $.ajax({
        url:"loginprocess.php", //the page containing php script
        type: "POST", //request type
		  
		  data: {signup:signup,mobile:mobile,uname:user,country:country,email:email,desc:des},
        success:function(result){
			var data=JSON.parse(result);
			 if(data.success=="Data Inserted Successfully")
				{
				   // Swal.fire("Login Unsuccessful");       
					 Swal.fire({
				  // title: "Are you sure?",
				   text: "Thank you for contacting us,We will get back you soon!!!",
				   // type: "warning",
				   // showCancelButton: true,
				   confirmButtonColor: "#DD6B55",
				   confirmButtonText: "OK.",
				   closeOnConfirm: false,
				   showLoaderOnConfirm: true
				//  cancelButtonAriaLabel: 'Thumbs down'
				}).then(function(){
						window.location.href="thank-you.php"; 
					 }); 
			  }
			 else
			 {
				  Swal.fire({
				  // title: "Are you sure?",
				   text: data.success,
				   // type: "warning",
				   // showCancelButton: true,
				   confirmButtonColor: "#DD6B55",
				   confirmButtonText: "OK.",
				   closeOnConfirm: false,
				   showLoaderOnConfirm: true
				//  cancelButtonAriaLabel: 'Thumbs down'
				})
				}
				}
     });
		}
});
</script>

