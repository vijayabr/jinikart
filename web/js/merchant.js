$(document).ready(function(){
			
		var dataUrl=$('#note').val();	
		$.ajax({
			type: "POST",
	    	   url: dataUrl ,
			   success: function(data){
				  if(data['data']['totalcount']){
				   $("#badge").text(data['data']['totalcount']);
				  }
				  if(data['data']['requestedProductCount']){
				   $("#requested").text("order product count : "+data['data']['requestedProductCount']);
				  }
				  if(data['data']['deliveredProductCount']){
				   $("#delivered").text("Delvered product count :  "+data['data']['deliveredProductCount']);
				  }
				  if(data['data']['processedProductCount']){
				   $("#processed").text("processed product count : " +data['data']['processedProductCount']);
				  }
			     }
        }); 
		
		$('#notification').click(function(){
			if(document.getElementById("notificationmessage").style.display=="none"){
			document.getElementById("notificationmessage").style.display="block";
			}else{
				document.getElementById("notificationmessage").style.display="none";
			}
			 
		});


});