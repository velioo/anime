$(document).ready(function() {
	$('#submit_login').click(function() {
		var url = getLoginUrl();
		var username = $('#modal_username').val();
		var password = $('#modal_password').val();

		 $.ajax({
		        method: "POST",
		        url: url,
		        data: { username: username, password: password}
		      })
		    .done(function(msg) {	  
		    	if(msg != "Fail") {
		    		location.reload();
		    	} else {
		    		$('#login_error').show();
		    	}
		    }); 
	});
	
	$("#modal_username, #modal_password").keypress(function(e) {
		if(e.keyCode == 13) {
			var url = getLoginUrl();
			var username = $('#modal_username').val();
			var password = $('#modal_password').val();

			 $.ajax({
			        method: "POST",
			        url: url,
			        data: { username: username, password: password}
			      })
			    .done(function(msg) {	  
			    	if(msg != "Fail") {
			    		location.reload();
			    	} else {
			    		$('#login_error').show();
			    	}
			    }); 
		}
	
	});
	
	$('#login_modal').on('hidden.bs.modal', function () {
		$('#login_error').hide();
	});
	
	$('.log_in_modal').click(function(e) {
		e.preventDefault();
		$('#login_modal').modal('show');
		$('body').css("padding-right", "0");
	});
});