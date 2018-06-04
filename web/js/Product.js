$(document).ready(function(){
    	 $("#search").click(function(){
	
    	    
			 
			var minprice =$("#min").val();    	
			var maxprice =$("#max").val(); 
			var category =$("#categorylist").val();    	
			var brand =$("#brandlist");
			var discount=$('[name="discount"]').val();
			var ramsize=$('[name="ramsize"]').val();
			var camera=$('[name="camera"]').val();
			var sortlist = new Array();
			sortlist["minprice"]= minprice;
			sortlist["maxprice"] =maxprice;
			sortlist["category"]=category;
			sortlist["brand"]=brand;
			sortlist["discount"]=discount;
			sortlist["ramsize"]=ramsize;
			sortlist["camera"]=camera;
			alert(sortlist["minprice"].toString());
			alert(sortlist["minprice"]);
			
			 $.ajax({
	                url:'{{ (path('advancedproductList_page')) }}',
	                type: "POST",
	                dataType: "json",
	                data: {
	                    "sortList"
	                },
	                async: true,
	                success: function (data)
	                {
	                    alert("true");
	                    
	                }
	            });
				
		  });
		

});

/*$.ajax({
type: "POST",
      url: "resources/dialogs/editResponsibleUser.php",
      data: {user: user,
             partnerid: partnerid},
      success: function(msg){
            $('.productmessage').html(msg).hide().fadeIn(500).fadeOut(4000);

          },
        });
         $('[name="discount"]').on('change',function(){
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
