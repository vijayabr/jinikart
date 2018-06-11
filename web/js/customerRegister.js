$(document).ready(function(){
	
		
	$('#customer_fname').on('input', function() {
		var input=$(this);
		var rec = /^[a-z A-Z]{0,30}$/;
		var is_fname=rec.test(input.val());
		if(!is_fname){
			$("#checkfName").text("Incorrect data entered");
		}else{
			$("#checkfName").text("");
		}
	});
	$('#customer_lname').on('input', function() {
		var input=$(this);
		var rec = /^[a-z A-Z _ .]{0,20}$/;
		var is_personname=rec.test(input.val());
		if(!is_personname){
			$("#checklname").text("Incorrect data entered");
		}else{
			$("#checklname").text("");
		}
	});
	$('#customer_email').on('input', function() {
		var input=$(this);
		var rec = /^([a-zA-Z]{0,3}[a-zA-Z0-9]+@([a-zA-Z]{3,10}))\.([a-zA-Z]{2,5})$/;
		var is_email=rec.test(input.val());
		if(!is_email){
			$("#checkemail").text("Incorrect data entered");
		}else{
			$("#checkemail").text("");
		}
	});
	$('#customer_mobile_no').on('input', function() {
		var input=$(this);
		var rec = /^[789]\d{9}$/;
		var is_mbno=rec.test(input.val());
		if(!is_mbno){
			$("#checkmobile").text("Incorrect data entered");
		}else{
			$("#checkmobile").text("");
		}
	});

	$('#customer_pincode').on('input', function() {
		var input=$(this);
		var rec = /^[1-9][0-9]{5}$/;
		var is_pin=rec.test(input.val());
		if(!is_pin){
			$("#checkpincode").text("Incorrect data entered");
		}else{
			$("#checkpincode").text("");
		}
	});
	$('#customer_question1').on('input', function() {
		var input=$(this);
		var rec = /^[a-zA-Z][a-zA-Z]{2,10}$/;
		var is_q1=rec.test(input.val());
		if(!is_q1){
			$("#checkq1").text("Incorrect data entered");
		}else{
			$("#checkq1").text("");
		}
	});
	$('#customer_question2').on('input', function() {
		var input=$(this);
		var rec = /^[a-zA-Z][a-zA-Z]{2,10}$/;
		var is_q2=rec.test(input.val());
		if(!is_q2){
			$("#checkq2").text("Incorrect data entered");
		}else{
			$("#checkq2").text("");
		}
	});
});
