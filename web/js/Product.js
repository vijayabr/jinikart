$(document).ready(function(){
	$('#min').on('input', function() {
		var input=$(this);
		var rec = /^\d*$/;
		var is_no=rec.test(input.val());
		if(!is_no){
			$("#checkmin").text("price should be numeric");
		}else{
			$("#checkmin").text("");
		}
		if(input >= 0){
			$("#checkmin").text("price should be greater then 0");
		}
	});
	
	$('#max').on('input', function() {
		var input=$(this);
		var rec = /^\d*$/;
		var is_no=rec.test(input.val());
		if(!is_no){
			$("#checkmax").text("price should be numeric");
		}else{
			$("#checkmax").text("");
		}
		if(input <= 100000){
			$("#checkmin").text("price should be lesser then 100000");
		}
	});
	
		$("#search").click(function(){
 	    
		 
		var minprice =$("#min").val();    	
		var maxprice =$("#max").val(); 
		var category =$("#categorylist").val();    	
		var brand =$("#brandlist").val();
		var discount=$('[name="discount"]').val();
		var ramsize=$('[name="ramsize"]').val();
		var camera=$('[name="camera"]').val();
		var merchant=$('[name="merchant"]').val();
		
		var sortlist = {
			"minprice" :minprice,	
			"maxprice":maxprice,
			"category":category,
			"brand":brand,
			"discount":discount,
			"ramsize":ramsize,
			"camera":camera,
			"merchant":merchant				
		}
					
		var dataUrl=$('#url').val();
		$.ajax({
			type: "POST",
			   data: {"sortlist": sortlist},
			   url: dataUrl ,
			   success: function(data){
			     $('#productsList').html(data);
			     }
        }); 			
	  });
	

});

/*
 * <input type="hidden" id="url" name="url" value="{{ path('advancedproductList_page', { 'sortlist': "samsung" }) }}"/>     
 * 		 $('[name="discount"]').on('change',function(){
			 alert($('[name="discount"]').val());		          
			   
			 });
		 $('[name="ramsize"]').on('change',function(){
			alert($('[name="ramsize"]').val());		          
			   
			 });
		 $('[name="camera"]').on('change',function(){
			 alert($('[name="camera"]').val());		          
			   
			 });
		 $("#brandlist").on('change',function(){
			 alert(brand.val());		          
			   
			 });
			 $("#categorylist").on('change',function(){
			 alert(category.val());		          
			   
			 });
			 $("#advancedform").click(function(){//main division
		    });*/
