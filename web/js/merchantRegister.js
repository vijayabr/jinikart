$(document).ready(function(){
	
		
	$('#merchant_companyName').on('input', function() {
		var input=$(this);
		var rec = /^[a-z A-Z]{0,30}$/;
		var is_companyname=rec.test(input.val());
		if(!is_companyname){
			$("#checkCompanyName").text("Incorrect data entered");
		}else{
			$("#checkCompanyName").text("");
		}
	});
	$('#merchant_contactPersonName').on('input', function() {
		var input=$(this);
		var rec = /^[a-z A-Z _ .]{0,30}$/;
		var is_personname=rec.test(input.val());
		if(!is_personname){
			$("#checkContactPerson").text("Incorrect data entered");
		}else{
			$("#checkContactPerson").text("");
		}
	});
	$('#merchant_email').on('input', function() {
		var input=$(this);
		var rec = /^([a-zA-Z]{0,3}[a-zA-Z0-9]+@([a-zA-Z]{3,10}))\.([a-zA-Z]{2,5})$/;
		var is_email=rec.test(input.val());
		if(!is_email){
			$("#checkemail").text("Incorrect data entered");
		}else{
			$("#checkemail").text("");
		}
	});
	$('#merchant_mobileNo').on('input', function() {
		var input=$(this);
		var rec = /^[6789]\d{9}$/;
		var is_email=rec.test(input.val());
		if(!is_email){
			$("#checkmobile").text("Incorrect data entered");
		}else{
			$("#checkmobile").text("");
		}
	});

	$('#merchant_pincode').on('input', function() {
		var input=$(this);
		var rec = /^[1-9][0-9]{5}$/;
		var is_email=rec.test(input.val());
		if(!is_email){
			$("#checkpincode").text("Incorrect data entered");
		}else{
			$("#checkpincode").text("");
		}
	});
});
