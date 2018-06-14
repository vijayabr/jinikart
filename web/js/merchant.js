$(document).ready(function(){
					
		var dataUrl=$('#note').val();	
		$.ajax({
			type: "POST",
	    	   url: dataUrl ,
			   success: function(data){
				   $("#badge").text(data);
			    
			     }
        }); 			


});