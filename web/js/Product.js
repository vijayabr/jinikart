$(document).ready(function(){
	
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
