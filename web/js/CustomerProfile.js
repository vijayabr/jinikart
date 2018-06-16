
$(document).ready(function(){

    $("#profile_email").css("background-color", "yellow");
    $("#profile_mobile_no").css("background-color", "yellow");
   
    var fname =$("#profile_fname").val();
    var lname =$("#profile_lname").val();
    var addr1 =$("#profile_address_line1").val();
    var addr2 =$("#profile_address_line2").val();
    var pincode =$("#profile_pincode").val();
    var state=$("#profile_state").val();
    var country =$("#profile_country").val();
    var plan =$("#profile_plan").val();
    var ans1 =$("#profile_question1").val();
    var ans2 =$("#profile_question2").val();
    
    	
    
   /* $("#profile_fname").change(function(){
        alert("Your first name will be changed");
      
    });
    $("#profile_lname").change(function(){
        alert("Your last name will be changed");
    });
    $("#profile_address_line1").change(function(){
        alert("Your address first line will be changed");
       
    });
    $("#profile_address_line2").change(function(){
        alert("Your address second line will be changed");
        
    });
    $("#profile_pincode").change(function(){
        alert("Your address pincode will be changed");
        });
    $("#profile_state").change(function(){
        alert("Your state will be changed");
        });
    $("#profile_country").change(function(){
        alert("Your country will be changed");
        });
    $("#profile_plan").change(function(){
        alert("Your plan will be changed");
        });
    $("#profile_question1").change(function(){
        alert("Your Answer for the first question will be changed");
                
    });
    $("#profile_question2").change(function(){
        alert("Your Answer for the second question will be changed");
        });
*/    
    var prev;
    $(".profilePic").hover(function(){
    	$("#editPicContent").fadeIn();
    	$("#editPicImage").fadeOut(10);
    	
    	$("#editPicContent").html("Edit Image");
    		
    },
    function() {
    	$("#editPicContent").html(" ");
    	$("#editPicContent").fadeOut();
    	$("#editPicImage").fadeIn();    	

    }
    );
    $("#customerprofileupdate").click(function(){
    
    	var message ="successfuly  updated your profile with this changes:\n";
    	var fname1 =$("#profile_fname").val();   
    	var lname1 =$("#profile_lname").val();
    	var addr11 =$("#profile_address_line1").val();
    	var addr21 =$("#profile_address_line2").val(); 
    	var pincode1 =$("#profile_pincode").val();
    	var state1=$("#profile_state").val();
    	var country1 =$("#profile_country").val();
    	var plan1 =$("#profile_plan").val();
    	var ans11 =$("#profile_question1").val();
    	var ans21 =$("#profile_question2").val();
    	
    	if(fname != fname1){        
       		message= message +"first name is changed from  "+fname+"    to   "+fname1+"\n";
    	}		
    
    	if(lname != lname1){        
       		message= message +"last name is changed from  "+lname+"   to   "+lname1+"\n";
    	}		
    	if(addr1 != addr11){        
       		message= message +"first line of address is changed from  "+addr1+"   to   "+addr11+"\n";
    	}		
    	if(addr2 != addr21){        
       		message= message +"second line of address is changed from   "+addr2+"   to  "+addr21+"\n";
    	}		
    	if(pincode != pincode1){        
       		message= message +"pincode is changed  from   "+pincode+"   to   "+pincode11+"\n";
    	}		
    	if(state != state1){        
       		message= message +"state is changed  from  "+state+"  to  "+state1+"\n";
    	}		
    	if(country != country1){        
       		message= message +"country is changed from  "+country+"  to  "+country1+"\n";
    	}		
    	if(plan != plan1){        
       		message= message +"plan fname is changed  from  "+plan+"   to   "+plan1+"\n";
    	}		
    	if(ans1 != ans11){        
       		message= message +"Answer for first question is changed from  "+ans1+"   to   "+ans11+"\n";
    	}		
    		
    	if(ans2 != ans21){        
       		message= message +"Answer fro second question changed from  "+ans2+"   to   "+ans21+"\n";
    	}		
    	alert(message);
        
    });
	
});