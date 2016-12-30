$(document).ready(function() {
	$('#wrap').on("click", ".fa-stack", function(e){
		var url = getActorUserStatusUrl();
		var actor_id = $(this).parent().data('id');
		var status = $(this).data('value');
		var self = $(this);
		
	    $.ajax({
	        method: "POST",
	        url: url,
	        data: { actor_id: actor_id, status: status}
	      })
	    .done(function(msg) {	  
	    	if(msg == "Success") {
	    		if(self.hasClass('love')) {
	    			if(self.hasClass('love_on')) {
	    				self.removeClass('love_on');
	    			} else {
	    				self.next().removeClass('hate_on');
	    				self.addClass('love_on');
	    			}
	    		} else if(self.hasClass('hate')){
	    			if(self.hasClass('hate_on')) {
	    				self.removeClass('hate_on');
	    			} else {
	    				self.prev().removeClass('love_on');
	    				self.addClass('hate_on');
	    			}
	    		} 
	    	} else if(msg == "401"){
	    		$('#login_modal').modal('show');
	    		$('body').css("padding-right", "0");
	    	} else {
	    		window.alert("Failed to update status");
	    	}
	    }); 
		
	});	
});