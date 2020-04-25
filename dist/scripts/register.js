$(document).ready(function() {
    $('#user-feedback').load('validate.php').show();
    $('#username').keyup(function(){
        $.post('validate.php', { username: form.username.value },
        function(result){
            $('#user-feedback').html(result).show();
        })
    });
});


// JQuery Plugin
// Plugin: https://phppot.com/jquery/jquery-password-strength-checker/

function checkPasswordStrength() {
	var number = /([0-9])/;
	var alphabets = /([a-zA-Z])/;
	var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
	
	if($('#password').val().length<8) {
		$('#password-strength-status').removeClass();
		$('#password-strength-status').addClass('weak-password');
		$('#password-strength-status').html("Weak (should be atleast 8 characters.)");
	} else {  	
	    if($('#password').val().match(number) && $('#password').val().match(alphabets) && $('#password').val().match(special_characters)) {            
			$('#password-strength-status').removeClass();
			$('#password-strength-status').addClass('strong-password');
			$('#password-strength-status').html("Strong");
        } else {
			$('#password-strength-status').removeClass();
			$('#password-strength-status').addClass('medium-password');
			$('#password-strength-status').html("Medium (should include a combinations of 8 characters, numbers and special characters.)");
        } 
    }
}