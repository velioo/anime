$('#wrap #follow_button').click(function() {
	var url;
	var following_id = getUserId();
	var self = $(this);
	
	if(self.hasClass('button-blue') && self.text() == "Follow") {
		url = getFollowUrl();
	} else if(self.hasClass('button-red') && self.text() == "Unfollow") {
		url = getUnfollowUrl();
	} else {
		return;
	}
	
	$.ajax({
        method: "POST",
        url: url,
        data: { following_id: following_id }
      })
    .done(function(msg) {
    	if(msg == "Success") {	
    		if(self.hasClass('button-blue')) {		
    			self.removeClass('button-blue').addClass('button-red');
    			self.text('Unfollow');
    		} else {
    			self.removeClass('button-red').addClass('button-blue');
    			self.text('Follow');
    		}		
    	} else {
    		
    	}
    });	
	
});	