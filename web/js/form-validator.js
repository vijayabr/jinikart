
/*function checkForm() {
// Fetching values from all input fields and storing them in variables.

var password = document.getElementById("password").value;
var email = document.getElementById("email").value;
//Check input Fields Should not be blanks.
if (name == '' || password == '' || email == '' || website == '') {
alert("Fill All Fields");
}
} else {
//Notifying error fields
var username1 = document.getElementById("username");
var password1 = document.getElementById("password");
var email1 = document.getElementById("email");
var website1 = document.getElementById("website");
//Check All Values/Informations Filled by User are Valid Or Not.If All Fields Are invalid Then Generate alert.
if (username1.innerHTML == 'Must be 3+ letters' || password1.innerHTML == 'Password too short' || email1.innerHTML == 'Invalid email' || website1.innerHTML == 'Invalid website') {
alert("Fill Valid Information");
} else {
//Submit Form When All values are valid.
document.getElementById("merchantform").submit();
  }
 }
}*/

 
 /*$(document).ready(function validate() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
	 alert("hii");
  $("form[name='merchantform']").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      companyName: "required",
      contactPersonName: "required",
      email: {
        required: true,
        // Specify that email should be validated
        // by the built-in "email" rule
        email: true
      },
      password: {
        required: true,
        minlength: 6
      },
      mobile_no: {
    	required:true,
    	maxlength: 10
      }
    },
    // Specify validation error messages
    messages: {
      companyName: "Please enter your Company name",
      contactPersonName: "Please enter your Last name",
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 6 characters long"
      },
      email: "Please enter a valid email address",
      mobile_no: {
    	required: "Please provide a Mobile NUmber",
    	maxlength: "Must be 10 digits"
    },  
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
  
    submitHandler: function(form) {
      form.submit();
    }
  });
});
/*

function validate() {
	alert("hiiii");
	var name = document.getElementByName("companyName");
	alert(name);
	
}
*/
 